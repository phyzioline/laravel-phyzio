<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntakeFormResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'intake_form_id',
        'patient_id',
        'appointment_id',
        'responses',
        'status',
        'submitted_at'
    ];

    protected $casts = [
        'responses' => 'array',
        'submitted_at' => 'datetime'
    ];

    /**
     * Relationship: Intake Form
     */
    public function intakeForm(): BelongsTo
    {
        return $this->belongsTo(IntakeForm::class, 'intake_form_id');
    }

    /**
     * Relationship: Patient
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Relationship: Appointment
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(ClinicAppointment::class, 'appointment_id');
    }

    /**
     * Mark as submitted
     */
    public function submit(): void
    {
        $this->update([
            'status' => 'submitted',
            'submitted_at' => now()
        ]);
    }
}

