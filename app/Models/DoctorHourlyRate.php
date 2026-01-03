<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorHourlyRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'doctor_id',
        'hourly_rate',
        'rate_type',
        'variable_rates',
        'effective_from',
        'effective_to',
        'max_hours_per_day',
        'max_hours_per_week',
        'allowed_specialties',
        'is_active'
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'variable_rates' => 'array',
        'allowed_specialties' => 'array',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'is_active' => 'boolean',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get effective hourly rate for a specific specialty or time
     */
    public function getEffectiveRate(?string $specialty = null, ?string $timeSlot = null): float
    {
        if ($this->rate_type === 'variable' && $this->variable_rates) {
            // Check for specialty-specific rate
            if ($specialty && isset($this->variable_rates[$specialty])) {
                return (float) $this->variable_rates[$specialty];
            }
            
            // Check for time-slot specific rate
            if ($timeSlot && isset($this->variable_rates[$timeSlot])) {
                return (float) $this->variable_rates[$timeSlot];
            }
        }
        
        return (float) $this->hourly_rate;
    }

    /**
     * Check if doctor can work more hours today
     */
    public function canWorkMoreHoursToday(): bool
    {
        $today = now()->toDateString();
        $hoursToday = DoctorWorkLog::where('clinic_id', $this->clinic_id)
            ->where('doctor_id', $this->doctor_id)
            ->where('work_date', $today)
            ->where('status', '!=', 'cancelled')
            ->sum('hours_worked');
        
        return $hoursToday < $this->max_hours_per_day;
    }

    /**
     * Check if doctor can work more hours this week
     */
    public function canWorkMoreHoursThisWeek(): bool
    {
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        
        $hoursThisWeek = DoctorWorkLog::where('clinic_id', $this->clinic_id)
            ->where('doctor_id', $this->doctor_id)
            ->whereBetween('work_date', [$weekStart, $weekEnd])
            ->where('status', '!=', 'cancelled')
            ->sum('hours_worked');
        
        return $hoursThisWeek < $this->max_hours_per_week;
    }
}

