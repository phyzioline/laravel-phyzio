<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMetric extends Model
{
    protected $fillable = [
        'product_id',
        'views',
        'clicks',
        'add_to_cart_count',
        'purchases',
        'conversion_rate',
        'velocity',
        'total_sales',
        'revenue',
        'last_sale_date',
    ];

    protected $casts = [
        'conversion_rate' => 'decimal:2',
        'velocity' => 'decimal:2',
        'revenue' => 'decimal:2',
        'last_sale_date' => 'date',
    ];

    /**
     * Get the product that owns the metrics.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Increment views.
     */
    public function incrementViews()
    {
        $this->increment('views');
        $this->updateConversionRate();
    }

    /**
     * Increment clicks.
     */
    public function incrementClicks()
    {
        $this->increment('clicks');
        $this->updateConversionRate();
    }

    /**
     * Increment add to cart.
     */
    public function incrementAddToCart()
    {
        $this->increment('add_to_cart_count');
        $this->updateConversionRate();
    }

    /**
     * Record a purchase.
     */
    public function recordPurchase($amount)
    {
        $this->increment('purchases');
        $this->increment('total_sales');
        $this->increment('revenue', $amount);
        $this->update([
            'last_sale_date' => now(),
        ]);
        $this->updateConversionRate();
        $this->updateVelocity();
    }

    /**
     * Update conversion rate.
     */
    protected function updateConversionRate()
    {
        if ($this->views > 0) {
            $this->conversion_rate = ($this->purchases / $this->views) * 100;
            $this->save();
        }
    }

    /**
     * Update velocity (sales per day).
     */
    protected function updateVelocity()
    {
        $daysSinceFirstSale = $this->created_at->diffInDays(now());
        if ($daysSinceFirstSale > 0) {
            $this->velocity = $this->total_sales / $daysSinceFirstSale;
            $this->velocity = $this->total_sales; // If created today, use total sales
        }
        $this->save();
    }
    
    /**
     * Get or create metrics for a product.
     */
    public static function getOrCreate($productId)
    {
        return static::firstOrCreate(
            ['product_id' => $productId],
            [
                'views' => 0,
                'clicks' => 0,
                'add_to_cart_count' => 0,
                'purchases' => 0,
                'conversion_rate' => 0,
                'velocity' => 0,
                'total_sales' => 0,
                'revenue' => 0,
            ]
        );
    }
}

