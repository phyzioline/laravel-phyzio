<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingLog extends Model
{
    public $timestamps = false; // Using only created_at
    
    protected $fillable = [
        'shipment_id',
        'status',
        'location',
        'description',
        'source',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the shipment that owns the tracking log.
     */
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Scope for API-sourced logs.
     */
    public function scopeFromApi($query)
    {
        return $query->where('source', 'api');
    }

    /**
     * Scope for manual logs.
     */
    public function scopeManual($query)
    {
        return $query->where('source', 'manual');
    }
}
