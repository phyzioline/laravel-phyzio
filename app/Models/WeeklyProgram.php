<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WeeklyProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'episode_id',
        'therapist_id',
        'program_name',
        'specialty',
        'sessions_per_week',
        'total_weeks',
        'total_sessions',
        'session_types',
        'progression_rules',
        'reassessment_interval_weeks',
        'reassessment_schedule',
        'payment_plan',
        'total_price',
        'discount_percentage',
        'weekly_price',
        'monthly_price',
        'paid_amount',
        'remaining_balance',
        'status',
        'start_date',
        'end_date',
        'actual_end_date',
        'auto_booking_enabled',
        'preferred_times',
        'preferred_days',
        'notes',
        'goals'
    ];

    protected $casts = [
        'session_types' => 'array',
        'progression_rules' => 'array',
        'reassessment_schedule' => 'array',
        'preferred_times' => 'array',
        'preferred_days' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_end_date' => 'date',
        'total_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'weekly_price' => 'decimal:2',
        'monthly_price' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'auto_booking_enabled' => 'boolean',
        'sessions_per_week' => 'integer',
        'total_weeks' => 'integer',
        'total_sessions' => 'integer',
        'reassessment_interval_weeks' => 'integer'
    ];

    /**
     * Relationship: Clinic
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Relationship: Patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Relationship: Episode
     */
    public function episode()
    {
        return $this->belongsTo(EpisodeOfCare::class, 'episode_id');
    }

    /**
     * Relationship: Therapist
     */
    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }

    /**
     * Relationship: Program Sessions
     */
    public function sessions()
    {
        return $this->hasMany(ProgramSession::class, 'program_id');
    }

    /**
     * Get completed sessions count
     */
    public function getCompletedSessionsCount(): int
    {
        return $this->sessions()->where('status', 'completed')->count();
    }

    /**
     * Get remaining sessions count
     */
    public function getRemainingSessionsCount(): int
    {
        return $this->total_sessions - $this->getCompletedSessionsCount();
    }

    /**
     * Get completion percentage
     */
    public function getCompletionPercentage(): float
    {
        if ($this->total_sessions === 0) {
            return 0;
        }
        return ($this->getCompletedSessionsCount() / $this->total_sessions) * 100;
    }

    /**
     * Check if program is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if program is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Calculate end date from start date and total weeks
     */
    public function calculateEndDate(): Carbon
    {
        return Carbon::parse($this->start_date)->addWeeks($this->total_weeks);
    }

    /**
     * Calculate total sessions
     */
    public function calculateTotalSessions(): int
    {
        return $this->sessions_per_week * $this->total_weeks;
    }

    /**
     * Get sessions for a specific week
     */
    public function getSessionsForWeek(int $weekNumber)
    {
        return $this->sessions()->where('week_number', $weekNumber)->get();
    }

    /**
     * Get next scheduled session
     */
    public function getNextScheduledSession()
    {
        return $this->sessions()
            ->where('status', 'scheduled')
            ->where('scheduled_date', '>=', now())
            ->orderBy('scheduled_date')
            ->first();
    }

    /**
     * Scope: Active programs
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: For patient
     */
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Scope: For specialty
     */
    public function scopeForSpecialty($query, string $specialty)
    {
        return $query->where('specialty', $specialty);
    }
}

