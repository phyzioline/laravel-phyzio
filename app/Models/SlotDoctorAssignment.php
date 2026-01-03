<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlotDoctorAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'slot_id',
        'appointment_id',
        'doctor_id',
        'clinic_id',
        'assigned_at',
        'assigned_by',
        'status',
        'notes'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    public function slot()
    {
        return $this->belongsTo(BookingSlot::class, 'slot_id');
    }

    public function appointment()
    {
        return $this->belongsTo(ClinicAppointment::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Create work log entry for this assignment
     */
    public function createWorkLog(): DoctorWorkLog
    {
        $slot = $this->slot;
        $appointment = $this->appointment;
        $hoursWorked = $slot->slot_duration_minutes / 60;
        
        // Get doctor's hourly rate
        $hourlyRate = DoctorHourlyRate::where('clinic_id', $this->clinic_id)
            ->where('doctor_id', $this->doctor_id)
            ->where('is_active', true)
            ->first();
        
        $rate = $hourlyRate ? (float) $hourlyRate->hourly_rate : 0;
        
        return DoctorWorkLog::create([
            'clinic_id' => $this->clinic_id,
            'doctor_id' => $this->doctor_id,
            'appointment_id' => $this->appointment_id,
            'slot_id' => $this->slot_id,
            'assignment_id' => $this->id,
            'patient_id' => $appointment->patient_id,
            'work_date' => $slot->slot_start_time->toDateString(),
            'start_time' => $slot->slot_start_time,
            'end_time' => $slot->slot_end_time,
            'hours_worked' => $hoursWorked,
            'minutes_worked' => $slot->slot_duration_minutes,
            'hourly_rate' => $rate,
            'total_amount' => $hoursWorked * $rate,
            'session_type' => $appointment->booking_type,
            'specialty' => $appointment->specialty,
            'status' => 'pending',
        ]);
    }
}

