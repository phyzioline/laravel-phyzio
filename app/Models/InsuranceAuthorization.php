<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InsuranceAuthorization extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'patient_insurance_id',
        'authorization_number',
        'referral_number',
        'authorization_date',
        'expiration_date',
        'approved_visits',
        'used_visits',
        'remaining_visits',
        'approved_amount',
        'diagnosis_codes',
        'procedure_codes',
        'status',
        'notes',
        'conditions',
    ];

    protected $casts = [
        'authorization_date' => 'date',
        'expiration_date' => 'date',
        'approved_visits' => 'integer',
        'used_visits' => 'integer',
        'remaining_visits' => 'integer',
        'approved_amount' => 'decimal:2',
        'conditions' => 'array',
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

    public function claims(): HasMany
    {
        return $this->hasMany(InsuranceClaim::class, 'authorization_id');
    }

    /**
     * Check if authorization is valid for a given date
     */
    public function isValidForDate(\Carbon\Carbon $date): bool
    {
        return $this->status === 'approved' 
            && $date->greaterThanOrEqualTo($this->authorization_date)
            && $date->lessThanOrEqualTo($this->expiration_date)
            && $this->remaining_visits > 0;
    }

    /**
     * Use one visit from authorization
     */
    public function useVisit(): void
    {
        if ($this->remaining_visits > 0) {
            $this->increment('used_visits');
            $this->decrement('remaining_visits');
            
            if ($this->remaining_visits <= 0) {
                $this->update(['status' => 'exhausted']);
            }
        }
    }
}

