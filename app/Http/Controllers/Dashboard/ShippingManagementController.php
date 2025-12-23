<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\ShippingManagementService;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShippingManagementController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingManagementService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    /**
     * Display shipping management dashboard.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get filters
        $status = $request->get('status');
        $vendorId = $request->get('vendor_id');
        $city = $request->get('city');
        $provider = $request->get('provider');
        
        // Build query
        $query = Shipment::with(['order', 'vendor', 'items.product']);
        
        if ($user->hasRole('admin')) {
            // Admin sees all shipments
        } else {
            // Vendor sees only their shipments
            $query->where('vendor_id', $user->id);
        }
        
        // Apply filters
        if ($status) {
            $query->where('shipment_status', $status);
        }
        
        if ($vendorId) {
            $query->where('vendor_id', $vendorId);
        }
        
        if ($city) {
            $query->where('customer_city', $city);
        }
        
        if ($provider) {
            $query->where('shipping_provider', $provider);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhere('shipping_provider_id', 'like', "%{$search}%")
                  ->orWhereHas('order', function($q) use ($search) {
                      $q->where('order_number', 'like', "%{$search}%");
                  })
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }
        
        $shipments = $query->latest()->paginate(20);
        
        // Get statistics
        $stats = $this->shippingService->getShippingStatistics(
            $user->hasRole('admin') ? null : $user->id
        );
        
        // Get filter options
        $vendors = User::where('type', 'vendor')->get();
        $cities = Shipment::distinct('customer_city')->pluck('customer_city')->filter();
        $providers = Shipment::distinct('shipping_provider')->pluck('shipping_provider')->filter();
        
        return view('dashboard.pages.shipping-management.index', compact(
            'shipments', 'stats', 'vendors', 'cities', 'providers'
        ));
    }

    /**
     * Show shipment details.
     */
    public function show($id)
    {
        $shipment = Shipment::with([
            'order',
            'vendor',
            'items.product',
            'trackingLogs' => function($q) {
                $q->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);
        
        // Check authorization
        if (!auth()->user()->hasRole('admin') && $shipment->vendor_id !== auth()->id()) {
            abort(403);
        }
        
        return view('dashboard.pages.shipping-management.show', compact('shipment'));
    }

    /**
     * Create shipment from order.
     */
    public function create(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'vendor_id' => 'required|exists:users,id',
            'item_ids' => 'nullable|array',
            'item_ids.*' => 'exists:items_orders,id',
        ]);
        
        try {
            $shipment = $this->shippingService->createShipment(
                $request->order_id,
                $request->vendor_id,
                $request->item_ids ?? []
            );
            
            return redirect()
                ->route('dashboard.shipping-management.show', $shipment->id)
                ->with('success', 'Shipment created successfully');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Create shipment with shipping provider.
     */
    public function createWithProvider(Request $request, $id)
    {
        $request->validate([
            'provider' => 'required|in:bosta,aramex,dhl,manual',
            'shipping_method' => 'required|in:express,standard,economy',
        ]);
        
        try {
            $shipment = $this->shippingService->createShipmentWithProvider(
                $id,
                $request->provider,
                ['method' => $request->shipping_method]
            );
            
            return redirect()
                ->route('dashboard.shipping-management.show', $id)
                ->with('success', 'Shipment created with ' . ucfirst($request->provider) . ' successfully');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Update shipment status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,ready_to_ship,picked_up,shipped,in_transit,out_for_delivery,delivered,returned,exception,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $shipment = Shipment::findOrFail($id);
        
        // Check authorization
        if (!auth()->user()->hasRole('admin') && $shipment->vendor_id !== auth()->id()) {
            abort(403);
        }
        
        $oldStatus = $shipment->shipment_status;
        $updateData = ['shipment_status' => $request->status];
        
        // Update timestamps based on status
        switch ($request->status) {
            case 'ready_to_ship':
                $updateData['ready_to_ship_at'] = now();
                break;
            case 'picked_up':
                $updateData['picked_up_at'] = now();
                break;
            case 'in_transit':
                $updateData['in_transit_at'] = now();
                break;
            case 'out_for_delivery':
                $updateData['out_for_delivery_at'] = now();
                break;
            case 'delivered':
                $updateData['delivered_at'] = now();
                $updateData['delivered_to'] = $request->delivered_to ?? null;
                $updateData['delivery_notes'] = $request->delivery_notes ?? null;
                break;
            case 'exception':
                $updateData['exception_at'] = now();
                $updateData['exception_reason'] = $request->exception_reason ?? null;
                break;
        }
        
        $shipment->update($updateData);
        
        // Create tracking log
        \App\Models\TrackingLog::create([
            'shipment_id' => $shipment->id,
            'status' => $request->status,
            'description' => $request->notes ?? "Status updated from {$oldStatus} to {$request->status}",
            'source' => 'manual',
        ]);
        
        return redirect()
            ->back()
            ->with('success', 'Shipment status updated successfully');
    }

    /**
     * Bulk create shipments.
     */
    public function bulkCreate(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'vendor_id' => 'nullable|exists:users,id',
        ]);
        
        $result = $this->shippingService->bulkCreateShipments(
            $request->order_ids,
            $request->vendor_id
        );
        
        $message = "Created {$result['total_created']} shipments";
        if ($result['total_failed'] > 0) {
            $message .= ". Failed: {$result['total_failed']}";
        }
        
        return redirect()
            ->route('dashboard.shipping-management.index')
            ->with('success', $message);
    }

    /**
     * Get shipping cost estimate.
     */
    public function estimateCost(Request $request)
    {
        $request->validate([
            'from_city' => 'required|string',
            'to_city' => 'required|string',
            'weight' => 'required|integer|min:1',
            'method' => 'nullable|in:express,standard,economy',
        ]);
        
        $cost = $this->shippingService->estimateShippingCost(
            $request->from_city,
            $request->to_city,
            $request->weight,
            $request->method ?? 'standard'
        );
        
        return response()->json([
            'cost' => $cost,
            'currency' => 'EGP',
        ]);
    }

    /**
     * Download shipping label.
     */
    public function downloadLabel($id)
    {
        $shipment = Shipment::findOrFail($id);
        
        if (!$shipment->shipping_label_url) {
            return redirect()->back()->with('error', 'Shipping label not available');
        }
        
        return redirect($shipment->shipping_label_url);
    }

    /**
     * Print shipping label.
     */
    public function printLabel($id)
    {
        $shipment = Shipment::with(['order', 'vendor', 'items.product'])->findOrFail($id);
        
        return view('dashboard.pages.shipping-management.label', compact('shipment'));
    }
}

