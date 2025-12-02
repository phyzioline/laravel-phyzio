<?php
namespace App\Services\Web;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class OrderService
{
    protected $baseUrl;
    protected $secretKey;
    protected $publicKey;
    protected $cardId;
    protected $api_key;
    protected $walletId;
    protected $walletIdFrame;
    protected $cardIdFrame;

    public function __construct()
    {
        $this->baseUrl       = env('PAYMOB_API_URL');
        $this->secretKey     = env('PAYMOB_SECRET_KEY');
        $this->publicKey     = env('PAYMOB_PUBLIC_KEY');
        $this->cardId        = env('PAYMOB_INTEGRATION_ID');
        $this->walletId      = env('PAYMOB_WALLET_INTEGRATION_ID');
        $this->api_key       = env('PAYMOB_API_KEY');
        $this->cardIdFrame   = env('PAYMOB_IFRAME_ID');
        $this->walletIdFrame = env('PAYMOB_WALLET_IFRAME_ID');
    }

    public function cashOrder($data)
    {
        $user = auth()->user();

        $cartItems = Cart::where('user_id', $user->id)->get();

        $order = Order::create([
            'user_id'        => $user->id,
            'total'          => $cartItems->sum('total'),
            'name'           => $data['name'],
            'address'        => $data['address'],
            'payment_method' => 'cash',
            'payment_id'     => null,
            'phone'          => $data['phone'],
            'payment_status' => 'pending',
        ]);

        foreach ($cartItems as $item) {
            $order->items()->updateOrCreate(
                ['order_id' => $order->id, 'product_id' => $item->product_id],
                [
                    'quantity' => $item->quantity,
                    'price'    => $item->price,
                    'total'    => $item->price * $item->quantity,
                ]
            );
            if ($item->product) {
                $item->product->decrement('amount', $item->quantity);
            }
        }
        Cart::where('user_id', $user->id)->delete();
        Session::flash('message', ['type' => 'success', 'text' => __('تم إنشاء الطلب بنجاح')]);
        return redirect()->route('carts.index')->with('success', 'تم إنشاء الطلب بنجاح');
    }

    public function store($data)
    {

        $user = auth()->user();

        if (! $user) {
            return response()->json(['success' => false, 'message' => 'المستخدم غير موجود'], 401);
        }

        $authResponse = Http::post("https://accept.paymob.com/api/auth/tokens", [
            'api_key' => $this->api_key,
        ]);

        if (! $authResponse->successful()) {
            return response()->json([
                'status'  => false,
                'message' => __('فشل في المصادقة مع Paymob'),
            ], 404);
        }

        $cartItems = Cart::where('user_id', $user->id)->get();

        $authToken = $authResponse->json()['token'];

        $orderResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $authToken,
            'Content-Type'  => 'application/json',
        ])->post("https://accept.paymob.com/api/ecommerce/orders", [
            'auth_token'      => $authToken,
            'delivery_needed' => false,
            'amount_cents'    => $cartItems->sum('total') * 100,
            'currency'        => 'EGP',
            'items'           => [],
        ]);

        if (! $orderResponse->successful()) {
            return response()->json([
                'status'  => false,
                'message' => __('فشل في إنشاء الطلب في Paymob'),
            ], 404);
        }

        $paymobOrderId = $orderResponse->json()['id'];

        $order = Order::updateOrCreate(
            ['user_id' => auth()->user()->id],
            [
                'total'          => $cartItems->sum('total'),
                'name'           => $data['name'],
                'address'        => $data['address'],
                'payment_method' => 'card',
                'payment_id'     => $paymobOrderId,
                'phone'          => $data['phone'],
                'payment_status' => 'pending',
            ]);

        $billing = [
            "apartment"       => "123",
            "first_name"      => $user->name,
            "last_name"       => "غير محدد",
            "street"          => 'لا يوجد عنوان',
            "building"        => "456",
            "phone_number"    => $user->phone,
            "city"            => 'غير محدد',
            "country"         => "EG",
            "email"           => $user->email,
            "floor"           => "1",
            "state"           => 'غير محدد',
            "postal_code"     => "12345",
            "shipping_method" => "PKG",
        ];

        $integrationId = request()->input('payment_method') == 'card' ? $this->cardId : $this->walletId;

        $paymentKeyResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $authToken,
            'Content-Type'  => 'application/json',
        ])->post("https://accept.paymob.com/api/acceptance/payment_keys", [
            'auth_token'     => $authToken,
            'amount_cents'   => $cartItems->sum('total') * 100,
            'expiration'     => 3600,
            'order_id'       => $paymobOrderId,
            'billing_data'   => $billing,
            'currency'       => 'EGP',
            'integration_id' => $integrationId,
        ]);

        foreach ($cartItems as $item) {
            $order->items()->updateOrCreate(
                ['order_id' => $order->id, 'product_id' => $item->product_id],
                [
                    'quantity' => $item->quantity,
                    'price'    => $item->price,
                    'total'    => $item->price * $item->quantity,
                ]
            );
        }

        if (! $paymentKeyResponse->successful()) {
            Log::error('Paymob Payment Key Error:', $paymentKeyResponse->json());
            return response()->json([
                'status'  => false,
                'message' => __('فشل في إنشاء مفتاح الدفع'),
            ], 404);
        }

        $paymentKey = $paymentKeyResponse->json()['token'];

        $iframeId = request()->input('payment_method') == 'card' ? env('PAYMOB_IFRAME_ID') : env('PAYMOB_WALLET_IFRAME_ID');
        return [
            'payment_id'   => $paymobOrderId,
            'price'        => $cartItems->sum('total'),
            'redirect_url' => "https://accept.paymob.com/api/acceptance/iframes/{$iframeId}?payment_token={$paymentKey}&amount={$cartItems->sum('total')}",
        ];
    }

}
