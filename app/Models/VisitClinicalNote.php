<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitClinicalNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_visit_id',
        'chief_complaint',
        'assessment_findings',
        'treatment_performed',
        'outcome_measures',
        'next_plan',
        'vital_signs'
    ];

    protected $casts = [
        'assessment_findings' => 'array',
        'treatment_performed' => 'array',
        'vital_signs' => 'array',
    ];

    public function homeVisit()
    {
        return $this->belongsTo(HomeVisit::class);
    }
}
