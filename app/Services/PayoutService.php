<?php

namespace App\Services;

use App\Models\Payout;
use App\Models\VendorWallet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayoutService
{
    protected $walletService;

    /**
     * Minimum payout amount (in currency).
     */
    const MINIMUM_PAYOUT = 100;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Request a payout (vendor initiates withdrawal).
     */
    public function requestPayout($vendorId, $amount, $payoutMethod, $notes = null)
    {
        return DB::transaction(function () use ($vendorId, $amount, $payoutMethod, $notes) {
            
            // Validate minimum amount
            if ($amount < self::MINIMUM_PAYOUT) {
                throw new \Exception("Minimum payout amount is " . self::MINIMUM_PAYOUT);
            }

            $wallet = $this->walletService->getVendorWallet($vendorId);

            // Check if vendor has sufficient available balance
            if ($wallet->available_balance < $amount) {
                throw new \Exception("Insufficient available balance. Available: {$wallet->available_balance}");
            }

            // Deduct from available balance
            if (!$wallet->deductAvailable($amount)) {
                throw new \Exception("Failed to deduct from wallet");
            }

            // Create payout request
            $payout = Payout::create([
                'vendor_id' => $vendorId,
                'amount' => $amount,
                'status' => 'pending',
                'payout_method' => $payoutMethod,
                'notes' => $notes,
            ]);

            Log::info("Payout requested by vendor {$vendorId}: Amount {$amount}, Method {$payoutMethod}");

            return $payout;
        });
    }

    /**
     * Approve payout (admin action).
     */
    public function approvePayout($payoutId, $adminId)
    {
        return DB::transaction(function () use ($payoutId, $adminId) {
            $payout = Payout::findOrFail($payoutId);

            if ($payout->status !== 'pending') {
                throw new \Exception("Payout is not in pending status");
            }

            $payout->update([
                'status' => 'processing',
                'approved_by' => $adminId,
                'approved_at' => Carbon::now(),
            ]);

            Log::info("Payout #{$payoutId} approved by admin {$adminId}");

            return $payout;
        });
    }

    /**
     * Mark payout as paid (after bank transfer completion).
     */
    public function markAsPaid($payoutId, $referenceNumber = null)
    {
        return DB::transaction(function () use ($payoutId, $referenceNumber) {
            $payout = Payout::findOrFail($payoutId);

            $payout->update([
                'status' => 'paid',
                'reference_number' => $referenceNumber,
                'paid_at' => Carbon::now(),
            ]);

            Log::info("Payout #{$payoutId} marked as paid. Reference: {$referenceNumber}");

            return $payout;
        });
    }

    /**
     * Cancel/reject payout and return funds to wallet.
     */
    public function cancelPayout($payoutId, $reason = null)
    {
        return DB::transaction(function () use ($payoutId, $reason) {
            $payout = Payout::findOrFail($payoutId);

            if ($payout->status === 'paid') {
                throw new \Exception("Cannot cancel a paid payout");
            }

            // Return amount to vendor's available balance
            $wallet = $this->walletService->getVendorWallet($payout->vendor_id);
            $wallet->available_balance += $payout->amount;
            $wallet->save();

            $payout->update([
                'status' => 'cancelled',
                'notes' => $reason ? "Cancelled: {$reason}" : "Cancelled",
            ]);

            Log::info("Payout #{$payoutId} cancelled. Amount {$payout->amount} returned to wallet.");

            return $payout;
        });
    }

    /**
     * Get payout history for vendor.
     */
    public function getVendorPayouts($vendorId, $status = null)
    {
        $query = Payout::where('vendor_id', $vendorId);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->get();
    }

    /**
     * Get pending payouts for admin approval.
     */
    public function getPendingPayouts()
    {
        return Payout::where('status', 'pending')
            ->with('vendor:id,name,email')
            ->latest()
            ->get();
    }

    /**
     * Get all payouts for admin (with filters).
     */
    public function getAllPayouts($status = null, $vendorId = null)
    {
        $query = Payout::with('vendor:id,name,email');

        if ($status) {
            $query->where('status', $status);
        }

        if ($vendorId) {
            $query->where('vendor_id', $vendorId);
        }

        return $query->latest()->get();
    }

    /**
     * Process bulk payout approvals (admin).
     */
    public function bulkApprovePayouts($payoutIds, $adminId)
    {
        $approved = [];
        $failed = [];

        foreach ($payoutIds as $payoutId) {
            try {
                $this->approvePayout($payoutId, $adminId);
                $approved[] = $payoutId;
            } catch (\Exception $e) {
                $failed[$payoutId] = $e->getMessage();
            }
        }

        return [
            'approved' => $approved,
            'failed' => $failed,
            'total_approved' => count($approved),
            'total_failed' => count($failed),
        ];
    }

    /**
     * Get payout statistics for admin dashboard.
     */
    public function getPayoutStatistics()
    {
        return [
            'pending_count' => Payout::where('status', 'pending')->count(),
            'pending_amount' => Payout::where('status', 'pending')->sum('amount'),
            'processing_count' => Payout::where('status', 'processing')->count(),
            'processing_amount' => Payout::where('status', 'processing')->sum('amount'),
            'paid_today' => Payout::where('status', 'paid')
                ->whereDate('paid_at', Carbon::today())
                ->sum('amount'),
            'paid_this_month' => Payout::where('status', 'paid')
                ->whereMonth('paid_at', Carbon::now()->month)
                ->sum('amount'),
        ];
    }
}
