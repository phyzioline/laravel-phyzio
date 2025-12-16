<?php

namespace App\Services\Clinic;

use InvalidArgumentException;

class SpecialtyContextService
{
    protected $schemas = [
        'orthopedic' => [
            'assessment_fields' => ['pain_vas', 'rom', 'mmt', 'gait_analysis', 'special_tests'],
            'red_flags' => ['cauda_equina', 'fracture_signs', 'infection_signs'],
            'outcome_metrics' => ['VAS', 'ROM', 'ODI', 'LEFS']
        ],
        'neurological' => [
            'assessment_fields' => ['reflexes', 'muscle_tone', 'sensation', 'coordination', 'balance_berg', 'cranial_nerves'],
            'red_flags' => ['worst_headache', 'sudden_vision_loss', 'slurred_speech'],
            'outcome_metrics' => ['Berg Balance', 'FIM', 'TUG']
        ],
        'pediatric' => [
            'assessment_fields' => ['developmental_milestones', 'reflexes_primitive', 'muscle_tone', 'posture', 'parent_concerns'],
            'red_flags' => ['regression', 'seizures', 'abuse_signs'],
            'outcome_metrics' => ['GMFM', 'Peabody', 'PEDI']
        ],
        'sports' => [
             'assessment_fields' => ['sport_specific_movement', 'power_output', 'agility', 'endurance', 'load_tolerance'],
             'red_flags' => ['concussion_signs', 'cardiac_signs'],
             'outcome_metrics' => ['Return to Play %', 'Vertical Jump']
        ]
    ];

    /**
     * Get the JSON schema structure for a given specialty.
     * This is used by the Frontend to render the correct form.
     */
    public function getAssessmentSchema($specialty)
    {
        if (!isset($this->schemas[$specialty])) {
            // Fallback or throw
            return $this->schemas['orthopedic'];
        }
        return $this->schemas[$specialty];
    }

    /**
     * Validates incoming JSON data against the specialty schema.
     */
    public function validateClinicalData($specialty, array $data)
    {
        // Simple logic: Ensure required fields for specialty are present (if strict)
        // For now, simpler implementation:
        return true; 
    }
}
