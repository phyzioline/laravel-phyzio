<?php

namespace App\Services;

use App\Models\Product;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Log;

/**
 * Inventory Alert Service
 * 
 * Monitors product stock levels and sends notifications to vendors
 * when stock falls below configured thresholds.
 */
class InventoryAlertService
{
    /**
     * Default stock thresholds
     */
    protected $thresholds = [
        'critical' => 3,  // Urgent alert
        'warning' => 10,  // Warning alert
    ];

    /**
     * Check and alert for low stock products.
     * 
     * @param Product $product
     * @param int|null $previousStock Previous stock level (to avoid duplicate alerts)
     * @return void
     */
    public function checkAndAlert(Product $product, ?int $previousStock = null): void
    {
        $currentStock = $product->amount ?? 0;
        
        // Skip if product has no owner (vendor)
        if (!$product->user_id) {
            return;
        }

        // Get vendor
        $vendor = $product->user;
        if (!$vendor || !$vendor->email) {
            return;
        }

        // Check if stock crossed a threshold
        $shouldAlert = false;
        $threshold = null;
        $urgency = null;

        // Critical threshold (3 or less)
        if ($currentStock <= $this->thresholds['critical'] && $currentStock > 0) {
            // Only alert if previous stock was above threshold (to avoid duplicate alerts)
            if ($previousStock === null || $previousStock > $this->thresholds['critical']) {
                $shouldAlert = true;
                $threshold = $this->thresholds['critical'];
                $urgency = 'critical';
            }
        }
        // Warning threshold (10 or less, but above critical)
        elseif ($currentStock <= $this->thresholds['warning'] && $currentStock > $this->thresholds['critical']) {
            // Only alert if previous stock was above threshold
            if ($previousStock === null || $previousStock > $this->thresholds['warning']) {
                $shouldAlert = true;
                $threshold = $this->thresholds['warning'];
                $urgency = 'warning';
            }
        }

        if ($shouldAlert) {
            try {
                $vendor->notify(new LowStockNotification($product, $currentStock, $threshold));
                
                Log::info('Low stock alert sent', [
                    'product_id' => $product->id,
                    'vendor_id' => $vendor->id,
                    'current_stock' => $currentStock,
                    'threshold' => $threshold,
                    'urgency' => $urgency
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send low stock alert', [
                    'product_id' => $product->id,
                    'vendor_id' => $vendor->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Check all products for low stock and send alerts.
     * Should be called by a scheduled command (cron job).
     * 
     * @return int Number of alerts sent
     */
    public function checkAllProducts(): int
    {
        $alertCount = 0;

        // Get all active products with stock
        $products = Product::where('status', 'active')
            ->where('amount', '>', 0)
            ->where('amount', '<=', $this->thresholds['warning'])
            ->whereNotNull('user_id')
            ->with('user')
            ->get();

        foreach ($products as $product) {
            // Check if we should alert (avoid duplicate alerts within 24 hours)
            $lastAlert = \DB::table('notifications')
                ->where('notifiable_id', $product->user_id)
                ->where('notifiable_type', \App\Models\User::class)
                ->where('type', LowStockNotification::class)
                ->where('data->product_id', $product->id)
                ->where('created_at', '>', now()->subHours(24))
                ->exists();

            if (!$lastAlert) {
                $this->checkAndAlert($product);
                $alertCount++;
            }
        }

        return $alertCount;
    }

    /**
     * Get stock threshold configuration.
     * 
     * @return array
     */
    public function getThresholds(): array
    {
        return $this->thresholds;
    }

    /**
     * Set custom thresholds.
     * 
     * @param int $critical
     * @param int $warning
     * @return void
     */
    public function setThresholds(int $critical, int $warning): void
    {
        $this->thresholds = [
            'critical' => $critical,
            'warning' => $warning,
        ];
    }
}

