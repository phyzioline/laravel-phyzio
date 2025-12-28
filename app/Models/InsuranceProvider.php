<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InsuranceProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'payer_id',
        'contact_phone',
        'contact_email',
        'claims_address',
        'eligibility_endpoint',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    public function patientInsurances(): HasMany
    {
        return $this->hasMany(PatientInsurance::class, 'insurance_provider_id');
    }

    public function claims(): HasMany
    {
        return $this->hasMany(InsuranceClaim::class, 'insurance_provider_id');
    }
}

