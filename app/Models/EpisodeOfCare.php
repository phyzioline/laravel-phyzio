<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpisodeOfCare extends Model
{
    use HasFactory;

    protected $table = 'episodes_of_care';

    protected $fillable = [
        'patient_id',
        'clinic_id',
        'primary_therapist_id',
        'specialty', // orthopedic, pediatric, neurological, sports
        'diagnosis_icd',
        'chief_complaint',
        'start_date',
        'end_date',
        'status',
        'discharge_summary'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function clinic()
    {
        return $this->belongsTo(User::class, 'clinic_id');
    }
    
    public function primaryTherapist()
    {
        return $this->belongsTo(User::class, 'primary_therapist_id');
    }

    public function assessments()
    {
        return $this->hasMany(ClinicalAssessment::class, 'episode_id');
    }

    public function treatments()
    {
        return $this->hasMany(Treatment::class, 'episode_id');
    }

    public function outcomes()
    {
        return $this->hasMany(Outcome::class, 'episode_id');
    }
}
