<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'doctor_id',
        'appointment_date',
        'duration_minutes',
        'status',
        'notes',
        'visit_type',
        'location',
        'payment_method',
        'specialty',
        'session_type'
    ];

    protected $casts = [
        'appointment_date' => 'datetime'
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Relationship: Additional reservation data
     */
    public function additionalData()
    {
        return $this->hasOne(ReservationAdditionalData::class, 'appointment_id');
    }

    /**
     * Check if appointment has additional data
     */
    public function hasAdditionalData(): bool
    {
        return $this->additionalData !== null;
    }

    /**
     * Get specialty-specific data field
     */
    public function getAdditionalDataField(string $key, $default = null)
    {
        if (!$this->additionalData) {
            return $default;
        }

        return $this->additionalData->getDataField($key, $default);
    }
}
