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

    $products = Product::where('product_name_en', 'LIKE', "%$keyword%")
        ->orWhere('product_name_ar', 'LIKE', "%$keyword%")
      
        ->paginate(10);

    $categories = Category::with('subcategories')->get();

    return view('web.pages.show', compact('products', 'categories'));
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
