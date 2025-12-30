<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicSpecialty extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'specialty',
        'is_primary',
        'is_active',
        'activated_at'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
        'activated_at' => 'datetime'
    ];

    /**
     * Available specialty types
     */
    public const SPECIALTIES = [
        'musculoskeletal' => 'Musculoskeletal / Orthopedic Physical Therapy',
        'neurological' => 'Neurological Physical Therapy',
        'cardiopulmonary' => 'Cardiopulmonary Physical Therapy',
        'pediatric' => 'Pediatric Physical Therapy',
        'geriatric' => 'Geriatric Physical Therapy',
        'sports' => 'Sports Physical Therapy',
        'womens_health' => "Women's Health / Pelvic Floor Physical Therapy",
        'pain_management' => 'Pain Management Physical Therapy',
        'occupational' => 'Occupational Therapy',
        'home_care' => 'Home Care / Mobile Physical Therapy',
        'multi_specialty' => 'Multi-Specialty Clinic'
    ];

    /**
     * Get all available specialties as array
     */
    public static function getAvailableSpecialties(): array
    {
        return self::SPECIALTIES;
    }

    /**
     * Get specialty display name
     */
    public function getDisplayNameAttribute(): string
    {
        return self::SPECIALTIES[$this->specialty] ?? ucfirst(str_replace('_', ' ', $this->specialty));
    }

    /**
     * Relationship: Clinic
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Scope: Active specialties
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Primary specialty
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}

