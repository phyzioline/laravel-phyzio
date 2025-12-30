<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PTSpecialtyConfig extends Model
{
    use HasFactory;

    protected $table = 'pt_specialty_configs';

    protected $fillable = [
        'specialty_key',
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'assessment_components',
        'outcome_measures',
        'tools_devices',
        'is_active',
    ];

    protected $casts = [
        'assessment_components' => 'array',
        'outcome_measures' => 'array',
        'tools_devices' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Available specialty keys
     */
    public const SPECIALTY_KEYS = [
        'musculoskeletal' => 'Musculoskeletal / Orthopedic',
        'neurological' => 'Neurological Physical Therapy',
        'cardiopulmonary' => 'Cardiopulmonary Physical Therapy',
        'pediatric' => 'Pediatric Physical Therapy',
        'geriatric' => 'Geriatric Physical Therapy',
        'sports' => 'Sports Physical Therapy',
        'womens_health' => "Women's Health / Pelvic Floor",
        'pain_management' => 'Pain Management Physical Therapy',
        'occupational' => 'Occupational Therapy',
    ];

    /**
     * Get specialty by key
     */
    public static function getByKey(string $key): ?self
    {
        return self::where('specialty_key', $key)->where('is_active', true)->first();
    }

    /**
     * Get all active specialties
     */
    public static function getActiveSpecialties()
    {
        return self::where('is_active', true)->get();
    }
}

