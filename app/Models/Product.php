<?php
namespace App\Models;

use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the vendor (same as user) that owns this product.
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }

    public function reviews()
    {
        return $this->hasManyAs(Review::class, 'product_id');
    }

    // Fix: hasManyAs is not correct, it's just hasMany
    public function productReviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getStatusAttribute($value)
    {
        return $value === 'active' ? 'Active' : 'Inactive';
    }
    public function favorite()
{
    return $this->hasOne(Favorite::class)->where('user_id', auth()->id());
}

    public function orderItems()
    {
        return $this->hasMany(ItemsOrder::class, 'product_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $latestId = Product::max('id') + 1;
            // SKU Format: V{VendorID}-P{NextID}-{RandomSuffix}
            // Example: V5-P123-A7B
            $vendorId = $product->user_id ?? auth()->id() ?? 1; // Fallback to 1 if no user
            $product->sku = 'V' . $vendorId . '-P' . $latestId . '-' . strtoupper(\Illuminate\Support\Str::random(3));
        });
    }

    public function getImageUrlAttribute()
    {
        return $this->productImages->first() ? asset($this->productImages->first()->image) : null;
    }

    /**
     * Get the vendor name for "Sold by" display.
     */
    public function getSoldByNameAttribute()
    {
        return $this->vendor ? $this->vendor->name : 'Phyzioline';
    }

    /**
     * Get full SKU with vendor prefix.
     */
    public function getFullSkuAttribute()
    {
        return $this->sku;
    }

    /**
     * Get the product price formatted in the user's currency.
     */
    public function getFormattedPriceAttribute()
    {
        $price = (float) $this->product_price;
        $service = new \App\Services\CurrencyService();
        return $service->format($price);
    }

    public function getAverageRatingAttribute()
    {
        return $this->productReviews()->avg('rating') ?: 0;
    }

    public function getReviewCountAttribute()
    {
        return $this->productReviews()->count();
    }

    /**
     * Get product metrics.
     */
    public function metrics()
    {
        return $this->hasOne(ProductMetric::class);
    }

    /**
     * Get product badges.
     */
    public function badges()
    {
        return $this->hasMany(ProductBadge::class)->active()->orderBy('priority', 'desc');
    }

    /**
     * Get primary badge (highest priority).
     */
    public function getPrimaryBadgeAttribute()
    {
        return $this->badges()->first();
    }

    /**
     * Check if product has low stock.
     */
    public function isLowStock($threshold = 10)
    {
        return $this->amount <= $threshold && $this->amount > 0;
    }

    /**
     * Get stock urgency message.
     */
    public function getStockUrgencyMessage()
    {
        if ($this->amount <= 0) {
            return null;
        }
        if ($this->amount <= 3) {
            return "Only {$this->amount} left in stock";
        }
        if ($this->amount <= 10) {
            return "Only {$this->amount} left in stock";
        }
        return null;
    }
}
