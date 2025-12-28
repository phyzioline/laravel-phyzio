<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentReservation extends Model
{
    use HasFactory;

    protected $table = 'equipment_reservations';

    protected $fillable = [
        'equipment_id',
        'appointment_id',
        'clinic_id',
        'reserved_from',
        'reserved_to',
        'status',
        'notes'
    ];

    protected $casts = [
        'reserved_from' => 'datetime',
        'reserved_to' => 'datetime'
    ];

    /**
     * Relationship: Equipment
     */
    public function equipment()
    {
        return $this->belongsTo(EquipmentInventory::class, 'equipment_id');
    }

    /**
     * Relationship: Appointment
     */
    public function appointment()
    {
        return $this->belongsTo(ClinicAppointment::class, 'appointment_id');
    }

    /**
     * Relationship: Clinic
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Mark as in use
     */
    public function markAsInUse(): void
    {
        $this->update(['status' => 'in_use']);
    }

    /**
     * Mark as returned
     */
    public function markAsReturned(): void
    {
        $this->update(['status' => 'returned']);
    }

    /**
     * Cancel reservation
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}

