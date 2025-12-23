<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductBadge;
use App\Models\ProductMetric;
use Illuminate\Support\Facades\DB;

class ProductBadgeService
{
    /**
     * Assign badges based on sales data.
     */
    public function assignBadges()
    {
        // Best Seller: Top 10% by total sales
        $this->assignBestSeller();
        
        // Fast Moving: High velocity (sales per day)
        $this->assignFastMoving();
        
        // Top Clinic Choice: High conversion rate + reviews
        $this->assignTopClinicChoice();
        
        // Physio Recommended: High rating + verified purchases
        $this->assignPhysioRecommended();
    }

    /**
     * Assign Best Seller badge.
     */
    protected function assignBestSeller()
    {
        $topProducts = ProductMetric::orderBy('total_sales', 'desc')
            ->limit(DB::table('products')->count() * 0.1) // Top 10%
            ->pluck('product_id');
        
        foreach ($topProducts as $productId) {
            ProductBadge::updateOrCreate(
                [
                    'product_id' => $productId,
                    'badge_type' => 'best_seller',
                ],
                [
                    'priority' => 10,
                    'expires_at' => null, // Permanent
                ]
            );
        }
    }

    /**
     * Assign Fast Moving badge.
     */
    protected function assignFastMoving()
    {
        $fastProducts = ProductMetric::where('velocity', '>', 2) // More than 2 sales per day
            ->orderBy('velocity', 'desc')
            ->limit(20)
            ->pluck('product_id');
        
        foreach ($fastProducts as $productId) {
            ProductBadge::updateOrCreate(
                [
                    'product_id' => $productId,
                    'badge_type' => 'fast_moving',
                ],
                [
                    'priority' => 8,
                    'expires_at' => now()->addDays(30), // 30 days
                ]
            );
        }
    }

    /**
     * Assign Top Clinic Choice badge.
     */
    protected function assignTopClinicChoice()
    {
        $topProducts = ProductMetric::where('conversion_rate', '>', 5) // >5% conversion
            ->whereHas('product', function($q) {
                $q->where('review_count', '>', 10); // At least 10 reviews
            })
            ->orderBy('conversion_rate', 'desc')
            ->limit(15)
            ->pluck('product_id');
        
        foreach ($topProducts as $productId) {
            ProductBadge::updateOrCreate(
                [
                    'product_id' => $productId,
                    'badge_type' => 'top_clinic_choice',
                ],
                [
                    'priority' => 9,
                    'expires_at' => now()->addDays(60), // 60 days
                ]
            );
        }
    }

    /**
     * Assign Physio Recommended badge.
     */
    protected function assignPhysioRecommended()
    {
        $recommended = Product::whereHas('productReviews', function($q) {
                $q->where('rating', '>=', 4); // 4+ stars
            })
            ->where('review_count', '>=', 5)
            ->orderBy('average_rating', 'desc')
            ->limit(20)
            ->pluck('id');
        
        foreach ($recommended as $productId) {
            ProductBadge::updateOrCreate(
                [
                    'product_id' => $productId,
                    'badge_type' => 'physio_recommended',
                ],
                [
                    'priority' => 7,
                    'expires_at' => now()->addDays(90), // 90 days
                ]
            );
        }
    }
}

