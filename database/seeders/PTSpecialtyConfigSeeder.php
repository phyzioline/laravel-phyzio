<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PTSpecialtyConfig;

class PTSpecialtyConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            [
                'specialty_key' => 'musculoskeletal',
                'name_en' => 'Musculoskeletal / Orthopedic Physical Therapy',
                'name_ar' => 'العلاج الطبيعي العضلي الهيكلي / العظام',
                'description_en' => 'Assessment and treatment of musculoskeletal conditions including neck, back, shoulder, knee, hip, and ankle injuries. Post-fracture and post-surgical rehabilitation.',
                'description_ar' => 'تقييم وعلاج الحالات العضلية الهيكلية بما في ذلك إصابات الرقبة والظهر والكتف والركبة والورك والكاحل. إعادة التأهيل بعد الكسور وبعد الجراحة.',
                'assessment_components' => [
                    'subjective' => ['pain', 'mechanism_of_injury', 'functional_limitations'],
                    'objective' => ['posture', 'rom', 'strength', 'special_tests'],
                ],
                'outcome_measures' => ['ODI', 'NDI', 'DASH', 'QuickDASH', 'LEFS'],
                'tools_devices' => ['goniometer', 'digital_inclinometer', 'hand_held_dynamometer', 'posture_grid', 'taping_tools', 'resistance_bands'],
                'is_active' => true,
            ],
            [
                'specialty_key' => 'neurological',
                'name_en' => 'Neurological Physical Therapy',
                'name_ar' => 'العلاج الطبيعي العصبي',
                'description_en' => 'Rehabilitation for stroke, spinal cord injury, Parkinson\'s disease, multiple sclerosis, and traumatic brain injury.',
                'description_ar' => 'إعادة التأهيل للسكتة الدماغية وإصابات الحبل الشوكي ومرض باركنسون والتصلب المتعدد وإصابات الدماغ الرضحية.',
                'assessment_components' => [
                    'subjective' => ['functional_independence', 'fatigue', 'balance_confidence'],
                    'objective' => ['tone', 'reflexes', 'coordination', 'balance', 'gait'],
                ],
                'outcome_measures' => ['FIM', 'Barthel', 'Berg', 'TUG'],
                'tools_devices' => ['reflex_hammer', 'balance_board', 'gait_mat', 'parallel_bars', 'NMES', 'FES'],
                'is_active' => true,
            ],
            [
                'specialty_key' => 'cardiopulmonary',
                'name_en' => 'Cardiopulmonary Physical Therapy',
                'name_ar' => 'العلاج الطبيعي القلبي الرئوي',
                'description_en' => 'Rehabilitation for post-ICU patients, COPD, cardiac rehabilitation, and post-COVID recovery.',
                'description_ar' => 'إعادة التأهيل لمرضى ما بعد العناية المركزة ومرض الانسداد الرئوي المزمن وإعادة تأهيل القلب والتعافي بعد COVID.',
                'assessment_components' => [
                    'subjective' => ['dyspnea_scale', 'exercise_tolerance'],
                    'objective' => ['vital_signs', 'chest_expansion', 'breath_sounds', 'oxygen_saturation'],
                ],
                'outcome_measures' => ['6MWT', 'Borg'],
                'tools_devices' => ['pulse_oximeter', 'spirometer', 'incentive_spirometer', 'treadmill', 'cycle_ergometer'],
                'is_active' => true,
            ],
            [
                'specialty_key' => 'pediatric',
                'name_en' => 'Pediatric Physical Therapy',
                'name_ar' => 'العلاج الطبيعي للأطفال',
                'description_en' => 'Treatment for developmental delays, cerebral palsy, genetic disorders, and pediatric orthopedic conditions.',
                'description_ar' => 'علاج تأخر النمو والشلل الدماغي والاضطرابات الوراثية والحالات العظمية للأطفال.',
                'assessment_components' => [
                    'subjective' => ['parent_caregiver_report', 'birth_history', 'developmental_history'],
                    'objective' => ['gross_motor_milestones', 'primitive_reflexes', 'postural_reactions'],
                ],
                'outcome_measures' => ['GMFM', 'PDMS'],
                'tools_devices' => ['pediatric_balance_tools', 'therapy_balls', 'pediatric_gait_aids', 'sensory_toys'],
                'is_active' => true,
            ],
            [
                'specialty_key' => 'geriatric',
                'name_en' => 'Geriatric Physical Therapy',
                'name_ar' => 'العلاج الطبيعي لكبار السن',
                'description_en' => 'Treatment for osteoarthritis, osteoporosis, falls prevention, and post-hip fracture rehabilitation.',
                'description_ar' => 'علاج التهاب المفاصل وهشاشة العظام والوقاية من السقوط وإعادة تأهيل ما بعد كسر الورك.',
                'assessment_components' => [
                    'subjective' => ['fall_history', 'functional_independence'],
                    'objective' => ['balance', 'strength', 'gait_speed'],
                ],
                'outcome_measures' => ['Berg', 'Tinetti', 'TUG'],
                'tools_devices' => ['walkers', 'canes', 'balance_trainers'],
                'is_active' => true,
            ],
            [
                'specialty_key' => 'sports',
                'name_en' => 'Sports Physical Therapy',
                'name_ar' => 'العلاج الطبيعي الرياضي',
                'description_en' => 'Treatment of athletic injuries, performance rehabilitation, and return-to-play protocols.',
                'description_ar' => 'علاج الإصابات الرياضية وإعادة تأهيل الأداء وبروتوكولات العودة للعب.',
                'assessment_components' => [
                    'subjective' => ['sport_specific_demands'],
                    'objective' => ['power', 'agility', 'symmetry_tests'],
                ],
                'outcome_measures' => ['Hop_Test', 'LSI'],
                'tools_devices' => ['force_plates', 'agility_ladders', 'plyometric_equipment'],
                'is_active' => true,
            ],
            [
                'specialty_key' => 'womens_health',
                'name_en' => 'Women\'s Health / Pelvic Floor Physical Therapy',
                'name_ar' => 'صحة المرأة / العلاج الطبيعي لقاع الحوض',
                'description_en' => 'Treatment for urinary incontinence, pelvic pain, and post-partum rehabilitation.',
                'description_ar' => 'علاج سلس البول وآلام الحوض وإعادة التأهيل بعد الولادة.',
                'assessment_components' => [
                    'subjective' => ['bladder_bowel_diary', 'pain_mapping'],
                    'objective' => ['pelvic_floor_strength', 'endurance'],
                ],
                'outcome_measures' => ['PFDI', 'PFIQ'],
                'tools_devices' => ['biofeedback', 'pelvic_floor_trainers'],
                'is_active' => true,
            ],
            [
                'specialty_key' => 'pain_management',
                'name_en' => 'Pain Management Physical Therapy',
                'name_ar' => 'العلاج الطبيعي لإدارة الألم',
                'description_en' => 'Treatment for chronic pain, fibromyalgia, and myofascial pain syndromes.',
                'description_ar' => 'علاج الألم المزمن والفيبروميالغيا ومتلازمات الألم الليفي العضلي.',
                'assessment_components' => [
                    'subjective' => ['pain_sensitization', 'psychosocial_factors'],
                    'objective' => ['pain_profile', 'chronicity_level'],
                ],
                'outcome_measures' => ['NPRS', 'Pain_Catastrophizing_Scale'],
                'tools_devices' => ['TENS', 'dry_needling_tools', 'pain_questionnaires'],
                'is_active' => true,
            ],
        ];

        foreach ($specialties as $specialty) {
            PTSpecialtyConfig::updateOrCreate(
                ['specialty_key' => $specialty['specialty_key']],
                $specialty
            );
        }
    }
}

