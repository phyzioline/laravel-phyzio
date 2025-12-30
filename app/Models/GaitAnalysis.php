<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaitAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'episode_id',
        'gait_speed',
        'cadence',
        'step_length',
        'stride_length',
        'gait_pattern',
        'deviations',
        'assistive_devices',
        'assessed_at',
        'therapist_id',
    ];

    protected $casts = [
        'gait_speed' => 'decimal:2',
        'step_length' => 'decimal:2',
        'stride_length' => 'decimal:2',
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

