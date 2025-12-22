<?php

namespace App\Services;

use App\Models\VendorWallet;
use App\Models\VendorPayment;
use App\Models\ItemsOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletService
{
    /**
     * Default hold period in days (Amazon uses 14 days).
     */
    const HOLD_PERIOD_DAYS = 14;

    /**
     * Get or create vendor wallet.
     */
    public function getVendorWallet($vendorId)
    {
        return VendorWallet::firstOrCreate(
            ['vendor_id' => $vendorId],
            [
                'pending_balance' => 0,
                'available_balance' => 0,
                'on_hold_balance' => 0,
                'total_earned' => 0,
            ]
        );
    }

    /**
     * Calculate commission for a vendor.
     */
    public function calculateCommission($amount, $vendorId)
    {
        $vendor = User::find($vendorId);
        
        // Check if vendor has custom commission rate (future feature)
        // For now, use the rate from items_orders default (15%)
        $commissionRate = 15.00; // Can be made dynamic per vendor
        
        return round(($amount * $commissionRate) / 100, 2);
    }

    /**
     * Add earnings to vendor's pending balance.
     * Called when order is placed (payment confirmed).
     */
    public function addToPending($vendorId, $amount, $orderId, $orderItemId = null)
    {
        return DB::transaction(function () use ($vendorId, $amount, $orderId, $orderItemId) {
            $wallet = $this->getVendorWallet($vendorId);
            
            // Add to pending balance
            $wallet->addPending($amount);
            
            // Update vendor payment record with hold date
            if ($orderItemId) {
                $vendorPayment = VendorPayment::where('order_item_id', $orderItemId)->first();
                if ($vendorPayment) {
                    $vendorPayment->update([
                        'hold_until' => Carbon::now()->addDays(self::HOLD_PERIOD_DAYS),
                        'settled' => false,
                    ]);
                }
            }
            
            Log::info("Added {$amount} to pending balance for vendor {$vendorId}");
            
            return $wallet;
        });
    }

    /**
     * Move pending to available (settlement).
     * Called after delivery + hold period expires.
     */
    public function settlePendingBalance($vendorId, $amount)
    {
        return DB::transaction(function () use ($vendorId, $amount) {
            $wallet = $this->getVendorWallet($vendorId);
            
            if ($wallet->releasePending($amount)) {
                Log::info("Settled {$amount} for vendor {$vendorId}");
                return true;
            }
            
            Log::warning("Failed to settle {$amount} for vendor {$vendorId} - insufficient pending balance");
            return false;
        });
    }

    /**
     * Process settlements for all eligible vendor payments.
     * Called by cron job daily.
     */
    public function processSettlements()
    {
        $now = Carbon::now();
        
        // Get all vendor payments where hold period has expired
        $eligiblePayments = VendorPayment::where('settled', false)
            ->where('status', 'pending')
            ->where('hold_until', '<=', $now)
            ->get();
        
        $settledCount = 0;
        
        foreach ($eligiblePayments as $payment) {
            try {
                if ($this->settlePendingBalance($payment->vendor_id, $payment->vendor_earnings)) {
                    $payment->update(['settled' => true]);
                    $settledCount++;
                }
            } catch (\Exception $e) {
                Log::error("Settlement failed for payment #{$payment->id}: " . $e->getMessage());
            }
        }
        
        Log::info("Processed {$settledCount} settlements");
        
        return $settledCount;
    }

    /**
     * Freeze balance for dispute/return.
     */
    public function freezeBalance($vendorId, $amount, $reason)
    {
        return DB::transaction(function () use ($vendorId, $amount, $reason) {
            $wallet = $this->getVendorWallet($vendorId);
            
            if ($wallet->freezeAmount($amount)) {
                Log::info("Froze {$amount} for vendor {$vendorId}. Reason: {$reason}");
                return true;
            }
            
            return false;
        });
    }

    /**
     * Unfreeze balance (dispute resolved in vendor's favor).
     */
    public function unfreezeBalance($vendorId, $amount)
    {
        return DB::transaction(function () use ($vendorId, $amount) {
            $wallet = $this->getVendorWallet($vendorId);
            
            if ($wallet->unfreezeAmount($amount)) {
                Log::info("Unfroze {$amount} for vendor {$vendorId}");
                return true;
            }
            
            return false;
        });
    }

    /**
     * Deduct from available balance (for payout).
     */
    public function deductForPayout($vendorId, $amount)
    {
        $wallet = $this->getVendorWallet($vendorId);
        
        if ($wallet->deductAvailable($amount)) {
            Log::info("Deducted {$amount} from available balance for vendor {$vendorId} payout");
            return true;
        }
        
        Log::warning("Failed to deduct {$amount} for vendor {$vendorId} - insufficient available balance");
        return false;
    }

    /**
     * Get wallet summary for vendor dashboard.
     */
    public function getWalletSummary($vendorId)
    {
        $wallet = $this->getVendorWallet($vendorId);
        
        // Get upcoming settlements
        $upcomingSettlements = VendorPayment::where('vendor_id', $vendorId)
            ->where('settled', false)
            ->where('status', 'pending')
            ->whereNotNull('hold_until')
            ->orderBy('hold_until', 'asc')
            ->get();
        
        return [
            'pending_balance' => $wallet->pending_balance,
            'available_balance' => $wallet->available_balance,
            'on_hold_balance' => $wallet->on_hold_balance,
            'total_earned' => $wallet->total_earned,
            'upcoming_settlements' => $upcomingSettlements,
            'next_settlement_date' => $upcomingSettlements->first()?->hold_until,
        ];
    }
}
