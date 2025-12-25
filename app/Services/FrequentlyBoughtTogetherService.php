<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ItemsOrder;
use Illuminate\Support\Facades\DB;

class FrequentlyBoughtTogetherService
{
    /**
     * Get products frequently bought together with the given product.
     */
    public function getFrequentlyBoughtTogether($productId, $limit = 4)
    {
        // Find orders that contain this product
        $orderIds = ItemsOrder::where('product_id', $productId)
            ->pluck('order_id')
            ->unique();
        
        if ($orderIds->isEmpty()) {
            return collect([]);
        }
        
        // Find other products in those same orders
        $relatedProductIds = ItemsOrder::whereIn('order_id', $orderIds)
            ->where('product_id', '!=', $productId)
            ->select('product_id', DB::raw('COUNT(*) as frequency'))
            ->groupBy('product_id')
            ->orderBy('frequency', 'desc')
            ->limit($limit)
            ->pluck('product_id');
        
        if ($relatedProductIds->isEmpty()) {
            // Fallback: Get products from same category
            $product = Product::find($productId);
            if ($product) {
                return Product::where('category_id', $product->category_id)
                    ->where('id', '!=', $productId)
                    ->where('status', 'active')
                    ->where('amount', '>', 0)
                    ->with(['badges', 'metrics', 'productImages'])
                    ->limit($limit)
                    ->get();
            }
            return collect([]);
        }
        
        // Get the products with their details
        return Product::whereIn('id', $relatedProductIds)
            ->where('status', 'active')
            ->where('amount', '>', 0)
            ->with(['badges', 'metrics', 'productImages'])
            ->get()
            ->sortBy(function($product) use ($relatedProductIds) {
                // Maintain the frequency order
                return array_search($product->id, $relatedProductIds->toArray());
            })
            ->values();
    }
}

