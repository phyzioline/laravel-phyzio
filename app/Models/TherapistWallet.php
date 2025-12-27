<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TherapistWallet extends Model
{
    protected $fillable = [
        'therapist_id',
        'pending_balance',
        'available_balance',
        'on_hold_balance',
        'total_earned',
    ];

    protected $casts = [
        'pending_balance' => 'decimal:2',
        'available_balance' => 'decimal:2',
        'on_hold_balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
    ];

    /**
     * Get the therapist that owns the wallet.
     */
    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }

    /**
     * Get all payouts for this wallet.
     */
    public function payouts()
    {
        return $this->hasMany(Payout::class, 'therapist_id', 'therapist_id');
    }

    /**
     * Add amount to pending balance.
     */
    public function addPending($amount)
    {
        $this->pending_balance += $amount;
        $this->total_earned += $amount;
        $this->save();
    }

    /**
     * Move amount from pending to available.
     */
    public function releasePending($amount)
    {
        if ($this->pending_balance >= $amount) {
            $this->pending_balance -= $amount;
            $this->available_balance += $amount;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Deduct amount from available balance (for payout).
     */
    public function deductAvailable($amount)
    {
        if ($this->available_balance >= $amount) {
            $this->available_balance -= $amount;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Freeze amount from pending to on-hold.
     */
    public function freezeAmount($amount)
    {
        if ($this->pending_balance >= $amount) {
            $this->pending_balance -= $amount;
            $this->on_hold_balance += $amount;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Release frozen amount back to pending.
     */
    public function unfreezeAmount($amount)
    {
        if ($this->on_hold_balance >= $amount) {
            $this->on_hold_balance -= $amount;
            $this->pending_balance += $amount;
            $this->save();
            return true;
        }
        return false;
    }
}

