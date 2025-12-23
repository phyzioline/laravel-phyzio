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

                 Cart::where('user_id',$order->user_id)->delete();
                 foreach ($order->items as $item) {
                    if ($item->product && $item->product->amount >= $item->quantity) {
                        $item->product->decrement('amount', $item->quantity);
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
