<?php

namespace App\Http\Controllers\Clinic;

use App\Models\Waitlist;
use App\Models\Patient;
use App\Services\Scheduling\WaitlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaitlistController extends BaseClinicController
{
    protected $waitlistService;

    public function __construct(WaitlistService $waitlistService)
    {
        $this->waitlistService = $waitlistService;
    }

    /**
     * Display a listing of waitlist entries
     */
    public function index(Request $request)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            $waitlist = collect();
            return view('web.clinic.waitlist.index', compact('waitlist', 'clinic'));
        }

        $query = Waitlist::where('clinic_id', $clinic->id)
            ->with(['patient', 'doctor']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('specialty')) {
            $query->where('specialty', $request->specialty);
        }

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        $waitlist = $query->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        // Get filter options
        $patients = Patient::where('clinic_id', $clinic->id)->get();
        
        // Get doctors/therapists who have appointments or programs with this clinic
        // First, get therapist IDs who have appointments or programs at this clinic
        $therapistIds = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
            ->whereNotNull('doctor_id')
            ->distinct()
            ->pluck('doctor_id')
            ->merge(
                \App\Models\WeeklyProgram::where('clinic_id', $clinic->id)
                    ->whereNotNull('therapist_id')
                    ->distinct()
                    ->pluck('therapist_id')
            )
            ->unique()
            ->toArray();
        
        // Get therapists - if we have IDs, filter by them, otherwise show all therapists
        $doctorsQuery = \App\Models\User::where('type', 'therapist');
        
        if (!empty($therapistIds)) {
            $doctorsQuery->whereIn('id', $therapistIds);
        }
        
        $doctors = $doctorsQuery->get();

        // Statistics
        $stats = [
            'total' => Waitlist::where('clinic_id', $clinic->id)->count(),
            'active' => Waitlist::where('clinic_id', $clinic->id)->where('status', 'active')->count(),
            'urgent' => Waitlist::where('clinic_id', $clinic->id)->where('priority', 'urgent')->where('status', 'active')->count(),
            'notified' => Waitlist::where('clinic_id', $clinic->id)->where('status', 'notified')->count(),
        ];

        return view('web.clinic.waitlist.index', compact(
            'waitlist',
            'patients',
            'doctors',
            'stats',
            'clinic'
        ));
    }

    /**
     * Show the form for creating a new waitlist entry
     */
    public function create(Request $request)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }

        $patientId = $request->get('patient_id');
        $patient = $patientId ? Patient::find($patientId) : null;

        $patients = Patient::where('clinic_id', $clinic->id)->get();
        
        // Get doctors/therapists who have appointments or programs with this clinic
        // First, get therapist IDs who have appointments or programs at this clinic
        $therapistIds = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
            ->whereNotNull('doctor_id')
            ->distinct()
            ->pluck('doctor_id')
            ->merge(
                \App\Models\WeeklyProgram::where('clinic_id', $clinic->id)
                    ->whereNotNull('therapist_id')
                    ->distinct()
                    ->pluck('therapist_id')
            )
            ->unique()
            ->toArray();
        
        // Get therapists - if we have IDs, filter by them, otherwise show all therapists
        $doctorsQuery = \App\Models\User::where('type', 'therapist');
        
        if (!empty($therapistIds)) {
            $doctorsQuery->whereIn('id', $therapistIds);
        }
        
        $doctors = $doctorsQuery->get();

        return view('web.clinic.waitlist.create', compact(
            'clinic',
            'patients',
            'doctors',
            'patient'
        ));
    }

    /**
     * Store a newly created waitlist entry
     */
    public function store(Request $request)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return redirect()->back()->with('error', 'Clinic not found.');
        }

        $validator = \Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:users,id',
            'specialty' => 'nullable|string',
            'visit_type' => 'nullable|in:evaluation,followup,re_evaluation',
            'priority' => 'required|in:low,normal,high,urgent',
            'preferred_start_date' => 'nullable|date',
            'preferred_end_date' => 'nullable|date|after_or_equal:preferred_start_date',
            'preferred_times' => 'nullable|array',
            'preferred_days' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'clinic_id' => $clinic->id,
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'specialty' => $request->specialty ?? $clinic->primary_specialty,
                'visit_type' => $request->visit_type,
                'priority' => $request->priority,
                'preferred_start_date' => $request->preferred_start_date,
                'preferred_end_date' => $request->preferred_end_date,
                'preferred_times' => $request->preferred_times,
                'preferred_days' => $request->preferred_days,
                'notes' => $request->notes,
            ];

            $waitlistEntry = $this->waitlistService->addToWaitlist($data);

            return redirect()->route('clinic.waitlist.index')
                ->with('success', 'Patient added to waitlist successfully.');

        } catch (\Exception $e) {
            \Log::error('Failed to add to waitlist', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to add to waitlist: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove patient from waitlist
     */
    public function destroy($id)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return response()->json([
                'success' => false,
                'message' => 'Clinic not found.'
            ], 404);
        }

        $waitlist = Waitlist::where('clinic_id', $clinic->id)
            ->findOrFail($id);

        $waitlist->cancel();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Patient removed from waitlist.'
            ]);
        }

        return redirect()->route('clinic.waitlist.index')
            ->with('success', 'Patient removed from waitlist.');
    }

    /**
     * Get waitlist position for a patient
     */
    public function getPosition(Request $request)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return response()->json([
                'success' => false,
                'message' => 'Clinic not found.'
            ], 404);
        }

        $request->validate([
            'patient_id' => 'required|exists:patients,id'
        ]);

        $position = $this->waitlistService->getWaitlistPosition(
            $request->patient_id,
            $clinic->id
        );

        return response()->json([
            'success' => true,
            'position' => $position
        ]);
    }
}

