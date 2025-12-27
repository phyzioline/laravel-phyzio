<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'description',
        'address',
        'city',
        'country',
        'phone',
        'email',
        'subscription_tier',
        'subscription_start',
        'subscription_end',
        'is_active',
        'primary_specialty',
        'specialty_selected',
        'specialty_selected_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'specialty_selected' => 'boolean',
        'subscription_start' => 'datetime',
        'subscription_end' => 'datetime',
        'specialty_selected_at' => 'datetime'
    ];

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function appointments()
    {
        return $this->hasMany(ClinicAppointment::class);
    }

    /**
     * Relationship: Clinic Specialties (many-to-many)
     */
    public function specialties()
    {
        return $this->hasMany(ClinicSpecialty::class);
    }

    /**
     * Get primary specialty
     */
    public function primarySpecialty()
    {
        return $this->hasOne(ClinicSpecialty::class)->where('is_primary', true)->where('is_active', true);
    }

    /**
     * Get active specialties
     */
    public function activeSpecialties()
    {
        return $this->hasMany(ClinicSpecialty::class)->where('is_active', true);
    }

    /**
     * Check if clinic has selected specialty
     */
    public function hasSelectedSpecialty(): bool
    {
        return $this->specialty_selected && !empty($this->primary_specialty);
    }

    /**
     * Check if clinic has a specific specialty active
     */
    public function hasSpecialty(string $specialty): bool
    {
        return $this->specialties()
            ->where('specialty', $specialty)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Check if clinic is multi-specialty
     */
    public function isMultiSpecialty(): bool
    {
        return $this->activeSpecialties()->count() > 1;
    }

    /**
     * Get specialty display name
     */
    public function getPrimarySpecialtyDisplayName(): ?string
    {
        if (!$this->primary_specialty) {
            return null;
        }

        return ClinicSpecialty::SPECIALTIES[$this->primary_specialty] ?? ucfirst(str_replace('_', ' ', $this->primary_specialty));
    }
}
