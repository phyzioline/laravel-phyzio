<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardiopulmonaryAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'episode_id',
        'heart_rate',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'respiratory_rate',
        'oxygen_saturation',
        'chest_expansion',
        'breath_sounds',
        'dyspnea_scale',
        'six_minute_walk_distance',
        'exercise_tolerance',
        'on_oxygen',
        'assessed_at',
        'therapist_id',
    ];

    protected $casts = [
        'chest_expansion' => 'decimal:2',
        'on_oxygen' => 'boolean',
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

