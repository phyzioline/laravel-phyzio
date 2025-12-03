<?php
namespace App\Services\Web;

use App\Models\Category;
use App\Models\Product;

class ShowService
{

    public function show()
    {
        $categories = Category::where('status', 'active')
            ->with(['subCategories' => function ($query) {
                $query->where('status', 'active');
            }])
            ->get();
        $products      = Product::where('amount','>' ,'0')->where('status', 'active')->paginate(50);
        $count_product = Product::where('amount','>' ,'0')->where('status', 'active')->count();
        return view('web.pages.show', compact('categories', 'products', 'count_product'));
    }

    public function product($id)
    {
        $product  = Product::where('id', $id)->first();
        $products = Product::where('sub_category_id', $product->sub_category_id)
            ->where('id', '!=', $id)->where('status', 'active')
            ->get();
        //  $products = Product::
        //     where('id', '!=', $id)->where('status','active')
        //     ->get();
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

}
