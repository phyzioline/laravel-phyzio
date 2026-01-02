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

    public function outcomeMeasures()
    {
        return $this->hasMany(OutcomeMeasure::class, 'assessment_id');
    }

    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }

    /**
     * Get specialty key from episode
     */
    public function getSpecialtyKeyAttribute(): ?string
    {
        return $this->episode->specialty_key ?? null;
    }

    /**
     * Relationships to specialty-specific data
     */
    public function jointROM()
    {
        return $this->hasMany(\App\Models\JointROMMeasurement::class, 'assessment_id');
    }

    public function muscleStrength()
    {
        return $this->hasMany(\App\Models\MuscleStrengthGrade::class, 'assessment_id');
    }

    public function specialTests()
    {
        return $this->hasMany(\App\Models\SpecialOrthopedicTest::class, 'assessment_id');
    }

    public function painAssessment()
    {
        return $this->hasOne(\App\Models\PainAssessment::class, 'assessment_id');
    }

    public function neurologicalAssessment()
    {
        return $this->hasOne(\App\Models\NeurologicalAssessment::class, 'assessment_id');
    }

    public function cardiopulmonaryAssessment()
    {
        return $this->hasOne(\App\Models\CardiopulmonaryAssessment::class, 'assessment_id');
    }

    public function pediatricAssessment()
    {
        return $this->hasOne(\App\Models\PediatricAssessment::class, 'assessment_id');
    }

    public function geriatricAssessment()
    {
        return $this->hasOne(\App\Models\GeriatricAssessment::class, 'assessment_id');
    }

    public function sportsAssessment()
    {
        return $this->hasOne(\App\Models\SportsPerformanceAssessment::class, 'assessment_id');
    }

    public function pelvicFloorAssessment()
    {
        return $this->hasOne(\App\Models\PelvicFloorAssessment::class, 'assessment_id');
    }

    public function painManagementAssessment()
    {
        return $this->hasOne(\App\Models\PainManagementAssessment::class, 'assessment_id');
    }

    public function outcomeMeasures()
    {
        return $this->hasMany(\App\Models\OutcomeMeasure::class, 'assessment_id');
    }

    public function functionalScores()
    {
        return $this->hasMany(\App\Models\FunctionalScore::class, 'assessment_id');
    }

    public function postureAnalysis()
    {
        return $this->hasOne(\App\Models\PostureAnalysis::class, 'assessment_id');
    }

    public function gaitAnalysis()
    {
        return $this->hasOne(\App\Models\GaitAnalysis::class, 'assessment_id');
    }

    public function vitalSigns()
    {
        return $this->hasMany(\App\Models\VitalSignsLog::class, 'assessment_id');
    }
}
