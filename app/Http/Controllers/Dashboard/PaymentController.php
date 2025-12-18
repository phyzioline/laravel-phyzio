<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\VendorPayment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the vendor payments.
     */
    public function index()
    {
        $vendorId = auth()->user()->id;

        // Statistics
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

        // Recent Transactions
        $payments = VendorPayment::where('vendor_id', $vendorId)
            ->with(['order', 'orderItem.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dashboard.pages.payments.index', compact(
            'totalEarnings',
            'pendingPayments',
            'lastPayout',
            'payments'
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
}

