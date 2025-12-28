<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'reminder_type',
        'minutes_before',
        'status',
        'scheduled_for',
        'sent_at',
        'error_message'
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'sent_at' => 'datetime'
    ];

    /**
     * Relationship: Appointment
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(ClinicAppointment::class, 'appointment_id');
    }

    /**
     * Scope: Pending reminders
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Due reminders
     */
    public function scopeDue($query)
    {
        return $query->where('status', 'pending')
            ->where('scheduled_for', '<=', now());
    }
}

