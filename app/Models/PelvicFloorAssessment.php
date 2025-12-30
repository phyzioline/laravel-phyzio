<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelvicFloorAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'episode_id',
        'pelvic_strength_grade',
        'endurance_seconds',
        'continence_status',
        'bladder_diary_summary',
        'pain_mapping',
        'pfdi_score',
        'pfiq_score',
        'postpartum_status',
        'assessed_at',
        'therapist_id',
    ];

    protected $casts = [
        'pfdi_score' => 'decimal:2',
        'pfiq_score' => 'decimal:2',
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
}

