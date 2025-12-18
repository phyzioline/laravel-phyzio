<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\VendorPayment;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PaymentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
        return [
            new Middleware('can:financials-index', only: ['index', 'showVendorPayment', 'detail', 'updateStatus']),
        ];
        ];
    }
    /**
     * Display a listing of the vendor payments.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Check if user is Admin to show ALL payments
        if ($user->hasRole('admin')) {
             // Admin sees overall statistics
            $totalEarnings = VendorPayment::where('status', 'paid')->sum('vendor_earnings');
            $pendingPayments = VendorPayment::where('status', 'pending')->sum('vendor_earnings');
            
            // Latest payout across all vendors
            $lastPayout = VendorPayment::where('status', 'paid')
                ->latest('paid_at')
                ->first();

            // All Transactions with Filters
            $query = VendorPayment::with(['order', 'orderItem.product', 'vendor'])
                ->orderBy('created_at', 'desc');

            if ($request->filled('vendor_id')) {
                $query->where('vendor_id', $request->vendor_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $payments = $query->paginate(15);
            $vendors = \App\Models\User::role('vendor')->get(); // Get vendors for filter
        } else {
             // Vendor sees ONLY their stats
            $vendorId = $user->id;

            $totalEarnings = VendorPayment::where('vendor_id', $vendorId)
                ->where('status', 'paid')
                ->sum('vendor_earnings');

            $pendingPayments = VendorPayment::where('vendor_id', $vendorId)
                ->where('status', 'pending')
                ->sum('vendor_earnings');

            $lastPayout = VendorPayment::where('vendor_id', $vendorId)
                ->where('status', 'paid')
                ->latest('paid_at')
                ->first();

            $payments = VendorPayment::where('vendor_id', $vendorId)
                ->with(['order', 'orderItem.product'])
                ->orderBy('created_at', 'desc');
            
            if ($request->filled('status')) {
                $payments->where('status', $request->status);
            }
                
            $payments = $payments->paginate(15);
            $vendors = []; // Not needed for vendor view
        }

        return view('dashboard.pages.payments.index', compact(
            'totalEarnings',
            'pendingPayments',
            'lastPayout',
            'payments',
            'vendors'
        ));
    }

    /**
     * Show vendor payment details (VendorPayment model).
     */
    public function showVendorPayment($id)
    {
        $vp = VendorPayment::with(['payment', 'order', 'orderItem.product'])->findOrFail($id);

        $this->authorize('view', $vp);

        if (request()->wantsJson()) {
            return response()->json($vp->load('payment', 'order', 'orderItem'));
        }

        return view('dashboard.pages.payments.show', compact('vp'));
    }

    /**
     * Show raw payment detail (Payment model) guarded by PaymentPolicy.
     */
    public function detail($id)
    {
        $payment = \App\Models\Payment::findOrFail($id);

        $this->authorize('view', $payment);

        // Minimal JSON for now (view can be implemented later)
        return response()->json($payment->load('paymentable'));
    }
    /**
     * Update payment status (Admin only)
     */
    public function updateStatus(Request $request, $id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        $vp = VendorPayment::findOrFail($id);
        
        $vp->update([
            'status' => $request->status,
            'paid_at' => $request->status === 'paid' ? now() : ($request->status === 'pending' ? null : $vp->paid_at),
        ]);

        return redirect()->back()->with('message', ['type' => 'success', 'text' => 'Payment status updated successfully']);
    }
}

