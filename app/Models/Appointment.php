<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'therapist_id',
        'appointment_date',
        'appointment_time',
        'duration_hours',
        'status',
        'location',
        'location_address',
        'location_lat',
        'location_lng',
        'notes',
        'patient_notes',
        'therapist_notes',
        'cancellation_reason',
        'price',
        'payment_status',
        'payment_method',
        'payment_transaction_id',
        'confirmed_at',
        'completed_at',
        'cancelled_at'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }

    public function payments()
    {
        return $this->morphMany(\App\Models\Payment::class, 'paymentable');
    }
}
