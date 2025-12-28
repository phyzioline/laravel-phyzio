<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PatientInsurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'insurance_provider_id',
        'policy_number',
        'group_number',
        'subscriber_name',
        'subscriber_relationship',
        'effective_date',
        'expiration_date',
        'is_primary',
        'benefits',
        'copay_info',
        'is_active',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'expiration_date' => 'date',
        'is_primary' => 'boolean',
        'benefits' => 'array',
        'copay_info' => 'array',
        'is_active' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function insuranceProvider(): BelongsTo
    {
        return $this->belongsTo(InsuranceProvider::class, 'insurance_provider_id');
    }

    public function authorizations(): HasMany
    {
        return $this->hasMany(InsuranceAuthorization::class, 'patient_insurance_id');
    }

    public function claims(): HasMany
    {
        return $this->hasMany(InsuranceClaim::class, 'patient_insurance_id');
    }

    public function eligibilityVerifications(): HasMany
    {
        return $this->hasMany(EligibilityVerification::class, 'patient_insurance_id');
    }
}

