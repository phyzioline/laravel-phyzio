<?php
namespace App\Http\Controllers\Web;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\Web\OrderService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\OrderRequest;
 
class OrderController extends Controller
{
    public function __construct(public OrderService $orderService)
    {}

    public function store(OrderRequest $orderRequest)
    {
        // Track purchases for metrics
        $cartItems = \App\Models\Cart::when(auth()->check(), function($q) {
            $q->where('user_id', auth()->id());
        }, function($q) {
            $cookieId = \Illuminate\Support\Facades\Cookie::get('cart_id');
            $q->where('cookie_id', $cookieId)->whereNull('user_id');
        })->with('product')->get();
        
        // Validate stock availability before creating order
        foreach ($cartItems as $item) {
            if (!$item->product) {
                return response()->json([
                    'success' => false,
                    'message' => __('Product not found in cart. Please refresh and try again.')
                ], 400);
            }
            
            $availableStock = $item->product->amount ?? 0;
            $requestedQuantity = $item->quantity;
            
            if ($availableStock < $requestedQuantity) {
                $productName = $item->product->{'product_name_' . app()->getLocale()} ?? $item->product->product_name_en ?? 'Product';
                $message = $availableStock == 0 
                    ? __(':product is currently out of stock.', ['product' => $productName])
                    : __('Only :count items available for :product.', ['count' => $availableStock, 'product' => $productName]);
                
                return back()->withErrors(['cart' => $message])->withInput();
            }
        }
        
        foreach ($cartItems as $item) {
            $metric = \App\Models\ProductMetric::firstOrCreate(
                ['product_id' => $item->product_id],
                ['views' => 0, 'clicks' => 0, 'add_to_cart_count' => 0, 'purchases' => 0]
            );
            $metric->recordPurchase($item->price * $item->quantity);
        }
        
        $method   = $orderRequest->payment_method == 'cash' ? 'cashOrder' : 'store';
        $response = $this->orderService->$method($orderRequest->validated());

        // If response is JsonResponse (error from service), return it directly
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            return $response;
        }

        if ($orderRequest->payment_method === 'card') {
            return view('web.pages.payment-frame', [
                'iframe_url' => $response['redirect_url'],
                'price'      => $response['price'],
            ]);
        }

        return $response;
    }

    public function callback(Request $request)
    {
        $data = $request->all();

        if ($data) {
            $order = Order::where('payment_id', $data['order'])->first();

            if (! $order) {
                abort(404, 'Order not found');
            }

            $url     = route('home.' . app()->getLocale());
            $success = filter_var($data['success'], FILTER_VALIDATE_BOOLEAN);

            if ($success) {
                $balance = $data['amount_cents'] / 100;
                $order->update([
                    'payment_status' => 'paid',
                    'total'          => $balance,
                    'status'       => 'completed',
                ]);
                
                // Calculate Vendor Payments (Now Paid)
                $this->orderService->calculateVendorPayments($order);
                // Mark payments as paid
                \App\Models\VendorPayment::where('order_id', $order->id)->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                 // Clear cart for authenticated or guest orders
                 if ($order->user_id) {
                     Cart::where('user_id', $order->user_id)->delete();
                 } else {
                     // For guest orders, clear by guest_token or email
                     $cookieId = \Illuminate\Support\Facades\Cookie::get('cart_id');
                     if ($cookieId) {
                         Cart::where('cookie_id', $cookieId)->whereNull('user_id')->delete();
                     }
                 }
                 foreach ($order->items as $item) {
                    if ($item->product) {
                        $availableStock = $item->product->amount ?? 0;
                        $requestedQuantity = $item->quantity;
                        
                        // Safely decrement stock - ensure it doesn't go below zero
                        if ($availableStock >= $requestedQuantity) {
                            $newAmount = max(0, $availableStock - $requestedQuantity);
                            $item->product->update(['amount' => $newAmount]);
                        }
                    }
                }

                return view('payment', compact('order', 'url'));

            } else {
                $order->update([
                    'payment_status' => 'faild',
                ]);
                $url = route('home.' . app()->getLocale());
            }

            return view('payment', compact('order', 'url'));
        }
    }

}
