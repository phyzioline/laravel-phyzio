<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\ShippingService;
use App\Services\WalletService;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    protected $shippingService;
    protected $walletService;

    public function __construct(ShippingService $shippingService, WalletService $walletService)
    {
        $this->shippingService = $shippingService;
        $this->walletService = $walletService;
    }

    /**
     * Display all shipments (admin oversight).
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $vendorId = $request->get('vendor_id');
        
        $query = \App\Models\Shipment::with(['order', 'vendor', 'items.product']);
        
        if ($status) {
            $query->where('shipment_status', $status);
        }
        
        if ($vendorId) {
            $query->where('vendor_id', $vendorId);
        }
        
        $shipments = $query->latest()->paginate(20);
        $vendors = \App\Models\User::where('type', 'vendor')->get();

        return view('dashboard.shipments.index', compact('shipments', 'vendors'));
    }

    /**
     * Show shipment details (admin view).
     */
    public function show($id)
    {
        $shipment = $this->shippingService->getShipmentDetails($id);
        
        return view('dashboard.shipments.show', compact('shipment'));
    }

    /**
     * Update shipment status (admin action).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,shipped,in_transit,delivered,returned',
            'notes' => 'nullable|string|max:500',
        ]);

        $this->shippingService->updateShipmentStatus(
            $id,
            $request->status,
            'admin',
            $request->notes ?? "Status updated by admin to: {$request->status}"
        );

        return redirect()
            ->route('dashboard.shipments.show', $id)
            ->with('success', 'Shipment status updated successfully');
    }

    /**
     * Get overdue shipments (admin dashboard widget).
     */
    public function overdue()
    {
        $overdueShipments = $this->shippingService->getOverdueShipments(3);
        
        return view('dashboard.shipments.overdue', compact('overdueShipments'));
    }
}
