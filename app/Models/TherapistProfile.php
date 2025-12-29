<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TherapistProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'specialization',
        'years_experience',
        'license_number',
        'license_document',
        'id_document',
        'hourly_rate',
        'home_visit_rate',
        'available_areas',
        'working_hours',
        'rating',
        'total_reviews',
        'status',
        'verified_at',
        'total_earnings',
        'platform_balance',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'iban',
        'swift_code',
        'can_access_clinic',
        'can_access_instructor',
        'home_visit_verified',
        'courses_verified',
        'clinic_verified',
        'home_visit_verified_at',
        'courses_verified_at',
        'clinic_verified_at',
        'profile_photo',
        'profile_image'
    ];

    protected $casts = [
        'available_areas' => 'array',
        'working_hours' => 'array',
        'verified_at' => 'datetime',
        'home_visit_verified' => 'boolean',
        'courses_verified' => 'boolean',
        'clinic_verified' => 'boolean',
        'home_visit_verified_at' => 'datetime',
        'courses_verified_at' => 'datetime',
        'clinic_verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function homeVisits()
    {
        return $this->hasMany(HomeVisit::class, 'therapist_id', 'user_id');
    }

    // New AI Feature: Verified Skills
    public function verifiedSkills()
    {
        return $this->hasManyThrough(
            Skill::class,
            SkillVerification::class,
            'user_id', // Foreign key on skill_verifications table...
            'id',      // Foreign key on skills table...
            'user_id', // Local key on therapist_profiles table...
            'skill_id' // Local key on skill_verifications table...
        )->where('skill_verifications.status', 'approved');
    }

    /**
     * Get module verifications
     */
    public function moduleVerifications()
    {
        return $this->hasMany(TherapistModuleVerification::class);
    }

    /**
     * Get verification for a specific module
     */
    public function getModuleVerification(string $moduleType)
    {
        return $this->moduleVerifications()->where('module_type', $moduleType)->first();
    }

    /**
     * Check if therapist can access home visit module
     */
    public function canAccessHomeVisit(): bool
    {
        return $this->home_visit_verified === true;
    }

    /**
     * Check if therapist can access courses module
     */
    public function canAccessCourses(): bool
    {
        return $this->courses_verified === true;
    }

    /**
     * Check if therapist can access clinic module
     */
    public function canAccessClinic(): bool
    {
        return $this->clinic_verified === true;
    }

    /**
     * Check if therapist can access a specific module
     */
    public function canAccessModule(string $moduleType): bool
    {
        return match($moduleType) {
            'home_visit' => $this->canAccessHomeVisit(),
            'courses' => $this->canAccessCourses(),
            'clinic' => $this->canAccessClinic(),
            default => false,
        };
    }
}
