<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeriatricAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'episode_id',
        'fall_history_count',
        'fall_circumstances',
        'berg_balance_score',
        'tinetti_score',
        'tug_time',
        'gait_speed',
        'mobility_aids',
        'independence_score',
        'fall_risk_level',
        'assessed_at',
        'therapist_id',
    ];

    protected $casts = [
        'berg_balance_score' => 'decimal:2',
        'tinetti_score' => 'decimal:2',
        'tug_time' => 'decimal:2',
        'gait_speed' => 'decimal:2',
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

