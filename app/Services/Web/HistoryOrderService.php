<?php

namespace App\Services\Web;

use App\Models\Order;


class HistoryOrderService
{
    public function __construct(public Order $order){}

    public function index()
    {
        $orders = $this->order->where('user_id', auth()->id())->with('items.product')->get();
        return view('web.pages.history_order', compact('orders'));
    }

}
