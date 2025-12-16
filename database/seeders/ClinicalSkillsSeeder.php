<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClinicalSkillsSeeder extends Seeder
{
    public function run()
    {
        $skills = [
            // Manual Therapy
            [
                'skill_name' => 'Grade V Manipulation (HVT)',
                'specialty' => 'Musculoskeletal',
                'body_region' => 'Cervical Spine',
                'indications' => 'Facet joint lock, Hypomobility',
                'contraindications' => 'Osteoporosis, VBI, Fracture',
                'risk_level' => 'high'
            ],
            [
                'skill_name' => 'Mulligan SNAGs',
                'specialty' => 'Musculoskeletal',
                'body_region' => 'Lumbar Spine',
                'indications' => 'Painful motion restriction',
                'contraindications' => 'Severe nerve root compression',
                'risk_level' => 'medium'
            ],
            [
                'skill_name' => 'Maitland Mobilization (Grade I-IV)',
                'specialty' => 'Musculoskeletal',
                'body_region' => 'Glenohumeral Joint',
                'indications' => 'Adhesive Capsulitis (Frozen Shoulder)',
                'contraindications' => 'Active infection, malignancy',
                'risk_level' => 'medium'
            ],
            
            // Neuro Rehab
            [
                'skill_name' => 'PNF Rhythmic Initiation',
                'specialty' => 'Neurological',
                'body_region' => 'Upper Limb',
                'indications' => 'Parkinsonism, Rigidity',
                'contraindications' => 'Unstable fracture',
                'risk_level' => 'low'
            ],
            [
                'skill_name' => 'Bobath Handling / NDT',
                'specialty' => 'Neurological',
                'body_region' => 'Trunk / Pelvis',
                'indications' => 'Stroke, Cerebral Palsy',
                'contraindications' => 'None specific',
                'risk_level' => 'medium'
            ],
            
            // Sports & Modalities
            [
                'skill_name' => 'Dry Needling (Trigger Point)',
                'specialty' => 'Sports',
                'body_region' => 'Gluteals / Piriformis',
                'indications' => 'Myofascial Pain Syndrome',
                'contraindications' => 'Needle phobia, Bleeding disorders',
                'risk_level' => 'high'
            ],
            [
                'skill_name' => 'Kinesio Taping (Fascial Correction)',
                'specialty' => 'Sports',
                'body_region' => 'Knee (Patella)',
                'indications' => 'Patellofemoral Pain Syndrome',
                'contraindications' => 'Skin allergy, Open wound',
                'risk_level' => 'low'
            ],
            [
                'skill_name' => 'Shockwave Therapy (ESWT)',
                'specialty' => 'Sports',
                'body_region' => 'Plantarfascia',
                'indications' => 'Plantar Fasciitis, Chronic Tendinopathy',
                'contraindications' => 'Pregnancy, Pacemaker, Acute inflammation',
                'risk_level' => 'medium'
            ],
            
            // Pediatric
            [
                'skill_name' => 'Vojta Reflex Locomotion',
                'specialty' => 'Pediatric',
                'body_region' => 'Whole Body',
                'indications' => 'Cerebral Palsy, Developmental Delay',
                'contraindications' => 'Acute fever, Vaccination < 48h',
                'risk_level' => 'medium'
            ],
            
            // Cardiopulmonary
            [
                'skill_name' => 'Postural Drainage & Percussion',
                'specialty' => 'Cardiopulmonary',
                'body_region' => 'Thorax',
                'indications' => 'Cystic Fibrosis, Pneumonia',
                'contraindications' => 'Rib fracture, Hemoptysis',
                'risk_level' => 'medium'
            ],
             [
                'skill_name' => 'Suctioning (Open/Closed)',
                'specialty' => 'Cardiopulmonary',
                'body_region' => 'Airways',
                'indications' => 'Secretions retention',
                'contraindications' => 'Severe bronchospasm',
                'risk_level' => 'high'
            ],
        ];

        foreach ($skills as $skill) {
            DB::table('skills')->updateOrInsert(
                ['skill_name' => $skill['skill_name']], // Unique check
                array_merge($skill, ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()])
            );
        }
    }
}
