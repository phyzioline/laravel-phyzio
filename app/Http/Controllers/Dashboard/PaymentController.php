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
            new Middleware('can:financials-index', only: ['index', 'showVendorPayment', 'detail', 'updateStatus']),
        ];
    }
    /**
     * Display a listing of the vendor payments.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $view = $request->get('view', 'statement'); // Default to statement view

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

            // Enhanced Filters
            if ($request->filled('search_order')) {
                $query->whereHas('order', function($q) use ($request) {
                    $q->where('id', 'like', '%' . $request->search_order . '%')
                      ->orWhere('reference_number', 'like', '%' . $request->search_order . '%');
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $payments = $query->paginate(20);
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
            
            // Enhanced Filters for Vendor
            if ($request->filled('search_order')) {
                $payments->whereHas('order', function($q) use ($request) {
                    $q->where('id', 'like', '%' . $request->search_order . '%')
                      ->orWhere('reference_number', 'like', '%' . $request->search_order . '%');
                });
            }
            
            if ($request->filled('status')) {
                $payments->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $payments->whereDate('created_at', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $payments->whereDate('created_at', '<=', $request->date_to);
            }
            
            if ($request->filled('date_type') && $request->date_type == 'past') {
                // Example logic for "Past number of days" - simplifying to last 30 days if selected
                 $payments->whereDate('created_at', '>=', now()->subDays(30));
            }
                
            $payments = $payments->paginate(20);
            $vendors = []; // Not needed for vendor view
        }

        $currentView = $view;

        // Route to appropriate view based on tab
        switch ($view) {
            case 'transaction':
                return view('dashboard.pages.finance.transaction', compact(
                    'totalEarnings', 'pendingPayments', 'lastPayout', 'payments', 'vendors', 'currentView'
                ));
            
            case 'all-statements':
                return $this->allStatements($request, $totalEarnings, $pendingPayments, $lastPayout, $payments, $vendors);
            
            case 'disbursements':
                return $this->disbursements($request, $user);
            
            case 'advertising':
                return $this->advertisingInvoiceHistory($request, $user);
            
            case 'reports':
                return $this->reportsRepository($request, $user);
            
            case 'statement':
            default:
                return view('dashboard.pages.finance.statement', compact(
                    'totalEarnings', 'pendingPayments', 'lastPayout', 'payments', 'vendors', 'currentView'
                ));
        }
    }

    /**
     * All Statements view
     */
    private function allStatements($request, $totalEarnings, $pendingPayments, $lastPayout, $payments, $vendors)
    {
        $currentView = 'all-statements';
        return view('dashboard.pages.finance.all-statements', compact(
            'totalEarnings', 'pendingPayments', 'lastPayout', 'payments', 'vendors', 'currentView'
        ));
    }

    /**
     * Disbursements view (Payouts)
     */
    private function disbursements($request, $user)
    {
        $payoutService = app(\App\Services\PayoutService::class);
        
        if ($user->hasRole('admin')) {
            $payouts = $payoutService->getAllPayouts(
                $request->get('status'),
                $request->get('vendor_id')
            );
            $vendors = \App\Models\User::where('type', 'vendor')->get();
        } else {
            $payouts = $payoutService->getVendorPayouts($user->id, $request->get('status'));
            $vendors = [];
        }
        
        $currentView = 'disbursements';
        $stats = $payoutService->getPayoutStatistics();
        return view('dashboard.pages.finance.disbursements', compact('payouts', 'vendors', 'currentView', 'stats'));
    }

    /**
     * Advertising Invoice History
     */
    private function advertisingInvoiceHistory($request, $user)
    {
        // Placeholder for advertising invoices - can be expanded later
        $currentView = 'advertising';
        return view('dashboard.pages.finance.advertising', compact('currentView'));
    }

    /**
     * Reports Repository
     */
    private function reportsRepository($request, $user)
    {
        $currentView = 'reports';
        return view('dashboard.pages.finance.reports', compact('currentView'));
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

