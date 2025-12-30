<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitalSignsLog extends Model
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
        'temperature',
        'notes',
        'recorded_at',
        'therapist_id',
    ];

    protected $casts = [
        'temperature' => 'decimal:1',
        'recorded_at' => 'datetime',
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

