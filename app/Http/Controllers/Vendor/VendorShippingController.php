<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorShippingController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    /**
     * List vendor's shipments.
     */
    public function index(Request $request)
    {
        $vendorId = Auth::id();
        $status = $request->get('status');
        
        $shipments = $this->shippingService->getVendorShipments($vendorId, $status);

        return view('vendor.shipping.index', compact('shipments'));
    }

    /**
     * Show shipment details with tracking history.
     */
    public function show($id)
    {
        $shipment = $this->shippingService->getShipmentDetails($id);

        // Verify vendor owns this shipment
        if ($shipment->vendor_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('vendor.shipping.show', compact('shipment'));
    }

    /**
     * Update shipping information (add tracking).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'courier' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $shipment = $this->shippingService->getShipmentDetails($id);

        // Verify vendor owns this shipment
        if ($shipment->vendor_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $this->shippingService->updateTracking(
            $id,
            $request->courier,
            $request->tracking_number,
            $request->notes
        );

        return redirect()
            ->route('vendor.shipments.show', $id)
            ->with('success', 'Tracking information updated successfully');
    }
}
