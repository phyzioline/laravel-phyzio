<?php
namespace App\Services\Web;

use App\Models\Category;
use App\Models\Product;

class ShowService
{

    public function show($request = null)
    {
        $categories = Category::where('status', 'active')
            ->with(['subCategories' => function ($query) {
                $query->where('status', 'active');
            }])
            ->get();
        
        $query = Product::where('products.amount', '>', '0')
            ->where('products.status', 'active')
            ->whereHas('user', function($q) {
                // Show products from:
                // 1. Buyers (no verification needed)
                // 2. Verified vendors/companies (approved and visible)
                // 3. Existing vendors/companies (grandfather clause - NULL or pending status from before migration)
                $q->where(function($subQ) {
                    $subQ->where('type', 'buyer')
                         ->orWhere(function($typeQ) {
                             $typeQ->whereIn('type', ['vendor', 'company'])
                                   ->where(function($statusQ) {
                                       // New verified users
                                       $statusQ->where(function($verified) {
                                           $verified->where('verification_status', 'approved')
                                                    ->where('profile_visibility', 'visible');
                                       })
                                       // OR existing users (grandfather clause)
                                       ->orWhereNull('verification_status')
                                       ->orWhere('verification_status', 'pending');
                                   });
                         });
                });
            })
            ->with(['badges', 'metrics', 'user']);

        if ($request && $request->has('category') && $request->category != null) {
            $query->where('products.category_id', $request->category);
        }

        // Conversion-based ranking (Amazon style)
        // Formula: Conversion Rate × Velocity × Total Sales
        $query->leftJoin('product_metrics', 'products.id', '=', 'product_metrics.product_id')
            ->select('products.*')
            ->orderByRaw('COALESCE(product_metrics.conversion_rate, 0) * COALESCE(product_metrics.velocity, 0) * COALESCE(product_metrics.total_sales, 0) DESC')
            ->orderBy('products.created_at', 'desc');

        $products = $query->paginate(50)->withQueryString();
        $count_product = $products->total();
        
        // Track views for metrics
        foreach ($products as $product) {
            $this->trackProductView($product->id);
        }

        
        return view('web.pages.show', compact('categories', 'products', 'count_product'));
    }

    public function product($id)
    {
        $product = Product::with(['badges', 'metrics', 'productReviews.user'])->where('id', $id)->firstOrFail();
        
        // Track product view
        $this->trackProductView($id);
        
        // Related products with conversion-based ranking
        $products = Product::where('products.sub_category_id', $product->sub_category_id)
            ->where('products.id', '!=', $id)
            ->where('products.status', 'active')
            ->with(['badges', 'metrics'])
            ->leftJoin('product_metrics', 'products.id', '=', 'product_metrics.product_id')
            ->select('products.*')
            ->orderByRaw('(product_metrics.conversion_rate * product_metrics.velocity) DESC')
            ->limit(8)
            ->get();
        
        // Frequently bought together
        $frequentlyBoughtTogether = (new \App\Services\FrequentlyBoughtTogetherService())
            ->getFrequentlyBoughtTogether($id, 4);
        
        // Delivery date prediction (default to Cairo)
        $deliveryService = new \App\Services\DeliveryDateService();
        $deliveryInfo = $deliveryService->getDeliveryMessage('Cairo', null, 'standard');
            
        return view('web.pages.showDetails', compact('product', 'products', 'frequentlyBoughtTogether', 'deliveryInfo'));
    }
    public function ProductBySubCategory($id)
    {
        $products = Product::where('sub_category_id', $id)
            ->where('amount','>' ,'0')
            ->where('status', 'active')
            ->whereHas('user', function($q) {
                // Same visibility logic as main product listing
                $q->where(function($subQ) {
                    $subQ->where('type', 'buyer')
                         ->orWhere(function($typeQ) {
                             $typeQ->whereIn('type', ['vendor', 'company'])
                                   ->where(function($statusQ) {
                                       $statusQ->where(function($verified) {
                                           $verified->where('verification_status', 'approved')
                                                    ->where('profile_visibility', 'visible');
                                       })
                                       ->orWhereNull('verification_status')
                                       ->orWhere('verification_status', 'pending');
                                   });
                         });
                });
            })
            ->paginate(50)
            ->withQueryString();
        $count_product = Product::where('amount','>' ,'0')
            ->where('sub_category_id', $id)
            ->where('status','active')
            ->whereHas('user', function($q) {
                $q->where(function($subQ) {
                    $subQ->where('type', 'buyer')
                         ->orWhere(function($typeQ) {
                             $typeQ->whereIn('type', ['vendor', 'company'])
                                   ->where(function($statusQ) {
                                       $statusQ->where(function($verified) {
                                           $verified->where('verification_status', 'approved')
                                                    ->where('profile_visibility', 'visible');
                                       })
                                       ->orWhereNull('verification_status')
                                       ->orWhere('verification_status', 'pending');
                                   });
                         });
                });
            })
            ->count();

        $categories = Category::with('subcategories')->get();

        return view('web.pages.show', compact('products', 'categories', 'count_product'));
    }

    /**
     * Track product view for metrics.
     */
    protected function trackProductView($productId)
    {
        $metric = \App\Models\ProductMetric::firstOrCreate(
            ['product_id' => $productId],
            [
                'views' => 0,
                'clicks' => 0,
                'add_to_cart_count' => 0,
                'purchases' => 0,
            ]
        );
        
        $metric->incrementViews();
    }

}
