<?php

namespace App\Services;

use App\Models\EarningsTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EarningsSettlementService
{
    /**
     * Process settlements for all eligible earnings transactions.
     * Moves pending earnings to available after hold period expires.
     * 
     * @return array Statistics about processed settlements
     */
    public function processSettlements(): array
    {
        $now = Carbon::now()->startOfDay();
        
        // Get all earnings transactions where hold period has expired
        $eligibleTransactions = EarningsTransaction::where('status', 'pending')
            ->whereNotNull('hold_until')
            ->where('hold_until', '<=', $now)
            ->with('user')
            ->get();
        
        $settledCount = 0;
        $totalAmount = 0;
        $errors = [];
        
        foreach ($eligibleTransactions as $transaction) {
            try {
                DB::beginTransaction();
                
                // Update transaction status to available
                $transaction->update([
                    'status' => 'available',
                    'settled_at' => Carbon::now(),
                ]);
                
                $settledCount++;
                $totalAmount += $transaction->net_earnings;
                
                Log::info("Settled earnings transaction #{$transaction->id} for user #{$transaction->user_id}: {$transaction->net_earnings}");
                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $errorMsg = "Settlement failed for transaction #{$transaction->id}: " . $e->getMessage();
                $errors[] = $errorMsg;
                Log::error($errorMsg);
            }
        }
        
        if ($settledCount > 0) {
            Log::info("Processed {$settledCount} earnings settlements. Total amount: {$totalAmount}");
        }
        
        return [
            'settled_count' => $settledCount,
            'total_amount' => $totalAmount,
            'errors' => $errors,
        ];
    }
    
    /**
     * Get statistics about pending settlements.
     * 
     * @return array
     */
    public function getSettlementStats(): array
    {
        $now = Carbon::now();
        $today = Carbon::today();
        $nextWeek = Carbon::today()->addWeek();
        
        // Transactions ready to settle today
        $readyToday = EarningsTransaction::where('status', 'pending')
            ->whereNotNull('hold_until')
            ->where('hold_until', '<=', $today)
            ->count();
        
        // Transactions settling this week
        $settlingThisWeek = EarningsTransaction::where('status', 'pending')
            ->whereNotNull('hold_until')
            ->whereBetween('hold_until', [$today, $nextWeek])
            ->count();
        
        // Total pending amount
        $totalPending = EarningsTransaction::where('status', 'pending')
            ->sum('net_earnings');
        
        // Total available amount
        $totalAvailable = EarningsTransaction::where('status', 'available')
            ->sum('net_earnings');
        
        // Next settlement date
        $nextSettlement = EarningsTransaction::where('status', 'pending')
            ->whereNotNull('hold_until')
            ->where('hold_until', '>', $now)
            ->orderBy('hold_until', 'asc')
            ->value('hold_until');
        
        return [
            'ready_today' => $readyToday,
            'settling_this_week' => $settlingThisWeek,
            'total_pending' => $totalPending,
            'total_available' => $totalAvailable,
            'next_settlement_date' => $nextSettlement,
        ];
    }
    
    /**
     * Manually settle a specific transaction (admin action).
     * 
     * @param int $transactionId
     * @param string|null $notes
     * @return bool
     */
    public function manualSettle(int $transactionId, ?string $notes = null): bool
    {
        try {
            $transaction = EarningsTransaction::findOrFail($transactionId);
            
            if ($transaction->status !== 'pending') {
                throw new \Exception("Transaction is not in pending status");
            }
            
            DB::beginTransaction();
            
            $transaction->update([
                'status' => 'available',
                'settled_at' => Carbon::now(),
                'notes' => $notes ? ($transaction->notes ? $transaction->notes . "\n" . $notes : $notes) : $transaction->notes,
            ]);
            
            Log::info("Manually settled earnings transaction #{$transactionId} by admin");
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Manual settlement failed for transaction #{$transactionId}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Put a transaction on hold (admin action for disputes).
     * 
     * @param int $transactionId
     * @param string $reason
     * @return bool
     */
    public function putOnHold(int $transactionId, string $reason): bool
    {
        try {
            $transaction = EarningsTransaction::findOrFail($transactionId);
            
            if (!in_array($transaction->status, ['pending', 'available'])) {
                throw new \Exception("Transaction cannot be put on hold in current status");
            }
            
            DB::beginTransaction();
            
            $transaction->update([
                'status' => 'on_hold',
                'notes' => $transaction->notes 
                    ? $transaction->notes . "\n[On Hold: " . Carbon::now()->format('Y-m-d H:i:s') . "] " . $reason
                    : "[On Hold: " . Carbon::now()->format('Y-m-d H:i:s') . "] " . $reason,
            ]);
            
            Log::info("Put earnings transaction #{$transactionId} on hold. Reason: {$reason}");
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to put transaction #{$transactionId} on hold: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Release a transaction from hold.
     * 
     * @param int $transactionId
     * @return bool
     */
    public function releaseFromHold(int $transactionId): bool
    {
        try {
            $transaction = EarningsTransaction::findOrFail($transactionId);
            
            if ($transaction->status !== 'on_hold') {
                throw new \Exception("Transaction is not on hold");
            }
            
            DB::beginTransaction();
            
            // Determine new status based on hold_until date
            $newStatus = ($transaction->hold_until && $transaction->hold_until->isPast())
                ? 'available'
                : 'pending';
            
            $transaction->update([
                'status' => $newStatus,
                'notes' => $transaction->notes 
                    ? $transaction->notes . "\n[Released from hold: " . Carbon::now()->format('Y-m-d H:i:s') . "]"
                    : "[Released from hold: " . Carbon::now()->format('Y-m-d H:i:s') . "]",
            ]);
            
            Log::info("Released earnings transaction #{$transactionId} from hold");
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to release transaction #{$transactionId} from hold: " . $e->getMessage());
            return false;
        }
    }
}

