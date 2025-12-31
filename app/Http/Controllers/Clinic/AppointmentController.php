<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\ClinicAppointment;
use App\Models\Patient;
use App\Models\Clinic;
use App\Models\ReservationAdditionalData;
use App\Models\User; // Assuming therapists are Users
use App\Services\Clinic\SpecialtyReservationFieldsService;
use App\Services\Clinic\PaymentCalculatorService;
use App\Services\Clinic\AppointmentOverlapService;
use App\Services\Clinic\BillingAutomationService;
use App\Services\Clinic\EquipmentAllocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends BaseClinicController
{
    protected $fieldsService;
    protected $paymentCalculator;
    protected $overlapService;
    protected $billingAutomation;
    protected $equipmentAllocation;

    public function __construct(
        SpecialtyReservationFieldsService $fieldsService,
        PaymentCalculatorService $paymentCalculator,
        AppointmentOverlapService $overlapService,
        BillingAutomationService $billingAutomation,
        EquipmentAllocationService $equipmentAllocation
    ) {
        $this->fieldsService = $fieldsService;
        $this->paymentCalculator = $paymentCalculator;
        $this->overlapService = $overlapService;
        $this->billingAutomation = $billingAutomation;
        $this->equipmentAllocation = $equipmentAllocation;
    }

    /**
     * Display the calendar view.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);

        // Handle week navigation
        $weekOffset = (int) $request->get('week', 0);
        $startOfWeek = Carbon::now()->startOfWeek()->addWeeks($weekOffset);
        $endOfWeek = $startOfWeek->copy()->addDays(6); // Show 1 week (7 days)
        
        // Show empty state instead of using clinic_id = 0
        if (!$clinic) {
            $appointments = collect();
            $patients = collect();
            $therapists = collect();
            return view('web.clinic.appointments.index', compact('appointments', 'startOfWeek', 'patients', 'therapists', 'clinic'));
        }
        
        $appointments = ClinicAppointment::with(['patient', 'doctor', 'additionalData'])
                        ->where('clinic_id', $clinic->id)
                        ->whereDate('appointment_date', '>=', $startOfWeek->format('Y-m-d'))
                        ->whereDate('appointment_date', '<=', $endOfWeek->format('Y-m-d'))
                        ->get();

        $patients = Patient::where('clinic_id', $clinic->id)->get(); 
        
        // CRITICAL: Only show therapists/doctors assigned to THIS clinic via clinic_staff table
        // This ensures proper data isolation - no external doctors from other clinics
        $therapistIds = \App\Models\ClinicStaff::where('clinic_id', $clinic->id)
            ->whereIn('role', ['therapist', 'doctor'])
            ->where('is_active', true)
            ->pluck('user_id')
            ->toArray();
        
        if (empty($therapistIds)) {
            $therapists = collect();
        } else {
            $therapists = User::whereIn('id', $therapistIds)
                ->whereIn('type', ['therapist', 'doctor'])
                ->get();
        }

        return view('web.clinic.appointments.index', compact('appointments', 'startOfWeek', 'patients', 'therapists', 'clinic'));
    }

    /**
     * Show form to create new appointment
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);

        // Show empty state instead of redirecting
        if (!$clinic) {
            $appointments = collect();
            $patients = collect();
            $therapists = collect();
            $startOfWeek = now()->startOfWeek();
            return view('web.clinic.appointments.index', compact('appointments', 'startOfWeek', 'patients', 'therapists', 'clinic'));
        }

        $patients = Patient::where('clinic_id', $clinic->id)->get();
        
        // CRITICAL FIX: Only get therapists assigned to THIS clinic
        // Check if clinic_staff table exists, otherwise fallback to global query
        if (Schema::hasTable('clinic_staff')) {
            $therapists = User::where('type', 'therapist')
                ->whereHas('clinicStaff', function($q) use ($clinic) {
                    $q->where('clinic_id', $clinic->id)
                      ->where('is_active', true);
                })
                ->get();
        } else {
            // Fallback: all therapists (for backwards compatibility until migration runs)
            $therapists = User::where('type', 'therapist')->get();
        }
        
        // Get specialty for form fields
        $specialty = $request->get('specialty', $clinic->primary_specialty);
        $specialtyFields = $this->fieldsService->getFieldsSchema($specialty);

        return view('web.clinic.appointments.create', compact(
            'clinic',
            'patients',
            'therapists',
            'specialty',
            'specialtyFields'
        ));
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);

        if (!$clinic) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Clinic not found.'
                ], 404);
            }
            return redirect()->back()->with('error', 'Clinic not found.');
        }

        // Verify patient belongs to this clinic
        $patient = Patient::where('clinic_id', $clinic->id)
            ->findOrFail($request->patient_id);
        
        // CRITICAL: Verify doctor belongs to this clinic if provided
        if ($request->filled('doctor_id')) {
            $therapistStaff = \App\Models\ClinicStaff::where('clinic_id', $clinic->id)
                ->where('user_id', $request->doctor_id)
                ->whereIn('role', ['therapist', 'doctor'])
                ->where('is_active', true)
                ->first();
            
            if (!$therapistStaff) {
                return back()
                    ->withInput()
                    ->with('error', 'Selected doctor is not assigned to your clinic.');
            }
        }

        // Map Quick Book form fields to store() method expectations
        // Form has 'type' but validation expects 'visit_type'
        $request->merge([
            'visit_type' => $request->input('type', 'followup'), // Map 'type' to 'visit_type'
            'location' => $request->input('location', 'clinic'), // Default to clinic
            'specialty' => $request->input('specialty', $clinic->primary_specialty ?? 'general'), // Use clinic's specialty
        ]);

        $validator = \Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'visit_type' => 'required|in:evaluation,followup,re_evaluation,session', // Added 'session' for Quick Book
            'location' => 'required|in:clinic,home',
            'payment_method' => 'nullable|in:cash,card,insurance',
            'specialty' => 'required|string',
            'duration_minutes' => 'nullable|integer|min:15|max:120',
            'session_type' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $start = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
        $durationMinutes = $request->duration_minutes ?? 60;
        
        // Check for overlaps before creating appointment
        $overlapCheck = $this->overlapService->checkOverlaps(
            $request->doctor_id,
            $request->patient_id,
            $start,
            $durationMinutes
        );

        if ($overlapCheck['has_overlap']) {
            $errorMessage = implode(' ', array_column($overlapCheck['conflicts'], 'message'));
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Appointment conflict detected: ' . $errorMessage,
                    'errors' => ['appointment_time' => [$errorMessage]],
                    'overlap_details' => $overlapCheck
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors(['appointment_time' => $errorMessage])
                ->withInput();
        }
        
        // Map 'session' type to 'followup' for database
        $visitType = $request->visit_type;
        if ($visitType === 'session') {
            $visitType = 'followup';
        }
        
        // Create appointment
        $appointment = ClinicAppointment::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $start,
            'duration_minutes' => $request->duration_minutes ?? 60,
            'status' => 'scheduled',
            'visit_type' => $visitType,
            'location' => $request->location,
            'payment_method' => $request->payment_method,
            'specialty' => $request->specialty,
            'session_type' => $request->session_type ?? $request->input('type')
        ]);

        // Save specialty-specific additional data
        $specialty = $request->specialty;
        $specialtyFields = $this->fieldsService->getFieldsSchema($specialty);
        $specialtyData = [];

        foreach ($specialtyFields as $field => $config) {
            if ($request->has($field)) {
                $value = $request->input($field);
                
                // Handle checkbox arrays
                if ($config['type'] === 'checkbox' && is_array($value)) {
                    $specialtyData[$field] = $value;
                } else {
                    $specialtyData[$field] = $value;
                }
            }
        }

        if (!empty($specialtyData)) {
            ReservationAdditionalData::create([
                'appointment_id' => $appointment->id,
                'specialty' => $specialty,
                'data' => $specialtyData
            ]);
        }

        // Calculate and store price if needed
        try {
            $pricing = $this->paymentCalculator->calculateAppointmentPrice($appointment);
            // You can store this in a separate pricing table or invoice
        } catch (\Exception $e) {
            // Log but don't fail appointment creation
            \Log::warning('Failed to calculate appointment price', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage()
            ]);
        }

        // Allocate equipment if specified in additional data
        if ($request->has('equipment') && is_array($request->equipment) && !empty($request->equipment)) {
            $equipmentTypes = array_filter($request->equipment);
            if (!empty($equipmentTypes)) {
                $allocation = $this->equipmentAllocation->allocateEquipment($appointment, $equipmentTypes);
                if (!$allocation['success'] && !empty($allocation['errors'])) {
                    // Log but don't fail appointment - equipment can be allocated later
                    \Log::warning('Equipment allocation failed', [
                        'appointment_id' => $appointment->id,
                        'errors' => $allocation['errors']
                    ]);
                }
            }
        }

        // Return JSON for AJAX requests, redirect for regular form submissions
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment scheduled successfully.',
                'redirect' => route('clinic.appointments.index')
            ]);
        }

        return redirect()->route('clinic.appointments.index')
            ->with('success', 'Appointment scheduled successfully.');
    }

    /**
     * Get specialty fields for AJAX request
     */
    public function getSpecialtyFields(Request $request)
    {
        $specialty = $request->get('specialty');
        $fields = $this->fieldsService->getFieldsSchema($specialty);

        return response()->json([
            'success' => true,
            'fields' => $fields
        ]);
    }

    /**
     * Calculate appointment price preview
     */
    public function calculatePrice(Request $request)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);

        if (!$clinic) {
            return response()->json([
                'success' => false,
                'message' => 'Clinic not found.'
            ], 404);
        }

        $request->validate([
            'specialty' => 'required|string',
            'visit_type' => 'required|in:evaluation,followup,re_evaluation',
            'location' => 'required|in:clinic,home',
            'duration_minutes' => 'nullable|integer',
            'therapist_level' => 'nullable|string',
            'equipment' => 'nullable|array'
        ]);

        try {
            $params = [
                'specialty' => $request->specialty,
                'visit_type' => $request->visit_type,
                'location' => $request->location,
                'duration_minutes' => $request->duration_minutes ?? 60,
                'therapist_level' => $request->therapist_level ?? 'senior',
                'equipment' => $request->equipment ?? []
            ];

            $pricing = $this->paymentCalculator->calculateSessionPrice($clinic, $params);

            return response()->json([
                'success' => true,
                'pricing' => $pricing
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update appointment status
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);

        if (!$clinic) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Clinic not found.'
                ], 404);
            }
            return redirect()->back()->with('error', 'Clinic not found.');
        }

        $appointment = ClinicAppointment::where('clinic_id', $clinic->id)
            ->findOrFail($id);

        $validator = \Validator::make($request->all(), [
            'status' => 'required|in:scheduled,confirmed,in_progress,completed,cancelled,no_show',
            'appointment_date' => 'nullable|date',
            'appointment_time' => 'nullable',
            'duration_minutes' => 'nullable|integer|min:15|max:120',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // If updating time, check for overlaps
        if ($request->has('appointment_date') && $request->has('appointment_time')) {
            $newStart = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
            $durationMinutes = $request->duration_minutes ?? $appointment->duration_minutes;
            
            $overlapCheck = $this->overlapService->checkOverlaps(
                $appointment->doctor_id,
                $appointment->patient_id,
                $newStart,
                $durationMinutes,
                $appointment->id // Exclude current appointment
            );

            if ($overlapCheck['has_overlap']) {
                $errorMessage = implode(' ', array_column($overlapCheck['conflicts'], 'message'));
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Appointment conflict detected: ' . $errorMessage,
                        'errors' => ['appointment_time' => [$errorMessage]]
                    ], 422);
                }
                
                return redirect()->back()
                    ->withErrors(['appointment_time' => $errorMessage])
                    ->withInput();
            }

            $appointment->appointment_date = $newStart;
        }

        $oldStatus = $appointment->status;
        $newStatus = $request->status;

        // Update appointment
        $appointment->update([
            'status' => $newStatus,
            'duration_minutes' => $request->duration_minutes ?? $appointment->duration_minutes,
            'notes' => $request->notes ?? $appointment->notes,
        ]);

        // Trigger billing automation when appointment is completed
        if ($newStatus === 'completed' && $oldStatus !== 'completed') {
            try {
                $invoiceData = $this->billingAutomation->generateInvoiceOnCompletion($appointment);
                
                // If payment method is insurance, create insurance claim
                if ($appointment->payment_method === 'insurance' && $invoiceData) {
                    $this->billingAutomation->processInsuranceClaim($appointment, $invoiceData);
                }
            } catch (\Exception $e) {
                \Log::error('Billing automation failed', [
                    'appointment_id' => $appointment->id,
                    'error' => $e->getMessage()
                ]);
                // Don't fail the appointment update if billing fails
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment updated successfully.',
                'appointment' => $appointment->fresh(['patient', 'doctor'])
            ]);
        }

        return redirect()->route('clinic.appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Get available time slots for a doctor
     */
    public function getAvailableSlots(Request $request)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);

        if (!$clinic) {
            return response()->json([
                'success' => false,
                'message' => 'Clinic not found.'
            ], 404);
        }

        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'slot_duration' => 'nullable|integer|min:15|max:120'
        ]);

        $doctorId = $request->doctor_id;
        $date = Carbon::parse($request->date);
        $slotDuration = $request->slot_duration ?? 60;

        $availableSlots = $this->overlapService->getAvailableSlots(
            $doctorId,
            $date,
            $slotDuration
        );

        return response()->json([
            'success' => true,
            'slots' => $availableSlots
        ]);
    }

    // getUserClinic method inherited from BaseClinicController
}
