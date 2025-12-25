<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
        'is_approved', // Optional moderation
        'verified_purchase', // Verified purchase badge
    ];
    
    protected $casts = [
        'is_approved' => 'boolean',
        'verified_purchase' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * Check if this review is from a verified purchase.
     */
    public function isVerifiedPurchase()
    {
        if ($this->verified_purchase) {
            return true;
        }
        
        // Check if user has purchased this product
        return \App\Models\ItemsOrder::whereHas('order', function($q) {
                $q->where('user_id', $this->user_id)
                  ->where('payment_status', 'paid');
            })
            ->where('product_id', $this->product_id)
            ->exists();
    }
}
