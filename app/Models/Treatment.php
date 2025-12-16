<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'episode_id',
        'assessment_id',
        'therapist_id',
        'treatment_date',
        'category',
        'details', // json
        'duration_minutes',
        'patient_response'
    ];

    protected $casts = [
        'details' => 'array',
        'treatment_date' => 'date'
    ];

    public function episode()
    {
        return $this->belongsTo(EpisodeOfCare::class, 'episode_id');
    }
}
