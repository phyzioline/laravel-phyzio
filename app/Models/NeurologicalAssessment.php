<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NeurologicalAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'episode_id',
        'tone_scale',
        'reflexes',
        'coordination_tests',
        'balance_score',
        'gait_analysis',
        'sensory_testing',
        'functional_level',
        'assessed_at',
        'therapist_id',
    ];

    protected $casts = [
        'balance_score' => 'decimal:2',
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

