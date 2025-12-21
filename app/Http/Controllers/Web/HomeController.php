<?php

namespace App\Http\Controllers\Web;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ensure locale is set correctly from URL
        $locale = LaravelLocalization::getCurrentLocale();
        if ($locale) {
            app()->setLocale($locale);
        }
        
        $categories = Category::where('status', 'active')->get();
        
        // Get products with order count for sorting by best selling
        $productsQuery = Product::where('status', 'active')
            ->withCount(['orderItems' => function($query) {
                $query->selectRaw('COALESCE(SUM(quantity), 0)');
            }])
            ->orderByDesc('order_items_count');
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $productsQuery->where(function($query) use ($search) {
                $query->where('product_name_en', 'LIKE', "%{$search}%")
                      ->orWhere('product_name_ar', 'LIKE', "%{$search}%");
            });
        }
        
        // Paginate with 50 products per page
        $products = $productsQuery->paginate(50);
        
        return view('web.pages.index', compact('categories','products'));
    }
}
