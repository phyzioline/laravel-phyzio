<?php

namespace App\Http\Controllers\Web;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('status', 'active')->get();
        $products = Product::where('status', 'active')->get();
        if ($request->has('search')) {
            $search = $request->input('search');
            $products = $products->filter(function ($product) use ($search) {
                return str_contains(strtolower($product->product_name), strtolower($search));
            });
        }
        return view('web.pages.index', compact('categories','products'));
    }
}
