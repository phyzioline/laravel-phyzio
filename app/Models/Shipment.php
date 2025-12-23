<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'order_id',
        'vendor_id',
        'vendor_name',
        'vendor_phone',
        'vendor_address',
        'vendor_city',
        'vendor_governorate',
        'vendor_lat',
        'vendor_lng',
        'customer_name',
        'customer_phone',
        'customer_address',
        'customer_city',
        'customer_governorate',
        'customer_lat',
        'customer_lng',
        'courier',
        'shipping_provider',
        'shipping_provider_id',
        'tracking_number',
        'shipping_label_url',
        'shipping_cost',
        'shipping_method',
        'package_weight',
        'package_length',
        'package_width',
        'package_height',
        'package_description',
        'shipment_status',
        'shipped_at',
        'ready_to_ship_at',
        'picked_up_at',
        'in_transit_at',
        'out_for_delivery_at',
        'delivered_at',
        'exception_at',
        'exception_reason',
        'delivered_to',
        'delivery_notes',
        'notes',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'ready_to_ship_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'in_transit_at' => 'datetime',
        'out_for_delivery_at' => 'datetime',
        'delivered_at' => 'datetime',
        'exception_at' => 'datetime',
        'vendor_lat' => 'decimal:7',
        'vendor_lng' => 'decimal:7',
        'customer_lat' => 'decimal:7',
        'customer_lng' => 'decimal:7',
        'shipping_cost' => 'decimal:2',
        'package_weight' => 'integer',
        'package_length' => 'integer',
        'package_width' => 'integer',
        'package_height' => 'integer',
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
