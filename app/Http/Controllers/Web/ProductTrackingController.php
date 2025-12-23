<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductMetric;
use Illuminate\Http\Request;

class ProductTrackingController extends Controller
{
    /**
     * Track product click.
     */
    public function trackClick($productId)
    {
        $metric = ProductMetric::firstOrCreate(
            ['product_id' => $productId],
            [
                'views' => 0,
                'clicks' => 0,
                'add_to_cart_count' => 0,
                'purchases' => 0,
            ]
        );
        
        $metric->incrementClicks();
        
        return response()->json(['success' => true]);
    }

    /**
     * Track add to cart.
     */
    public function trackAddToCart($productId)
    {
        $metric = ProductMetric::firstOrCreate(
            ['product_id' => $productId],
            [
                'views' => 0,
                'clicks' => 0,
                'add_to_cart_count' => 0,
                'purchases' => 0,
            ]
        );
        
        $metric->incrementAddToCart();
        
        return response()->json(['success' => true]);
    }
}

