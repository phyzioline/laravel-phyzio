<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationAdditionalData extends Model
{
    use HasFactory;

    protected $table = 'reservation_additional_data';

    protected $fillable = [
        'appointment_id',
        'specialty',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    /**
     * Relationship: Appointment
     */
    public function appointment()
    {
        return $this->belongsTo(ClinicAppointment::class, 'appointment_id');
    }

    /**
     * Get a specific data field
     */
    public function getDataField(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Set a specific data field
     */
    public function setDataField(string $key, $value): void
    {
        $data = $this->data ?? [];
        $data[$key] = $value;
        $this->data = $data;
    }

    /**
     * Get all data as array
     */
    public function getAllData(): array
    {
        return $this->data ?? [];
    }
}

