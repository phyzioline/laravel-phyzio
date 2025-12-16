<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicalAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'episode_id',
        'therapist_id',
        'assessment_date',
        'type', // initial, re_eval
        'subjective_data', // json
        'objective_data',  // json
        'analysis',
        'red_flags_detected',
        'attachments' // json
    ];

    protected $casts = [
        'subjective_data' => 'array',
        'objective_data' => 'array',
        'attachments' => 'array',
        'red_flags_detected' => 'boolean',
        'assessment_date' => 'date'
    ];

    public function episode()
    {
        return $this->belongsTo(EpisodeOfCare::class, 'episode_id');
    }

    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }
}
