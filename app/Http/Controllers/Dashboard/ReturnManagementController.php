<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\ReturnModel;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnManagementController extends Controller
{
    /**
     * Display all return requests for admin review.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = ReturnModel::with(['orderItem.product', 'orderItem.order.user', 'approver'])
            ->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $returns = $query->paginate(20);
        $stats = [
            'requested' => ReturnModel::where('status', 'requested')->count(),
            'approved' => ReturnModel::where('status', 'approved')->count(),
            'rejected' => ReturnModel::where('status', 'rejected')->count(),
            'refunded' => ReturnModel::where('status', 'refunded')->count(),
        ];

        return view('dashboard.returns.index', compact('returns', 'stats', 'status'));
    }

    /**
     * Display a specific return request.
     */
    public function show($id)
    {
        $return = ReturnModel::with([
            'orderItem.product', 
            'orderItem.order.user', 
            'approver',
            'shipment'
        ])->findOrFail($id);

        return view('dashboard.returns.show', compact('return'));
    }

    /**
     * Approve a return request.
     */
    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'refund_amount' => 'required|numeric|min:0',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $return = ReturnModel::with('orderItem')->findOrFail($id);

        if ($return->status !== 'requested') {
            return back()->with('error', 'Return request has already been processed');
        }

        $return->update([
            'status' => 'approved',
            'refund_amount' => $validated['refund_amount'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Notify customer
        if ($return->orderItem->order->user) {
            // Send notification
        }

        return back()->with('success', 'Return request approved. Proceed with refund processing.');
    }

    /**
     * Reject a return request.
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $return = ReturnModel::findOrFail($id);

        if ($return->status !== 'requested') {
            return back()->with('error', 'Return request has already been processed');
        }

        $return->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'resolved_at' => now(),
        ]);

        // Notify customer
        if ($return->orderItem->order->user) {
            // Send rejection notification
        }

        return back()->with('success', 'Return request rejected');
    }

    /**
     * Process refund for an approved return.
     */
    public function refund(Request $request, $id)
    {
        $validated = $request->validate([
            'refund_method' => 'required|in:original_payment,wallet,manual',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($id, $validated) {
            $return = ReturnModel::with(['orderItem.order'])->findOrFail($id);

            if ($return->status !== 'approved') {
                return back()->with('error', 'Return must be approved before refund');
            }

            // Process refund based on method
            switch ($validated['refund_method']) {
                case 'wallet':
                    // Add to user wallet
                    $wallet = $return->orderItem->order->user->vendorWallet ?? $return->orderItem->order->user->therapistWallet;
                    if ($wallet) {
                        $wallet->increment('balance', $return->refund_amount);
                    }
                    break;
                    
                case 'original_payment':
                    // Integration with payment gateway for refund
                    // This would call Paymob refund API
                    break;
                    
                case 'manual':
                    // Manual refund - admin handles outside system
                    break;
            }

            // Update return status
            $return->update([
                'status' => 'refunded',
                'resolved_at' => now(),
                'admin_notes' => ($return->admin_notes ?? '') . "\n" . $validated['admin_notes'],
            ]);

            // Update order item status if needed
            // Could add 'returned' field to items_orders table

            return redirect()->route('dashboard.returns.index')
                ->with('success', 'Refund processed successfully');
        });
    }
}
