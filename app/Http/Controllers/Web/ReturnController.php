<?php

namespace App\Http\Controllers\Web;

use App\Models\ReturnModel;
use App\Models\ItemsOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    /**
     * Display return request form for an order item.
     */
    public function create($orderItemId)
    {
        $orderItem = ItemsOrder::with(['product', 'order'])->findOrFail($orderItemId);
        
        // Verify user owns this order
        if ($orderItem->order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this return request');
        }

        // Check if return already exists
        $existingReturn = ReturnModel::where('order_item_id', $orderItemId)->first();
        if ($existingReturn) {
            return redirect()->route('returns.show.' . app()->getLocale(), $existingReturn->id)
                ->with('info', 'Return request already exists for this item');
        }

        // Check if order is eligible for return (within 14 days, status is delivered)
        if ($orderItem->order->status !== 'delivered') {
            return back()->with('error', 'Returns can only be requested for delivered orders');
        }

        $deliveredAt = $orderItem->order->updated_at; // Assuming last update = delivery
        $daysSinceDelivery = now()->diffInDays($deliveredAt);
        
        if ($daysSinceDelivery > 14) {
            return back()->with('error', 'Return window has expired (14 days after delivery)');
        }

        return view('web.returns.create', compact('orderItem'));
    }

    /**
     * Store a new return request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_item_id' => 'required|exists:items_orders,id',
            'reason' => 'required|string|min:10|max:500',
            'refund_amount' => 'nullable|numeric|min:0',
        ]);

        $orderItem = ItemsOrder::with('order')->findOrFail($validated['order_item_id']);
        
        // Verify ownership
        if ($orderItem->order->user_id !== Auth::id()) {
            abort(403);
        }

        // Create return request
        $return = ReturnModel::create([
            'order_item_id' => $validated['order_item_id'],
            'reason' => $validated['reason'],
            'refund_amount' => $validated['refund_amount'] ?? $orderItem->total,
            'status' => 'requested',
        ]);

        // Notify admin
        $admins = \App\Models\User::role('admin')->get();
        foreach ($admins as $admin) {
            // Notification logic here
        }

        return redirect()->route('returns.show.' . app()->getLocale(), $return->id)
            ->with('success', 'Return request submitted successfully. We will review it within 2-3 business days.');
    }

    /**
     * Display a specific return request.
     */
    public function show($id)
    {
        $return = ReturnModel::with(['orderItem.product', 'orderItem.order', 'approver'])
            ->findOrFail($id);

        // Verify user owns this return
        if ($return->orderItem->order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('web.returns.show', compact('return'));
    }

    /**
     * Display all returns for the authenticated user.
     */
    public function index()
    {
        $returns = ReturnModel::with(['orderItem.product', 'orderItem.order'])
            ->whereHas('orderItem.order', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('web.returns.index', compact('returns'));
    }

    /**
     * Cancel a return request (only if status is 'requested').
     */
    public function cancel($id)
    {
        $return = ReturnModel::with('orderItem.order')->findOrFail($id);

        // Verify ownership
        if ($return->orderItem->order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($return->status !== 'requested') {
            return back()->with('error', 'Cannot cancel return request that is already processed');
        }

        $return->update(['status' => 'cancelled']);

        return back()->with('success', 'Return request cancelled successfully');
    }
}
