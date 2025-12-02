<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\ItemsOrder;
use App\Models\Order;
use Illuminate\Support\Facades\Session;


class CartModelRepository implements CartRepository
{
    protected function getCookieId()
    {
        $cookie_id = Cookie::get('cart_id');
        if (!$cookie_id) {
            $cookie_id = Str::uuid();
            Cookie::queue('cart_id', $cookie_id, 30 * 24 * 60);
        }
        return $cookie_id;
    }

    public function get()
    {
        return Cart::where('user_id', auth()->user()->id)->get();
    }

    public function add(Product $product, $quantity = 1)
    {
        $item = Cart::where('product_id', $product->id)
                    ->where('user_id', auth()->user()->id)
                    ->first();

        if (!$item) {
            return Cart::create([
                'user_id'    => Auth::check() ? Auth::id() : null,
                'cookie_id'  => $this->getCookieId(),
                'product_id' => $product->id,
                'quantity'   => $quantity,
            ]);
        }

        $item->increment('quantity', $quantity);
        return $item;
    }

    public function update($id, $quantity)
    {
        Cart::where('id', $id)->update([
            'quantity' => $quantity,
        ]);
    }

    
    public function delete($id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->first();

        if (! $cart) {
            return back()->with('error', 'العنصر غير موجود أو ليس لك');
        }

        $order = Order::where('user_id', $cart->user_id)->first();

        if ($order) {
            ItemsOrder::where('order_id', $order->id)
                ->where('product_id', $cart->product_id)
                ->delete();
        }

        $cart->delete();
            Session::flash('message', ['type' => 'success', 'text' => __('تم حذف المنتج من السلة والطلب')]);

        return back()->with('success', 'تم حذف المنتج من السلة والطلب');
    }

    public function flush()
    {
        Cart::where('user_id', auth()->user()->id)->delete();
    }



    public function total()
    {
        $total =  Cart::join('products', 'products.id', '=', 'carts.product_id')
        ->where('carts.user_id', auth()->user()->id)
        ->selectRaw('SUM(products.product_price * carts.quantity) as total')
        ->value('total');
        return view('web.pages.cart' , compact('total'));
    }
}
