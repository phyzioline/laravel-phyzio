<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClinicalTemplate;

class ClinicalTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            // Orthopedic Templates
            [
                'name' => 'Orthopedic Initial Evaluation',
                'specialty' => 'ortho',
                'note_type' => 'evaluation',
                'description' => 'Standard orthopedic initial evaluation template',
                'fields_schema' => [
                    'chief_complaint' => ['type' => 'textarea', 'label' => 'Chief Complaint', 'required' => true],
                    'history_of_present_illness' => ['type' => 'textarea', 'label' => 'History of Present Illness', 'required' => true],
                    'past_medical_history' => ['type' => 'textarea', 'label' => 'Past Medical History'],
                    'medications' => ['type' => 'textarea', 'label' => 'Current Medications'],
                    'pain_scale' => ['type' => 'number', 'label' => 'Pain Scale (0-10)', 'min' => 0, 'max' => 10],
                    'rom_measurements' => ['type' => 'textarea', 'label' => 'Range of Motion'],
                    'strength_testing' => ['type' => 'textarea', 'label' => 'Manual Muscle Testing'],
                    'special_tests' => ['type' => 'textarea', 'label' => 'Special Tests'],
                    'functional_assessment' => ['type' => 'textarea', 'label' => 'Functional Assessment'],
                ],
                'default_values' => [
                    'pain_scale' => 0
                ],
                'coding_rules' => [
                    'default_icd10' => ['M25.561', 'M79.3'],
                    'default_cpt' => ['97161', '97162', '97163']
                ],
                'is_system_template' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Orthopedic SOAP Note',
                'specialty' => 'ortho',
                'note_type' => 'soap',
                'description' => 'Standard orthopedic SOAP note template',
                'fields_schema' => [
                    'subjective' => ['type' => 'textarea', 'label' => 'Subjective', 'required' => true],
                    'objective' => ['type' => 'textarea', 'label' => 'Objective', 'required' => true],
                    'assessment' => ['type' => 'textarea', 'label' => 'Assessment', 'required' => true],
                    'plan' => ['type' => 'textarea', 'label' => 'Plan', 'required' => true],
                ],
                'coding_rules' => [
                    'default_cpt' => ['97110', '97112', '97140']
                ],
                'is_system_template' => true,
                'is_active' => true,
            ],

            // Pediatric Templates
            [
                'name' => 'Pediatric Initial Evaluation',
                'specialty' => 'pediatric',
                'note_type' => 'evaluation',
                'description' => 'Pediatric-specific initial evaluation template',
                'fields_schema' => [
                    'chief_complaint' => ['type' => 'textarea', 'label' => 'Chief Complaint', 'required' => true],
                    'birth_history' => ['type' => 'textarea', 'label' => 'Birth History'],
                    'developmental_milestones' => ['type' => 'textarea', 'label' => 'Developmental Milestones'],
                    'family_history' => ['type' => 'textarea', 'label' => 'Family History'],
                    'play_activities' => ['type' => 'textarea', 'label' => 'Play Activities'],
                    'gross_motor_skills' => ['type' => 'textarea', 'label' => 'Gross Motor Skills'],
                    'fine_motor_skills' => ['type' => 'textarea', 'label' => 'Fine Motor Skills'],
                    'sensory_integration' => ['type' => 'textarea', 'label' => 'Sensory Integration'],
                ],
                'coding_rules' => [
                    'default_icd10' => ['G80.9', 'F84.0'],
                    'default_cpt' => ['97161', '97162']
                ],
                'is_system_template' => true,
                'is_active' => true,
            ],

            // Neurological Templates
            [
                'name' => 'Neurological Initial Evaluation',
                'specialty' => 'neuro',
                'note_type' => 'evaluation',
                'description' => 'Neurological PT initial evaluation template',
                'fields_schema' => [
                    'chief_complaint' => ['type' => 'textarea', 'label' => 'Chief Complaint', 'required' => true],
                    'neurological_history' => ['type' => 'textarea', 'label' => 'Neurological History'],
                    'cognitive_assessment' => ['type' => 'textarea', 'label' => 'Cognitive Assessment'],
                    'balance_assessment' => ['type' => 'textarea', 'label' => 'Balance Assessment'],
                    'gait_analysis' => ['type' => 'textarea', 'label' => 'Gait Analysis'],
                    'tone_assessment' => ['type' => 'textarea', 'label' => 'Tone Assessment'],
                    'coordination_testing' => ['type' => 'textarea', 'label' => 'Coordination Testing'],
                ],
                'coding_rules' => [
                    'default_icd10' => ['G81.90', 'I69.398'],
                    'default_cpt' => ['97161', '97112', '97116']
                ],
                'is_system_template' => true,
                'is_active' => true,
            ],

            // Sports Medicine Templates
            [
                'name' => 'Sports Medicine Evaluation',
                'specialty' => 'sports',
                'note_type' => 'evaluation',
                'description' => 'Sports medicine evaluation template',
                'fields_schema' => [
                    'chief_complaint' => ['type' => 'textarea', 'label' => 'Chief Complaint', 'required' => true],
                    'mechanism_of_injury' => ['type' => 'textarea', 'label' => 'Mechanism of Injury'],
                    'sport_specific_history' => ['type' => 'textarea', 'label' => 'Sport-Specific History'],
                    'performance_goals' => ['type' => 'textarea', 'label' => 'Performance Goals'],
                    'functional_sports_testing' => ['type' => 'textarea', 'label' => 'Functional Sports Testing'],
                ],
                'coding_rules' => [
                    'default_icd10' => ['S83.511A'],
                    'default_cpt' => ['97161', '97110']
                ],
                'is_system_template' => true,
                'is_active' => true,
            ],

            // Women's Health Templates
            [
                'name' => "Women's Health Evaluation",
                'specialty' => 'women_health',
                'note_type' => 'evaluation',
                'description' => "Women's health/pelvic floor evaluation template",
                'fields_schema' => [
                    'chief_complaint' => ['type' => 'textarea', 'label' => 'Chief Complaint', 'required' => true],
                    'obstetric_history' => ['type' => 'textarea', 'label' => 'Obstetric History'],
                    'gynecological_history' => ['type' => 'textarea', 'label' => 'Gynecological History'],
                    'pelvic_floor_assessment' => ['type' => 'textarea', 'label' => 'Pelvic Floor Assessment'],
                    'functional_limitations' => ['type' => 'textarea', 'label' => 'Functional Limitations'],
                ],
                'coding_rules' => [
                    'default_icd10' => ['N81.9', 'M79.3'],
                    'default_cpt' => ['97161', '97110']
                ],
                'is_system_template' => true,
                'is_active' => true,
            ],

            // Geriatric Templates
            [
                'name' => 'Geriatric Evaluation',
                'specialty' => 'geriatrics',
                'note_type' => 'evaluation',
                'description' => 'Geriatric physical therapy evaluation template',
                'fields_schema' => [
                    'chief_complaint' => ['type' => 'textarea', 'label' => 'Chief Complaint', 'required' => true],
                    'falls_history' => ['type' => 'textarea', 'label' => 'Falls History'],
                    'balance_assessment' => ['type' => 'textarea', 'label' => 'Balance Assessment'],
                    'mobility_assessment' => ['type' => 'textarea', 'label' => 'Mobility Assessment'],
                    'home_safety' => ['type' => 'textarea', 'label' => 'Home Safety Assessment'],
                ],
                'coding_rules' => [
                    'default_icd10' => ['R29.6', 'M54.5'],
                    'default_cpt' => ['97161', '97116']
                ],
                'is_system_template' => true,
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            ClinicalTemplate::updateOrCreate(
                [
                    'name' => $template['name'],
                    'specialty' => $template['specialty'],
                    'note_type' => $template['note_type'],
                    'is_system_template' => true
                ],
                $template
            );
        }

        $this->command->info('Clinical templates seeded successfully!');
    }
}

