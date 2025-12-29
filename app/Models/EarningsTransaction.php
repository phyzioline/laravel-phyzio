<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EarningsTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'source',
        'source_type',
        'source_id',
        'amount',
        'platform_fee',
        'net_earnings',
        'status',
        'hold_until',
        'settled_at',
        'payout_id',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'net_earnings' => 'decimal:2',
        'hold_until' => 'date',
        'settled_at' => 'date',
    ];

    /**
     * Get the user who earned this.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the source model (polymorphic).
     */
    public function sourceModel()
    {
        return $this->morphTo('source', 'source_type', 'source_id');
    }

    /**
     * Get the payout if linked.
     */
    public function payout(): BelongsTo
    {
        return $this->belongsTo(Payout::class);
    }

    /**
     * Scope for filtering by source.
     */
    public function scopeBySource($query, string $source)
    {
        return $query->where('source', $source);
    }

    /**
     * Scope for filtering by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}

