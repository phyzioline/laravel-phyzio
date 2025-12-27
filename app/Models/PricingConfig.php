<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingConfig extends Model
{
    use HasFactory;

    protected $table = 'clinic_pricing_configs';

    protected $fillable = [
        'clinic_id',
        'specialty',
        'base_price',
        'evaluation_price',
        'followup_price',
        're_evaluation_price',
        'specialty_adjustment',
        'therapist_level_multipliers',
        'equipment_pricing',
        'location_factors',
        'duration_factors',
        'discount_rules',
        'insurance_enabled',
        'insurance_agreements',
        'is_active'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'evaluation_price' => 'decimal:2',
        'followup_price' => 'decimal:2',
        're_evaluation_price' => 'decimal:2',
        'specialty_adjustment' => 'decimal:2',
        'therapist_level_multipliers' => 'array',
        'equipment_pricing' => 'array',
        'location_factors' => 'array',
        'duration_factors' => 'array',
        'discount_rules' => 'array',
        'insurance_enabled' => 'boolean',
        'insurance_agreements' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Default therapist level multipliers
     */
    public const DEFAULT_THERAPIST_MULTIPLIERS = [
        'junior' => 1.0,
        'senior' => 1.3,
        'consultant' => 1.5
    ];

    /**
     * Default location factors
     */
    public const DEFAULT_LOCATION_FACTORS = [
        'clinic' => 1.0,
        'home_base' => 1.2,
        'home_premium' => 1.5
    ];

    /**
     * Default duration factors
     */
    public const DEFAULT_DURATION_FACTORS = [
        30 => 0.7,
        45 => 0.85,
        60 => 1.0,
        90 => 1.4
    ];

    /**
     * Relationship: Clinic
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get price for visit type
     */
    public function getPriceForVisitType(string $visitType): float
    {
        return match($visitType) {
            'evaluation' => $this->evaluation_price ?? $this->base_price * 1.5,
            're_evaluation' => $this->re_evaluation_price ?? $this->base_price * 1.3,
            'followup' => $this->followup_price ?? $this->base_price,
            default => $this->base_price
        };
    }

    /**
     * Get therapist level multiplier
     */
    public function getTherapistMultiplier(string $level): float
    {
        $multipliers = $this->therapist_level_multipliers ?? self::DEFAULT_THERAPIST_MULTIPLIERS;
        return $multipliers[$level] ?? 1.0;
    }

    /**
     * Get location factor
     */
    public function getLocationFactor(string $location): float
    {
        $factors = $this->location_factors ?? self::DEFAULT_LOCATION_FACTORS;
        
        if ($location === 'home') {
            return $factors['home_base'] ?? 1.2;
        }
        
        return $factors['clinic'] ?? 1.0;
    }

    /**
     * Get duration factor
     */
    public function getDurationFactor(int $durationMinutes): float
    {
        $factors = $this->duration_factors ?? self::DEFAULT_DURATION_FACTORS;
        return $factors[$durationMinutes] ?? 1.0;
    }

    /**
     * Get equipment price
     */
    public function getEquipmentPrice(string $equipment): float
    {
        $pricing = $this->equipment_pricing ?? [];
        return $pricing[$equipment] ?? 0;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentage(string $discountType): float
    {
        $rules = $this->discount_rules ?? [];
        return $rules[$discountType] ?? 0;
    }

    /**
     * Create default config for clinic and specialty
     */
    public static function createDefault(Clinic $clinic, string $specialty): self
    {
        return self::create([
            'clinic_id' => $clinic->id,
            'specialty' => $specialty,
            'base_price' => self::getDefaultBasePrice($specialty),
            'evaluation_price' => self::getDefaultBasePrice($specialty) * 1.5,
            'followup_price' => self::getDefaultBasePrice($specialty),
            're_evaluation_price' => self::getDefaultBasePrice($specialty) * 1.3,
            'specialty_adjustment' => 1.0,
            'therapist_level_multipliers' => self::DEFAULT_THERAPIST_MULTIPLIERS,
            'equipment_pricing' => self::getDefaultEquipmentPricing(),
            'location_factors' => self::DEFAULT_LOCATION_FACTORS,
            'duration_factors' => self::DEFAULT_DURATION_FACTORS,
            'discount_rules' => [
                'weekly_program' => 10,
                'monthly_package' => 20,
                'insurance' => 0
            ],
            'insurance_enabled' => false,
            'is_active' => true
        ]);
    }

    /**
     * Get default base price by specialty
     */
    protected static function getDefaultBasePrice(string $specialty): float
    {
        return match($specialty) {
            'sports' => 150.00,
            'neurological' => 120.00,
            'orthopedic' => 100.00,
            'womens_health' => 110.00,
            'cardiorespiratory' => 115.00,
            'pediatric' => 90.00,
            'geriatric' => 95.00,
            'home_care' => 130.00,
            default => 100.00
        };
    }

    /**
     * Get default equipment pricing
     */
    protected static function getDefaultEquipmentPricing(): array
    {
        return [
            'shockwave' => 50.00,
            'biofeedback' => 30.00,
            'ultrasound' => 25.00,
            'tens' => 15.00,
            'laser' => 40.00
        ];
    }
}

