<?php

namespace App\Services;

use App\Models\Shipment;
use App\Models\Order;
use App\Models\ItemsOrder;
use App\Models\User;
use App\Models\TrackingLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ShippingManagementService
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Create shipment with full vendor and customer address details.
     */
    public function createShipment($orderId, $vendorId, $itemIds = [])
    {
        return DB::transaction(function () use ($orderId, $vendorId, $itemIds) {
            $order = Order::findOrFail($orderId);
            $vendor = User::findOrFail($vendorId);
            
            // Check if shipment already exists for this order/vendor combination
            $existingShipment = Shipment::where('order_id', $orderId)
                ->where('vendor_id', $vendorId)
                ->where('shipment_status', '!=', 'cancelled')
                ->first();
            
            if ($existingShipment) {
                // Check if all requested items are already in a shipment
                $requestedItemIds = !empty($itemIds) ? $itemIds : ItemsOrder::where('order_id', $orderId)
                    ->where('vendor_id', $vendorId)
                    ->pluck('id')
                    ->toArray();
                
                $itemsInShipment = ItemsOrder::where('shipment_id', $existingShipment->id)
                    ->whereIn('id', $requestedItemIds)
                    ->count();
                
                if ($itemsInShipment === count($requestedItemIds)) {
                    throw new \Exception('A shipment already exists for this order and vendor combination.');
                }
            }
            
            // Get vendor address (from vendor profile or default)
            $vendorAddress = $this->getVendorAddress($vendor);
            
            // Get customer address from order
            $customerAddress = [
                'name' => $order->name,
                'phone' => $order->phone,
                'address' => $order->address,
                'city' => $this->extractCity($order->address),
                'governorate' => $this->extractGovernorate($order->address),
            ];
            
            // Get items for this shipment
            $items = ItemsOrder::where('order_id', $orderId)
                ->where('vendor_id', $vendorId)
                ->when(!empty($itemIds), function($q) use ($itemIds) {
                    $q->whereIn('id', $itemIds);
                })
                ->whereNull('shipment_id')
                ->get();
            
            if ($items->isEmpty()) {
                throw new \Exception('No items available for shipment');
            }
            
            // Calculate package dimensions and weight
            $packageDetails = $this->calculatePackageDetails($items);
            
            // Create shipment
            $shipment = Shipment::create([
                'order_id' => $orderId,
                'vendor_id' => $vendorId,
                'vendor_name' => $vendor->name,
                'vendor_phone' => $vendor->phone,
                'vendor_address' => $vendorAddress['address'],
                'vendor_city' => $vendorAddress['city'],
                'vendor_governorate' => $vendorAddress['governorate'],
                'vendor_lat' => $vendorAddress['lat'],
                'vendor_lng' => $vendorAddress['lng'],
                'customer_name' => $customerAddress['name'],
                'customer_phone' => $customerAddress['phone'],
                'customer_address' => $customerAddress['address'],
                'customer_city' => $customerAddress['city'],
                'customer_governorate' => $customerAddress['governorate'],
                'shipment_status' => 'pending',
                'package_weight' => $packageDetails['weight'],
                'package_length' => $packageDetails['length'],
                'package_width' => $packageDetails['width'],
                'package_height' => $packageDetails['height'],
                'package_description' => $this->generatePackageDescription($items),
            ]);
            
            // Link items to shipment
            $items->each(function($item) use ($shipment) {
                $item->update(['shipment_id' => $shipment->id]);
            });
            
            // Create initial tracking log using ShippingService to prevent duplicates
            $shippingService = app(\App\Services\ShippingService::class);
            $shippingService->logTrackingUpdate(
                $shipment->id,
                'pending',
                'system',
                'Shipment created and ready for processing'
            );
            
            Log::info("Shipment #{$shipment->id} created for order #{$orderId}, vendor #{$vendorId}");
            
            return $shipment;
        });
    }

    /**
     * Create shipment via shipping provider API (Bosta, etc.).
     */
    public function createShipmentWithProvider($shipmentId, $provider = 'bosta', $options = [])
    {
        $shipment = Shipment::findOrFail($shipmentId);
        
        // Prepare shipment data for provider
        $shipmentData = [
            'pickup_address' => [
                'name' => $shipment->vendor_name,
                'phone' => $shipment->vendor_phone,
                'address' => $shipment->vendor_address,
                'city' => $shipment->vendor_city,
                'governorate' => $shipment->vendor_governorate,
                'lat' => $shipment->vendor_lat,
                'lng' => $shipment->vendor_lng,
            ],
            'delivery_address' => [
                'name' => $shipment->customer_name,
                'phone' => $shipment->customer_phone,
                'address' => $shipment->customer_address,
                'city' => $shipment->customer_city,
                'governorate' => $shipment->customer_governorate,
                'lat' => $shipment->customer_lat,
                'lng' => $shipment->customer_lng,
            ],
            'package' => [
                'weight' => $shipment->package_weight,
                'length' => $shipment->package_length,
                'width' => $shipment->package_width,
                'height' => $shipment->package_height,
                'description' => $shipment->package_description,
            ],
            'shipping_method' => $options['method'] ?? 'standard',
        ];
        
        // Call provider API
        $response = $this->callShippingProvider($provider, 'create', $shipmentData);
        
        if ($response['success']) {
            $shipment->update([
                'shipping_provider' => $provider,
                'shipping_provider_id' => $response['data']['id'],
                'tracking_number' => $response['data']['tracking_number'],
                'shipping_cost' => $response['data']['cost'],
                'shipping_method' => $response['data']['method'],
                'shipping_label_url' => $response['data']['label_url'] ?? null,
                'shipment_status' => 'ready_to_ship',
                'ready_to_ship_at' => now(),
            ]);
            
            // Use ShippingService to log tracking update (prevents duplicates)
            $shippingService = app(\App\Services\ShippingService::class);
            $shippingService->logTrackingUpdate(
                $shipment->id,
                'ready_to_ship',
                'api',
                "Shipment created with {$provider}. Tracking: {$response['data']['tracking_number']}"
            );
            
            return $shipment;
        }
        
        throw new \Exception('Failed to create shipment with provider: ' . ($response['error'] ?? 'Unknown error'));
    }

    /**
     * Update shipment status from provider webhook.
     */
    public function updateStatusFromProvider($providerId, $status, $data = [])
    {
        $shipment = Shipment::where('shipping_provider_id', $providerId)
            ->where('shipping_provider', $data['provider'] ?? 'bosta')
            ->firstOrFail();
        
        $oldStatus = $shipment->shipment_status;
        $statusMap = [
            'ready_to_pickup' => 'ready_to_ship',
            'picked_up' => 'picked_up',
            'in_transit' => 'in_transit',
            'out_for_delivery' => 'out_for_delivery',
            'delivered' => 'delivered',
            'exception' => 'exception',
            'returned' => 'returned',
        ];
        
        $newStatus = $statusMap[$status] ?? $status;
        
        $updateData = ['shipment_status' => $newStatus];
        
        // Update timestamps based on status
        switch ($newStatus) {
            case 'picked_up':
                $updateData['picked_up_at'] = now();
                break;
            case 'in_transit':
                $updateData['in_transit_at'] = now();
                break;
            case 'out_for_delivery':
                $updateData['out_for_delivery_at'] = now();
                break;
            case 'delivered':
                $updateData['delivered_at'] = now();
                $updateData['delivered_to'] = $data['delivered_to'] ?? null;
                $updateData['delivery_notes'] = $data['notes'] ?? null;
                // Trigger settlement
                $this->walletService->processSettlements();
                break;
            case 'exception':
                $updateData['exception_at'] = now();
                $updateData['exception_reason'] = $data['reason'] ?? null;
                break;
        }
        
        $shipment->update($updateData);
        
        // Use ShippingService to log tracking update (prevents duplicates)
        $shippingService = app(\App\Services\ShippingService::class);
        $shippingService->logTrackingUpdate(
            $shipment->id,
            $newStatus,
            'api',
            $data['description'] ?? "Status updated from {$oldStatus} to {$newStatus}",
            $data['location'] ?? null
        );
        
        return $shipment;
    }

    /**
     * Get vendor address details.
     */
    protected function getVendorAddress($vendor)
    {
        // Try to get from vendor profile or settings
        // For now, return default structure
        return [
            'address' => 'Vendor Warehouse Address', // Should be from vendor profile
            'city' => 'Cairo', // Default
            'governorate' => 'Cairo', // Default
            'lat' => null,
            'lng' => null,
        ];
    }

    /**
     * Extract city from address string.
     */
    protected function extractCity($address)
    {
        // Simple extraction - can be enhanced
        $cities = ['Cairo', 'Alexandria', 'Giza', 'Luxor', 'Aswan'];
        foreach ($cities as $city) {
            if (stripos($address, $city) !== false) {
                return $city;
            }
        }
        return 'Cairo'; // Default
    }

    /**
     * Extract governorate from address string.
     */
    protected function extractGovernorate($address)
    {
        // Simple extraction - can be enhanced
        $governorates = ['Cairo', 'Alexandria', 'Giza', 'Luxor', 'Aswan', 'Qalyubia', 'Dakahlia'];
        foreach ($governorates as $gov) {
            if (stripos($address, $gov) !== false) {
                return $gov;
            }
        }
        return 'Cairo'; // Default
    }

    /**
     * Calculate package dimensions and weight from items.
     */
    protected function calculatePackageDetails($items)
    {
        $totalWeight = 0;
        $maxLength = 0;
        $maxWidth = 0;
        $totalHeight = 0;
        
        foreach ($items as $item) {
            // Default dimensions per item (can be enhanced with product dimensions)
            $itemWeight = 500; // grams per item (default)
            $itemLength = 20; // cm
            $itemWidth = 15; // cm
            $itemHeight = 10; // cm
            
            $totalWeight += $itemWeight * $item->quantity;
            $maxLength = max($maxLength, $itemLength);
            $maxWidth = max($maxWidth, $itemWidth);
            $totalHeight += $itemHeight * $item->quantity;
        }
        
        return [
            'weight' => $totalWeight,
            'length' => $maxLength,
            'width' => $maxWidth,
            'height' => min($totalHeight, 100), // Max 100cm height
        ];
    }

    /**
     * Generate package description from items.
     */
    protected function generatePackageDescription($items)
    {
        $descriptions = [];
        foreach ($items as $item) {
            if ($item->product) {
                $descriptions[] = "{$item->quantity}x {$item->product->product_name_en}";
            }
        }
        return implode(', ', $descriptions);
    }

    /**
     * Call shipping provider API.
     */
    protected function callShippingProvider($provider, $action, $data)
    {
        // Placeholder for provider API integration
        // For Bosta: https://api.bosta.co/api/v2/
        // For now, return mock response
        
        $apiKey = config("shipping.providers.{$provider}.api_key");
        $baseUrl = config("shipping.providers.{$provider}.base_url");
        
        if (!$apiKey || !$baseUrl) {
            // Return mock response for development
            return [
                'success' => true,
                'data' => [
                    'id' => 'BOSTA-' . time(),
                    'tracking_number' => 'BOSTA' . rand(100000, 999999),
                    'cost' => 50.00,
                    'method' => $data['shipping_method'] ?? 'standard',
                    'label_url' => null,
                ],
            ];
        }
        
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->post("{$baseUrl}/shipments", $data);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }
            
            return [
                'success' => false,
                'error' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error("Shipping provider API error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get shipping cost estimate.
     */
    public function estimateShippingCost($fromCity, $toCity, $weight, $method = 'standard')
    {
        // Simple cost calculation based on distance and weight
        $baseCost = 30; // Base cost
        $weightCost = ($weight / 1000) * 5; // 5 EGP per kg
        $distanceMultiplier = $this->getDistanceMultiplier($fromCity, $toCity);
        
        $cost = ($baseCost + $weightCost) * $distanceMultiplier;
        
        // Method multiplier
        if ($method === 'express') {
            $cost *= 1.5;
        } elseif ($method === 'economy') {
            $cost *= 0.8;
        }
        
        return round($cost, 2);
    }

    /**
     * Get distance multiplier between cities.
     */
    protected function getDistanceMultiplier($fromCity, $toCity)
    {
        if ($fromCity === $toCity) {
            return 1.0;
        }
        
        // Same governorate
        if ($this->isSameGovernorate($fromCity, $toCity)) {
            return 1.2;
        }
        
        // Different governorates
        return 1.5;
    }

    /**
     * Check if cities are in same governorate.
     */
    protected function isSameGovernorate($city1, $city2)
    {
        // Simplified - can be enhanced with actual governorate mapping
        return false;
    }

    /**
     * Bulk create shipments for multiple orders.
     */
    public function bulkCreateShipments($orderIds, $vendorId = null)
    {
        $created = [];
        $failed = [];
        
        foreach ($orderIds as $orderId) {
            try {
                $order = Order::findOrFail($orderId);
                $vendors = $vendorId 
                    ? [$vendorId] 
                    : $order->items()->distinct('vendor_id')->pluck('vendor_id');
                
                foreach ($vendors as $vid) {
                    $shipment = $this->createShipment($orderId, $vid);
                    $created[] = $shipment->id;
                }
            } catch (\Exception $e) {
                $failed[$orderId] = $e->getMessage();
            }
        }
        
        return [
            'created' => $created,
            'failed' => $failed,
            'total_created' => count($created),
            'total_failed' => count($failed),
        ];
    }

    /**
     * Get shipping statistics for dashboard.
     */
    public function getShippingStatistics($vendorId = null)
    {
        $baseQuery = Shipment::query();
        
        if ($vendorId) {
            $baseQuery->where('vendor_id', $vendorId);
        }
        
        return [
            'pending' => (clone $baseQuery)->where('shipment_status', 'pending')->count(),
            'ready_to_ship' => (clone $baseQuery)->where('shipment_status', 'ready_to_ship')->count(),
            'in_transit' => (clone $baseQuery)->whereIn('shipment_status', ['in_transit', 'out_for_delivery'])->count(),
            'delivered_today' => (clone $baseQuery)->where('shipment_status', 'delivered')
                ->whereDate('delivered_at', Carbon::today())->count(),
            'delivered_this_month' => (clone $baseQuery)->where('shipment_status', 'delivered')
                ->whereMonth('delivered_at', Carbon::now()->month)->count(),
            'total_revenue' => (clone $baseQuery)->where('shipment_status', 'delivered')
                ->sum('shipping_cost') ?? 0,
        ];
    }
}

