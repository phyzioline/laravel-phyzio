<?php
namespace App\Models;

use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    /**
     * Fix encoding issues for product names and descriptions
     */
    protected function fixEncoding($text)
    {
        if (empty($text)) {
            return $text;
        }

        // If it's already a string, ensure it's properly encoded
        if (!is_string($text)) {
            $text = (string) $text;
        }

        // Get list of supported encodings
        $supportedEncodings = mb_list_encodings();
        
        // Check if text is already valid UTF-8
        if (mb_check_encoding($text, 'UTF-8')) {
            // Check if it looks like valid Arabic (contains Arabic character patterns)
            // Arabic Unicode range: U+0600 to U+06FF
            if (preg_match('/[\x{0600}-\x{06FF}]/u', $text)) {
                // Already has Arabic characters, likely correct
                return $text;
            }
            
            // Detect garbled text patterns (common in double/triple encoding)
            // Patterns: å, Ç, Ê, Ï, æ, á, ã, ä, Ó, Ñ, etc.
            $garbledPattern = '/[åÇÊÏæáãäÓÑÎÔØ]/u';
            $hasGarbledChars = preg_match($garbledPattern, $text);
            
            if ($hasGarbledChars && !preg_match('/[\x{0600}-\x{06FF}]/u', $text)) {
                // Try multiple encoding fix strategies
                $fixStrategies = [
                    // Strategy 1: Treat as ISO-8859-1 -> Windows-1256 -> UTF-8
                    function($t) use ($supportedEncodings) {
                        if (in_array('ISO-8859-1', $supportedEncodings) && in_array('Windows-1256', $supportedEncodings)) {
                            $step1 = @mb_convert_encoding($t, 'Windows-1256', 'ISO-8859-1');
                            if ($step1) {
                                $step2 = @mb_convert_encoding($step1, 'UTF-8', 'Windows-1256');
                                if ($step2 && preg_match('/[\x{0600}-\x{06FF}]/u', $step2)) {
                                    return $step2;
                                }
                            }
                        }
                        return null;
                    },
                    // Strategy 2: Treat as Windows-1252 -> Windows-1256 -> UTF-8
                    function($t) use ($supportedEncodings) {
                        if (in_array('Windows-1252', $supportedEncodings) && in_array('Windows-1256', $supportedEncodings)) {
                            $step1 = @mb_convert_encoding($t, 'Windows-1256', 'Windows-1252');
                            if ($step1) {
                                $step2 = @mb_convert_encoding($step1, 'UTF-8', 'Windows-1256');
                                if ($step2 && preg_match('/[\x{0600}-\x{06FF}]/u', $step2)) {
                                    return $step2;
                                }
                            }
                        }
                        return null;
                    },
                    // Strategy 3: Direct Windows-1256 conversion (if text was misread)
                    function($t) use ($supportedEncodings) {
                        if (in_array('Windows-1256', $supportedEncodings)) {
                            // Try encoding to Windows-1256 then back to UTF-8
                            $encoded = @mb_convert_encoding($t, 'Windows-1256', 'UTF-8');
                            if ($encoded) {
                                $decoded = @mb_convert_encoding($encoded, 'UTF-8', 'Windows-1256');
                                if ($decoded && preg_match('/[\x{0600}-\x{06FF}]/u', $decoded)) {
                                    return $decoded;
                                }
                            }
                        }
                        return null;
                    },
                    // Strategy 4: ISO-8859-1 -> ISO-8859-6 -> UTF-8
                    function($t) use ($supportedEncodings) {
                        if (in_array('ISO-8859-1', $supportedEncodings) && in_array('ISO-8859-6', $supportedEncodings)) {
                            $step1 = @mb_convert_encoding($t, 'ISO-8859-6', 'ISO-8859-1');
                            if ($step1) {
                                $step2 = @mb_convert_encoding($step1, 'UTF-8', 'ISO-8859-6');
                                if ($step2 && preg_match('/[\x{0600}-\x{06FF}]/u', $step2)) {
                                    return $step2;
                                }
                            }
                        }
                        return null;
                    },
                    // Strategy 5: Try iconv as fallback
                    function($t) {
                        if (function_exists('iconv')) {
                            // Try iconv with error handling
                            $result = @iconv('ISO-8859-1', 'UTF-8//IGNORE', $t);
                            if ($result && preg_match('/[\x{0600}-\x{06FF}]/u', $result)) {
                                return $result;
                            }
                            // Try Windows-1256
                            $result = @iconv('Windows-1256', 'UTF-8//IGNORE', $t);
                            if ($result && preg_match('/[\x{0600}-\x{06FF}]/u', $result)) {
                                return $result;
                            }
                        }
                        return null;
                    },
                ];
                
                // Try each strategy
                foreach ($fixStrategies as $strategy) {
                    $fixed = $strategy($text);
                    if ($fixed && mb_check_encoding($fixed, 'UTF-8') && preg_match('/[\x{0600}-\x{06FF}]/u', $fixed)) {
                        return $fixed;
                    }
                }
            }
            
            // Try to detect if it's Windows-1256 (Arabic Windows encoding) misread as UTF-8
            if (in_array('Windows-1256', $supportedEncodings)) {
                $windows1256 = @mb_convert_encoding($text, 'UTF-8', 'Windows-1256');
                if ($windows1256 && preg_match('/[\x{0600}-\x{06FF}]/u', $windows1256)) {
                    return $windows1256;
                }
            }
            
            return $text;
        }

        // Try to convert from various encodings (common Arabic encodings first)
        $encodingsToTry = ['Windows-1256', 'ISO-8859-6', 'CP1256', 'UTF-8', 'ISO-8859-1', 'Windows-1252'];
        $encodings = array_intersect($encodingsToTry, $supportedEncodings);
        
        if (!in_array('UTF-8', $encodings)) {
            array_unshift($encodings, 'UTF-8');
        }
        
        foreach ($encodings as $encoding) {
            $converted = @mb_convert_encoding($text, 'UTF-8', $encoding);
            if ($converted && mb_check_encoding($converted, 'UTF-8')) {
                if (strlen($converted) > 0) {
                    return $converted;
                }
            }
        }

        // Last resort: clean and force UTF-8
        $cleaned = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        $cleaned = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $cleaned);
        return $cleaned;
    }

    /**
     * Accessor for product_name_ar
     */
    public function getProductNameArAttribute($value)
    {
        return $this->fixEncoding($value ?? '');
    }

    /**
     * Accessor for product_name_en
     */
    public function getProductNameEnAttribute($value)
    {
        return $this->fixEncoding($value ?? '');
    }

    /**
     * Accessor for short_description_ar
     */
    public function getShortDescriptionArAttribute($value)
    {
        return $this->fixEncoding($value ?? '');
    }

    /**
     * Accessor for short_description_en
     */
    public function getShortDescriptionEnAttribute($value)
    {
        return $this->fixEncoding($value ?? '');
    }

    /**
     * Accessor for long_description_ar
     */
    public function getLongDescriptionArAttribute($value)
    {
        return $this->fixEncoding($value ?? '');
    }

    /**
     * Accessor for long_description_en
     */
    public function getLongDescriptionEnAttribute($value)
    {
        return $this->fixEncoding($value ?? '');
    }

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

