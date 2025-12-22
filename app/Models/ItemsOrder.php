<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VendorPayment;

class ItemsOrder extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'vendor_id',
        'shipment_id',
        'quantity',
        'price',
        'total',
        'commission_rate',
        'commission_amount',
        'shipping_fee',
        'vendor_earnings',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'id');
    }

    public function vendorPayment()
    {
        return $this->hasOne(VendorPayment::class, 'order_item_id', 'id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function return()
    {
        return $this->hasOne(ReturnModel::class, 'order_item_id');
    }

    /**
     * Calculate commission amount based on price and rate.
     */
    public function calculateCommission()
    {
        $subtotal = $this->price * $this->quantity;
        return round(($subtotal * $this->commission_rate) / 100, 2);
    }

    /**
     * Calculate vendor earnings.
     */
    public function calculateVendorEarnings()
    {
        $subtotal = $this->price * $this->quantity;
        $commission = $this->calculateCommission();
        return round($subtotal - $commission, 2);
    }

    /**
     * Boot method to auto-populate vendor fields.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            // Auto-populate vendor_id from product
            if (empty($item->vendor_id) && $item->product) {
                $item->vendor_id = $item->product->user_id;
            }

            // Auto-calculate commission if not set
            if (empty($item->commission_amount)) {
                $item->commission_amount = $item->calculateCommission();
            }

            // Auto-calculate vendor earnings
            if (empty($item->vendor_earnings)) {
                $item->vendor_earnings = $item->calculateVendorEarnings();
            }
        });

        static::created(function ($item) {
            // Create vendor payment record only if table exists (migration-safe)
            try {
                if (\Schema::hasTable('vendor_payments')) {
                    VendorPayment::create([
                        'vendor_id' => $item->vendor_id,
                        'order_id' => $item->order_id,
                        'order_item_id' => $item->id,
                        'product_amount' => $item->price,
                        'quantity' => $item->quantity,
                        'subtotal' => $item->total,
                        'commission_rate' => $item->commission_rate,
                        'commission_amount' => $item->commission_amount,
                        'shipping_fee' => $item->shipping_fee ?? 0,
                        'vendor_earnings' => $item->vendor_earnings,
                        'status' => 'pending'
                    ]);
                }
            } catch (\Exception $e) {
                // Silently fail during migration - table might not exist yet
                \Log::info('VendorPayment creation skipped: ' . $e->getMessage());
            }
        });
    }
}
