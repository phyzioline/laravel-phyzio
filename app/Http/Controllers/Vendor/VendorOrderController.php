<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class VendorOrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * List vendor's orders.
     */
    public function index()
    {
        $vendorId = Auth::id();
        $orders = $this->orderService->getVendorOrders($vendorId);

        return view('vendor.orders.index', compact('orders'));
    }

    /**
     * Show order details (vendor only sees their items).
     */
    public function show($id)
    {
        $vendorId = Auth::id();
        $order = $this->orderService->getVendorOrderDetails($id, $vendorId);

        return view('vendor.orders.show', compact('order'));
    }
}
