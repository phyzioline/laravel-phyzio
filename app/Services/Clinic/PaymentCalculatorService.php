<?php

namespace App\Services\Clinic;

use App\Models\PricingConfig;
use App\Models\Clinic;
use App\Models\ClinicAppointment;
use Illuminate\Support\Facades\Log;

class PaymentCalculatorService
{
    /**
     * Calculate session price based on multiple factors
     * 
     * Formula:
     * Total = Base Price + Specialty Adjustment + Therapist Level + Equipment + Location + Duration - Discount
     * 
     * @param Clinic $clinic
     * @param array $params
     * @return array
     */
    public function calculateSessionPrice(Clinic $clinic, array $params): array
    {
        try {
            // Get pricing config for specialty
            $specialty = $params['specialty'] ?? $clinic->primary_specialty;
            $pricingConfig = $this->getPricingConfig($clinic, $specialty);

            // Start with base price based on visit type
            $visitType = $params['visit_type'] ?? 'followup';
            $basePrice = $pricingConfig->getPriceForVisitType($visitType);

            // Apply specialty adjustment
            $specialtyAdjustment = $pricingConfig->specialty_adjustment;
            $price = $basePrice * $specialtyAdjustment;

            // Apply therapist level multiplier
            if (isset($params['therapist_level'])) {
                $therapistMultiplier = $pricingConfig->getTherapistMultiplier($params['therapist_level']);
                $price = $price * $therapistMultiplier;
            }

            // Add equipment usage fees
            $equipmentTotal = 0;
            if (isset($params['equipment']) && is_array($params['equipment'])) {
                foreach ($params['equipment'] as $equipment) {
                    $equipmentTotal += $pricingConfig->getEquipmentPrice($equipment);
                }
            }
            $price += $equipmentTotal;

            // Apply location factor
            $location = $params['location'] ?? 'clinic';
            $locationFactor = $pricingConfig->getLocationFactor($location);
            $price = $price * $locationFactor;

            // Apply duration factor
            $durationMinutes = $params['duration_minutes'] ?? 60;
            $durationFactor = $pricingConfig->getDurationFactor($durationMinutes);
            $price = $price * $durationFactor;

            // Calculate subtotal before discount
            $subtotal = $price;

            // Apply discount if applicable
            $discountAmount = 0;
            $discountPercentage = 0;
            if (isset($params['discount_type'])) {
                $discountPercentage = $pricingConfig->getDiscountPercentage($params['discount_type']);
                $discountAmount = ($subtotal * $discountPercentage) / 100;
                $price = $subtotal - $discountAmount;
            }

            // Final price
            $finalPrice = round($price, 2);

            return [
                'base_price' => round($basePrice, 2),
                'specialty_adjustment' => $specialtyAdjustment,
                'therapist_multiplier' => $therapistMultiplier ?? 1.0,
                'equipment_total' => round($equipmentTotal, 2),
                'location_factor' => $locationFactor,
                'duration_factor' => $durationFactor,
                'subtotal' => round($subtotal, 2),
                'discount_percentage' => $discountPercentage,
                'discount_amount' => round($discountAmount, 2),
                'final_price' => $finalPrice,
                'breakdown' => [
                    'Base Price' => round($basePrice, 2),
                    'Specialty Adjustment' => ($specialtyAdjustment - 1) * 100 . '%',
                    'Therapist Level' => ($therapistMultiplier ?? 1.0) > 1 ? '+' . (($therapistMultiplier ?? 1.0) - 1) * 100 . '%' : 'Standard',
                    'Equipment' => $equipmentTotal > 0 ? '+' . round($equipmentTotal, 2) : 'None',
                    'Location' => $locationFactor > 1 ? '+' . (($locationFactor - 1) * 100) . '%' : 'Standard',
                    'Duration' => $durationFactor > 1 ? '+' . (($durationFactor - 1) * 100) . '%' : 'Standard',
                    'Subtotal' => round($subtotal, 2),
                    'Discount' => $discountPercentage > 0 ? '-' . $discountPercentage . '%' : 'None',
                    'Total' => $finalPrice
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Payment calculation failed', [
                'clinic_id' => $clinic->id,
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Calculate program price with discount
     * 
     * @param Clinic $clinic
     * @param array $params
     * @return array
     */
    public function calculateProgramPrice(Clinic $clinic, array $params): array
    {
        $specialty = $params['specialty'] ?? $clinic->primary_specialty;
        $sessionsPerWeek = $params['sessions_per_week'] ?? 2;
        $totalWeeks = $params['total_weeks'] ?? 4;
        $totalSessions = $sessionsPerWeek * $totalWeeks;

        // Calculate single session price
        $sessionParams = [
            'specialty' => $specialty,
            'visit_type' => 'followup',
            'location' => $params['location'] ?? 'clinic',
            'duration_minutes' => $params['duration_minutes'] ?? 60,
            'therapist_level' => $params['therapist_level'] ?? 'senior'
        ];

        $sessionPrice = $this->calculateSessionPrice($clinic, $sessionParams);
        $singleSessionPrice = $sessionPrice['final_price'];

        // Calculate total without discount
        $totalWithoutDiscount = $singleSessionPrice * $totalSessions;

        // Apply program discount
        $pricingConfig = $this->getPricingConfig($clinic, $specialty);
        $discountPercentage = $pricingConfig->getDiscountPercentage('weekly_program');
        $discountAmount = ($totalWithoutDiscount * $discountPercentage) / 100;
        $totalWithDiscount = $totalWithoutDiscount - $discountAmount;

        // Calculate payment plan prices
        $weeklyPrice = $totalWithDiscount / $totalWeeks;
        $monthlyPrice = ($totalWithDiscount / $totalWeeks) * 4; // Approximate month = 4 weeks

        return [
            'single_session_price' => $singleSessionPrice,
            'total_sessions' => $totalSessions,
            'total_without_discount' => round($totalWithoutDiscount, 2),
            'discount_percentage' => $discountPercentage,
            'discount_amount' => round($discountAmount, 2),
            'total_with_discount' => round($totalWithDiscount, 2),
            'weekly_price' => round($weeklyPrice, 2),
            'monthly_price' => round($monthlyPrice, 2),
            'upfront_price' => round($totalWithDiscount, 2)
        ];
    }

    /**
     * Get or create pricing config for clinic and specialty
     * 
     * @param Clinic $clinic
     * @param string $specialty
     * @return PricingConfig
     */
    protected function getPricingConfig(Clinic $clinic, string $specialty): PricingConfig
    {
        $config = PricingConfig::where('clinic_id', $clinic->id)
            ->where('specialty', $specialty)
            ->where('is_active', true)
            ->first();

        if (!$config) {
            // Create default config
            $config = PricingConfig::createDefault($clinic, $specialty);
        }

        return $config;
    }

    /**
     * Calculate price for appointment
     * 
     * @param ClinicAppointment $appointment
     * @return array
     */
    public function calculateAppointmentPrice(ClinicAppointment $appointment): array
    {
        $clinic = $appointment->clinic;
        $specialty = $appointment->specialty ?? $clinic->primary_specialty;

        // Get therapist level if available
        $therapistLevel = 'senior'; // Default, should be fetched from therapist profile
        if ($appointment->doctor) {
            // TODO: Get therapist level from therapist profile
            // $therapistLevel = $appointment->doctor->therapistProfile->level ?? 'senior';
        }

        // Get equipment from additional data
        $equipment = [];
        if ($appointment->additionalData) {
            $data = $appointment->additionalData->getAllData();
            $equipment = $data['equipment'] ?? [];
        }

        $params = [
            'specialty' => $specialty,
            'visit_type' => $appointment->visit_type ?? 'followup',
            'location' => $appointment->location ?? 'clinic',
            'duration_minutes' => $appointment->duration_minutes ?? 60,
            'therapist_level' => $therapistLevel,
            'equipment' => $equipment
        ];

        return $this->calculateSessionPrice($clinic, $params);
    }
}

