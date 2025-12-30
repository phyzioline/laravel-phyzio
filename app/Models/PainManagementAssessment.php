<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PainManagementAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'episode_id',
        'pain_type',
        'pain_sensitization_findings',
        'psychosocial_factors',
        'pain_catastrophizing_score',
        'pain_profile',
        'chronicity_level',
        'treatment_response',
        'assessed_at',
        'therapist_id',
    ];

    protected $casts = [
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

