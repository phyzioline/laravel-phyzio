<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Tag;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $user         = User::count();
        $vendor       = User::where('type', 'vendor')->count();
        $buyer        = User::where('type', 'buyer')->count();
        $product      = Product::count();
        $order        = Order::count();
        $order_cash        = Order::where('payment_method', 'cash')->count();
        $order_card        = Order::where('payment_method', 'card')->count();
        $category     = Category::count();
        $sub_category = SubCategory::count();
        $tag          = Tag::count();
        $product_only = Product::where('user_id', auth()->user()->id)->count();
        $order_only   = Order::whereHas('items.product', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->count();
        $order__card_only = Order::where('payment_method', 'card')->whereHas('items.product', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->count();

        $order__cash_only = Order::where('payment_method', 'cash')->whereHas('items.product', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->count();

        return view('dashboard.pages.index', compact('user', 'vendor', 'buyer', 'product', 'order', 'order_card','order_cash','category', 'sub_category', 'tag','order__card_only','order__cash_only'));
    }
}
