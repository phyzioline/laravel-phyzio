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
     * Also creates EarningsTransaction record for tracking.
     * 
     * @param int $therapistId
     * @param float $amount Gross amount (before platform fee)
     * @param int $holdDays Number of days to hold in pending (default 14)
     * @param string $source Source of earnings: 'home_visit', 'course', or 'clinic'
     * @param string|null $sourceType Model class name (e.g., 'App\Models\HomeVisit')
     * @param int|null $sourceId ID of the source record
     * @param float|null $platformFee Platform commission (if null, calculates 15%)
     * @return TherapistWallet
     */
    public function addEarnings($therapistId, $amount, $holdDays = 14, $source = 'home_visit', $sourceType = null, $sourceId = null, $platformFee = null)
    {
        $wallet = $this->getTherapistWallet($therapistId);
        
        // Calculate platform fee if not provided (default 15%)
        if ($platformFee === null) {
            $platformFee = ($amount * 15) / 100;
        }
        
        $netEarnings = $amount - $platformFee;
        $holdUntil = $holdDays > 0 ? Carbon::now()->addDays($holdDays) : null;
        
        // Create EarningsTransaction record
        $transaction = \App\Models\EarningsTransaction::create([
            'user_id' => $therapistId,
            'source' => $source,
            'source_type' => $sourceType ?? $this->getSourceType($source),
            'source_id' => $sourceId ?? 0,
            'amount' => $amount,
            'platform_fee' => $platformFee,
            'net_earnings' => $netEarnings,
            'status' => $holdDays > 0 ? 'pending' : 'available',
            'hold_until' => $holdUntil,
        ]);
        
        if ($holdDays > 0) {
            // Add to pending (will be released after hold period)
            $wallet->addPending($netEarnings);
            Log::info("Added earnings to therapist {$therapistId} wallet: {$netEarnings} from {$source} (pending for {$holdDays} days). Transaction ID: {$transaction->id}");
        } else {
            // Add directly to available
            $wallet->available_balance += $netEarnings;
            $wallet->total_earned += $netEarnings;
            $wallet->save();
            Log::info("Added earnings to therapist {$therapistId} wallet: {$netEarnings} from {$source} (available immediately). Transaction ID: {$transaction->id}");
        }
        
        return $wallet;
    }
    
    /**
     * Get source type class name based on source string.
     */
    protected function getSourceType($source)
    {
        $map = [
            'home_visit' => \App\Models\HomeVisit::class,
            'course' => \App\Models\Enrollment::class,
            'clinic' => \App\Models\WeeklyProgram::class,
        ];
        
        return $map[$source] ?? 'App\Models\User';
    }

    /**
     * Process settlement - move pending balance to available after hold period.
     * This should be called by a daily cron job.
     * Now uses EarningsTransaction records for accurate settlement tracking.
     */
    public function processSettlements()
    {
        // Use EarningsSettlementService to process settlements
        $settlementService = app(\App\Services\EarningsSettlementService::class);
        $result = $settlementService->processSettlements();
        
        // Update wallet balances based on settled transactions
        $settledTransactions = \App\Models\EarningsTransaction::where('status', 'available')
            ->whereNotNull('settled_at')
            ->whereDate('settled_at', Carbon::today())
            ->get();
        
        foreach ($settledTransactions as $transaction) {
            $wallet = $this->getTherapistWallet($transaction->user_id);
            
            // Move from pending to available in wallet
            if ($wallet->pending_balance >= $transaction->net_earnings) {
                $wallet->releasePending($transaction->net_earnings);
                Log::info("Updated wallet for therapist {$transaction->user_id} after settlement of transaction #{$transaction->id}");
            }
        }
        
        return $result['settled_count'];
    }
}

