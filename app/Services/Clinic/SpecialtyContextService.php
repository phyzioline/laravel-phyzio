<?php

namespace App\Services\Clinic;

use InvalidArgumentException;

class SpecialtyContextService
{
    protected $schemas = [
        'orthopedic' => [
            'assessment_fields' => ['pain_vas', 'rom', 'mmt', 'gait_analysis', 'special_tests', 'postural_assessment'],
            'red_flags' => ['cauda_equina', 'fracture_signs', 'infection_signs'],
            'outcome_metrics' => ['VAS', 'ROM', 'ODI', 'LEFS'],
            'default_session_duration' => 60,
            'typical_sessions_per_week' => [2, 3]
        ],
        'neurological' => [
            'assessment_fields' => ['reflexes', 'muscle_tone', 'sensation', 'coordination', 'balance_berg', 'cranial_nerves', 'spasticity_ashworth'],
            'red_flags' => ['worst_headache', 'sudden_vision_loss', 'slurred_speech'],
            'outcome_metrics' => ['Berg Balance', 'FIM', 'TUG', 'Modified Ashworth'],
            'default_session_duration' => 90,
            'typical_sessions_per_week' => [3, 5]
        ],
        'pediatric' => [
            'assessment_fields' => ['developmental_milestones', 'reflexes_primitive', 'muscle_tone', 'posture', 'parent_concerns', 'sensory_integration'],
            'red_flags' => ['regression', 'seizures', 'abuse_signs'],
            'outcome_metrics' => ['GMFM', 'Peabody', 'PEDI'],
            'default_session_duration' => 45,
            'typical_sessions_per_week' => [1, 2]
        ],
        'sports' => [
            'assessment_fields' => ['sport_specific_movement', 'power_output', 'agility', 'endurance', 'load_tolerance', 'strength_symmetry', 'fms'],
            'red_flags' => ['concussion_signs', 'cardiac_signs'],
            'outcome_metrics' => ['Return to Play %', 'Vertical Jump', 'Agility Time'],
            'default_session_duration' => 60,
            'typical_sessions_per_week' => [2, 4]
        ],
        'geriatric' => [
            'assessment_fields' => ['fall_risk_morse', 'mobility_scales', 'cognitive_screening', 'balance_assessment', 'assistive_device_use'],
            'red_flags' => ['severe_fall_risk', 'cognitive_decline', 'medication_interactions'],
            'outcome_metrics' => ['Morse Fall Scale', 'TUG', 'Berg Balance', 'MMSE'],
            'default_session_duration' => 45,
            'typical_sessions_per_week' => [1, 2]
        ],
        'womens_health' => [
            'assessment_fields' => ['pelvic_floor_strength', 'pain_function_questionnaires', 'posture_pregnancy', 'diastasis_recti'],
            'red_flags' => ['severe_pain', 'bleeding', 'infection_signs'],
            'outcome_metrics' => ['Pelvic Floor Strength', 'Pain Scale', 'Functional Status'],
            'default_session_duration' => 60,
            'typical_sessions_per_week' => [1, 2],
            'requires_privacy' => true
        ],
        'cardiorespiratory' => [
            'assessment_fields' => ['vital_signs', 'exercise_tolerance', 'respiratory_function', 'functional_capacity'],
            'red_flags' => ['chest_pain', 'severe_dyspnea', 'arrhythmia'],
            'outcome_metrics' => ['6MWT', 'VO2 Max', 'O2 Saturation', 'Exercise Tolerance'],
            'default_session_duration' => 60,
            'typical_sessions_per_week' => [2, 3]
        ],
        'home_care' => [
            'assessment_fields' => ['home_environment', 'safety_assessment', 'functional_mobility', 'equipment_availability'],
            'red_flags' => ['unsafe_environment', 'patient_unsafe_at_home'],
            'outcome_metrics' => ['Functional Independence', 'Home Safety Score'],
            'default_session_duration' => 60,
            'typical_sessions_per_week' => [1, 3],
            'requires_travel' => true
        ],
        'multi_specialty' => [
            'assessment_fields' => [], // Dynamic based on selected specialty per episode
            'red_flags' => [],
            'outcome_metrics' => [],
            'default_session_duration' => 60,
            'typical_sessions_per_week' => [2, 3],
            'is_multi_specialty' => true
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
        if (!isset($this->schemas[$specialty])) {
            return false;
        }

        $schema = $this->schemas[$specialty];
        
        // Check if required assessment fields are present
        if (isset($schema['required_fields'])) {
            foreach ($schema['required_fields'] as $field) {
                if (!isset($data[$field])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get default session duration for specialty
     */
    public function getDefaultSessionDuration($specialty): int
    {
        if (!isset($this->schemas[$specialty])) {
            return 60; // Default
        }

        return $this->schemas[$specialty]['default_session_duration'] ?? 60;
    }

    /**
     * Get typical sessions per week for specialty
     */
    public function getTypicalSessionsPerWeek($specialty): array
    {
        if (!isset($this->schemas[$specialty])) {
            return [2, 3]; // Default
        }

        return $this->schemas[$specialty]['typical_sessions_per_week'] ?? [2, 3];
    }

    /**
     * Check if specialty requires privacy controls
     */
    public function requiresPrivacy($specialty): bool
    {
        if (!isset($this->schemas[$specialty])) {
            return false;
        }

        return $this->schemas[$specialty]['requires_privacy'] ?? false;
    }

    /**
     * Check if specialty requires travel (home care)
     */
    public function requiresTravel($specialty): bool
    {
        if (!isset($this->schemas[$specialty])) {
            return false;
        }

        return $this->schemas[$specialty]['requires_travel'] ?? false;
    }

    /**
     * Get all available specialties
     */
    public function getAvailableSpecialties(): array
    {
        return array_keys($this->schemas);
    }
}
