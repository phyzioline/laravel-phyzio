<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $fillable = [
        'vendor_id',
        'amount',
        'status',
        'payout_method',
        'reference_number',
        'notes',
        'approved_by',
        'approved_at',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the vendor that owns the payout.
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Get the admin who approved the payout.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope for pending payouts.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for paid payouts.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Check if payout is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payout is paid.
     */
    public function isPaid()
    {
        return $this->status === 'paid';
    }
}
