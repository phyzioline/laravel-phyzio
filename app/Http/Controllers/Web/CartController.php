<?php

namespace App\Http\Controllers\Web;

 use App\Models\Cart;
 use App\Models\Product;
use Illuminate\Http\Request;
 use App\Repositories\Cart\CartRepository;
 use App\Http\Controllers\Controller;


class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CartRepository $cart)
    {
        $items = $cart->get();
        
        // Calculate total for authenticated or guest
        $userId = auth()->check() ? auth()->id() : null;
        $cookieId = \Illuminate\Support\Facades\Cookie::get('cart_id');
        
        $total = Cart::join('products', 'products.id', '=', 'carts.product_id')
            ->when($userId, function($q) use ($userId) {
                $q->where('carts.user_id', $userId);
            }, function($q) use ($cookieId) {
                $q->where('carts.cookie_id', $cookieId)->whereNull('carts.user_id');
            })
            ->selectRaw('SUM(products.product_price * carts.quantity) as total')
            ->value('total') ?? 0;
            
        return view('web.pages.cart', compact('items', 'total'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CartRepository $cart)
    {
        $request->validate([
            'product_id' => ['required' , 'int' , 'exists:products,id'],
            'quantity' => ['nullable' , 'int' , 'min:1'],
            'engineer_selected' => ['nullable', 'boolean'],
        ]);
        $product = Product::findOrFail($request->product_id);
        
        // Check stock availability
        $requestedQuantity = $request->quantity ?? 1;
        $availableStock = $product->amount ?? 0;
        
        if ($availableStock <= 0) {
            return back()->withErrors(['product_id' => __('This product is currently out of stock.')])->withInput();
        }
        
        // Check if requested quantity exceeds available stock
        $existingCartItem = Cart::where('product_id', $product->id)
            ->when(auth()->check(), function($q) {
                $q->where('user_id', auth()->id());
            }, function($q) {
                $cookieId = \Illuminate\Support\Facades\Cookie::get('cart_id');
                $q->where('cookie_id', $cookieId)->whereNull('user_id');
            })
            ->first();
        
        $currentCartQuantity = $existingCartItem ? $existingCartItem->quantity : 0;
        $totalRequestedQuantity = $currentCartQuantity + $requestedQuantity;
        
        if ($totalRequestedQuantity > $availableStock) {
            $availableMessage = $availableStock == 1 
                ? __('Only 1 item available.') 
                : __('Only :count items available.', ['count' => $availableStock]);
            return back()->withErrors(['quantity' => $availableMessage])->withInput();
        }
        
        // Check if engineer is required
        if ($product->has_engineer_option && $product->engineer_required && !$request->engineer_selected) {
            return back()->withErrors(['engineer_selected' => __('Engineer service is required for this product.')]);
        }
        
        try {
            $cart->add($product, $requestedQuantity);
        } catch (\Exception $e) {
            return back()->withErrors(['quantity' => $e->getMessage()])->withInput();
        }
        
        // Track add to cart for metrics
        $metric = \App\Models\ProductMetric::firstOrCreate(
            ['product_id' => $product->id],
            ['views' => 0, 'clicks' => 0, 'add_to_cart_count' => 0, 'purchases' => 0]
        );
        $metric->incrementAddToCart();
        
        $cart_product = Cart::where('product_id', $request->product_id)->first();
        
        // Calculate price with engineer service if selected
        $basePrice = $product->product_price;
        $engineerPrice = 0;
        $engineerSelected = $request->engineer_selected && $product->has_engineer_option;
        
        if ($engineerSelected) {
            $engineerPrice = $product->engineer_price ?? 0;
        }
        
        $unitPrice = $basePrice + $engineerPrice;
        $totalPrice = $unitPrice * $request->quantity;
        
        // Store engineer option in JSON options field
        $options = [
            'engineer_selected' => $engineerSelected,
            'engineer_price' => $engineerPrice,
        ];
        
        $cart_product->update([
            'price' => $unitPrice,
            'total' => $totalPrice,
            'options' => $options,
        ]);

        return to_route('carts.index');

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartRepository $cart)
    {
        $request->validate([
            'product_id' => ['required' , 'int' , 'exists:products,id'],
            'quantity' => ['nullable' , 'int' , 'min:1'],
        ]);
        $product = Product::findOrFail($request->product_id);



        $cart->update($product, $request->quantity);

        return to_route('carts.index');
    }


    public function update_carts(Request $request, CartRepository $cart)
    {
        $id=request('id');
        $request->validate([
            'quantity' => ['required' , 'int' , 'min:1'],
        ]);
        
        $cartItem = Cart::where('id', $id)->first();
        if (!$cartItem) {
            return response()->json([
                'message' => 'Cart item not found',
                'status' => 404,
            ], 404);
        }
        
        $product = Product::findOrFail($cartItem->product_id);
        $requestedQuantity = $request->quantity;
        $availableStock = $product->amount ?? 0;
        
        // Check stock availability
        if ($availableStock <= 0) {
            return response()->json([
                'message' => __('This product is currently out of stock.'),
                'status' => 400,
                'error' => 'out_of_stock'
            ], 400);
        }
        
        // Check if requested quantity exceeds available stock
        if ($requestedQuantity > $availableStock) {
            $availableMessage = $availableStock == 1 
                ? __('Only 1 item available.') 
                : __('Only :count items available.', ['count' => $availableStock]);
            return response()->json([
                'message' => $availableMessage,
                'status' => 400,
                'error' => 'insufficient_stock',
                'available' => $availableStock
            ], 400);
        }
        
        // Update cart item
        $options = is_string($cartItem->options) ? json_decode($cartItem->options, true) : ($cartItem->options ?? []);
        $engineerPrice = $options['engineer_price'] ?? 0;
        $unitPrice = $product->product_price + $engineerPrice;
        
        $cartItem->quantity = $requestedQuantity;
        $cartItem->total = $unitPrice * $requestedQuantity;
        $cartItem->save();
        
        return response()->json([
            'message' => 'Cart updated successfully',
            'status' => 200,
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartRepository $cart, $id)
    {
        $cart->delete($id);
        return to_route('carts.index')->with('success', 'Item removed from cart successfully');
    }

    // public function total(CartRepository $cart)
    // {
    //      $cart->total();

    //  }

public function getTotal()
{
    $userId = auth()->check() ? auth()->id() : null;
    $cookieId = \Illuminate\Support\Facades\Cookie::get('cart_id');
    
    $total = Cart::join('products', 'products.id', '=', 'carts.product_id')
        ->when($userId, function($q) use ($userId) {
            $q->where('carts.user_id', $userId);
        }, function($q) use ($cookieId) {
            $q->where('carts.cookie_id', $cookieId)->whereNull('carts.user_id');
        })
        ->selectRaw('SUM(products.product_price * carts.quantity) as total')
        ->value('total') ?? 0;

    return response()->json([
        'total' => number_format($total, 2)
    ]);
}

    public function flush(CartRepository $cart)
    {
        $cart->flush();
        return to_route('carts.index')->with('success', 'Item removed from cart successfully');

    }


}
