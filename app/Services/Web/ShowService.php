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
        
        $query = Product::where('amount', '>', '0')
            ->where('status', 'active')
            ->with(['badges', 'metrics']);

        if ($request && $request->has('category') && $request->category != null) {
            $query->where('category_id', $request->category);
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
        $product = Product::with(['badges', 'metrics'])->where('id', $id)->firstOrFail();
        
        // Track product view
        $this->trackProductView($id);
        
        // Related products with conversion-based ranking
        $products = Product::where('sub_category_id', $product->sub_category_id)
            ->where('id', '!=', $id)
            ->where('status', 'active')
            ->with(['badges', 'metrics'])
            ->leftJoin('product_metrics', 'products.id', '=', 'product_metrics.product_id')
            ->select('products.*')
            ->orderByRaw('(product_metrics.conversion_rate * product_metrics.velocity) DESC')
            ->limit(8)
            ->get();
            
        return view('web.pages.showDetails', compact('product', 'products'));
    }
    public function ProductBySubCategory($id)
    {
        $products = Product::where('sub_category_id', $id)
            ->where('amount','>' ,'0')->where('status', 'active')
            ->paginate(50)
            ->withQueryString();
        $count_product = Product::where('amount','>' ,'0')->where('sub_category_id', $id)->where('status','active')->count();

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
