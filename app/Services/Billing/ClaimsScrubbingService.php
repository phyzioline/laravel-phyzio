<?php

namespace App\Services\Billing;

use App\Models\InsuranceClaim;
use Illuminate\Support\Facades\Log;

class ClaimsScrubbingService
{
    /**
     * Scrub claim for errors before submission
     *
     * @param InsuranceClaim $claim
     * @return array
     */
    public function scrubClaim(InsuranceClaim $claim): array
    {
        $errors = [];
        $warnings = [];
        $checks = [];

        // 1. Required fields check
        $checks['required_fields'] = $this->checkRequiredFields($claim, $errors);

        // 2. Diagnosis codes validation
        $checks['diagnosis_codes'] = $this->validateDiagnosisCodes($claim, $errors, $warnings);

        // 3. Procedure codes validation
        $checks['procedure_codes'] = $this->validateProcedureCodes($claim, $errors, $warnings);

        // 4. Date validation
        $checks['dates'] = $this->validateDates($claim, $errors);

        // 5. Authorization check
        $checks['authorization'] = $this->checkAuthorization($claim, $warnings);

        // 6. Eligibility check
        $checks['eligibility'] = $this->checkEligibility($claim, $warnings);

        // 7. NCCI edits (National Correct Coding Initiative)
        $checks['ncci'] = $this->checkNCCIEdits($claim, $errors, $warnings);

        // 8. 8-minute rule validation
        $checks['eight_minute_rule'] = $this->checkEightMinuteRule($claim, $warnings);

        return [
            'errors' => $errors,
            'warnings' => $warnings,
            'checks' => $checks,
            'can_submit' => empty($errors),
            'scrubbed_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * Check required fields
     */
    protected function checkRequiredFields(InsuranceClaim $claim, array &$errors): bool
    {
        $required = [
            'patient_id' => $claim->patient_id,
            'patient_insurance_id' => $claim->patient_insurance_id,
            'date_of_service' => $claim->date_of_service,
            'billed_amount' => $claim->billed_amount,
        ];

        foreach ($required as $field => $value) {
            if (empty($value)) {
                $errors[] = "Required field missing: {$field}";
            }
        }

        return empty($errors);
    }

    /**
     * Validate diagnosis codes (ICD-10)
     */
    protected function validateDiagnosisCodes(InsuranceClaim $claim, array &$errors, array &$warnings): bool
    {
        if (empty($claim->diagnosis_codes)) {
            $errors[] = 'Diagnosis codes are required';
            return false;
        }

        $codes = array_filter(explode(',', $claim->diagnosis_codes));
        
        if (empty($codes)) {
            $errors[] = 'At least one diagnosis code is required';
            return false;
        }

        // Validate ICD-10 format (basic check)
        foreach ($codes as $code) {
            $code = trim($code);
            // ICD-10 codes: Letter followed by 2-7 alphanumeric characters
            if (!preg_match('/^[A-Z][0-9A-Z]{1,6}$/i', $code)) {
                $warnings[] = "Invalid ICD-10 format: {$code}";
            }
        }

        return true;
    }

    /**
     * Validate procedure codes (CPT)
     */
    protected function validateProcedureCodes(InsuranceClaim $claim, array &$errors, array &$warnings): bool
    {
        if (empty($claim->procedure_codes)) {
            $errors[] = 'Procedure codes are required';
            return false;
        }

        $codes = array_filter(explode(',', $claim->procedure_codes));
        
        if (empty($codes)) {
            $errors[] = 'At least one procedure code is required';
            return false;
        }

        // Validate CPT format (5 digits)
        foreach ($codes as $code) {
            $code = trim($code);
            if (!preg_match('/^\d{5}$/', $code)) {
                $warnings[] = "Invalid CPT format: {$code} (should be 5 digits)";
            }
        }

        return true;
    }

    /**
     * Validate dates
     */
    protected function validateDates(InsuranceClaim $claim, array &$errors): bool
    {
        if (!$claim->date_of_service) {
            $errors[] = 'Date of service is required';
            return false;
        }

        // Date of service cannot be in the future
        if ($claim->date_of_service->isFuture()) {
            $errors[] = 'Date of service cannot be in the future';
        }

        // Date of service should not be too old (typically 1 year)
        if ($claim->date_of_service->lt(now()->subYear())) {
            $warnings[] = 'Date of service is more than 1 year old';
        }

        return true;
    }

    /**
     * Check authorization
     */
    protected function checkAuthorization(InsuranceClaim $claim, array &$warnings): bool
    {
        if (!$claim->authorization_id) {
            $warnings[] = 'No authorization attached to claim';
            return false;
        }

        $authorization = $claim->authorization;
        
        if (!$authorization->isValidForDate($claim->date_of_service)) {
            $warnings[] = 'Authorization is not valid for date of service';
            return false;
        }

        return true;
    }

    /**
     * Check eligibility
     */
    protected function checkEligibility(InsuranceClaim $claim, array &$warnings): bool
    {
        $insurance = $claim->patientInsurance;
        
        if (!$insurance->is_active) {
            $warnings[] = 'Patient insurance is not active';
            return false;
        }

        // Check if coverage is valid for date of service
        if ($claim->date_of_service->lt($insurance->effective_date)) {
            $warnings[] = 'Date of service is before insurance effective date';
            return false;
        }

        if ($insurance->expiration_date && $claim->date_of_service->gt($insurance->expiration_date)) {
            $warnings[] = 'Date of service is after insurance expiration date';
            return false;
        }

        return true;
    }

    /**
     * Check NCCI edits (simplified)
     */
    protected function checkNCCIEdits(InsuranceClaim $claim, array &$errors, array &$warnings): bool
    {
        // This is a simplified check. In production, you'd use NCCI edit database
        $procedureCodes = array_filter(explode(',', $claim->procedure_codes ?? ''));
        
        if (count($procedureCodes) > 1) {
            // Check for mutually exclusive codes
            // This would require a comprehensive NCCI database
            $warnings[] = 'Multiple procedure codes - verify NCCI edits';
        }

        return true;
    }

    /**
     * Check 8-minute rule for timed codes
     */
    protected function checkEightMinuteRule(InsuranceClaim $claim, array &$warnings): bool
    {
        // 8-minute rule: For timed CPT codes, units are based on 8-minute increments
        // This is a simplified check
        if ($claim->appointment) {
            $duration = $claim->appointment->duration_minutes ?? 0;
            
            if ($duration > 0 && $duration < 8) {
                $warnings[] = 'Duration is less than 8 minutes - may not qualify for timed codes';
            }
        }

        return true;
    }
}

