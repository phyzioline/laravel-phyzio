<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use App\Services\PayoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorWalletController extends Controller
{
    protected $walletService;
    protected $payoutService;

    public function __construct(WalletService $walletService, PayoutService $payoutService)
    {
        $this->walletService = $walletService;
        $this->payoutService = $payoutService;
    }

    /**
     * Show wallet dashboard.
     */
    public function index()
    {
        $vendorId = Auth::id();
        
        $walletSummary = $this->walletService->getWalletSummary($vendorId);
        $payoutHistory = $this->payoutService->getVendorPayouts($vendorId);

        return view('vendor.wallet.index', compact('walletSummary', 'payoutHistory'));
    }

    /**
     * Request a payout.
     */
    public function requestPayout(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'payout_method' => 'required|in:bank_transfer,payoneer,wise',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $vendorId = Auth::id();
            
            $payout = $this->payoutService->requestPayout(
                $vendorId,
                $request->amount,
                $request->payout_method,
                $request->notes
            );

            return redirect()
                ->route('dashboard.vendor.wallet')
                ->with('success', 'Payout request submitted successfully. Awaiting admin approval.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
