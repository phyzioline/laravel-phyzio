<?php

namespace App\Services\Web;

use App\Models\Order;
use App\Services\ShippingService;

class HistoryOrderService
{
    public function __construct(
        public Order $order,
        public ShippingService $shippingService
    ){}

    public function index()
    {
        $orders = $this->order->where('user_id', auth()->id())
            ->with([
                'items.product.productImages',
                'shipments.trackingLogs' => function($q) {
                    $q->orderBy('created_at', 'desc');
                },
                'shipments.vendor:id,name',
                'shipments.items.product:id,product_name_en,product_name_ar'
            ])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Enhance each order with tracking information
        foreach ($orders as $order) {
            $order->tracking_info = $this->shippingService->getTrackingForCustomer($order->id);
        }
        
        return view('web.pages.history_order', compact('orders'));
    }

    /**
     * Get detailed tracking for a specific order.
     */
    public function tracking($orderId)
    {
        $order = $this->order->where('user_id', auth()->id())
            ->with([
                'items.product.productImages',
                'shipments.trackingLogs' => function($q) {
                    $q->orderBy('created_at', 'asc'); // Timeline order
                },
                'shipments.vendor:id,name',
                'shipments.items.product:id,product_name_en,product_name_ar'
            ])
            ->findOrFail($orderId);
        
        $trackingInfo = $this->shippingService->getTrackingForCustomer($orderId);
        
        return view('web.pages.order_tracking', compact('order', 'trackingInfo'));
    }
}
