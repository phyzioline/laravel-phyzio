<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Stock Reservation Service
 * 
 * Prevents race conditions by reserving stock when order is created
 * and confirming/releasing reservation based on payment status.
 */
class StockReservationService
{
    /**
     * Reserve stock for order items.
     * 
     * @param Order $order
     * @return bool Success status
     */
    public function reserveStockForOrder(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                
                if (!$product) {
                    Log::error('Stock reservation failed: Product not found', [
                        'product_id' => $item->product_id,
                        'order_id' => $order->id
                    ]);
                    return false;
                }

                $requestedQuantity = $item->quantity;
                $availableStock = $product->amount - $product->amount_reserved;

                // Check if enough stock available
                if ($availableStock < $requestedQuantity) {
                    Log::warning('Stock reservation failed: Insufficient stock', [
                        'product_id' => $product->id,
                        'requested' => $requestedQuantity,
                        'available' => $availableStock,
                        'total_stock' => $product->amount,
                        'reserved' => $product->amount_reserved,
                        'order_id' => $order->id
                    ]);
                    return false;
                }

                // Reserve the stock
                $product->increment('amount_reserved', $requestedQuantity);

                Log::info('Stock reserved successfully', [
                    'product_id' => $product->id,
                    'quantity' => $requestedQuantity,
                    'order_id' => $order->id,
                    'new_reserved_amount' => $product->fresh()->amount_reserved
                ]);
            }

            return true;
        });
    }

    /**
     * Confirm reservation and decrement actual stock.
     * Called when payment is confirmed.
     * 
     * @param Order $order
     * @return bool Success status
     */
    public function confirmReservation(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                
                if (!$product) {
                    Log::error('Stock confirmation failed: Product not found', [
                        'product_id' => $item->product_id,
                        'order_id' => $order->id
                    ]);
                    continue;
                }

                $quantity = $item->quantity;

                // Decrement actual stock
                $product->decrement('amount', $quantity);
                
                // Release the reservation
                $product->decrement('amount_reserved', $quantity);

                Log::info('Stock reservation confirmed', [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'order_id' => $order->id,
                    'remaining_stock' => $product->fresh()->amount,
                    'reserved' => $product->fresh()->amount_reserved
                ]);
            }

            return true;
        });
    }

    /**
     * Release stock reservation without decrementing stock.
     * Called when payment fails or order is cancelled.
     * 
     * @param Order $order
     * @return bool Success status
     */
    public function releaseReservation(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                
                if (!$product) {
                    Log::error('Stock release failed: Product not found', [
                        'product_id' => $item->product_id,
                        'order_id' => $order->id
                    ]);
                    continue;
                }

                $quantity = $item->quantity;

                // Release the reservation (don't touch actual stock)
                $product->decrement('amount_reserved', $quantity);

                Log::info('Stock reservation released', [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'order_id' => $order->id,
                    'total_stock' => $product->fresh()->amount,
                    'reserved' => $product->fresh()->amount_reserved
                ]);
            }

            return true;
        });
    }

    /**
     * Release expired reservations.
     * Should be called by cron job every few minutes.
     * 
     * @param int $timeoutMinutes Minutes after which to consider reservation expired
     * @return int Number of orders processed
     */
    public function releaseExpiredReservations(int $timeoutMinutes = 15): int
    {
        $cutoffTime = Carbon::now()->subMinutes($timeoutMinutes);
        
        // Find orders that are still pending payment and older than timeout
        $expiredOrders = Order::where('status', 'pending_payment')
            ->where('payment_status', 'pending')
            ->where('created_at', '<', $cutoffTime)
            ->with('items')
            ->get();

        $count = 0;
        foreach ($expiredOrders as $order) {
            if ($this->releaseReservation($order)) {
                // Update order status to cancelled
                $order->update([
                    'status' => 'cancelled',
                    'payment_status' => 'failed'
                ]);
                
                $count++;
                
                Log::info('Expired reservation auto-released', [
                    'order_id' => $order->id,
                    'created_at' => $order->created_at,
                    'timeout_minutes' => $timeoutMinutes
                ]);
            }
        }

        return $count;
    }

    /**
     * Get available stock for a product (excluding reserved).
     * 
     * @param int|Product $product
     * @return int Available quantity
     */
    public function getAvailableStock($product): int
    {
        if (is_numeric($product)) {
            $product = Product::find($product);
        }

        if (!$product) {
            return 0;
        }

        return max(0, $product->amount - ($product->amount_reserved ?? 0));
    }

    /**
     * Check if product has enough available stock.
     * 
     * @param int|Product $product
     * @param int $requestedQuantity
     * @return bool
     */
    public function hasAvailableStock($product, int $requestedQuantity): bool
    {
        return $this->getAvailableStock($product) >= $requestedQuantity;
    }
}
