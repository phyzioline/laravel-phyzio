<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\ClinicAppointment;
use App\Models\IntakeForm;
use App\Models\IntakeFormResponse;
use App\Services\Clinic\AppointmentOverlapService;
use App\Services\Scheduling\WaitlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SelfSchedulingController extends Controller
{
    protected $overlapService;
    protected $waitlistService;

    public function __construct(
        AppointmentOverlapService $overlapService,
        WaitlistService $waitlistService
    ) {
        $this->overlapService = $overlapService;
        $this->waitlistService = $waitlistService;
    }

    /**
     * Show self-scheduling page for patients
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get patient's clinic (if they have one assigned)
        $clinic = null;
        $patient = null;
        if ($user->type === 'patient' || $user->type === 'user') {
            // Try to find clinic through patient record
            $patient = \App\Models\Patient::where('user_id', $user->id)->first();
            if ($patient) {
                $clinic = Clinic::find($patient->clinic_id);
            }
        }
        
        // If clinic_id provided in request, use that
        if ($request->filled('clinic_id')) {
            $clinic = Clinic::find($request->clinic_id);
        }

        // If no clinic, show clinic selection
        if (!$clinic) {
            $clinics = Clinic::where('is_active', true)->get();
            return view('web.patient.self_scheduling.select_clinic', compact('clinics'));
        }

        // Get available intake forms
        $intakeForms = IntakeForm::where('clinic_id', $clinic->id)
            ->where('is_active', true)
            ->get();

        // Get available doctors
        $doctors = \App\Models\User::where('type', 'therapist')
            ->whereHas('clinics', function($q) use ($clinic) {
                $q->where('clinics.id', $clinic->id);
            })
            ->get();

        return view('web.patient.self_scheduling.index', compact(
            'clinic',
            'intakeForms',
            'doctors'
        ));
    }

    /**
     * Get available time slots
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'duration' => 'nullable|integer|min:15|max:120'
        ]);

        $clinic = Clinic::findOrFail($request->clinic_id);
        $doctorId = $request->doctor_id;
        $date = Carbon::parse($request->date);
        $duration = $request->duration ?? 60;

        $slots = $this->overlapService->getAvailableSlots(
            $doctorId,
            $date,
            $duration
        );

        return response()->json([
            'success' => true,
            'slots' => $slots
        ]);
    }

    /**
     * Store self-scheduled appointment
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $patient = \App\Models\Patient::where('user_id', $user->id)->first();

        if (!$patient) {
            return redirect()->back()
                ->with('error', 'Patient record not found. Please contact the clinic.');
        }

        $validator = \Validator::make($request->all(), [
            'clinic_id' => 'required|exists:clinics,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'visit_type' => 'required|in:evaluation,followup,re_evaluation',
            'specialty' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:15|max:120',
            'intake_form_id' => 'nullable|exists:intake_forms,id',
            'intake_responses' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $clinic = Clinic::findOrFail($request->clinic_id);
        $start = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
        $duration = $request->duration_minutes ?? 60;

        // Check for overlaps
        $overlapCheck = $this->overlapService->checkOverlaps(
            $request->doctor_id,
            $patient->id,
            $start,
            $duration
        );

        if ($overlapCheck['has_overlap']) {
            return redirect()->back()
                ->withErrors(['appointment_time' => 'This time slot is no longer available.'])
                ->withInput();
        }

        try {
            // Create appointment
            $appointment = ClinicAppointment::create([
                'clinic_id' => $clinic->id,
                'patient_id' => $patient->id,
                'doctor_id' => $request->doctor_id,
                'appointment_date' => $start,
                'duration_minutes' => $duration,
                'status' => 'scheduled',
                'visit_type' => $request->visit_type,
                'location' => 'clinic',
                'specialty' => $request->specialty ?? $clinic->primary_specialty,
            ]);

            // Save intake form response if provided
            if ($request->intake_form_id && $request->intake_responses) {
                IntakeFormResponse::create([
                    'intake_form_id' => $request->intake_form_id,
                    'patient_id' => $patient->id,
                    'appointment_id' => $appointment->id,
                    'responses' => $request->intake_responses,
                    'status' => 'submitted',
                    'submitted_at' => now(),
                ]);
            }

            return redirect()->route('patient.appointments.index')
                ->with('success', 'Appointment scheduled successfully!');

        } catch (\Exception $e) {
            \Log::error('Failed to create self-scheduled appointment', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to schedule appointment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Submit intake form response
     */
    public function submitIntakeForm(Request $request)
    {
        $user = Auth::user();
        $patient = \App\Models\Patient::where('user_id', $user->id)->first();

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient record not found.'
            ], 404);
        }

        $request->validate([
            'intake_form_id' => 'required|exists:intake_forms,id',
            'responses' => 'required|array',
            'appointment_id' => 'nullable|exists:clinic_appointments,id',
        ]);

        try {
            $response = IntakeFormResponse::create([
                'intake_form_id' => $request->intake_form_id,
                'patient_id' => $patient->id,
                'appointment_id' => $request->appointment_id,
                'responses' => $request->responses,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Intake form submitted successfully.',
                'response_id' => $response->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit form: ' . $e->getMessage()
            ], 500);
        }
    }
}

