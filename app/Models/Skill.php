<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'skill_name',
        'specialty',
        'body_region',
        'indications',
        'contraindications',
        'risk_level', // low, medium, high
        'force_level',
        'therapist_position',
        'patient_position',
        'safety_risk_score'
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_skills');
    }

    public function verifications()
    {
        return $this->hasMany(SkillVerification::class);
    }
}
