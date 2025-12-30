<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportsPerformanceAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'episode_id',
        'sport_type',
        'sport_specific_demands',
        'hop_test_distance',
        'limb_symmetry_index',
        'agility_time',
        'power_output',
        'rtp_status',
        'performance_metrics',
        'assessed_at',
        'therapist_id',
    ];

    protected $casts = [
        'hop_test_distance' => 'decimal:2',
        'limb_symmetry_index' => 'decimal:2',
        'agility_time' => 'decimal:2',
        'power_output' => 'decimal:2',
        'performance_metrics' => 'array',
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

