<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'order_id',
        'vendor_id',
        'courier',
        'tracking_number',
        'shipment_status',
        'shipped_at',
        'delivered_at',
        'notes',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Get the order that owns the shipment.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the vendor that owns the shipment.
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Get the items in this shipment.
     */
    public function items()
    {
        return $this->hasMany(ItemsOrder::class, 'shipment_id');
    }

    /**
     * Get the tracking logs for this shipment.
     */
    public function trackingLogs()
    {
        return $this->hasMany(TrackingLog::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the return request for this shipment.
     */
    public function return()
    {
        return $this->hasOne(ReturnModel::class);
    }

    /**
     * Check if shipment is delivered.
     */
    public function isDelivered()
    {
        return $this->shipment_status === 'delivered';
    }

    /**
     * Check if shipment has tracking.
     */
    public function hasTracking()
    {
        return !empty($this->tracking_number) && !empty($this->courier);
    }
}
