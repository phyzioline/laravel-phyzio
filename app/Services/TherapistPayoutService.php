<?php

namespace App\Services;

use App\Models\Payout;
use App\Models\PayoutSetting;
use App\Models\TherapistWallet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TherapistPayoutService
{
    /**
     * Get minimum payout amount from settings.
     */
    protected function getMinimumPayout()
    {
        $settings = PayoutSetting::getSettings();
        return (float) $settings->minimum_payout;
    }

    /**
     * Get or create therapist wallet.
     */
    public function getTherapistWallet($therapistId)
    {
        return TherapistWallet::firstOrCreate(
            ['therapist_id' => $therapistId],
            [
                'pending_balance' => 0,
                'available_balance' => 0,
                'on_hold_balance' => 0,
                'total_earned' => 0,
            ]
        );
    }

    /**
     * Request a payout (therapist initiates withdrawal).
     */
    public function requestPayout($therapistId, $amount, $payoutMethod, $notes = null, $profile = null)
    {
        return DB::transaction(function () use ($therapistId, $amount, $payoutMethod, $notes, $profile) {
            
            // Validate minimum amount
            $minimumPayout = $this->getMinimumPayout();
            if ($amount < $minimumPayout) {
                throw new \Exception("Minimum payout amount is " . $minimumPayout);
            }

            $wallet = $this->getTherapistWallet($therapistId);

            // Check if therapist has sufficient available balance
            if ($wallet->available_balance < $amount) {
                throw new \Exception("Insufficient available balance. Available: {$wallet->available_balance}");
            }

            // Deduct from available balance
            if (!$wallet->deductAvailable($amount)) {
                throw new \Exception("Failed to deduct from wallet");
            }

            // Build notes with bank details if bank transfer
            $payoutNotes = $notes;
            if ($payoutMethod === 'bank_transfer' && $profile) {
                $bankDetails = "Bank: {$profile->bank_name}\n";
                $bankDetails .= "Account Name: {$profile->bank_account_name}\n";
                $bankDetails .= "IBAN: {$profile->iban}\n";
                if ($profile->swift_code) {
                    $bankDetails .= "SWIFT: {$profile->swift_code}\n";
                }
                if ($notes) {
                    $bankDetails .= "\nNotes: {$notes}";
                }
                $payoutNotes = $bankDetails;
            }

            // Create payout request
            $payout = Payout::create([
                'therapist_id' => $therapistId,
                'amount' => $amount,
                'status' => 'pending',
                'payout_method' => $payoutMethod,
                'notes' => $payoutNotes,
            ]);

            Log::info("Payout requested by therapist {$therapistId}: Amount {$amount}, Method {$payoutMethod}");

            return $payout;
        });
    }

    /**
     * Get payout history for therapist.
     */
    public function getTherapistPayouts($therapistId, $status = null)
    {
        $query = Payout::where('therapist_id', $therapistId);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->get();
    }

    /**
     * Get wallet summary for therapist.
     */
    public function getWalletSummary($therapistId)
    {
        $wallet = $this->getTherapistWallet($therapistId);
        
        return [
            'pending_balance' => $wallet->pending_balance,
            'available_balance' => $wallet->available_balance,
            'on_hold_balance' => $wallet->on_hold_balance,
            'total_earned' => $wallet->total_earned,
            'total_balance' => $wallet->pending_balance + $wallet->available_balance + $wallet->on_hold_balance,
        ];
    }

    /**
     * Add earnings to wallet (when visit is completed and paid, or course is purchased).
     * 
     * @param int $therapistId
     * @param float $amount
     * @param int $holdDays Number of days to hold in pending (default 14)
     * @param string $source Source of earnings: 'home_visit' or 'course'
     * @return TherapistWallet
     */
    public function addEarnings($therapistId, $amount, $holdDays = 14, $source = 'home_visit')
    {
        $wallet = $this->getTherapistWallet($therapistId);
        
        if ($holdDays > 0) {
            // Add to pending (will be released after hold period)
            $wallet->addPending($amount);
            Log::info("Added earnings to therapist {$therapistId} wallet: {$amount} from {$source} (pending for {$holdDays} days)");
        } else {
            // Add directly to available
            $wallet->available_balance += $amount;
            $wallet->total_earned += $amount;
            $wallet->save();
            Log::info("Added earnings to therapist {$therapistId} wallet: {$amount} from {$source} (available immediately)");
        }
        
        return $wallet;
    }

    /**
     * Process settlement - move pending balance to available after hold period.
     * This should be called by a daily cron job.
     */
    public function processSettlements()
    {
        // Get all wallets with pending balance
        $wallets = TherapistWallet::where('pending_balance', '>', 0)->get();
        $processed = 0;

        foreach ($wallets as $wallet) {
            // For now, we'll release all pending after 14 days
            // In a real system, you'd track when each amount was added
            // For simplicity, we'll move all pending to available
            if ($wallet->pending_balance > 0) {
                $amount = $wallet->pending_balance;
                if ($wallet->releasePending($amount)) {
                    $processed++;
                    Log::info("Settled {$amount} for therapist {$wallet->therapist_id}");
                }
            }
        }

        return $processed;
    }
}

