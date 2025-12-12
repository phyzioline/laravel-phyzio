<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total',
        'status', 
        'name',
        'address',
        'payment_method',
        'payment_id',
        'payment_type',
        'phone',
        'payment_status',
        'order_number',
        'commission_total',
        'shipping_total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ItemsOrder::class);
    }

    public function vendorPayments()
    {
        return $this->hasMany(VendorPayment::class);
    }

    /**
     * Generate unique order number.
     */
    public static function generateOrderNumber()
    {
        $date = date('Ymd');
        $prefix = 'PHZ-' . $date . '-';
        
        // Get the last order number for today
        $lastOrder = self::where('order_number', 'LIKE', $prefix . '%')
            ->orderBy('order_number', 'desc')
            ->first();
        
        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->order_number, -5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get total commission for this order.
     */
    public function getTotalCommission()
    {
        return $this->items->sum('commission_amount');
    }

    /**
     * Get total shipping for this order.
     */
    public function getTotalShipping()
    {
        return $this->items->sum('shipping_fee');
    }

    /**
     * Boot method to auto-generate order number.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }
}
