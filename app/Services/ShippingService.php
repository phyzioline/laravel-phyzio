<?php

namespace App\Services;

use App\Models\Shipment;
use App\Models\TrackingLog;
use App\Models\VendorPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShippingService
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Update shipment with tracking information.
     * Called when vendor adds tracking number.
     */
    public function updateTracking($shipmentId, $courier, $trackingNumber, $notes = null)
    {
        return DB::transaction(function () use ($shipmentId, $courier, $trackingNumber, $notes) {
            $shipment = Shipment::findOrFail($shipmentId);
            
            $shipment->update([
                'courier' => $courier,
                'tracking_number' => $trackingNumber,
                'shipment_status' => 'shipped',
                'shipped_at' => Carbon::now(),
                'notes' => $notes,
            ]);
            
            // Create tracking log
            $this->logTrackingUpdate($shipmentId, 'shipped', 'manual', "Shipment marked as shipped by vendor. Courier: {$courier}");
            
            Log::info("Tracking updated for shipment #{$shipmentId}: {$courier} - {$trackingNumber}");
            
            return $shipment;
        });
    }

    /**
     * Log tracking status update.
     */
    public function logTrackingUpdate($shipmentId, $status, $source = 'manual', $description = null, $location = null)
    {
        return TrackingLog::create([
            'shipment_id' => $shipmentId,
            'status' => $status,
            'location' => $location,
            'description' => $description,
            'source' => $source,
        ]);
    }

    /**
     * Update shipment status.
     */
    public function updateShipmentStatus($shipmentId, $status, $source = 'manual', $description = null)
    {
        return DB::transaction(function () use ($shipmentId, $status, $source, $description) {
            $shipment = Shipment::findOrFail($shipmentId);
            
            $oldStatus = $shipment->shipment_status;
            $shipment->update(['shipment_status' => $status]);
            
            // Log the change
            $this->logTrackingUpdate($shipmentId, $status, $source, $description ?? "Status changed from {$oldStatus} to {$status}");
            
            // If delivered, trigger settlement
            if ($status === 'delivered') {
                $this->markAsDelivered($shipmentId);
            }
            
            return $shipment;
        });
    }

    /**
     * Mark shipment as delivered and start settlement process.
     */
    public function markAsDelivered($shipmentId)
    {
        return DB::transaction(function () use ($shipmentId) {
            $shipment = Shipment::findOrFail($shipmentId);
            
            $shipment->update([
                'shipment_status' => 'delivered',
                'delivered_at' => Carbon::now(),
            ]);
            
            // Log delivery
            $this->logTrackingUpdate($shipmentId, 'delivered', 'system', 'Package delivered successfully');
            
            // Trigger settlement process for vendor payments
            $items = $shipment->items;
            foreach ($items as $item) {
                if ($item->vendorPayment) {
                    $this->walletService->addToPending(
                        $item->vendor_id,
                        $item->vendor_earnings,
                        $item->order_id,
                        $item->id
                    );
                }
            }
            
            Log::info("Shipment #{$shipmentId} marked as delivered. Settlement process initiated.");
            
            return $shipment;
        });
    }

    /**
     * Get shipments for a vendor.
     */
    public function getVendorShipments($vendorId, $status = null)
    {
        $query = Shipment::where('vendor_id', $vendorId)
            ->with(['order', 'items.product', 'trackingLogs']);
        
        if ($status) {
            $query->where('shipment_status', $status);
        }
        
        return $query->latest()->get();
    }

    /**
     * Get shipment details with full tracking history.
     */
    public function getShipmentDetails($shipmentId)
    {
        return Shipment::with([
            'order',
            'vendor',
            'items.product',
            'trackingLogs' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }
        ])->findOrFail($shipmentId);
    }

    /**
     * Get tracking history for customer.
     */
    public function getTrackingForCustomer($orderId)
    {
        return Shipment::where('order_id', $orderId)
            ->with(['vendor:id,name', 'items.product:id,product_name_en', 'trackingLogs'])
            ->get();
    }

    /**
     * Fetch tracking updates from courier API (placeholder for future integration).
     */
    public function fetchCourierTracking($shipmentId)
    {
        $shipment = Shipment::findOrFail($shipmentId);
        
        if (!$shipment->hasTracking()) {
            return false;
        }
        
        // TODO: Implement courier API integration
        // For now, return false to indicate manual tracking only
        
        Log::info("Courier API integration not yet implemented for shipment #{$shipmentId}");
        
        return false;
    }

    /**
     * Get pending shipments (need vendor action).
     */
    public function getPendingShipments($vendorId)
    {
        return Shipment::where('vendor_id', $vendorId)
            ->where('shipment_status', 'pending')
            ->with(['order', 'items.product'])
            ->latest()
            ->get();
    }

    /**
     * Get overdue shipments (for admin monitoring).
     */
    public function getOverdueShipments($daysOverdue = 3)
    {
        $overdueDate = Carbon::now()->subDays($daysOverdue);
        
        return Shipment::where('shipment_status', 'pending')
            ->orWhere(function ($q) use ($overdueDate) {
                $q->where('shipment_status', 'shipped')
                  ->where('shipped_at', '<', $overdueDate);
            })
            ->with(['order', 'vendor', 'items.product'])
            ->get();
    }
}
