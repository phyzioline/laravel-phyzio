<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorWorkLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'doctor_id',
        'appointment_id',
        'slot_id',
        'assignment_id',
        'patient_id',
        'work_date',
        'start_time',
        'end_time',
        'hours_worked',
        'minutes_worked',
        'hourly_rate',
        'total_amount',
        'session_type',
        'specialty',
        'status',
        'payment_date',
        'notes'
    ];

    protected $casts = [
        'work_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'hours_worked' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function appointment()
    {
        return $this->belongsTo(ClinicAppointment::class);
    }

    public function slot()
    {
        return $this->belongsTo(BookingSlot::class);
    }

    public function assignment()
    {
        return $this->belongsTo(SlotDoctorAssignment::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Scope: Get logs for a specific date range
     */
    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('work_date', [$startDate, $endDate]);
    }

    /**
     * Scope: Get logs for a specific doctor
     */
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /**
     * Scope: Get paid logs
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope: Get pending payment logs
     */
    public function scopePendingPayment($query)
    {
        return $query->where('status', 'pending');
    }
}

