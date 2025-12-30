<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutcomeMeasure extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'episode_id',
        'measure_name',
        'specialty_key',
        'scores',
        'total_score',
        'percentage',
        'interpretation',
        'assessment_type',
        'assessed_at',
        'therapist_id',
    ];

    protected $casts = [
        'scores' => 'array',
        'total_score' => 'decimal:2',
        'percentage' => 'decimal:2',
        'assessed_at' => 'datetime',
    ];

    public function assessment()
    {
        return $this->belongsTo(ClinicalAssessment::class);
    }

    public function episode()
    {
        return $this->belongsTo(EpisodeOfCare::class);
    }

    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }

    /**
     * Common outcome measures by specialty
     */
    public static function getMeasuresBySpecialty(string $specialtyKey): array
    {
        $measures = [
            'musculoskeletal' => [
                'ODI' => 'Oswestry Disability Index',
                'NDI' => 'Neck Disability Index',
                'DASH' => 'Disabilities of the Arm, Shoulder and Hand',
                'QuickDASH' => 'Quick Disabilities of the Arm, Shoulder and Hand',
                'LEFS' => 'Lower Extremity Functional Scale',
            ],
            'neurological' => [
                'FIM' => 'Functional Independence Measure',
                'Barthel' => 'Barthel Index',
                'Berg' => 'Berg Balance Scale',
                'TUG' => 'Timed Up and Go',
            ],
            'cardiopulmonary' => [
                '6MWT' => '6-Minute Walk Test',
                'Borg' => 'Borg Scale',
            ],
            'pediatric' => [
                'GMFM' => 'Gross Motor Function Measure',
                'PDMS' => 'Peabody Developmental Motor Scales',
            ],
            'geriatric' => [
                'Berg' => 'Berg Balance Scale',
                'Tinetti' => 'Tinetti Test',
                'TUG' => 'Timed Up and Go',
            ],
            'sports' => [
                'LSI' => 'Limb Symmetry Index',
                'Hop_Test' => 'Hop Test',
            ],
            'womens_health' => [
                'PFDI' => 'Pelvic Floor Distress Inventory',
                'PFIQ' => 'Pelvic Floor Impact Questionnaire',
            ],
        ];

        return $measures[$specialtyKey] ?? [];
    }
}

