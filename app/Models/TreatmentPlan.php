<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TreatmentPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'created_by',
        'clinic_type',
        'plan_duration_weeks',
        'frequency',
        'frequency_days',
        'allowed_session_types',
        'session_duration_minutes',
        'intensive_hours',
        'status',
        'start_date',
        'end_date',
        'notes'
    ];

    protected $casts = [
        'frequency_days' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function appointments()
    {
        return $this->hasMany(ClinicAppointment::class);
    }

    /**
     * Generate sessions based on plan configuration
     */
    public function generateSessions(): array
    {
        $sessions = [];
        $startDate = $this->start_date ?? now();
        $endDate = $this->end_date ?? $startDate->copy()->addWeeks($this->plan_duration_weeks);
        
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $dayOfWeek = $currentDate->dayOfWeek; // 0 = Sunday, 6 = Saturday
            
            // Check if this day matches frequency
            if ($this->shouldGenerateSession($dayOfWeek)) {
                $sessions[] = [
                    'date' => $currentDate->copy(),
                    'time_window' => null, // To be set during booking
                    'duration' => $this->session_duration_minutes,
                    'type' => $this->allowed_session_types === 'both' ? 'regular' : $this->allowed_session_types,
                ];
            }
            
            $currentDate->addDay();
        }
        
        return $sessions;
    }

    private function shouldGenerateSession(int $dayOfWeek): bool
    {
        if ($this->frequency === 'daily') {
            return true; // All days except off-days (to be configured)
        }
        
        if ($this->frequency === 'three_per_week') {
            // Default: Mon, Wed, Fri (1, 3, 5)
            $defaultDays = [1, 3, 5];
            $days = $this->frequency_days ?? $defaultDays;
            return in_array($dayOfWeek, $days);
        }
        
        if ($this->frequency === 'two_per_week') {
            // Default: Sat, Mon (6, 1)
            $defaultDays = [6, 1];
            $days = $this->frequency_days ?? $defaultDays;
            return in_array($dayOfWeek, $days);
        }
        
        if ($this->frequency === 'weekly') {
            // Once per week (default: Saturday)
            $defaultDays = [6];
            $days = $this->frequency_days ?? $defaultDays;
            return in_array($dayOfWeek, $days);
        }
        
        return false;
    }
}
