<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PediatricAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'episode_id',
        'developmental_history',
        'birth_history',
        'gross_motor_milestones',
        'primitive_reflexes',
        'postural_reactions',
        'gmfm_score',
        'pdms_score',
        'age_adjusted_progress',
        'assessed_at',
        'therapist_id',
    ];

    protected $casts = [
        'gross_motor_milestones' => 'array',
        'primitive_reflexes' => 'array',
        'postural_reactions' => 'array',
        'gmfm_score' => 'decimal:2',
        'pdms_score' => 'decimal:2',
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

