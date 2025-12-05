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

    $products = Product::where('status', 'active')
        ->where('amount', '>', '0')
        ->where(function($query) use ($keyword) {
            $query->where('product_name_en', 'LIKE', "%$keyword%")
                  ->orWhere('product_name_ar', 'LIKE', "%$keyword%");
        })
        ->paginate(25);

    $count_product = Product::where('status', 'active')
        ->where('amount', '>', '0')
        ->where(function($query) use ($keyword) {
            $query->where('product_name_en', 'LIKE', "%$keyword%")
                  ->orWhere('product_name_ar', 'LIKE', "%$keyword%");
        })
        ->count();

    $categories = Category::with('subcategories')->get();

    return view('web.pages.show', compact('products', 'categories', 'count_product'));
}

    public function show()
    {
        return $this->showService->show();
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
