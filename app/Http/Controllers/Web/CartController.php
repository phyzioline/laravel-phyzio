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
        $items=Cart::get();
        // return $items;
        $items = $cart->get();
// return $items;
$total =  Cart::join('products', 'products.id', '=', 'carts.product_id')
        ->where('carts.user_id', auth()->user()->id)
        ->selectRaw('SUM(products.product_price * carts.quantity) as total')
        ->value('total');
        return view('web.pages.cart', compact('items' ,'total'));
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
        
        // Check if engineer is required
        if ($product->has_engineer_option && $product->engineer_required && !$request->engineer_selected) {
            return back()->withErrors(['engineer_selected' => __('Engineer service is required for this product.')]);
        }
        
        $cart->add($product, $request->quantity);
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
        // return $id;
        // return $request;
        $request->validate([
            // 'product_id' => ['required' , 'int' , 'exists:products,id'],
            'quantity' => ['nullable' , 'int' , 'min:1'],
        ]);
        $cart=Cart::where('id', $id)->first();
        $product = Product::findOrFail($cart->product_id);
         $quantity = $request->quantity;
    $cart->quantity = $quantity;
          $cart->total = $quantity * $cart->product->product_price;

    $cart->save();
// return $product;
        $cart->update(['quantity' => $request->quantity]);
return response()->json([
            'message' => 'Cart updated successfully',
            'status' => 200,
        ]);
        // return to_route('carts.index');
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
    $total = Cart::join('products', 'products.id', '=', 'carts.product_id')
        ->where('carts.user_id', auth()->id())
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
