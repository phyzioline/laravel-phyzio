<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorPayment extends Model
{
    protected $fillable = [
        'vendor_id',
        'order_id',
        'order_item_id',
        'product_amount',
        'quantity',
        'subtotal',
        'commission_rate',
        'commission_amount',
        'shipping_fee',
        'vendor_earnings',
        'status',
        'paid_at',
        'payment_reference'
    ];

    protected $casts = [
        'product_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'vendor_earnings' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

    /**
     * Get the vendor (user) that owns this payment.
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'id');
    }

    /**
     * Get the order this payment belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the order item this payment is for.
     */
    public function orderItem()
    {
        return $this->belongsTo(ItemsOrder::class, 'order_item_id', 'id');
    }

    /**
     * Scope to filter pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to filter paid payments.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope to filter by vendor.
     */
    public function scopeForVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }
}
