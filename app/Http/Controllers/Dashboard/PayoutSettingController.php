<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PayoutSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PayoutSettingController extends Controller
{
    /**
     * Show payout settings page.
     */
    public function index()
    {
        $settings = PayoutSetting::getSettings();
        $payoutService = app(\App\Services\PayoutService::class);
        $stats = $payoutService->getPayoutStatistics();
        
        // Get recent auto-payouts
        $recentAutoPayouts = \App\Models\Payout::where('payout_method', 'auto_weekly')
            ->with('vendor:id,name,email')
            ->latest()
            ->limit(20)
            ->get();

        return view('dashboard.pages.payouts.settings', compact('settings', 'stats', 'recentAutoPayouts'));
    }

    /**
     * Update payout settings.
     */
    public function update(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'hold_period_days' => 'required|integer|min:1|max:30',
            'auto_payout_enabled' => 'boolean',
            'minimum_payout' => 'required|numeric|min:1',
            'auto_payout_frequency' => 'required|in:weekly,biweekly,monthly',
        ]);

        $settings = PayoutSetting::getSettings();
        $settings->update([
            'hold_period_days' => $request->hold_period_days,
            'auto_payout_enabled' => $request->has('auto_payout_enabled'),
            'minimum_payout' => $request->minimum_payout,
            'auto_payout_frequency' => $request->auto_payout_frequency,
        ]);

        Session::flash('message', ['type' => 'success', 'text' => 'Payout settings updated successfully!']);
        return redirect()->back();
    }

    /**
     * Manually trigger auto-payout process.
     */
    public function triggerAutoPayout()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        try {
            $payoutService = app(\App\Services\PayoutService::class);
            $walletService = app(\App\Services\WalletService::class);

            // Process settlements first
            $walletService->processSettlements();

            // Create auto payouts
            $result = $payoutService->createAutoPayouts();

            Session::flash('message', [
                'type' => 'success',
                'text' => "Auto-payout process completed! Created: {$result['created']}, Skipped: {$result['skipped']}"
            ]);
        } catch (\Exception $e) {
            Session::flash('message', [
                'type' => 'error',
                'text' => 'Error processing auto-payouts: ' . $e->getMessage()
            ]);
        }

        return redirect()->back();
    }
}

