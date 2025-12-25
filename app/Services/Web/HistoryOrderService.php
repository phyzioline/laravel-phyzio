<?php

namespace App\Services\Web;

use App\Models\Order;


class HistoryOrderService
{
    public function __construct(public Order $order){}

    public function index()
    {
        $orders = $this->order->where('user_id', auth()->id())
            ->with(['items.product.productImages'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('web.pages.history_order', compact('orders'));
    }

}
