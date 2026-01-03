<?php

namespace App\Http\Controllers\Clinic;

use Illuminate\Http\Request;
use App\Models\PricingConfig;
use Illuminate\Support\Facades\DB;

class ServicePricingController extends BaseClinicController
{
    /**
     * Show service pricing management page
     */
    public function index()
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }
        
        // Get pricing configs for all specialties
        $pricingConfigs = PricingConfig::where('clinic_id', $clinic->id)
            ->where('is_active', true)
            ->get()
            ->keyBy('specialty');
        
        // Get default services if none exist
        $defaultServices = [
            'ultrasound' => ['name' => 'Ultrasound', 'price' => 25.00],
            'laser' => ['name' => 'Laser Therapy', 'price' => 40.00],
            'shockwave' => ['name' => 'Shockwave Therapy', 'price' => 50.00],
            'tens' => ['name' => 'TENS', 'price' => 15.00],
            'biofeedback' => ['name' => 'Biofeedback', 'price' => 30.00],
            'hot_pack' => ['name' => 'Hot Pack', 'price' => 10.00],
            'cold_pack' => ['name' => 'Cold Pack', 'price' => 10.00],
            'electrical_stimulation' => ['name' => 'Electrical Stimulation', 'price' => 20.00],
        ];
        
        return view('web.clinic.service-pricing.index', compact('clinic', 'pricingConfigs', 'defaultServices'));
    }

    /**
     * Update service pricing for a specialty
     */
    public function update(Request $request, $specialty)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return response()->json([
                'success' => false,
                'message' => 'Clinic not found.'
            ], 404);
        }
        
        $request->validate([
            'services' => 'required|array',
            'services.*.name' => 'required|string|max:255',
            'services.*.price' => 'required|numeric|min:0',
        ]);
        
        // Get or create pricing config
        $pricingConfig = PricingConfig::where('clinic_id', $clinic->id)
            ->where('specialty', $specialty)
            ->where('is_active', true)
            ->first();
        
        if (!$pricingConfig) {
            $pricingConfig = PricingConfig::createDefault($clinic, $specialty);
        }
        
        // Build equipment pricing array
        $equipmentPricing = [];
        foreach ($request->services as $key => $service) {
            if (!empty($service['name']) && isset($service['price'])) {
                $equipmentPricing[$key] = (float) $service['price'];
            }
        }
        
        // Update equipment pricing
        $pricingConfig->equipment_pricing = $equipmentPricing;
        $pricingConfig->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Service pricing updated successfully.',
            'pricing' => $equipmentPricing
        ]);
    }

    /**
     * Add new service
     */
    public function addService(Request $request, $specialty)
    {
        $clinic = $this->getUserClinic();
        
        $request->validate([
            'service_key' => 'required|string|max:100',
            'service_name' => 'required|string|max:255',
            'service_price' => 'required|numeric|min:0',
        ]);
        
        // Get or create pricing config
        $pricingConfig = PricingConfig::where('clinic_id', $clinic->id)
            ->where('specialty', $specialty)
            ->where('is_active', true)
            ->first();
        
        if (!$pricingConfig) {
            $pricingConfig = PricingConfig::createDefault($clinic, $specialty);
        }
        
        $equipmentPricing = $pricingConfig->equipment_pricing ?? [];
        $equipmentPricing[$request->service_key] = (float) $request->service_price;
        
        $pricingConfig->equipment_pricing = $equipmentPricing;
        $pricingConfig->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Service added successfully.',
            'service' => [
                'key' => $request->service_key,
                'name' => $request->service_name,
                'price' => $request->service_price
            ]
        ]);
    }

    /**
     * Remove service
     */
    public function removeService(Request $request, $specialty)
    {
        $clinic = $this->getUserClinic();
        
        $request->validate([
            'service_key' => 'required|string',
        ]);
        
        $pricingConfig = PricingConfig::where('clinic_id', $clinic->id)
            ->where('specialty', $specialty)
            ->where('is_active', true)
            ->first();
        
        if ($pricingConfig && $pricingConfig->equipment_pricing) {
            $equipmentPricing = $pricingConfig->equipment_pricing;
            unset($equipmentPricing[$request->service_key]);
            $pricingConfig->equipment_pricing = $equipmentPricing;
            $pricingConfig->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Service removed successfully.'
        ]);
    }
}

