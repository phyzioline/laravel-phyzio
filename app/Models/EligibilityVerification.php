<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EligibilityVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'patient_insurance_id',
        'verification_date',
        'service_date',
        'status',
        'is_eligible',
        'coverage_start_date',
        'coverage_end_date',
        'benefits',
        'copay_info',
        'deductible_info',
        'response_data',
        'error_message',
    ];

    protected $casts = [
        'verification_date' => 'date',
        'service_date' => 'date',
        'coverage_start_date' => 'date',
        'coverage_end_date' => 'date',
        'is_eligible' => 'boolean',
        'benefits' => 'array',
        'copay_info' => 'array',
        'deductible_info' => 'array',
    ];

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function patientInsurance(): BelongsTo
    {
        return $this->belongsTo(PatientInsurance::class);
    }
}

