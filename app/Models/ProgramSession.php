<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProgramSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'appointment_id',
        'scheduled_date',
        'scheduled_time',
        'week_number',
        'session_number',
        'session_in_program',
        'session_type',
        'status',
        'attended_at',
        'session_notes',
        'therapist_notes',
        'rescheduled_from_id',
        'original_date',
        'session_price'
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime',
        'attended_at' => 'datetime',
        'original_date' => 'date',
        'session_price' => 'decimal:2',
        'week_number' => 'integer',
        'session_number' => 'integer',
        'session_in_program' => 'integer'
    ];

    /**
     * Relationship: Program
     */
    public function program()
    {
        return $this->belongsTo(WeeklyProgram::class, 'program_id');
    }

    /**
     * Relationship: Appointment
     */
    public function appointment()
    {
        return $this->belongsTo(ClinicAppointment::class, 'appointment_id');
    }

    /**
     * Relationship: Rescheduled From
     */
    public function rescheduledFrom()
    {
        return $this->belongsTo(ProgramSession::class, 'rescheduled_from_id');
    }

    /**
     * Check if session is booked
     */
    public function isBooked(): bool
    {
        return $this->status === 'booked' && $this->appointment_id !== null;
    }

    /**
     * Check if session is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'attended_at' => now()
        ]);
    }

    /**
     * Cancel session
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
        
        // If has appointment, cancel it too
        if ($this->appointment) {
            $this->appointment->update(['status' => 'cancelled']);
        }
    }

    /**
     * Reschedule session
     */
    public function reschedule(Carbon $newDate, ?string $newTime = null): ProgramSession
    {
        // Create new session
        $newSession = $this->replicate();
        $newSession->scheduled_date = $newDate;
        $newSession->scheduled_time = $newTime ? Carbon::parse($newTime) : null;
        $newSession->status = 'scheduled';
        $newSession->appointment_id = null;
        $newSession->rescheduled_from_id = $this->id;
        $newSession->original_date = $this->scheduled_date;
        $newSession->save();

        // Cancel old session
        $this->cancel();

        return $newSession;
    }

    /**
     * Scope: Scheduled
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope: Completed
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: For week
     */
    public function scopeForWeek($query, int $weekNumber)
    {
        return $query->where('week_number', $weekNumber);
    }
}

