<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JointROMMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'episode_id',
        'joint_name',
        'movement',
        'type',
        'degrees',
        'end_feel',
        'notes',
        'measured_at',
        'therapist_id',
    ];

    protected $casts = [
        'degrees' => 'decimal:2',
        'measured_at' => 'datetime',
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

