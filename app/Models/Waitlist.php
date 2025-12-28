<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Waitlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'doctor_id',
        'specialty',
        'visit_type',
        'priority',
        'notes',
        'preferred_start_date',
        'preferred_end_date',
        'preferred_times',
        'preferred_days',
        'status',
        'notified_at',
        'booked_at'
    ];

    protected $casts = [
        'preferred_start_date' => 'date',
        'preferred_end_date' => 'date',
        'preferred_times' => 'array',
        'preferred_days' => 'array',
        'notified_at' => 'datetime',
        'booked_at' => 'datetime'
    ];

    /**
     * Relationship: Clinic
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Relationship: Patient
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Relationship: Doctor/Therapist
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Mark as notified
     */
    public function markAsNotified(): void
    {
        $this->update([
            'status' => 'notified',
            'notified_at' => now()
        ]);
    }

    /**
     * Mark as booked
     */
    public function markAsBooked(): void
    {
        $this->update([
            'status' => 'booked',
            'booked_at' => now()
        ]);
    }

    /**
     * Cancel waitlist entry
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    /**
     * Scope: Active entries
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: For priority
     */
    public function scopePriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope: For specialty
     */
    public function scopeForSpecialty($query, string $specialty)
    {
        return $query->where('specialty', $specialty);
    }
}

