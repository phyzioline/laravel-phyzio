<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\PayoutService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{
    protected $payoutService;
    protected $walletService;

    public function __construct(PayoutService $payoutService, WalletService $walletService)
    {
        $this->payoutService = $payoutService;
        $this->walletService = $walletService;
    }

    /**
     * Display all payout requests (admin view).
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $vendorId = $request->get('vendor_id');
        
        $payouts = $this->payoutService->getAllPayouts($status, $vendorId);
        $stats = $this->payoutService->getPayoutStatistics();
        $vendors = \App\Models\User::where('type', 'vendor')->get();

        return view('dashboard.payouts.index', compact('payouts', 'stats', 'vendors'));
    }

    /**
     * Show payout details.
     */
    public function show($id)
    {
        $payout = \App\Models\Payout::with('vendor')->findOrFail($id);
        $walletSummary = $this->walletService->getWalletSummary($payout->vendor_id);
        
        return view('dashboard.payouts.show', compact('payout', 'walletSummary'));
    }

    /**
     * Approve payout request.
     */
    public function approve($id)
    {
        try {
            $this->payoutService->approvePayout($id, Auth::id());
            
            return redirect()
                ->route('dashboard.payouts.show', $id)
                ->with('success', 'Payout approved and moved to processing');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Mark payout as paid.
     */
    public function markAsPaid(Request $request, $id)
    {
        $request->validate([
            'reference_number' => 'required|string|max:255',
        ]);

        try {
            $this->payoutService->markAsPaid($id, $request->reference_number);
            
            return redirect()
                ->route('dashboard.payouts.show', $id)
                ->with('success', 'Payout marked as paid successfully');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel/reject payout.
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->payoutService->cancelPayout($id, $request->reason);
            
            return redirect()
                ->route('dashboard.payouts.index')
                ->with('success', 'Payout cancelled and funds returned to vendor wallet');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Bulk approve payouts.
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'payout_ids' => 'required|array',
            'payout_ids.*' => 'exists:payouts,id',
        ]);

        $result = $this->payoutService->bulkApprovePayouts($request->payout_ids, Auth::id());
        
        return redirect()
            ->route('dashboard.payouts.index')
            ->with('success', "Approved {$result['total_approved']} payouts. Failed: {$result['total_failed']}");
    }
}
