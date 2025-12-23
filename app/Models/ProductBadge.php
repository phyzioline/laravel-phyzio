<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBadge extends Model
{
    protected $fillable = [
        'product_id',
        'badge_type',
        'priority',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'date',
    ];

    /**
     * Get the product that owns the badge.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope for active badges.
     */
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Get badge label.
     */
    public function getLabelAttribute()
    {
        return match($this->badge_type) {
            'best_seller' => 'Best Seller',
            'top_clinic_choice' => 'Top Clinic Choice',
            'physio_recommended' => 'Physio Recommended',
            'fast_moving' => 'Fast Moving',
            'new_arrival' => 'New Arrival',
            'trending' => 'Trending',
            default => 'Featured',
        };
    }
}

