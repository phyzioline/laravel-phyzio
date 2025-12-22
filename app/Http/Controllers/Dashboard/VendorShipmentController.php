<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\TrackingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::where('vendor_id', auth()->id())
            ->with(['order', 'items.product'])
            ->latest()
            ->paginate(10);
            
        return view('dashboard.pages.shipments.index', compact('shipments'));
    }

    public function create(Order $order)
    {
        // Verify vendor owns items in this order
        $vendorItems = $order->items()->whereHas('product', function($q) {
            $q->where('user_id', auth()->id());
        })->whereNull('shipment_id')->get();

        if ($vendorItems->isEmpty()) {
            return redirect()->back()->with('error', 'No pending items to ship for this order.');
        }

        return view('dashboard.pages.shipments.create', compact('order', 'vendorItems'));
    }

    public function store(Request $request, Order $order)
    {
        $request->validate([
            'courier' => 'required|string',
            'tracking_number' => 'required|string',
            'notes' => 'nullable|string',
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:items_orders,id'
        ]);

        DB::beginTransaction();
        try {
            // Create Shipment
            $shipment = Shipment::create([
                'order_id' => $order->id,
                'vendor_id' => auth()->id(),
                'courier' => $request->courier,
                'tracking_number' => $request->tracking_number,
                'shipment_status' => 'shipped',
                'shipped_at' => now(),
                'notes' => $request->notes
            ]);

            // Link Items and Update Inventory if needed (assuming inventory deducted on Order placement)
            \App\Models\ItemsOrder::whereIn('id', $request->item_ids)->update(['shipment_id' => $shipment->id]);

            // Log Tracking
            TrackingLog::create([
                'shipment_id' => $shipment->id,
                'status' => 'shipped',
                'notes' => 'Shipment created with tracking number ' . $request->tracking_number
            ]);

            DB::commit();
            return redirect()->route('dashboard.shipments.index')->with('success', 'Shipment created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating shipment: ' . $e->getMessage());
        }
    }

    public function updateTracking(Request $request, Shipment $shipment)
    {
        if ($shipment->vendor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:shipped,out_for_delivery,delivered,exception',
            'notes' => 'nullable|string'
        ]);

        $shipment->update([
            'shipment_status' => $request->status,
            'notes' => $request->notes // simplistic override, better to keep history
        ]);
        
        if ($request->status == 'delivered') {
             $shipment->update(['delivered_at' => now()]);
        }

        TrackingLog::create([
            'shipment_id' => $shipment->id,
            'status' => $request->status,
            'notes' => $request->notes ?? 'Status updated to ' . $request->status
        ]);

        return back()->with('success', 'Tracking updated.');
    }
}
