<?php

namespace App\Services\Clinic;

use App\Models\Clinic;

class SpecialtyModuleActivationService
{
    /**
     * Check if a feature/module should be visible for clinic
     * 
     * @param Clinic $clinic
     * @param string $feature
     * @return bool
     */
    public function isFeatureVisible(Clinic $clinic, string $feature): bool
    {
        if (!$clinic->hasSelectedSpecialty()) {
            return false; // No features visible until specialty selected
        }

        $specialty = $clinic->primary_specialty;
        $activeSpecialties = $clinic->activeSpecialties->pluck('specialty')->toArray();

        // Check if feature is available for any active specialty
        $availableFeatures = $this->getAvailableFeatures($specialty, $activeSpecialties);

        return in_array($feature, $availableFeatures);
    }

    /**
     * Get available features for specialty(ies)
     * 
     * @param string $primarySpecialty
     * @param array $activeSpecialties
     * @return array
     */
    protected function getAvailableFeatures(string $primarySpecialty, array $activeSpecialties): array
    {
        $features = [];

        // Core features available to all
        $coreFeatures = [
            'patients',
            'appointments',
            'billing',
            'reports',
            'staff',
            'settings'
        ];

        $features = array_merge($features, $coreFeatures);

        // Specialty-specific features
        foreach ($activeSpecialties as $specialty) {
            $specialtyFeatures = $this->getSpecialtyFeatures($specialty);
            $features = array_merge($features, $specialtyFeatures);
        }

        // Remove duplicates
        return array_unique($features);
    }

    /**
     * Get features for a specific specialty
     * 
     * @param string $specialty
     * @return array
     */
    protected function getSpecialtyFeatures(string $specialty): array
    {
        return match($specialty) {
            'orthopedic' => [
                'rom_measurements',
                'pain_scales',
                'postural_assessment',
                'gait_analysis',
                'equipment_management',
                'orthopedic_assessments',
                'orthopedic_treatment_plans'
            ],
            'pediatric' => [
                'developmental_milestones',
                'pediatric_assessments',
                'parent_portal',
                'school_reports',
                'play_based_therapy',
                'pediatric_treatment_plans',
                'guardian_management'
            ],
            'neurological' => [
                'neurological_assessments',
                'fim_scoring',
                'berg_balance',
                'spasticity_tracking',
                'caregiver_tracking',
                'neurological_treatment_plans',
                'recovery_curves'
            ],
            'sports' => [
                'athlete_profiles',
                'return_to_play',
                'performance_metrics',
                'sports_assessments',
                'coach_reports',
                'sports_treatment_plans',
                'competition_calendar'
            ],
            'geriatric' => [
                'fall_risk_assessment',
                'mobility_scales',
                'cognitive_screening',
                'family_access',
                'geriatric_assessments',
                'geriatric_treatment_plans',
                'safety_tracking'
            ],
            'womens_health' => [
                'pelvic_floor_assessments',
                'biofeedback_tracking',
                'pregnancy_tracking',
                'privacy_controls',
                'womens_health_assessments',
                'womens_health_treatment_plans'
            ],
            'cardiorespiratory' => [
                'vital_signs_monitoring',
                'exercise_tolerance',
                'cardiac_rehab',
                'respiratory_function',
                'cardiorespiratory_assessments',
                'cardiorespiratory_treatment_plans'
            ],
            'home_care' => [
                'home_assessments',
                'travel_optimization',
                'portable_equipment',
                'route_planning',
                'home_care_assessments',
                'home_care_treatment_plans'
            ],
            'multi_specialty' => [
                // All features available
                'all_assessments',
                'all_treatment_plans',
                'specialty_selector'
            ],
            default => []
        };
    }

    /**
     * Get hidden features for specialty (features to hide)
     * 
     * @param string $specialty
     * @return array
     */
    public function getHiddenFeatures(string $specialty): array
    {
        // Pediatric clinics should hide adult-only features
        if ($specialty === 'pediatric') {
            return [
                'adult_assessments',
                'adult_treatment_protocols',
                'geriatric_features'
            ];
        }

        // Geriatric clinics might hide pediatric features
        if ($specialty === 'geriatric') {
            return [
                'pediatric_features',
                'developmental_milestones'
            ];
        }

        return [];
    }

    /**
     * Check if module should be active
     * 
     * @param Clinic $clinic
     * @param string $module
     * @return bool
     */
    public function isModuleActive(Clinic $clinic, string $module): bool
    {
        return $this->isFeatureVisible($clinic, $module);
    }

    /**
     * Get workflow for specialty
     * 
     * @param string $specialty
     * @return array
     */
    public function getWorkflow(string $specialty): array
    {
        return match($specialty) {
            'orthopedic' => [
                'assessment' => 'orthopedic_assessment',
                'treatment' => 'orthopedic_treatment',
                'outcome' => 'orthopedic_outcome'
            ],
            'pediatric' => [
                'assessment' => 'pediatric_assessment',
                'treatment' => 'pediatric_treatment',
                'outcome' => 'pediatric_outcome',
                'parent_communication' => 'parent_reports'
            ],
            'neurological' => [
                'assessment' => 'neurological_assessment',
                'treatment' => 'neurological_treatment',
                'outcome' => 'neurological_outcome',
                'phases' => ['acute', 'subacute', 'chronic']
            ],
            'sports' => [
                'assessment' => 'sports_assessment',
                'treatment' => 'sports_treatment',
                'outcome' => 'return_to_play',
                'phases' => ['acute', 'subacute', 'return_to_play']
            ],
            default => [
                'assessment' => 'standard_assessment',
                'treatment' => 'standard_treatment',
                'outcome' => 'standard_outcome'
            ]
        };
    }
}

