<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnModel extends Model
{
    protected $table = 'returns';
    
    protected $fillable = [
        'order_item_id',
        'shipment_id',
        'reason',
        'status',
        'refund_amount',
        'approved_by',
        'approved_at',
        'resolved_at',
        'admin_notes',
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the order item that is being returned.
     */
    public function orderItem()
    {
        return $this->belongsTo(ItemsOrder::class, 'order_item_id');
    }

    /**
     * Get the shipment related to this return.
     */
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Get the admin who approved the return.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope for pending returns.
     */
    public function scopeRequested($query)
    {
        return $query->where('status', 'requested');
    }

    /**
     * Scope for approved returns.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Check if return is pending.
     */
    public function isRequested()
    {
        return $this->status === 'requested';
    }

    /**
     * Check if return is approved.
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if return is refunded.
     */
    public function isRefunded()
    {
        return $this->status === 'refunded';
    }
}
