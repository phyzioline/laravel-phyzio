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
        // Support both authenticated and guest checkout
        $user = auth()->user();
        $cookieId = \Illuminate\Support\Facades\Cookie::get('cart_id');
        
        if ($user) {
            $cartItems = Cart::where('user_id', $user->id)->get();
        } else {
            // Guest checkout
            $guestService = app(\App\Services\GuestCheckoutService::class);
            $order = $guestService->createGuestOrder($data, Cart::where('cookie_id', $cookieId)->whereNull('user_id')->get());
            
            // Calculate vendor payments
            $this->calculateVendorPayments($order);
            
            Session::flash('message', ['type' => 'success', 'text' => __('تم إنشاء الطلب بنجاح')]);
            $locale = app()->getLocale() ?: 'en';
            return redirect('/' . $locale . '/carts')->with('success', 'تم إنشاء الطلب بنجاح')->with('guest_order', $order);
        }

        $cartItems = Cart::where('user_id', $user->id)->get();

        // Save address if requested (One-Click Reorder)
        $addressId = null;
        if ($user && isset($data['save_address']) && $data['save_address']) {
            $address = \App\Models\UserAddress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'address' => $data['address'],
                ],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'city' => $data['city'] ?? null,
                    'governorate' => $data['governorate'] ?? null,
                    'is_default' => !\App\Models\UserAddress::where('user_id', $user->id)->where('is_default', true)->exists(),
                ]
            );
            $addressId = $address->id;
        }
        
        $order = Order::create([
            'user_id'        => $user->id,
            'address_id'     => $addressId,
            'order_number'   => 'ORD-' . date('Y') . '-' . strtoupper(uniqid()),
            'total'          => $cartItems->sum('total'),
            'name'           => $data['name'],
            'address'        => $data['address'],
            'payment_method' => 'cash',
            'payment_id'     => null,
            'phone'          => $data['phone'],
            'payment_status' => 'pending',
            'status'         => 'pending' // Cash orders usually start as pending until delivery
        ]);

        foreach ($cartItems as $item) {
            // Validate stock availability before creating order item
            if (!$item->product) {
                \Illuminate\Support\Facades\Log::warning('Product not found for cart item', ['cart_item_id' => $item->id]);
                continue;
            }
            
            $availableStock = $item->product->amount ?? 0;
            $requestedQuantity = $item->quantity;
            
            // Check if enough stock is available
            if ($availableStock < $requestedQuantity) {
                $productName = $item->product->{'product_name_' . app()->getLocale()} ?? $item->product->product_name_en ?? 'Product';
                throw new \Exception("Insufficient stock for {$productName}. Available: {$availableStock}, Requested: {$requestedQuantity}");
            }
            
            // Get engineer info from cart options
            $options = is_string($item->options) ? json_decode($item->options, true) : ($item->options ?? []);
            $engineerSelected = $options['engineer_selected'] ?? false;
            $engineerPrice = $options['engineer_price'] ?? 0;
            
            $order->items()->updateOrCreate(
                ['order_id' => $order->id, 'product_id' => $item->product_id],
                [
                    'quantity' => $item->quantity,
                    'price'    => $item->price,
                    'total'    => $item->price * $item->quantity,
                    'engineer_selected' => $engineerSelected,
                    'engineer_price' => $engineerPrice,
                ]
            );
            
            // Safely decrement stock - ensure it doesn't go below zero
            if ($availableStock >= $requestedQuantity) {
                $newAmount = max(0, $availableStock - $requestedQuantity);
                $item->product->update(['amount' => $newAmount]);
            }
        }
        
        // Calculate Vendor Payments (Pending for Cash Orders)
        $this->calculateVendorPayments($order);

        Cart::where('user_id', $user->id)->delete();
        
        // Send notification to all admins
        $admins = \App\Models\User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\OrderCreatedNotification($order));
        }

        // 1. Send Confirmation to Customer
        try {
            if ($user->email) {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OrderConfirmationMail($order));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send customer confirmation email: ' . $e->getMessage());
        }

        // 2. Send Notifications to Vendors (Suppliers)
        try {
            // Group items by vendor (product->user_id)
            $vendorItems = [];
            foreach ($order->items as $item) {
                if ($item->product && $item->product->user_id) {
                    $vendorId = $item->product->user_id;
                    if (!isset($vendorItems[$vendorId])) {
                        $vendorItems[$vendorId] = [];
                    }
                    $vendorItems[$vendorId][] = $item;
                }
            }

            // Send email to each vendor with their specific items
            foreach ($vendorItems as $vendorId => $items) {
                $vendor = \App\Models\User::find($vendorId);
                // Don't send if vendor is missing or is the current user (unless admin wants copy? assuming distinct)
                if ($vendor && $vendor->email) {
                     \Illuminate\Support\Facades\Mail::to($vendor->email)->send(new \App\Mail\SupplierOrderMail($order, $items));
                }
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send vendor notification email: ' . $e->getMessage());
        }
        
        Session::flash('message', ['type' => 'success', 'text' => __('تم إنشاء الطلب بنجاح')]);
        $locale = app()->getLocale() ?: 'en';
        return redirect('/' . $locale . '/carts')->with('success', 'تم إنشاء الطلب بنجاح');
    }

    public function store($data)
    {
        // Support both authenticated and guest checkout
        $user = auth()->user();
        $cookieId = \Illuminate\Support\Facades\Cookie::get('cart_id');
        
        if (!$user) {
            // Guest checkout for card payment
            $guestService = app(\App\Services\GuestCheckoutService::class);
            $cartItems = Cart::where('cookie_id', $cookieId)->whereNull('user_id')->get();
            
            if ($cartItems->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Cart is empty'], 400);
            }
            
            // Create guest order first
            $order = $guestService->createGuestOrder($data, $cartItems);
            $this->calculateVendorPayments($order);
            
            // Continue with payment processing using guest email
            $userEmail = $data['email'];
            $userName = $data['name'];
            $userPhone = $data['phone'];
        } else {
            $cartItems = Cart::where('user_id', $user->id)->get();
            $userEmail = $user->email;
            $userName = $user->name;
            $userPhone = $user->phone;
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

        $authToken = $authResponse->json()['token'];

        // Calculate total and ensure minimum amount (Paymob requires at least 10 cents = 0.10 EGP)
        $totalAmount = $cartItems->sum('total');
        $amountCents = max(10, (int)($totalAmount * 100)); // Minimum 10 cents (0.10 EGP)
        
        if ($totalAmount < 0.10) {
            return response()->json([
                'status'  => false,
                'message' => __('Minimum order amount is 0.10 EGP'),
            ], 400);
        }

        $orderResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $authToken,
            'Content-Type'  => 'application/json',
        ])->post("https://accept.paymob.com/api/ecommerce/orders", [
            'auth_token'      => $authToken,
            'delivery_needed' => false,
            'amount_cents'    => $amountCents,
            'currency'        => 'EGP',
            'items'           => [],
        ]);

        if (! $orderResponse->successful()) {
            Log::error('Paymob Order Creation Error:', [
                'status' => $orderResponse->status(),
                'body' => $orderResponse->body(),
                'json' => $orderResponse->json(),
            ]);
            return response()->json([
                'status'  => false,
                'message' => __('فشل في إنشاء الطلب في Paymob'),
                'details' => $orderResponse->json(),
            ], 404);
        }

        $paymobOrderId = $orderResponse->json()['id'];

        // Create or update order
        if (!$user && isset($order)) {
            // Guest order already created, just update payment_id
            $order->update(['payment_id' => $paymobOrderId]);
        } else {
            // Authenticated user order
            $addressId = null;
            if ($user && isset($data['save_address']) && $data['save_address']) {
                $address = \App\Models\UserAddress::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'address' => $data['address'],
                    ],
                    [
                        'name' => $data['name'],
                        'phone' => $data['phone'],
                        'city' => $data['city'] ?? null,
                        'governorate' => $data['governorate'] ?? null,
                        'is_default' => !\App\Models\UserAddress::where('user_id', $user->id)->where('is_default', true)->exists(),
                    ]
                );
                $addressId = $address->id;
            }
            
            $order = Order::updateOrCreate(
                ['user_id' => $user->id, 'payment_id' => $paymobOrderId],
                [
                    'address_id' => $addressId,
                    'order_number' => 'ORD-' . date('Y') . '-' . strtoupper(uniqid()),
                    'total' => $cartItems->sum('total'),
                    'name' => $data['name'],
                    'address' => $data['address'],
                    'payment_method' => 'card',
                    'phone' => $data['phone'],
                    'payment_status' => 'pending',
                    'status' => 'pending_payment', // NEW: pending_payment status for reservation
                ]
            );
        }

        $billing = [
            "apartment"       => "123",
            "first_name"      => $userName,
            "last_name"       => "غير محدد",
            "street"          => $data['address'] ?? 'لا يوجد عنوان',
            "building"        => "456",
            "phone_number"    => $userPhone,
            "city"            => $data['city'] ?? 'غير محدد',
            "country"         => "EG",
            "email"           => $userEmail,
            "floor"           => "1",
            "state"           => $data['governorate'] ?? 'غير محدد',
            "postal_code"     => "12345",
            "shipping_method" => "PKG",
        ];

        $integrationId = request()->input('payment_method') == 'card' ? $this->cardId : $this->walletId;

        // Use the same amount_cents calculated above
        $paymentKeyResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $authToken,
            'Content-Type'  => 'application/json',
        ])->post("https://accept.paymob.com/api/acceptance/payment_keys", [
            'auth_token'     => $authToken,
            'amount_cents'   => $amountCents,
            'expiration'     => 3600,
            'order_id'       => $paymobOrderId,
            'billing_data'   => $billing,
            'currency'       => 'EGP',
            'integration_id' => $integrationId,
        ]);

        // Create order items
        foreach ($cartItems as $item) {
            // Validate stock availability before creating order item
            if (!$item->product) {
                \Illuminate\Support\Facades\Log::warning('Product not found for cart item', ['cart_item_id' => $item->id]);
                continue;
            }
            
            // Get engineer info from cart options
            $options = is_string($item->options) ? json_decode($item->options, true) : ($item->options ?? []);
            $engineerSelected = $options['engineer_selected'] ?? false;
            $engineerPrice = $options['engineer_price'] ?? 0;
            
            $order->items()->updateOrCreate(
                ['order_id' => $order->id, 'product_id' => $item->product_id],
                [
                    'quantity' => $item->quantity,
                    'price'    => $item->price,
                    'total'    => $item->price * $item->quantity,
                    'engineer_selected' => $engineerSelected,
                    'engineer_price' => $engineerPrice,
                ]
            );
        }

        // ========================================
        // CRITICAL: Reserve Stock (NEW)
        // ========================================
        $stockService = app(\App\Services\StockReservationService::class);
        $reservationSuccess = $stockService->reserveStockForOrder($order);
        
        if (!$reservationSuccess) {
            // Stock reservation failed - cancel order and return error
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'failed'
            ]);
            
            return response()->json([
                'status'  => false,
                'message' => __('One or more items are out of stock. Please update your cart.'),
            ], 400);
        }
        
        Log::info('Stock reserved for order', [
            'order_id' => $order->id,
            'payment_id' => $paymobOrderId
        ]);
        // ========================================
        // End Stock Reservation
        // ========================================

        // Send notification to all admins
        $admins = \App\Models\User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\OrderCreatedNotification($order));
        }

        // 1. Send Confirmation to Customer
        try {
            $customerEmail = $user ? $user->email : ($order->email ?? null);
            if ($customerEmail) {
                \Illuminate\Support\Facades\Mail::to($customerEmail)->send(new \App\Mail\OrderConfirmationMail($order));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send customer confirmation email: ' . $e->getMessage());
        }

         // 2. Send Notifications to Vendors (Suppliers)
         try {
            $vendorItems = [];
            foreach ($order->items as $item) {
                if ($item->product && $item->product->user_id) {
                    $vendorId = $item->product->user_id;
                    if (!isset($vendorItems[$vendorId])) {
                        $vendorItems[$vendorId] = [];
                    }
                    $vendorItems[$vendorId][] = $item;
                }
            }

            foreach ($vendorItems as $vendorId => $items) {
                $vendor = \App\Models\User::find($vendorId);
                if ($vendor && $vendor->email) {
                     \Illuminate\Support\Facades\Mail::to($vendor->email)->send(new \App\Mail\SupplierOrderMail($order, $items));
                }
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send vendor notification email: ' . $e->getMessage());
        }
        
        // Clear cart
        if ($user) {
            Cart::where('user_id', $user->id)->delete();
        } else {
            Cart::where('cookie_id', $cookieId)->whereNull('user_id')->delete();
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
            'price'        => $totalAmount,
            'redirect_url' => "https://accept.paymob.com/api/acceptance/iframes/{$iframeId}?payment_token={$paymentKey}&amount={$totalAmount}",
        ];
    }

    /**
     * Calculate and record vendor payments for an order
     * 
     * @param Order $order
     * @return void
     */
    public function calculateVendorPayments(Order $order)
    {
        // Default commission rate (should ideally come from settings or vendor profile)
        $defaultCommissionRate = 15.00; // 15%

        foreach ($order->items as $item) {
            if ($item->product && $item->product->user_id) {
                $vendorId = $item->product->user_id;
                
                // Calculate financials - use item price or fallback to product price
                $productAmount = $item->price ?? $item->product->product_price ?? 0;
                if ($productAmount <= 0) {
                    \Illuminate\Support\Facades\Log::warning('Vendor payment calculation: product_amount is 0 or null', [
                        'order_id' => $order->id,
                        'item_id' => $item->id,
                        'product_id' => $item->product_id,
                        'item_price' => $item->price,
                        'product_price' => $item->product->product_price ?? null,
                    ]);
                    continue; // Skip this item if no valid price
                }
                $quantity = $item->quantity;
                $subtotal = $productAmount * $quantity;
                
                // Calculate commission
                $commissionAmount = ($subtotal * $defaultCommissionRate) / 100;
                $vendorEarnings = $subtotal - $commissionAmount;
                
                // Record payment
                \App\Models\VendorPayment::updateOrCreate(
                    [
                        'vendor_id' => $vendorId,
                        'order_id' => $order->id,
                        'order_item_id' => $item->id,
                    ],
                    [
                        'product_amount' => $productAmount,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                        'commission_rate' => $defaultCommissionRate,
                        'commission_amount' => $commissionAmount,
                        'vendor_earnings' => $vendorEarnings,
                        'status' => 'pending', // Pending until order is completed/paid
                        'payment_reference' => $order->order_number,
                    ]
                );
            }
        }
    }

}
