<?php

namespace App\Services\Billing;

use App\Models\EligibilityVerification;
use App\Models\PatientInsurance;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EligibilityVerificationService
{
    /**
     * Verify patient insurance eligibility
     *
     * @param PatientInsurance $patientInsurance
     * @param Carbon|null $serviceDate
     * @return EligibilityVerification
     */
    public function verifyEligibility(PatientInsurance $patientInsurance, ?Carbon $serviceDate = null): EligibilityVerification
    {
        $serviceDate = $serviceDate ?? Carbon::now();

        // Check for recent verification (within last 7 days)
        $recentVerification = EligibilityVerification::where('patient_insurance_id', $patientInsurance->id)
            ->where('status', 'verified')
            ->where('verification_date', '>=', Carbon::now()->subDays(7))
            ->where(function ($query) use ($serviceDate) {
                $query->whereNull('service_date')
                    ->orWhere('service_date', $serviceDate->format('Y-m-d'));
            })
            ->first();

        if ($recentVerification) {
            return $recentVerification;
        }

        // Create new verification record
        $verification = EligibilityVerification::create([
            'clinic_id' => $patientInsurance->patient->clinic_id ?? null,
            'patient_id' => $patientInsurance->patient_id,
            'patient_insurance_id' => $patientInsurance->id,
            'verification_date' => Carbon::now(),
            'service_date' => $serviceDate,
            'status' => 'pending',
        ]);

        try {
            // Attempt API verification if provider has endpoint configured
            $provider = $patientInsurance->insuranceProvider;
            
            if ($provider && $provider->eligibility_endpoint && $provider->settings) {
                $result = $this->verifyViaAPI($patientInsurance, $serviceDate, $provider);
                
                $verification->update([
                    'status' => $result['status'],
                    'is_eligible' => $result['is_eligible'],
                    'coverage_start_date' => $result['coverage_start_date'] ?? null,
                    'coverage_end_date' => $result['coverage_end_date'] ?? null,
                    'benefits' => $result['benefits'] ?? null,
                    'copay_info' => $result['copay_info'] ?? null,
                    'deductible_info' => $result['deductible_info'] ?? null,
                    'response_data' => $result['response_data'] ?? null,
                    'error_message' => $result['error_message'] ?? null,
                ]);
            } else {
                // Manual verification based on dates
                $isEligible = $this->verifyManually($patientInsurance, $serviceDate);
                
                $verification->update([
                    'status' => 'verified',
                    'is_eligible' => $isEligible,
                    'coverage_start_date' => $patientInsurance->effective_date,
                    'coverage_end_date' => $patientInsurance->expiration_date,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Eligibility verification failed', [
                'patient_insurance_id' => $patientInsurance->id,
                'error' => $e->getMessage()
            ]);

            $verification->update([
                'status' => 'failed',
                'is_eligible' => false,
                'error_message' => $e->getMessage(),
            ]);
        }

        return $verification->fresh();
    }

    /**
     * Verify eligibility via API
     *
     * @param PatientInsurance $patientInsurance
     * @param Carbon $serviceDate
     * @param \App\Models\InsuranceProvider $provider
     * @return array
     */
    protected function verifyViaAPI(PatientInsurance $patientInsurance, Carbon $serviceDate, $provider): array
    {
        $settings = $provider->settings ?? [];
        $endpoint = $provider->eligibility_endpoint;
        $apiKey = $settings['api_key'] ?? null;

        if (!$apiKey) {
            return [
                'status' => 'failed',
                'is_eligible' => false,
                'error_message' => 'API key not configured',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post($endpoint, [
                'member_id' => $patientInsurance->policy_number,
                'group_number' => $patientInsurance->group_number,
                'date_of_service' => $serviceDate->format('Y-m-d'),
                'subscriber_name' => $patientInsurance->subscriber_name,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'status' => 'verified',
                    'is_eligible' => $data['eligible'] ?? false,
                    'coverage_start_date' => $data['coverage_start'] ?? null,
                    'coverage_end_date' => $data['coverage_end'] ?? null,
                    'benefits' => $data['benefits'] ?? null,
                    'copay_info' => $data['copay'] ?? null,
                    'deductible_info' => $data['deductible'] ?? null,
                    'response_data' => json_encode($data),
                ];
            } else {
                return [
                    'status' => 'failed',
                    'is_eligible' => false,
                    'error_message' => 'API request failed: ' . $response->status(),
                    'response_data' => $response->body(),
                ];
            }

        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'is_eligible' => false,
                'error_message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Manual verification based on dates
     *
     * @param PatientInsurance $patientInsurance
     * @param Carbon $serviceDate
     * @return bool
     */
    protected function verifyManually(PatientInsurance $patientInsurance, Carbon $serviceDate): bool
    {
        if (!$patientInsurance->is_active) {
            return false;
        }

        if ($serviceDate->lessThan($patientInsurance->effective_date)) {
            return false;
        }

        if ($patientInsurance->expiration_date && $serviceDate->greaterThan($patientInsurance->expiration_date)) {
            return false;
        }

        return true;
    }

    /**
     * Batch verify multiple patients
     *
     * @param array $patientInsuranceIds
     * @param Carbon|null $serviceDate
     * @return array
     */
    public function batchVerify(array $patientInsuranceIds, ?Carbon $serviceDate = null): array
    {
        $results = [];

        foreach ($patientInsuranceIds as $insuranceId) {
            $patientInsurance = PatientInsurance::find($insuranceId);
            if ($patientInsurance) {
                $results[$insuranceId] = $this->verifyEligibility($patientInsurance, $serviceDate);
            }
        }

        return $results;
    }
}

