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
use App\Services\Clinic\IntensiveSessionService;
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
    protected $intensiveSessionService;

    public function __construct(
        SpecialtyReservationFieldsService $fieldsService,
        PaymentCalculatorService $paymentCalculator,
        AppointmentOverlapService $overlapService,
        BillingAutomationService $billingAutomation,
        EquipmentAllocationService $equipmentAllocation,
        IntensiveSessionService $intensiveSessionService
    ) {
        $this->fieldsService = $fieldsService;
        $this->paymentCalculator = $paymentCalculator;
        $this->overlapService = $overlapService;
        $this->billingAutomation = $billingAutomation;
        $this->equipmentAllocation = $equipmentAllocation;
        $this->intensiveSessionService = $intensiveSessionService;
    }

    /**
     * Display the calendar view.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);

        // Handle week navigation - support both 'week' and 'date' parameters
        $currentDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::now();
        $startOfWeek = $currentDate->copy()->startOfWeek(Carbon::SUNDAY); // Start week on Sunday
        $endOfWeek = $startOfWeek->copy()->addDays(6)->endOfDay(); // End week on Saturday
        
        // Show empty state instead of using clinic_id = 0
        if (!$clinic) {
            $appointments = collect();
            $patients = collect();
            $therapists = collect();
            return view('web.clinic.appointments.index', compact('appointments', 'startOfWeek', 'patients', 'therapists', 'clinic'));
        }
        
        // Load appointments for the week range (using datetime comparison for accuracy)
        $appointments = ClinicAppointment::with(['patient', 'doctor', 'additionalData'])
                        ->where('clinic_id', $clinic->id)
                        ->where('appointment_date', '>=', $startOfWeek->startOfDay())
                        ->where('appointment_date', '<=', $endOfWeek->endOfDay())
                        ->orderBy('appointment_date')
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
            'session_type' => 'nullable|string',
            'booking_type' => 'nullable|in:regular,intensive',
            'total_hours' => 'nullable|integer|min:1|max:4|required_if:booking_type,intensive'
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
        
        // Determine booking type
        $bookingType = $request->booking_type ?? 'regular';
        $totalHours = $request->total_hours ?? null;
        
        // For intensive sessions, calculate total duration
        $durationMinutes = $request->duration_minutes ?? 60;
        if ($bookingType === 'intensive' && $totalHours) {
            $durationMinutes = $totalHours * 60;
        }
        
        // Create appointment
        $appointmentData = [
            'clinic_id' => $clinic->id,
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id, // May be null for intensive (assigned per slot)
            'appointment_date' => $start,
            'duration_minutes' => $durationMinutes,
            'status' => 'scheduled',
            'visit_type' => $visitType,
            'location' => $request->location,
            'payment_method' => $request->payment_method,
            'specialty' => $request->specialty,
            'session_type' => $request->session_type ?? $request->input('type'),
            'booking_type' => $bookingType,
            'total_hours' => $totalHours,
        ];
        
        // Create appointment (regular or intensive)
        if ($bookingType === 'intensive' && $totalHours) {
            $appointment = $this->intensiveSessionService->createIntensiveSession($appointmentData, $totalHours);
        } else {
            $appointment = ClinicAppointment::create($appointmentData);
        }

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

        // Get the appointment date to preserve week view after redirect
        $appointmentDate = Carbon::parse($appointment->appointment_date);
        
        // For intensive sessions, redirect to slot assignment page
        $redirectRoute = route('clinic.appointments.index', ['date' => $appointmentDate->format('Y-m-d')]);
        if ($bookingType === 'intensive') {
            $redirectRoute = route('clinic.appointments.assignSlots', $appointment->id);
        }
        
        // Return JSON for AJAX requests, redirect for regular form submissions
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $bookingType === 'intensive' 
                    ? 'Intensive session created. Please assign doctors to slots.' 
                    : 'Appointment scheduled successfully.',
                'redirect' => $redirectRoute,
                'is_intensive' => $bookingType === 'intensive'
            ]);
        }

        return redirect($redirectRoute)
            ->with('success', $bookingType === 'intensive' 
                ? 'Intensive session created. Please assign doctors to slots.' 
                : 'Appointment scheduled successfully.');
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
     * Get available services for a specialty
     */
    public function getAvailableServices(Request $request)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);
        
        if (!$clinic) {
            return response()->json([
                'success' => false,
                'message' => 'Clinic not found.'
            ], 404);
        }
        
        $specialty = $request->get('specialty', $clinic->primary_specialty);
        
        // Get pricing config
        $pricingConfig = \App\Models\PricingConfig::where('clinic_id', $clinic->id)
            ->where('specialty', $specialty)
            ->where('is_active', true)
            ->first();
        
        if (!$pricingConfig) {
            $pricingConfig = \App\Models\PricingConfig::createDefault($clinic, $specialty);
        }
        
        $services = [];
        if ($pricingConfig->equipment_pricing) {
            foreach ($pricingConfig->equipment_pricing as $key => $price) {
                $services[] = [
                    'key' => $key,
                    'name' => ucfirst(str_replace('_', ' ', $key)),
                    'price' => (float) $price
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'services' => $services
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
            
            // Get service names for display
            $serviceDetails = [];
            if (!empty($params['equipment'])) {
                $pricingConfig = \App\Models\PricingConfig::where('clinic_id', $clinic->id)
                    ->where('specialty', $params['specialty'])
                    ->where('is_active', true)
                    ->first();
                
                if ($pricingConfig && $pricingConfig->equipment_pricing) {
                    foreach ($params['equipment'] as $equipmentKey) {
                        if (isset($pricingConfig->equipment_pricing[$equipmentKey])) {
                            $serviceDetails[] = [
                                'name' => ucfirst(str_replace('_', ' ', $equipmentKey)),
                                'price' => $pricingConfig->equipment_pricing[$equipmentKey]
                            ];
                        }
                    }
                }
            }
            
            $pricing['service_details'] = $serviceDetails;

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

    /**
     * Show slot assignment page for intensive session
     */
    public function assignSlots($appointmentId)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);
        
        $appointment = ClinicAppointment::with(['patient', 'bookingSlots.doctorAssignments.doctor'])
            ->where('clinic_id', $clinic->id)
            ->findOrFail($appointmentId);
        
        if ($appointment->booking_type !== 'intensive') {
            return redirect()->route('clinic.appointments.index')
                ->with('error', 'This appointment is not an intensive session.');
        }
        
        $slots = $appointment->bookingSlots()->orderBy('slot_number')->get();
        
        return view('web.clinic.appointments.assign-slots', compact('appointment', 'slots', 'clinic'));
    }

    /**
     * Assign doctor to a slot
     */
    public function assignSlotToDoctor(Request $request, $appointmentId, $slotId)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);
        
        $appointment = ClinicAppointment::where('clinic_id', $clinic->id)->findOrFail($appointmentId);
        $slot = \App\Models\BookingSlot::where('appointment_id', $appointment->id)
            ->findOrFail($slotId);
        
        $request->validate([
            'doctor_id' => 'required|exists:users,id'
        ]);
        
        try {
            $assignment = $this->intensiveSessionService->assignDoctorToSlot(
                $slotId,
                $request->doctor_id,
                $user->id
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Doctor assigned successfully.',
                'assignment' => $assignment->load('doctor')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Unassign doctor from a slot
     */
    public function unassignSlot($appointmentId, $slotId)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);
        
        $appointment = ClinicAppointment::where('clinic_id', $clinic->id)->findOrFail($appointmentId);
        $slot = \App\Models\BookingSlot::where('appointment_id', $appointment->id)
            ->findOrFail($slotId);
        
        $assignment = \App\Models\SlotDoctorAssignment::where('slot_id', $slotId)->first();
        
        if ($assignment) {
            // Delete work log
            \App\Models\DoctorWorkLog::where('assignment_id', $assignment->id)->delete();
            $assignment->delete();
        }
        
        $slot->update(['status' => 'pending']);
        
        return response()->json([
            'success' => true,
            'message' => 'Doctor unassigned successfully.'
        ]);
    }

    /**
     * Get available doctors for a slot
     */
    public function getAvailableDoctorsForSlot($appointmentId, $slotId)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);
        
        $appointment = ClinicAppointment::where('clinic_id', $clinic->id)->findOrFail($appointmentId);
        $slot = \App\Models\BookingSlot::where('appointment_id', $appointment->id)
            ->findOrFail($slotId);
        
        $doctors = $this->intensiveSessionService->getAvailableDoctors(
            $clinic->id,
            $slot,
            $appointment->specialty
        );
        
        return response()->json([
            'success' => true,
            'doctors' => $doctors
        ]);
    }

    // getUserClinic method inherited from BaseClinicController
}
