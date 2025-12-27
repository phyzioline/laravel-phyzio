<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'therapist_id',
        'patient_id',
        'diagnosis',
        'short_term_goals',
        'long_term_goals',
        'planned_sessions',
        'frequency',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function therapist()
    {
        return $this->belongsTo(TherapistProfile::class, 'therapist_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
