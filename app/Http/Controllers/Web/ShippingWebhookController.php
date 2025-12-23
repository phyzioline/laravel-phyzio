<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ShippingManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingWebhookController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingManagementService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    /**
     * Handle webhook from shipping providers (Bosta, Aramex, etc.).
     */
    public function handle(Request $request, $provider)
    {
        // Verify webhook signature (implement based on provider)
        // if (!$this->verifyWebhookSignature($request, $provider)) {
        //     abort(401, 'Invalid webhook signature');
        // }

        try {
            $data = $request->all();
            
            // Extract provider-specific data
            $providerId = $this->extractProviderId($data, $provider);
            $status = $this->extractStatus($data, $provider);
            $location = $this->extractLocation($data, $provider);
            $description = $this->extractDescription($data, $provider);
            
            // Update shipment status
            $this->shippingService->updateStatusFromProvider(
                $providerId,
                $status,
                [
                    'provider' => $provider,
                    'location' => $location,
                    'description' => $description,
                    'delivered_to' => $data['delivered_to'] ?? null,
                    'reason' => $data['exception_reason'] ?? null,
                ]
            );
            
            Log::info("Webhook received from {$provider} for shipment {$providerId}: {$status}");
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            Log::error("Webhook error from {$provider}: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Extract provider ID from webhook data.
     */
    protected function extractProviderId($data, $provider)
    {
        return match($provider) {
            'bosta' => $data['id'] ?? $data['shipment_id'] ?? null,
            'aramex' => $data['ShipmentNumber'] ?? $data['id'] ?? null,
            'dhl' => $data['shipmentId'] ?? $data['id'] ?? null,
            default => $data['id'] ?? null,
        };
    }

    /**
     * Extract status from webhook data.
     */
    protected function extractStatus($data, $provider)
    {
        $status = match($provider) {
            'bosta' => $data['state'] ?? $data['status'] ?? null,
            'aramex' => $data['Status'] ?? $data['status'] ?? null,
            'dhl' => $data['status'] ?? null,
            default => $data['status'] ?? null,
        };
        
        // Normalize status names
        return strtolower(str_replace(' ', '_', $status));
    }

    /**
     * Extract location from webhook data.
     */
    protected function extractLocation($data, $provider)
    {
        return match($provider) {
            'bosta' => $data['location'] ?? $data['current_location'] ?? null,
            'aramex' => $data['Location'] ?? $data['location'] ?? null,
            'dhl' => $data['location'] ?? null,
            default => $data['location'] ?? null,
        };
    }

    /**
     * Extract description from webhook data.
     */
    protected function extractDescription($data, $provider)
    {
        return match($provider) {
            'bosta' => $data['notes'] ?? $data['description'] ?? null,
            'aramex' => $data['Description'] ?? $data['description'] ?? null,
            'dhl' => $data['description'] ?? null,
            default => $data['description'] ?? $data['notes'] ?? null,
        };
    }
}

