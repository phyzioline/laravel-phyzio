<?php

namespace App\Services\Clinical;

use App\Models\ClinicalNote;
use Illuminate\Support\Facades\Log;

class CodingValidationService
{
    /**
     * Validate ICD-10 diagnosis codes
     * 
     * @param array $codes Array of ICD-10 codes
     * @return array ['valid' => bool, 'errors' => [], 'warnings' => []]
     */
    public function validateICD10Codes(array $codes): array
    {
        $errors = [];
        $warnings = [];
        
        foreach ($codes as $code) {
            // Basic ICD-10 format validation (A00.0 to Z99.9)
            if (!preg_match('/^[A-Z][0-9]{2}(\.[0-9]{1,2})?$/', $code)) {
                $errors[] = "Invalid ICD-10 code format: {$code}";
            }
            
            // Check if code exists in ICD-10 database (would need actual database)
            // For now, just validate format
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings
        ];
    }

    /**
     * Validate CPT procedure codes
     * 
     * @param array $codes Array of CPT codes
     * @return array ['valid' => bool, 'errors' => [], 'warnings' => []]
     */
    public function validateCPTCodes(array $codes): array
    {
        $errors = [];
        $warnings = [];
        
        foreach ($codes as $code) {
            // CPT codes are 5 digits (00100-99499)
            if (!preg_match('/^[0-9]{5}$/', $code)) {
                $errors[] = "Invalid CPT code format: {$code}";
            }
            
            // Check if code is valid for physical therapy
            $ptCodes = $this->getPhysicalTherapyCPTCodes();
            if (!in_array($code, $ptCodes)) {
                $warnings[] = "CPT code {$code} may not be typical for physical therapy";
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings
        ];
    }

    /**
     * Check NCCI (National Correct Coding Initiative) edits
     * 
     * @param array $cptCodes Array of CPT codes
     * @return array ['violations' => [], 'warnings' => []]
     */
    public function checkNCCIEdits(array $cptCodes): array
    {
        $violations = [];
        $warnings = [];
        
        // NCCI edits prevent certain code combinations
        // Common PT NCCI edits:
        $ncciEdits = [
            // Evaluation codes cannot be billed together
            '97161' => ['97162', '97163', '97164'],
            '97162' => ['97161', '97163', '97164'],
            '97163' => ['97161', '97162', '97164'],
            '97164' => ['97161', '97162', '97163'],
        ];
        
        foreach ($cptCodes as $code) {
            if (isset($ncciEdits[$code])) {
                $conflicts = array_intersect($cptCodes, $ncciEdits[$code]);
                if (!empty($conflicts)) {
                    $violations[] = "NCCI Edit: Code {$code} cannot be billed with " . implode(', ', $conflicts);
                }
            }
        }
        
        return [
            'violations' => $violations,
            'warnings' => $warnings
        ];
    }

    /**
     * Check 8-minute rule compliance
     * 
     * @param array $cptCodes Array of CPT codes with units
     * @param int $totalMinutes Total treatment minutes
     * @return array ['compliant' => bool, 'errors' => [], 'units' => []]
     */
    public function check8MinuteRule(array $cptCodes, int $totalMinutes): array
    {
        $errors = [];
        $units = [];
        
        // 8-minute rule: Each 15-minute unit requires at least 8 minutes
        // Units calculation:
        // 0-7 minutes = 0 units
        // 8-22 minutes = 1 unit
        // 23-37 minutes = 2 units
        // etc.
        
        foreach ($cptCodes as $code => $minutes) {
            $calculatedUnits = floor(($minutes + 7) / 15); // Round up to nearest unit
            
            if ($minutes > 0 && $calculatedUnits === 0) {
                $errors[] = "Code {$code}: {$minutes} minutes is less than 8-minute minimum";
            }
            
            $units[$code] = $calculatedUnits;
        }
        
        return [
            'compliant' => empty($errors),
            'errors' => $errors,
            'units' => $units
        ];
    }

    /**
     * Validate complete coding for a clinical note
     * 
     * @param ClinicalNote $note
     * @return array ['valid' => bool, 'errors' => [], 'warnings' => [], 'compliance' => []]
     */
    public function validateNoteCoding(ClinicalNote $note): array
    {
        $errors = [];
        $warnings = [];
        $compliance = [];
        
        // Validate ICD-10 codes
        if ($note->diagnosis_codes) {
            $icd10Validation = $this->validateICD10Codes($note->diagnosis_codes);
            $errors = array_merge($errors, $icd10Validation['errors']);
            $warnings = array_merge($warnings, $icd10Validation['warnings']);
        }
        
        // Validate CPT codes
        if ($note->procedure_codes) {
            $cptValidation = $this->validateCPTCodes($note->procedure_codes);
            $errors = array_merge($errors, $cptValidation['errors']);
            $warnings = array_merge($warnings, $cptValidation['warnings']);
            
            // Check NCCI edits
            $ncciCheck = $this->checkNCCIEdits($note->procedure_codes);
            $errors = array_merge($errors, $ncciCheck['violations']);
            $warnings = array_merge($warnings, $ncciCheck['warnings']);
            
            // Check 8-minute rule (if duration available)
            if ($note->appointment && $note->appointment->duration_minutes) {
                // This is simplified - would need actual code-to-minutes mapping
                $eightMinuteCheck = $this->check8MinuteRule(
                    array_fill_keys($note->procedure_codes, $note->appointment->duration_minutes),
                    $note->appointment->duration_minutes
                );
                $errors = array_merge($errors, $eightMinuteCheck['errors']);
                $compliance['8_minute_rule'] = $eightMinuteCheck;
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
            'compliance' => $compliance
        ];
    }

    /**
     * Get common physical therapy CPT codes
     * 
     * @return array
     */
    protected function getPhysicalTherapyCPTCodes(): array
    {
        return [
            // Evaluations
            '97161', '97162', '97163', '97164', // PT evaluations
            '97165', '97166', '97167', '97168', // OT evaluations
            
            // Therapeutic procedures
            '97110', // Therapeutic exercise
            '97112', // Neuromuscular reeducation
            '97113', // Aquatic therapy
            '97116', // Gait training
            '97140', // Manual therapy
            '97150', // Group therapy
            '97530', // Therapeutic activities
            '97535', // Self-care management
            '97537', // Community/work reintegration
            '97542', // Wheelchair management
            '97750', // FCE
            '97755', // Assistive technology assessment
            '97760', // Orthotic management
            '97761', // Prosthetic training
            '97762', // Checkout for orthotic/prosthetic
        ];
    }
}

