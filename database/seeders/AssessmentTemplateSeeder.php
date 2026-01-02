<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssessmentTemplate;

class AssessmentTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Low Back Pain Template
        AssessmentTemplate::create([
            'clinic_id' => null,
            'name' => 'Low Back Pain Assessment',
            'condition_code' => 'M54.5',
            'description' => 'Comprehensive assessment template for low back pain including pain scales, ROM, and special tests',
            'specialty' => 'orthopedic',
            'is_system_template' => true,
            'is_active' => true,
            'subjective_fields' => [
                [
                    'label' => 'Pain Location',
                    'type' => 'select',
                    'options' => ['Lower back', 'Right side', 'Left side', 'Central', 'Radiating to leg'],
                    'required' => true
                ],
                [
                    'label' => 'Pain Onset',
                    'type' => 'select',
                    'options' => ['Sudden', 'Gradual', 'After injury', 'Unknown'],
                    'required' => true
                ],
                [
                    'label' => 'Pain Duration',
                    'type' => 'select',
                    'options' => ['Acute (<6 weeks)', 'Subacute (6-12 weeks)', 'Chronic (>12 weeks)'],
                    'required' => true
                ],
                [
                    'label' => 'Aggravating Factors',
                    'type' => 'multiselect',
                    'options' => ['Sitting', 'Standing', 'Bending', 'Lifting', 'Walking', 'Sleeping'],
                    'required' => false
                ],
                [
                    'label' => 'Relieving Factors',
                    'type' => 'multiselect',
                    'options' => ['Rest', 'Heat', 'Cold', 'Medication', 'Movement', 'Position change'],
                    'required' => false
                ],
                [
                    'label' => 'Functional Limitations',
                    'type' => 'textarea',
                    'placeholder' => 'Describe how pain affects daily activities...',
                    'required' => false
                ]
            ],
            'objective_fields' => [
                [
                    'label' => 'Lumbar Flexion ROM',
                    'type' => 'rom_slider',
                    'joint' => 'lumbar',
                    'movement' => 'flexion',
                    'normal_range' => [0, 60],
                    'unit' => 'degrees',
                    'required' => true
                ],
                [
                    'label' => 'Lumbar Extension ROM',
                    'type' => 'rom_slider',
                    'joint' => 'lumbar',
                    'movement' => 'extension',
                    'normal_range' => [0, 25],
                    'unit' => 'degrees',
                    'required' => true
                ],
                [
                    'label' => 'Lumbar Lateral Flexion (Right)',
                    'type' => 'rom_slider',
                    'joint' => 'lumbar',
                    'movement' => 'lateral_flexion',
                    'normal_range' => [0, 25],
                    'unit' => 'degrees',
                    'required' => false
                ],
                [
                    'label' => 'Lumbar Lateral Flexion (Left)',
                    'type' => 'rom_slider',
                    'joint' => 'lumbar',
                    'movement' => 'lateral_flexion',
                    'normal_range' => [0, 25],
                    'unit' => 'degrees',
                    'required' => false
                ]
            ],
            'pain_scale_config' => [
                'type' => 'vas', // Visual Analog Scale
                'min' => 0,
                'max' => 10,
                'labels' => ['No pain', 'Mild', 'Moderate', 'Severe', 'Worst possible'],
                'show_current_value' => true
            ],
            'rom_config' => [
                'show_bilateral_comparison' => true,
                'show_normal_range' => true,
                'unit' => 'degrees'
            ],
            'special_tests' => [
                [
                    'name' => 'Straight Leg Raise (SLR)',
                    'side' => 'both',
                    'result_options' => ['Negative', 'Positive (pain)', 'Positive (radicular)', 'Limited by hamstring tightness']
                ],
                [
                    'name' => 'Slump Test',
                    'side' => 'both',
                    'result_options' => ['Negative', 'Positive']
                ],
                [
                    'name' => 'Patrick/FABER Test',
                    'side' => 'both',
                    'result_options' => ['Negative', 'Positive (SI joint)', 'Positive (hip)']
                ]
            ],
            'treatment_plan_suggestions' => [
                'Modalities' => ['Heat therapy', 'Cold therapy', 'TENS', 'Ultrasound'],
                'Exercises' => ['Core strengthening', 'Lumbar stabilization', 'Flexibility exercises', 'Postural correction'],
                'Manual Therapy' => ['Soft tissue mobilization', 'Joint mobilization', 'Myofascial release'],
                'Education' => ['Ergonomics', 'Lifting techniques', 'Activity modification']
            ]
        ]);

        // Knee OA Template
        AssessmentTemplate::create([
            'clinic_id' => null,
            'name' => 'Knee Osteoarthritis Assessment',
            'condition_code' => 'M17.9',
            'description' => 'Comprehensive assessment for knee osteoarthritis including pain, ROM, and functional assessment',
            'specialty' => 'orthopedic',
            'is_system_template' => true,
            'is_active' => true,
            'subjective_fields' => [
                [
                    'label' => 'Pain Location',
                    'type' => 'select',
                    'options' => ['Medial', 'Lateral', 'Anterior', 'Posterior', 'Diffuse'],
                    'required' => true
                ],
                [
                    'label' => 'Pain Pattern',
                    'type' => 'select',
                    'options' => ['Constant', 'Intermittent', 'Morning stiffness', 'Activity-related', 'Night pain'],
                    'required' => true
                ],
                [
                    'label' => 'Functional Limitations',
                    'type' => 'multiselect',
                    'options' => ['Walking', 'Stairs', 'Squatting', 'Sitting/Standing', 'Running', 'Sports'],
                    'required' => false
                ],
                [
                    'label' => 'Previous Treatments',
                    'type' => 'multiselect',
                    'options' => ['Medication', 'Injections', 'Surgery', 'Physical therapy', 'Bracing'],
                    'required' => false
                ]
            ],
            'objective_fields' => [
                [
                    'label' => 'Knee Flexion ROM',
                    'type' => 'rom_slider',
                    'joint' => 'knee',
                    'movement' => 'flexion',
                    'normal_range' => [0, 135],
                    'unit' => 'degrees',
                    'required' => true
                ],
                [
                    'label' => 'Knee Extension ROM',
                    'type' => 'rom_slider',
                    'joint' => 'knee',
                    'movement' => 'extension',
                    'normal_range' => [0, 0],
                    'unit' => 'degrees',
                    'required' => true
                ],
                [
                    'label' => 'Quadriceps Strength',
                    'type' => 'strength_grade',
                    'muscle' => 'quadriceps',
                    'scale' => '0-5',
                    'required' => true
                ]
            ],
            'pain_scale_config' => [
                'type' => 'vas',
                'min' => 0,
                'max' => 10,
                'labels' => ['No pain', 'Mild', 'Moderate', 'Severe', 'Worst possible'],
                'show_current_value' => true
            ],
            'rom_config' => [
                'show_bilateral_comparison' => true,
                'show_normal_range' => true,
                'unit' => 'degrees'
            ],
            'special_tests' => [
                [
                    'name' => 'McMurray Test',
                    'side' => 'both',
                    'result_options' => ['Negative', 'Positive (medial meniscus)', 'Positive (lateral meniscus)']
                ],
                [
                    'name' => 'Lachman Test',
                    'side' => 'both',
                    'result_options' => ['Negative', 'Positive (ACL laxity)']
                ],
                [
                    'name' => 'Patellar Grind Test',
                    'side' => 'both',
                    'result_options' => ['Negative', 'Positive (crepitus)', 'Positive (pain)']
                ]
            ],
            'treatment_plan_suggestions' => [
                'Modalities' => ['Heat therapy', 'Cold therapy', 'TENS', 'Ultrasound'],
                'Exercises' => ['Quadriceps strengthening', 'Hamstring stretching', 'Balance training', 'Low-impact cardio'],
                'Manual Therapy' => ['Joint mobilization', 'Soft tissue work', 'Patellar mobilization'],
                'Education' => ['Activity modification', 'Weight management', 'Assistive devices']
            ]
        ]);

        // Post-ACL Rehab Template
        AssessmentTemplate::create([
            'clinic_id' => null,
            'name' => 'Post-ACL Reconstruction Rehabilitation',
            'condition_code' => 'S83.51',
            'description' => 'Comprehensive assessment template for post-ACL reconstruction rehabilitation',
            'specialty' => 'sports',
            'is_system_template' => true,
            'is_active' => true,
            'subjective_fields' => [
                [
                    'label' => 'Surgery Date',
                    'type' => 'date',
                    'required' => true
                ],
                [
                    'label' => 'Graft Type',
                    'type' => 'select',
                    'options' => ['Patellar tendon', 'Hamstring', 'Quadriceps', 'Allograft'],
                    'required' => false
                ],
                [
                    'label' => 'Current Phase',
                    'type' => 'select',
                    'options' => ['Phase 1 (0-6 weeks)', 'Phase 2 (6-12 weeks)', 'Phase 3 (12-24 weeks)', 'Phase 4 (24+ weeks)'],
                    'required' => true
                ],
                [
                    'label' => 'Pain Level',
                    'type' => 'pain_scale',
                    'required' => true
                ],
                [
                    'label' => 'Swelling',
                    'type' => 'select',
                    'options' => ['None', 'Mild', 'Moderate', 'Severe'],
                    'required' => false
                ],
                [
                    'label' => 'Functional Goals',
                    'type' => 'multiselect',
                    'options' => ['Return to sport', 'Return to work', 'Daily activities', 'Recreational activities'],
                    'required' => false
                ]
            ],
            'objective_fields' => [
                [
                    'label' => 'Knee Flexion ROM',
                    'type' => 'rom_slider',
                    'joint' => 'knee',
                    'movement' => 'flexion',
                    'normal_range' => [0, 135],
                    'unit' => 'degrees',
                    'required' => true
                ],
                [
                    'label' => 'Knee Extension ROM',
                    'type' => 'rom_slider',
                    'joint' => 'knee',
                    'movement' => 'extension',
                    'normal_range' => [0, 0],
                    'unit' => 'degrees',
                    'required' => true
                ],
                [
                    'label' => 'Quadriceps Strength (Operated)',
                    'type' => 'strength_grade',
                    'muscle' => 'quadriceps',
                    'scale' => '0-5',
                    'required' => true
                ],
                [
                    'label' => 'Quadriceps Strength (Non-operated)',
                    'type' => 'strength_grade',
                    'muscle' => 'quadriceps',
                    'scale' => '0-5',
                    'required' => false
                ],
                [
                    'label' => 'Hamstring Strength (Operated)',
                    'type' => 'strength_grade',
                    'muscle' => 'hamstring',
                    'scale' => '0-5',
                    'required' => false
                ]
            ],
            'pain_scale_config' => [
                'type' => 'vas',
                'min' => 0,
                'max' => 10,
                'labels' => ['No pain', 'Mild', 'Moderate', 'Severe', 'Worst possible'],
                'show_current_value' => true
            ],
            'rom_config' => [
                'show_bilateral_comparison' => true,
                'show_normal_range' => true,
                'unit' => 'degrees',
                'compare_to_contralateral' => true
            ],
            'special_tests' => [
                [
                    'name' => 'Lachman Test',
                    'side' => 'operated',
                    'result_options' => ['Negative', 'Grade 1+', 'Grade 2+', 'Grade 3+']
                ],
                [
                    'name' => 'Pivot Shift Test',
                    'side' => 'operated',
                    'result_options' => ['Negative', 'Positive']
                ],
                [
                    'name' => 'Anterior Drawer Test',
                    'side' => 'operated',
                    'result_options' => ['Negative', 'Positive']
                ]
            ],
            'treatment_plan_suggestions' => [
                'Phase 1 (0-6 weeks)' => ['ROM exercises', 'Quad sets', 'SLR', 'Patellar mobilization', 'Edema control'],
                'Phase 2 (6-12 weeks)' => ['Progressive strengthening', 'Balance training', 'Gait training', 'Stationary bike'],
                'Phase 3 (12-24 weeks)' => ['Sport-specific training', 'Plyometrics', 'Agility drills', 'Running program'],
                'Phase 4 (24+ weeks)' => ['Return to sport testing', 'Advanced agility', 'Sport-specific drills', 'Maintenance program']
            ]
        ]);
    }
}

