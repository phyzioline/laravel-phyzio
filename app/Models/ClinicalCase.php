<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicalCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'pathology',
        'icd_code',
        'patient_age_group',
        'assessment_tools', // json
        'red_flags',
        'treatment_plan',
        'outcome_measures', // json
    ];

    protected $casts = [
        'assessment_tools' => 'array',
        'outcome_measures' => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
