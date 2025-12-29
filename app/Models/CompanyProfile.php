<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'description',
        'logo',
        'address',
        'phone',
        'website',
        'industry',
        'company_size',
        'subscription_plan',
        'status',
        'is_verified',
        'clinic_verified',
        'clinic_verified_at',
    ];

    protected $casts = [
        'clinic_verified' => 'boolean',
        'clinic_verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'clinic_id', 'user_id')
            ->where('posted_by_type', 'company');
    }

    /**
     * Get module verifications
     */
    public function moduleVerifications()
    {
        return $this->hasMany(CompanyModuleVerification::class);
    }

    /**
     * Get verification for clinic module
     */
    public function getClinicVerification()
    {
        return $this->moduleVerifications()->where('module_type', 'clinic')->first();
    }

    /**
     * Check if company can access clinic module
     */
    public function canAccessClinic(): bool
    {
        return $this->clinic_verified === true;
    }
}

