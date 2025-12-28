<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\WeeklyProgram;
use App\Models\Patient;
use App\Models\EpisodeOfCare;
use App\Services\Clinic\WeeklyProgramService;
use App\Services\Clinic\PaymentCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WeeklyProgramController extends BaseClinicController
{
    protected $programService;
    protected $paymentCalculator;

    public function __construct(
        WeeklyProgramService $programService,
        PaymentCalculatorService $paymentCalculator
    ) {
        $this->programService = $programService;
        $this->paymentCalculator = $paymentCalculator;
    }

    /**
     * Display list of programs
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);

        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }

        $query = WeeklyProgram::where('clinic_id', $clinic->id)
            ->with(['patient', 'therapist']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by specialty
        if ($request->has('specialty')) {
            $query->where('specialty', $request->specialty);
        }

        // Filter by patient
        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        $programs = $query->latest()->paginate(15);

        return view('web.clinic.programs.index', compact('programs', 'clinic'));
    }

    /**
     * Show form to create new program
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);

        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }

        $patients = Patient::where('clinic_id', $clinic->id)->get();
        $episodes = EpisodeOfCare::where('clinic_id', $clinic->id)
            ->where('status', 'active')
            ->get();

        // Get therapists (adjust based on your user model)
        $therapists = \App\Models\User::where('type', 'therapist')
            ->whereHas('therapistProfile', function($q) use ($clinic) {
                // Filter by clinic if relationship exists
            })
            ->get();

        $specialty = $request->get('specialty', $clinic->primary_specialty);
        $patientId = $request->get('patient_id');

        // Calculate preview pricing if patient and specialty selected
        $pricingPreview = null;
        if ($patientId && $specialty) {
            $pricingPreview = $this->paymentCalculator->calculateProgramPrice(
                $clinic,
                [
                    'specialty' => $specialty,
                    'sessions_per_week' => 2,
                    'total_weeks' => 4,
                    'location' => 'clinic',
                    'duration_minutes' => 60
                ]
            );
        }

        return view('web.clinic.programs.create', compact(
            'clinic',
            'patients',
            'episodes',
            'therapists',
            'specialty',
            'patientId',
            'pricingPreview'
        ));
    }

    /**
     * Store new program
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);

        if (!$clinic) {
            return response()->json([
                'success' => false,
                'message' => 'Clinic not found.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'program_name' => 'required|string|max:255',
            'specialty' => 'required|string',
            'sessions_per_week' => 'required|integer|min:1|max:5',
            'total_weeks' => 'required|integer|min:1|max:52',
            'start_date' => 'required|date|after_or_equal:today',
            'therapist_id' => 'nullable|exists:users,id',
            'episode_id' => 'nullable|exists:episodes,id',
            'payment_plan' => 'required|in:pay_per_week,monthly,upfront',
            'auto_booking_enabled' => 'boolean',
            'goals' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = [
                'clinic_id' => $clinic->id,
                'clinic' => $clinic,
                'patient_id' => $request->patient_id,
                'episode_id' => $request->episode_id,
                'therapist_id' => $request->therapist_id,
                'program_name' => $request->program_name,
                'specialty' => $request->specialty,
                'sessions_per_week' => $request->sessions_per_week,
                'total_weeks' => $request->total_weeks,
                'start_date' => $request->start_date,
                'payment_plan' => $request->payment_plan,
                'auto_booking_enabled' => $request->boolean('auto_booking_enabled', true),
                'duration_minutes' => $request->duration_minutes ?? 60,
                'therapist_level' => $request->therapist_level ?? 'senior',
                'location' => $request->location ?? 'clinic',
                'goals' => $request->goals,
                'notes' => $request->notes,
                'preferred_times' => $request->preferred_times,
                'preferred_days' => $request->preferred_days
            ];

            $program = $this->programService->createProgram($data);

            return response()->json([
                'success' => true,
                'message' => 'Program created successfully.',
                'program_id' => $program->id,
                'redirect' => route('clinic.programs.show', $program->id)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create program: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show program details
     */
    public function show($id)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);

        $program = WeeklyProgram::where('clinic_id', $clinic->id)
            ->with(['patient', 'therapist', 'episode', 'sessions.appointment'])
            ->findOrFail($id);

        $sessionsByWeek = $program->sessions()
            ->orderBy('week_number')
            ->orderBy('session_number')
            ->get()
            ->groupBy('week_number');

        return view('web.clinic.programs.show', compact('program', 'sessionsByWeek', 'clinic'));
    }

    /**
     * Activate program
     */
    public function activate($id)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);

        $program = WeeklyProgram::where('clinic_id', $clinic->id)->findOrFail($id);

        try {
            $this->programService->activateProgram($program);

            return redirect()->back()->with('success', 'Program activated and sessions booked successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to activate program: ' . $e->getMessage());
        }
    }

    /**
     * Calculate program price preview
     */
    public function calculatePrice(Request $request)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);

        $validator = Validator::make($request->all(), [
            'specialty' => 'required|string',
            'sessions_per_week' => 'required|integer|min:1|max:5',
            'total_weeks' => 'required|integer|min:1|max:52',
            'duration_minutes' => 'nullable|integer',
            'therapist_level' => 'nullable|string',
            'location' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $pricing = $this->paymentCalculator->calculateProgramPrice(
                $clinic,
                $request->all()
            );

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
     * Get user's clinic
     */
    protected function getUserClinic($user)
    {
        if (method_exists($user, 'clinic') && $user->clinic) {
            return $user->clinic;
        }

        if ($user->type === 'company' && method_exists($user, 'clinics')) {
            return $user->clinics()->first();
        }

        return \App\Models\Clinic::where('company_id', $user->id)->first();
    }
}

