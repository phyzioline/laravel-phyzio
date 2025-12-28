<?php

namespace App\Services\Clinic;

use App\Models\Clinic;

class SpecialtyProgramTemplateService
{
    /**
     * Get specialty-specific program template
     * 
     * @param string $specialty
     * @return array
     */
    public function getTemplate(string $specialty): array
    {
        return match($specialty) {
            'orthopedic' => $this->getOrthopedicTemplate(),
            'pediatric' => $this->getPediatricTemplate(),
            'neurological' => $this->getNeurologicalTemplate(),
            'sports' => $this->getSportsTemplate(),
            'geriatric' => $this->getGeriatricTemplate(),
            'womens_health' => $this->getWomensHealthTemplate(),
            'cardiorespiratory' => $this->getCardiorespiratoryTemplate(),
            'home_care' => $this->getHomeCareTemplate(),
            default => $this->getDefaultTemplate()
        };
    }

    /**
     * Orthopedic Physical Therapy Template
     */
    protected function getOrthopedicTemplate(): array
    {
        return [
            'name' => 'Orthopedic Rehabilitation Program',
            'description' => 'Structured program for musculoskeletal conditions, post-operative rehab, and pain management.',
            'sessions_per_week' => [2, 3],
            'default_sessions_per_week' => 2,
            'total_weeks' => [4, 6, 8, 12],
            'default_total_weeks' => 8,
            'session_duration_minutes' => 60,
            'reassessment_interval_weeks' => 4,
            'session_types' => ['evaluation', 'followup', 're_evaluation', 'discharge'],
            'progression_rules' => [
                'week_1_2' => 'Initial assessment and pain management',
                'week_3_4' => 'ROM and strength building',
                'week_5_6' => 'Functional training',
                'week_7_8' => 'Return to activity preparation'
            ],
            'goals_template' => [
                'Reduce pain by X%',
                'Improve ROM to X degrees',
                'Restore functional strength',
                'Return to daily activities'
            ],
            'payment_plans' => ['pay_per_week', 'monthly', 'upfront'],
            'default_payment_plan' => 'monthly',
            'discount_percentage' => 12
        ];
    }

    /**
     * Pediatric Physical Therapy Template
     */
    protected function getPediatricTemplate(): array
    {
        return [
            'name' => 'Pediatric Development Program',
            'description' => 'Play-based therapy focusing on developmental milestones and motor skills.',
            'sessions_per_week' => [1, 2],
            'default_sessions_per_week' => 1,
            'total_weeks' => [6, 8, 12, 16],
            'default_total_weeks' => 8,
            'session_duration_minutes' => 45,
            'reassessment_interval_weeks' => 4,
            'session_types' => ['evaluation', 'followup', 're_evaluation', 'discharge'],
            'progression_rules' => [
                'week_1_2' => 'Baseline assessment and parent education',
                'week_3_4' => 'Developmental goal focus',
                'week_5_6' => 'Skill building and play activities',
                'week_7_8' => 'Generalization and home program'
            ],
            'goals_template' => [
                'Achieve developmental milestone X',
                'Improve gross motor function',
                'Enhance balance and coordination',
                'Parent education and home program'
            ],
            'payment_plans' => ['pay_per_week', 'monthly', 'upfront'],
            'default_payment_plan' => 'monthly',
            'discount_percentage' => 10,
            'special_notes' => 'Shorter sessions, parent involvement required'
        ];
    }

    /**
     * Neurological Rehabilitation Template
     */
    protected function getNeurologicalTemplate(): array
    {
        return [
            'name' => 'Neurological Rehabilitation Program',
            'description' => 'Intensive rehabilitation for stroke, SCI, MS, and other neurological conditions.',
            'sessions_per_week' => [3, 4, 5],
            'default_sessions_per_week' => 3,
            'total_weeks' => [8, 12, 16, 20],
            'default_total_weeks' => 12,
            'session_duration_minutes' => 90,
            'reassessment_interval_weeks' => 4,
            'session_types' => ['evaluation', 'followup', 're_evaluation', 'discharge'],
            'progression_rules' => [
                'phase_acute' => 'Acute phase: Basic mobility and safety',
                'phase_subacute' => 'Subacute phase: Functional training',
                'phase_chronic' => 'Chronic phase: Maintenance and independence'
            ],
            'goals_template' => [
                'Improve functional independence (FIM score)',
                'Enhance balance and mobility',
                'Reduce spasticity',
                'Caregiver training'
            ],
            'payment_plans' => ['pay_per_week', 'monthly', 'upfront'],
            'default_payment_plan' => 'monthly',
            'discount_percentage' => 15,
            'special_notes' => 'Longer sessions, multi-phase approach, caregiver involvement'
        ];
    }

    /**
     * Sports Physical Therapy Template
     */
    protected function getSportsTemplate(): array
    {
        return [
            'name' => 'Sports Rehabilitation Program',
            'description' => 'Performance-focused rehab for athletes with return-to-play protocols.',
            'sessions_per_week' => [2, 3, 4],
            'default_sessions_per_week' => 3,
            'total_weeks' => [4, 6, 8, 12],
            'default_total_weeks' => 8,
            'session_duration_minutes' => 60,
            'reassessment_interval_weeks' => 2,
            'session_types' => ['evaluation', 'followup', 're_evaluation', 'discharge'],
            'progression_rules' => [
                'phase_acute' => 'Acute: Pain management and protection',
                'phase_subacute' => 'Subacute: Load management and strength',
                'phase_return_to_play' => 'Return to play: Sport-specific training'
            ],
            'goals_template' => [
                'Return to sport by [date]',
                'Restore strength symmetry',
                'Improve performance metrics',
                'Prevent re-injury'
            ],
            'payment_plans' => ['pay_per_week', 'monthly', 'upfront'],
            'default_payment_plan' => 'upfront',
            'discount_percentage' => 10,
            'special_notes' => 'Performance metrics, competition calendar integration'
        ];
    }

    /**
     * Geriatric Physical Therapy Template
     */
    protected function getGeriatricTemplate(): array
    {
        return [
            'name' => 'Geriatric Rehabilitation Program',
            'description' => 'Fall prevention and mobility enhancement for elderly patients.',
            'sessions_per_week' => [1, 2],
            'default_sessions_per_week' => 2,
            'total_weeks' => [4, 6, 8],
            'default_total_weeks' => 6,
            'session_duration_minutes' => 45,
            'reassessment_interval_weeks' => 3,
            'session_types' => ['evaluation', 'followup', 're_evaluation', 'discharge'],
            'progression_rules' => [
                'week_1_2' => 'Fall risk assessment and safety',
                'week_3_4' => 'Balance and strength training',
                'week_5_6' => 'Mobility and independence'
            ],
            'goals_template' => [
                'Reduce fall risk',
                'Improve mobility and balance',
                'Enhance independence in ADLs',
                'Family education'
            ],
            'payment_plans' => ['pay_per_week', 'monthly', 'upfront'],
            'default_payment_plan' => 'monthly',
            'discount_percentage' => 8,
            'special_notes' => 'Lower intensity, safety focus, family involvement'
        ];
    }

    /**
     * Women's Health / Pelvic Floor Template
     */
    protected function getWomensHealthTemplate(): array
    {
        return [
            'name' => 'Women\'s Health Program',
            'description' => 'Pregnancy and postpartum rehabilitation, pelvic floor therapy.',
            'sessions_per_week' => [1, 2],
            'default_sessions_per_week' => 1,
            'total_weeks' => [6, 8, 12],
            'default_total_weeks' => 12,
            'session_duration_minutes' => 60,
            'reassessment_interval_weeks' => 4,
            'session_types' => ['evaluation', 'followup', 're_evaluation', 'discharge'],
            'progression_rules' => [
                'stage_pregnancy' => 'Pregnancy: Core stability and preparation',
                'stage_postpartum' => 'Postpartum: Recovery and strengthening',
                'stage_maintenance' => 'Maintenance: Long-term health'
            ],
            'goals_template' => [
                'Improve pelvic floor strength',
                'Reduce pain and dysfunction',
                'Restore core stability',
                'Return to activities'
            ],
            'payment_plans' => ['pay_per_week', 'monthly', 'upfront'],
            'default_payment_plan' => 'monthly',
            'discount_percentage' => 10,
            'special_notes' => 'Privacy-focused, stage-based progression'
        ];
    }

    /**
     * Cardiorespiratory Physical Therapy Template
     */
    protected function getCardiorespiratoryTemplate(): array
    {
        return [
            'name' => 'Cardiorespiratory Rehabilitation Program',
            'description' => 'Cardiac and pulmonary rehabilitation with vital signs monitoring.',
            'sessions_per_week' => [2, 3],
            'default_sessions_per_week' => 2,
            'total_weeks' => [6, 8, 12],
            'default_total_weeks' => 8,
            'session_duration_minutes' => 60,
            'reassessment_interval_weeks' => 2,
            'session_types' => ['evaluation', 'followup', 're_evaluation', 'discharge'],
            'progression_rules' => [
                'week_1_2' => 'Baseline assessment and monitoring',
                'week_3_4' => 'Gradual exercise progression',
                'week_5_6' => 'Increased tolerance and endurance',
                'week_7_8' => 'Maintenance and independence'
            ],
            'goals_template' => [
                'Improve exercise tolerance',
                'Enhance cardiovascular fitness',
                'Manage symptoms',
                'Return to daily activities'
            ],
            'payment_plans' => ['pay_per_week', 'monthly', 'upfront'],
            'default_payment_plan' => 'monthly',
            'discount_percentage' => 12,
            'special_notes' => 'Vital signs monitoring required'
        ];
    }

    /**
     * Home Care / Mobile PT Template
     */
    protected function getHomeCareTemplate(): array
    {
        return [
            'name' => 'Home-Based Rehabilitation Program',
            'description' => 'Mobile physical therapy services at patient\'s home.',
            'sessions_per_week' => [1, 2],
            'default_sessions_per_week' => 2,
            'total_weeks' => [4, 6, 8],
            'default_total_weeks' => 6,
            'session_duration_minutes' => 60,
            'reassessment_interval_weeks' => 3,
            'session_types' => ['evaluation', 'followup', 're_evaluation', 'discharge'],
            'progression_rules' => [
                'week_1_2' => 'Home environment assessment',
                'week_3_4' => 'Functional training in home setting',
                'week_5_6' => 'Independence and safety'
            ],
            'goals_template' => [
                'Improve home mobility',
                'Enhance safety in home environment',
                'Increase independence',
                'Family/caregiver training'
            ],
            'payment_plans' => ['pay_per_week', 'monthly', 'upfront'],
            'default_payment_plan' => 'monthly',
            'discount_percentage' => 10,
            'special_notes' => 'Travel time included, route optimization, portable equipment'
        ];
    }

    /**
     * Default Template
     */
    protected function getDefaultTemplate(): array
    {
        return [
            'name' => 'Standard Rehabilitation Program',
            'description' => 'General physical therapy rehabilitation program.',
            'sessions_per_week' => [2, 3],
            'default_sessions_per_week' => 2,
            'total_weeks' => [4, 6, 8],
            'default_total_weeks' => 6,
            'session_duration_minutes' => 60,
            'reassessment_interval_weeks' => 4,
            'session_types' => ['evaluation', 'followup', 're_evaluation', 'discharge'],
            'progression_rules' => [],
            'goals_template' => [],
            'payment_plans' => ['pay_per_week', 'monthly', 'upfront'],
            'default_payment_plan' => 'monthly',
            'discount_percentage' => 10
        ];
    }

    /**
     * Get default values for program creation based on specialty
     * 
     * @param string $specialty
     * @return array
     */
    public function getDefaults(string $specialty): array
    {
        $template = $this->getTemplate($specialty);
        
        return [
            'sessions_per_week' => $template['default_sessions_per_week'],
            'total_weeks' => $template['default_total_weeks'],
            'duration_minutes' => $template['session_duration_minutes'],
            'reassessment_interval_weeks' => $template['reassessment_interval_weeks'],
            'payment_plan' => $template['default_payment_plan'],
            'goals' => $template['goals_template'] ?? []
        ];
    }

    /**
     * Get available sessions per week options for specialty
     * 
     * @param string $specialty
     * @return array
     */
    public function getSessionsPerWeekOptions(string $specialty): array
    {
        $template = $this->getTemplate($specialty);
        return $template['sessions_per_week'];
    }

    /**
     * Get available total weeks options for specialty
     * 
     * @param string $specialty
     * @return array
     */
    public function getTotalWeeksOptions(string $specialty): array
    {
        $template = $this->getTemplate($specialty);
        return $template['total_weeks'];
    }
}

