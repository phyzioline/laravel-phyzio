<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'therapist_id',
        'package_id',
        'location_lat',
        'location_lng',
        'address',
        'city',
        'scheduled_at',
        'arrived_at',
        'completed_at',
        'confirmed_at',
        'cancelled_at',
        'status',
        'complain_type',
        'urgency',
        'total_amount',
        'payment_method',
        'payment_status',
        'duration_hours',
        'notes',
        'patient_notes',
        'therapist_notes',
        'cancellation_reason',
        'payment_transaction_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'is_guest_booking'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'arrived_at' => 'datetime',
        'completed_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'location_lat' => 'float',
        'location_lng' => 'float',
        'total_amount' => 'decimal:2',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get patient name (from user or guest)
     */
    public function getPatientNameAttribute()
    {
        if ($this->is_guest_booking) {
            return $this->guest_name;
        }
        return $this->patient ? $this->patient->name : null;
    }

    /**
     * Get patient email (from user or guest)
     */
    public function getPatientEmailAttribute()
    {
        if ($this->is_guest_booking) {
            return $this->guest_email;
        }
        return $this->patient ? $this->patient->email : null;
    }

    /**
     * Get patient phone (from user or guest)
     */
    public function getPatientPhoneAttribute()
    {
        if ($this->is_guest_booking) {
            return $this->guest_phone;
        }
        return $this->patient ? $this->patient->phone : null;
    }

    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }

    public function clinicalNotes()
    {
        return $this->hasOne(VisitClinicalNote::class);
    }

    public function package()
    {
        return $this->belongsTo(VisitPackage::class);
    }

    public function payments()
    {
        return $this->morphMany(\App\Models\Payment::class, 'paymentable');
    }
}
