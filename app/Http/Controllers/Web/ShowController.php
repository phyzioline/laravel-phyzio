<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Services\Web\ShowService;
use App\Http\Controllers\Controller;
use Spatie\Permission\Commands\Show;
use App\Models\Product;
use App\Models\Category;

class ShowController extends Controller
{
    public function __construct(public ShowService $showService){}
    
    public function search(Request $request)
{
    $keyword = $request->input('search');
    $categoryId = $request->input('category');
    $minPrice = $request->input('min_price');
    $maxPrice = $request->input('max_price');
    $tags = $request->input('tags', []);
    $minRating = $request->input('min_rating');
    $sortBy = $request->input('sort_by', 'relevance'); // relevance, price_asc, price_desc, newest, popularity

    $query = Product::where('status', 'active')
        ->where('amount', '>', '0')
        ->whereHas('user', function($q) {
            // Show products from:
            // 1. Buyers (no verification needed)
            // 2. Verified vendors/companies (approved and visible)
            // 3. Existing vendors/companies (grandfather clause)
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
        });

    // Keyword search
    if ($keyword) {
        $query->where(function($query) use ($keyword) {
            $query->where('product_name_en', 'LIKE', "%$keyword%")
                  ->orWhere('product_name_ar', 'LIKE', "%$keyword%")
                  ->orWhere('short_description_en', 'LIKE', "%$keyword%")
                  ->orWhere('short_description_ar', 'LIKE', "%$keyword%");
        });
    }

    // Category filter
    if ($categoryId) {
        $query->where('category_id', $categoryId);
    }

    // Price range filter
    if ($minPrice !== null) {
        $query->where('product_price', '>=', $minPrice);
    }
    if ($maxPrice !== null) {
        $query->where('product_price', '<=', $maxPrice);
    }

    // Tags filter
    if (!empty($tags) && is_array($tags)) {
        $query->whereHas('tags', function($q) use ($tags) {
            $q->whereIn('tags.id', $tags);
        });
    }

    // Rating filter
    if ($minRating !== null) {
        $query->whereHas('productReviews', function($q) use ($minRating) {
            $q->selectRaw('AVG(rating) as avg_rating')
              ->groupBy('product_id')
              ->havingRaw('AVG(rating) >= ?', [$minRating]);
        });
    }

    // Sorting
    switch ($sortBy) {
        case 'price_asc':
            $query->orderBy('product_price', 'asc');
            break;
        case 'price_desc':
            $query->orderBy('product_price', 'desc');
            break;
        case 'newest':
            $query->orderBy('created_at', 'desc');
            break;
        case 'popularity':
            // Sort by conversion rate * velocity * total sales
            $query->leftJoin('product_metrics', 'products.id', '=', 'product_metrics.product_id')
                ->select('products.*')
                ->orderByRaw('COALESCE(product_metrics.conversion_rate, 0) * COALESCE(product_metrics.velocity, 0) * COALESCE(product_metrics.total_sales, 0) DESC')
                ->orderBy('products.created_at', 'desc');
            break;
        case 'rating':
            // Sort by average rating
            $query->leftJoin('reviews', 'products.id', '=', 'reviews.product_id')
                ->select('products.*')
                ->selectRaw('AVG(reviews.rating) as avg_rating')
                ->groupBy('products.id')
                ->orderBy('avg_rating', 'desc')
                ->orderBy('products.created_at', 'desc');
            break;
        case 'relevance':
        default:
            // Default: relevance (keyword match + popularity)
            if ($keyword) {
                $query->orderByRaw('
                    CASE 
                        WHEN product_name_en LIKE ? THEN 1
                        WHEN product_name_ar LIKE ? THEN 1
                        WHEN product_name_en LIKE ? THEN 2
                        WHEN product_name_ar LIKE ? THEN 2
                        ELSE 3
                    END
                ', ["$keyword", "$keyword", "%$keyword%", "%$keyword%"])
                ->orderBy('created_at', 'desc');
            } else {
                // If no keyword, sort by popularity
                $query->leftJoin('product_metrics', 'products.id', '=', 'product_metrics.product_id')
                    ->select('products.*')
                    ->orderByRaw('COALESCE(product_metrics.conversion_rate, 0) * COALESCE(product_metrics.velocity, 0) * COALESCE(product_metrics.total_sales, 0) DESC')
                    ->orderBy('products.created_at', 'desc');
            }
            break;
    }

    // Load relationships
    $query->with(['badges', 'metrics', 'user', 'productImages', 'tags', 'category']);

    $products = $query->paginate(25)->withQueryString();
    $count_product = $products->total();

    $categories = Category::with('subcategories')->get();
    
    // Get all tags for filter dropdown
    $allTags = \App\Models\Tag::whereHas('products', function($q) {
        $q->where('status', 'active')->where('amount', '>', 0);
    })->get();

    return view('web.pages.show', compact('products', 'categories', 'count_product', 'allTags', 'keyword', 'categoryId', 'minPrice', 'maxPrice', 'tags', 'minRating', 'sortBy'));
}

    public function show(Request $request)
    {
        return $this->showService->show($request);
    }
    public function product($id)
    {
        return $this->showService->product($id);
    }
    
    public function ProductBySubCategory($id)
    {
        return $this->showService->ProductBySubCategory($id);
    }
}
