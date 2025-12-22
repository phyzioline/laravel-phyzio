<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ItemsOrder;
use App\Models\Shipment;
use App\Models\Product;
use App\Models\VendorWallet;
use App\Models\VendorPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    /**
     * Create order and split by vendors (Amazon-style).
     * 
     * @param int $customerId
     * @param array $cartItems
     * @param array $orderData (name, address, phone, payment info)
     * @return Order
     */
    public function createOrder($customerId, $cartItems, $orderData)
    {
        return DB::transaction(function () use ($customerId, $cartItems, $orderData) {
            
            // 1️⃣ Calculate total
            $total = 0;
            $itemsData = [];
            
            foreach ($cartItems as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'] ?? 1;
                $itemTotal = $product->product_price * $quantity;
                $total += $itemTotal;
                
                $itemsData[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $product->product_price,
                    'total' => $itemTotal,
                ];
            }

            // 2️⃣ Create Order
            $order = Order::create([
                'user_id' => $customerId,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => $orderData['payment_status'] ?? 'pending',
                'name' => $orderData['name'] ?? null,
                'address' => $orderData['address'] ?? null,
                'phone' => $orderData['phone'] ?? null,
                'payment_method' => $orderData['payment_method'] ?? null,
                'payment_id' => $orderData['payment_id'] ?? null,
                'payment_type' => $orderData['payment_type'] ?? null,
            ]);

            // 3️⃣ Create Order Items & Group by Vendor
            $vendorGroups = [];
            
            foreach ($itemsData as $itemData) {
                $product = $itemData['product'];
                $vendorId = $product->user_id; // Product's vendor
                
                $orderItem = ItemsOrder::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'vendor_id' => $vendorId,
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'total' => $itemData['total'],
                    // Commission fields auto-calculated in ItemsOrder boot method
                ]);
                
                // Group items by vendor
                if (!isset($vendorGroups[$vendorId])) {
                    $vendorGroups[$vendorId] = [];
                }
                $vendorGroups[$vendorId][] = $orderItem;
            }

            // 4️⃣ Create Shipment per Vendor (AMAZON MAGIC HERE!)
            foreach ($vendorGroups as $vendorId => $items) {
                $shipment = Shipment::create([
                    'order_id' => $order->id,
                    'vendor_id' => $vendorId,
                    'shipment_status' => 'pending',
                ]);
                
                // Link items to shipment
                foreach ($items as $item) {
                    $item->update(['shipment_id' => $shipment->id]);
                }
            }

            // 5️⃣ Update Order Totals
            $order->update([
                'commission_total' => $order->getTotalCommission(),
                'shipping_total' => $order->getTotalShipping(),
            ]);

            // 6️⃣ Initialize Vendor Wallets (if they don't exist)
            foreach (array_keys($vendorGroups) as $vendorId) {
                VendorWallet::firstOrCreate(
                    ['vendor_id' => $vendorId],
                    [
                        'pending_balance' => 0,
                        'available_balance' => 0,
                        'on_hold_balance' => 0,
                        'total_earned' => 0,
                    ]
                );
            }

            Log::info("Order #{$order->id} created and split into " . count($vendorGroups) . " shipments");

            return $order;
        });
    }

    /**
     * Get vendor's orders (only their items).
     */
    public function getVendorOrders($vendorId, $status = null)
    {
        $query = Order::whereHas('items', function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })->with(['items' => function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId)->with('product');
        }, 'shipments' => function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        }]);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->get();
    }

    /**
     * Get order details for vendor (filtered to show only their items).
     */
    public function getVendorOrderDetails($orderId, $vendorId)
    {
        return Order::with(['items' => function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId)->with('product');
        }, 'shipments' => function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        }])->findOrFail($orderId);
    }
}
