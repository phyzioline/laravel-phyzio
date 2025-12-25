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
        if (Auth::check()) {
            return Cart::where('user_id', auth()->user()->id)->get();
        }
        // Guest cart by cookie
        return Cart::where('cookie_id', $this->getCookieId())
                   ->whereNull('user_id')
                   ->get();
    }

    public function add(Product $product, $quantity = 1)
    {
        // Check stock availability
        $availableStock = $product->amount ?? 0;
        if ($availableStock <= 0) {
            throw new \Exception(__('This product is currently out of stock.'));
        }
        
        $cookieId = $this->getCookieId();
        $userId = Auth::check() ? Auth::id() : null;
        
        $item = Cart::where('product_id', $product->id)
                    ->when($userId, function($q) use ($userId) {
                        $q->where('user_id', $userId);
                    }, function($q) use ($cookieId) {
                        $q->where('cookie_id', $cookieId)->whereNull('user_id');
                    })
                    ->first();

        $currentQuantity = $item ? $item->quantity : 0;
        $newQuantity = $currentQuantity + $quantity;
        
        // Check if total quantity exceeds available stock
        if ($newQuantity > $availableStock) {
            $availableMessage = $availableStock == 1 
                ? __('Only 1 item available.') 
                : __('Only :count items available.', ['count' => $availableStock]);
            throw new \Exception($availableMessage);
        }

        if (!$item) {
            return Cart::create([
                'user_id'    => $userId,
                'cookie_id'  => $cookieId,
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
        $userId = Auth::check() ? auth()->id() : null;
        $cookieId = $this->getCookieId();
        
        $cart = Cart::where('id', $id)
            ->when($userId, function($q) use ($userId) {
                $q->where('user_id', $userId);
            }, function($q) use ($cookieId) {
                $q->where('cookie_id', $cookieId)->whereNull('user_id');
            })
            ->first();

        if (! $cart) {
            return back()->with('error', 'العنصر غير موجود أو ليس لك');
        }

        if ($cart->user_id) {
            $order = Order::where('user_id', $cart->user_id)->first();
            if ($order) {
                ItemsOrder::where('order_id', $order->id)
                    ->where('product_id', $cart->product_id)
                    ->delete();
            }
        }

        $cart->delete();
        Session::flash('message', ['type' => 'success', 'text' => __('تم حذف المنتج من السلة والطلب')]);

        return back()->with('success', 'تم حذف المنتج من السلة والطلب');
    }

    public function flush()
    {
        $userId = Auth::check() ? auth()->id() : null;
        $cookieId = $this->getCookieId();
        
        if ($userId) {
            Cart::where('user_id', $userId)->delete();
        } else {
            Cart::where('cookie_id', $cookieId)->whereNull('user_id')->delete();
        }
    }



    public function total()
    {
        $userId = Auth::check() ? auth()->id() : null;
        $cookieId = $this->getCookieId();
        
        $total = Cart::join('products', 'products.id', '=', 'carts.product_id')
            ->when($userId, function($q) use ($userId) {
                $q->where('carts.user_id', $userId);
            }, function($q) use ($cookieId) {
                $q->where('carts.cookie_id', $cookieId)->whereNull('carts.user_id');
            })
            ->selectRaw('SUM(products.product_price * carts.quantity) as total')
            ->value('total') ?? 0;
            
        return view('web.pages.cart', compact('total'));
    }
}
