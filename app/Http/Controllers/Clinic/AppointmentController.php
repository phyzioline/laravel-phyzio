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
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    protected $fieldsService;
    protected $paymentCalculator;

    public function __construct(
        SpecialtyReservationFieldsService $fieldsService,
        PaymentCalculatorService $paymentCalculator
    ) {
        $this->fieldsService = $fieldsService;
        $this->paymentCalculator = $paymentCalculator;
    }

    /**
     * Display the calendar view.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $endOfWeek = $endOfWeek->addDays(7); // Show 2 weeks
        
        $appointments = ClinicAppointment::with(['patient', 'doctor', 'additionalData'])
                        ->where('clinic_id', $clinic->id ?? 0)
                        ->whereBetween('appointment_date', [$startOfWeek, $endOfWeek])
                        ->get();

        $patients = Patient::where('clinic_id', $clinic->id ?? 0)->get(); 
        $therapists = User::where('type', 'therapist')->get();

        return view('web.clinic.appointments.index', compact('appointments', 'startOfWeek', 'patients', 'therapists', 'clinic'));
    }

    /**
     * Show form to create new appointment
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
        $therapists = User::where('type', 'therapist')->get();
        
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
            return redirect()->back()->with('error', 'Clinic not found.');
        }

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'visit_type' => 'required|in:evaluation,followup,re_evaluation',
            'location' => 'required|in:clinic,home',
            'payment_method' => 'nullable|in:cash,card,insurance',
            'specialty' => 'required|string',
            'duration_minutes' => 'nullable|integer|min:15|max:120',
            'session_type' => 'nullable|string'
        ]);

        $start = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
        
        // Create appointment
        $appointment = ClinicAppointment::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $start,
            'duration_minutes' => $request->duration_minutes ?? 60,
            'status' => 'scheduled',
            'visit_type' => $request->visit_type,
            'location' => $request->location,
            'payment_method' => $request->payment_method,
            'specialty' => $request->specialty,
            'session_type' => $request->session_type
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

        return Clinic::where('company_id', $user->id)->first();
    }
}
