<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'clinic_id',
        'slot_number',
        'slot_start_time',
        'slot_end_time',
        'slot_duration_minutes',
        'status',
        'notes'
    ];

    protected $casts = [
        'slot_start_time' => 'datetime',
        'slot_end_time' => 'datetime',
    ];

    public function appointment()
    {
        return $this->belongsTo(ClinicAppointment::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function doctorAssignments()
    {
        return $this->hasMany(SlotDoctorAssignment::class, 'slot_id');
    }

    public function assignedDoctor()
    {
        return $this->hasOne(SlotDoctorAssignment::class, 'slot_id')
            ->where('status', '!=', 'cancelled')
            ->latest();
    }

    /**
     * Check if slot is available for assignment
     */
    public function isAvailable(): bool
    {
        return $this->status === 'pending' && !$this->assignedDoctor;
    }
}

