<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostureAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'episode_id',
        'view',
        'deviations',
        'alignment_notes',
        'photo_references',
        'assessed_at',
        'therapist_id',
    ];

    protected $casts = [
        'deviations' => 'array',
        'photo_references' => 'array',
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

