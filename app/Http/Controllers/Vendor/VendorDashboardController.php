<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Services\ShippingService;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;

class VendorDashboardController extends Controller
{
    protected $orderService;
    protected $shippingService;
    protected $walletService;

    public function __construct(
        OrderService $orderService,
        ShippingService $shippingService,
        WalletService $walletService
    ) {
        $this->orderService = $orderService;
        $this->shippingService = $shippingService;
        $this->walletService = $walletService;
    }

    /**
     * Vendor dashboard main page.
     */
    public function index()
    {
        $vendorId = Auth::id();

        // Get metrics
        $pendingShipments = $this->shippingService->getPendingShipments($vendorId);
        $allOrders = $this->orderService->getVendorOrders($vendorId);
        $walletSummary = $this->walletService->getWalletSummary($vendorId);

        // Calculate stats
        $stats = [
            'total_orders' => $allOrders->count(),
            'pending_shipments' => $pendingShipments->count(),
            'total_sales' => $allOrders->sum('total'),
            'available_balance' => $walletSummary['available_balance'],
            'pending_balance' => $walletSummary['pending_balance'],
        ];

        return view('vendor.dashboard', compact('stats', 'pendingShipments', 'walletSummary'));
    }
}
