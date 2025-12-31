<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
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
        $orderId = $request->get('order_id');
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $query = ReturnModel::with(['orderItem.product.productImages', 'orderItem.order.user', 'approver'])
            ->orderBy('created_at', 'desc');

        // Status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by order ID if provided
        if ($orderId) {
            $query->whereHas('orderItem', function($q) use ($orderId) {
                $q->where('order_id', $orderId);
            });
        }

        // Search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhereHas('orderItem.order', function($q) use ($search) {
                      $q->where('order_number', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('orderItem.product', function($q) use ($search) {
                      $q->where('product_name_en', 'like', "%{$search}%")
                        ->orWhere('product_name_ar', 'like', "%{$search}%");
                  });
            });
        }

        // Date range filter
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $returns = $query->paginate(50);
        
        // Enhanced statistics
        $baseQuery = ReturnModel::query();
        if ($dateFrom || $dateTo) {
            if ($dateFrom) {
                $baseQuery->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $baseQuery->whereDate('created_at', '<=', $dateTo);
            }
        }
        
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'requested' => (clone $baseQuery)->where('status', 'requested')->count(),
            'approved' => (clone $baseQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
            'refunded' => (clone $baseQuery)->where('status', 'refunded')->count(),
            'total_refund_amount' => (clone $baseQuery)->where('status', 'refunded')->sum('refund_amount'),
            'pending_refund_amount' => (clone $baseQuery)->where('status', 'approved')->sum('refund_amount'),
        ];

        return view('dashboard.returns.index', compact('returns', 'stats', 'status', 'search', 'dateFrom', 'dateTo', 'orderId'));
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
            $return->orderItem->order->user->notify(new \App\Notifications\ReturnStatusUpdated($return, 'approved'));
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
            $return->orderItem->order->user->notify(new \App\Notifications\ReturnStatusUpdated($return, 'rejected'));
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
