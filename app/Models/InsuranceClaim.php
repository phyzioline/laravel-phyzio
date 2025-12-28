<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InsuranceClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'appointment_id',
        'invoice_id',
        'patient_insurance_id',
        'authorization_id',
        'claim_number',
        'control_number',
        'claim_type',
        'date_of_service',
        'date_of_service_end',
        'billed_amount',
        'allowed_amount',
        'paid_amount',
        'patient_responsibility',
        'diagnosis_codes',
        'procedure_codes',
        'modifiers',
        'status',
        'submitted_at',
        'processed_at',
        'denial_reason',
        'denial_code',
        'notes',
        'scrubbing_results',
        'era_data',
        'requires_resubmission',
    ];

    protected $casts = [
        'date_of_service' => 'date',
        'date_of_service_end' => 'date',
        'billed_amount' => 'decimal:2',
        'allowed_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'patient_responsibility' => 'decimal:2',
        'submitted_at' => 'date',
        'processed_at' => 'date',
        'scrubbing_results' => 'array',
        'era_data' => 'array',
        'requires_resubmission' => 'boolean',
    ];

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(ClinicAppointment::class, 'appointment_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function patientInsurance(): BelongsTo
    {
        return $this->belongsTo(PatientInsurance::class);
    }

    public function authorization(): BelongsTo
    {
        return $this->belongsTo(InsuranceAuthorization::class, 'authorization_id');
    }

    public function denials(): HasMany
    {
        return $this->hasMany(ClaimDenial::class, 'claim_id');
    }

    /**
     * Generate unique claim number
     */
    public static function generateClaimNumber(int $clinicId): string
    {
        $prefix = 'CLM-' . $clinicId . '-';
        $datePart = now()->format('Ymd');
        $sequence = 1;

        $latestClaim = self::where('clinic_id', $clinicId)
            ->where('claim_number', 'like', $prefix . $datePart . '-%')
            ->orderBy('claim_number', 'desc')
            ->first();

        if ($latestClaim) {
            $parts = explode('-', $latestClaim->claim_number);
            $sequence = (int)end($parts) + 1;
        }

        return $prefix . $datePart . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}

