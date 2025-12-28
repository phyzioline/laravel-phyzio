<?php

namespace App\Http\Controllers\Clinic;

use App\Models\InsuranceClaim;
use App\Models\ClinicAppointment;
use App\Services\Billing\ClaimsSubmissionService;
use App\Services\Billing\DenialManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsuranceClaimController extends BaseClinicController
{
    protected $claimsSubmissionService;
    protected $denialManagementService;

    public function __construct(
        ClaimsSubmissionService $claimsSubmissionService,
        DenialManagementService $denialManagementService
    ) {
        $this->claimsSubmissionService = $claimsSubmissionService;
        $this->denialManagementService = $denialManagementService;
    }

    /**
     * Display a listing of insurance claims
     */
    public function index(Request $request)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            $claims = collect();
            return view('web.clinic.insurance_claims.index', compact('claims', 'clinic'));
        }

        $query = InsuranceClaim::where('clinic_id', $clinic->id)
            ->with(['patient', 'appointment', 'patientInsurance.insuranceProvider']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('date_from')) {
            $query->where('date_of_service', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date_of_service', '<=', $request->date_to);
        }

        $claims = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total' => InsuranceClaim::where('clinic_id', $clinic->id)->count(),
            'pending' => InsuranceClaim::where('clinic_id', $clinic->id)->where('status', 'pending')->count(),
            'submitted' => InsuranceClaim::where('clinic_id', $clinic->id)->where('status', 'submitted')->count(),
            'paid' => InsuranceClaim::where('clinic_id', $clinic->id)->where('status', 'paid')->count(),
            'denied' => InsuranceClaim::where('clinic_id', $clinic->id)->where('status', 'denied')->count(),
        ];

        return view('web.clinic.insurance_claims.index', compact('claims', 'stats', 'clinic'));
    }

    /**
     * Create claim from appointment
     */
    public function createFromAppointment($appointmentId)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return redirect()->back()->with('error', 'Clinic not found.');
        }

        $appointment = ClinicAppointment::where('clinic_id', $clinic->id)
            ->with(['patient', 'invoice'])
            ->findOrFail($appointmentId);

        // Check if claim already exists
        $existingClaim = InsuranceClaim::where('appointment_id', $appointment->id)->first();
        if ($existingClaim) {
            return redirect()->route('clinic.insurance-claims.show', $existingClaim->id)
                ->with('info', 'Claim already exists for this appointment.');
        }

        try {
            $claim = $this->claimsSubmissionService->createClaimFromAppointment(
                $appointment,
                $appointment->invoice
            );

            return redirect()->route('clinic.insurance-claims.show', $claim->id)
                ->with('success', 'Claim created successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create claim: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified claim
     */
    public function show($id)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }

        $claim = InsuranceClaim::where('clinic_id', $clinic->id)
            ->with([
                'patient',
                'appointment',
                'invoice',
                'patientInsurance.insuranceProvider',
                'authorization',
                'denials'
            ])
            ->findOrFail($id);

        return view('web.clinic.insurance_claims.show', compact('claim', 'clinic'));
    }

    /**
     * Submit claim
     */
    public function submit($id)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return redirect()->back()->with('error', 'Clinic not found.');
        }

        $claim = InsuranceClaim::where('clinic_id', $clinic->id)->findOrFail($id);

        if ($claim->status !== 'draft' && $claim->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only draft or pending claims can be submitted.');
        }

        try {
            $submitted = $this->claimsSubmissionService->submitClaim($claim);

            if ($submitted) {
                return redirect()->back()
                    ->with('success', 'Claim submitted successfully.');
            } else {
                return redirect()->back()
                    ->with('error', 'Failed to submit claim. Please check for errors.');
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to submit claim: ' . $e->getMessage());
        }
    }

    /**
     * Batch submit claims
     */
    public function batchSubmit(Request $request)
    {
        $clinic = $this->getUserClinic();

        if (!$clinic) {
            return response()->json(['success' => false, 'message' => 'Clinic not found.'], 404);
        }

        $claimIds = $request->input('claim_ids', []);

        if (empty($claimIds)) {
            return response()->json(['success' => false, 'message' => 'No claims selected.'], 400);
        }

        try {
            $results = $this->claimsSubmissionService->batchSubmit($claimIds);
            $successCount = count(array_filter($results));

            return response()->json([
                'success' => true,
                'message' => "{$successCount} of " . count($claimIds) . " claims submitted successfully.",
                'results' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit claims: ' . $e->getMessage()
            ], 500);
        }
    }
}

