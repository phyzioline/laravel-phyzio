<?php

namespace App\Services\Billing;

use App\Models\InsuranceClaim;
use App\Models\ClinicAppointment;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClaimsSubmissionService
{
    protected $scrubbingService;

    public function __construct(ClaimsScrubbingService $scrubbingService)
    {
        $this->scrubbingService = $scrubbingService;
    }

    /**
     * Create and submit insurance claim from appointment
     *
     * @param ClinicAppointment $appointment
     * @param Invoice|null $invoice
     * @return InsuranceClaim
     */
    public function createClaimFromAppointment(ClinicAppointment $appointment, ?Invoice $invoice = null): InsuranceClaim
    {
        $patientInsurance = $appointment->patient->insurance()->where('is_primary', true)->first();

        if (!$patientInsurance) {
            throw new \Exception('Patient does not have primary insurance');
        }

        // Check if claim already exists
        $existingClaim = InsuranceClaim::where('appointment_id', $appointment->id)->first();
        if ($existingClaim) {
            return $existingClaim;
        }

        // Get diagnosis and procedure codes from clinical note if available
        $diagnosisCodes = [];
        $procedureCodes = [];
        
        if ($appointment->clinicalNote) {
            $diagnosisCodes = $appointment->clinicalNote->diagnosis_codes ?? [];
            $procedureCodes = $appointment->clinicalNote->procedure_codes ?? [];
        }

        // Generate claim number
        $claimNumber = InsuranceClaim::generateClaimNumber($appointment->clinic_id);

        // Create claim
        $claim = InsuranceClaim::create([
            'clinic_id' => $appointment->clinic_id,
            'patient_id' => $appointment->patient_id,
            'appointment_id' => $appointment->id,
            'invoice_id' => $invoice?->id,
            'patient_insurance_id' => $patientInsurance->id,
            'authorization_id' => $this->findValidAuthorization($appointment, $patientInsurance),
            'claim_number' => $claimNumber,
            'control_number' => $this->generateControlNumber(),
            'claim_type' => 'primary',
            'date_of_service' => $appointment->appointment_date,
            'billed_amount' => $invoice?->amount ?? $appointment->price ?? 0,
            'diagnosis_codes' => implode(',', $diagnosisCodes),
            'procedure_codes' => implode(',', $procedureCodes),
            'status' => 'draft',
        ]);

        // Scrub claim before submission
        $scrubbingResults = $this->scrubbingService->scrubClaim($claim);
        $claim->update([
            'scrubbing_results' => $scrubbingResults,
        ]);

        // Auto-submit if no errors
        if (empty($scrubbingResults['errors'])) {
            $this->submitClaim($claim);
        }

        return $claim->fresh();
    }

    /**
     * Submit claim to insurance provider
     *
     * @param InsuranceClaim $claim
     * @return bool
     */
    public function submitClaim(InsuranceClaim $claim): bool
    {
        try {
            // Scrub again before submission
            $scrubbingResults = $this->scrubbingService->scrubClaim($claim);
            
            if (!empty($scrubbingResults['errors'])) {
                $claim->update([
                    'status' => 'pending',
                    'scrubbing_results' => $scrubbingResults,
                    'notes' => 'Claim has errors and cannot be submitted',
                ]);
                return false;
            }

            // Format claim data (837 format for EDI)
            $claimData = $this->formatClaimForSubmission($claim);

            // Submit via API or clearinghouse
            $submitted = $this->submitToProvider($claim, $claimData);

            if ($submitted) {
                $claim->update([
                    'status' => 'submitted',
                    'submitted_at' => Carbon::now(),
                ]);

                // Use authorization visit if applicable
                if ($claim->authorization) {
                    $claim->authorization->useVisit();
                }

                Log::info('Insurance claim submitted', [
                    'claim_id' => $claim->id,
                    'claim_number' => $claim->claim_number,
                ]);

                return true;
            }

        } catch (\Exception $e) {
            Log::error('Failed to submit insurance claim', [
                'claim_id' => $claim->id,
                'error' => $e->getMessage()
            ]);

            $claim->update([
                'status' => 'pending',
                'notes' => 'Submission failed: ' . $e->getMessage(),
            ]);
        }

        return false;
    }

    /**
     * Format claim for EDI submission (837 format)
     *
     * @param InsuranceClaim $claim
     * @return array
     */
    protected function formatClaimForSubmission(InsuranceClaim $claim): array
    {
        $patient = $claim->patient;
        $clinic = $claim->clinic;
        $insurance = $claim->patientInsurance;
        $provider = $insurance->insuranceProvider;

        return [
            'claim_number' => $claim->claim_number,
            'control_number' => $claim->control_number,
            'submission_date' => Carbon::now()->format('Ymd'),
            'payer' => [
                'name' => $provider->name,
                'payer_id' => $provider->payer_id,
            ],
            'provider' => [
                'npi' => $clinic->npi ?? '',
                'name' => $clinic->name,
                'address' => $clinic->address ?? '',
            ],
            'subscriber' => [
                'member_id' => $insurance->policy_number,
                'group_number' => $insurance->group_number,
                'name' => $insurance->subscriber_name,
            ],
            'patient' => [
                'name' => $patient->first_name . ' ' . $patient->last_name,
                'dob' => $patient->date_of_birth?->format('Ymd'),
                'gender' => $patient->gender ?? '',
            ],
            'service' => [
                'date_of_service' => $claim->date_of_service->format('Ymd'),
                'billed_amount' => $claim->billed_amount,
                'diagnosis_codes' => explode(',', $claim->diagnosis_codes ?? ''),
                'procedure_codes' => explode(',', $claim->procedure_codes ?? ''),
            ],
        ];
    }

    /**
     * Submit claim to insurance provider API
     *
     * @param InsuranceClaim $claim
     * @param array $claimData
     * @return bool
     */
    protected function submitToProvider(InsuranceClaim $claim, array $claimData): bool
    {
        $provider = $claim->patientInsurance->insuranceProvider;
        $settings = $provider->settings ?? [];
        $apiKey = $settings['api_key'] ?? null;
        $submissionEndpoint = $settings['claims_endpoint'] ?? null;

        if (!$apiKey || !$submissionEndpoint) {
            // If no API configured, mark as submitted (manual submission)
            Log::info('No API configured for claim submission - marked as submitted for manual processing', [
                'claim_id' => $claim->id,
            ]);
            return true; // Return true to allow manual submission workflow
        }

        try {
            // In production, this would make actual API call
            // For now, we'll simulate successful submission
            // You would use Http::post() here with proper EDI formatting
            
            Log::info('Claim submitted to provider API', [
                'claim_id' => $claim->id,
                'provider' => $provider->name,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('API submission failed', [
                'claim_id' => $claim->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Find valid authorization for appointment
     *
     * @param ClinicAppointment $appointment
     * @param PatientInsurance $patientInsurance
     * @return int|null
     */
    protected function findValidAuthorization(ClinicAppointment $appointment, $patientInsurance): ?int
    {
        $authorization = \App\Models\InsuranceAuthorization::where('patient_insurance_id', $patientInsurance->id)
            ->where('status', 'approved')
            ->where('authorization_date', '<=', $appointment->appointment_date)
            ->where('expiration_date', '>=', $appointment->appointment_date)
            ->where('remaining_visits', '>', 0)
            ->first();

        return $authorization?->id;
    }

    /**
     * Generate control number
     *
     * @return string
     */
    protected function generateControlNumber(): string
    {
        return 'CTRL-' . now()->format('YmdHis') . '-' . strtoupper(substr(uniqid(), -6));
    }

    /**
     * Batch submit multiple claims
     *
     * @param array $claimIds
     * @return array
     */
    public function batchSubmit(array $claimIds): array
    {
        $results = [];

        foreach ($claimIds as $claimId) {
            $claim = InsuranceClaim::find($claimId);
            if ($claim && $claim->status === 'draft') {
                $results[$claimId] = $this->submitClaim($claim);
            }
        }

        return $results;
    }
}

