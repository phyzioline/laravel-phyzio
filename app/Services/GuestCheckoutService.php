<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Cart;
use App\Models\UserAddress;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class GuestCheckoutService
{
    /**
     * Create guest order and save address.
     */
    public function createGuestOrder($data, $cartItems)
    {
        return DB::transaction(function () use ($data, $cartItems) {
            // Generate guest token for order tracking
            $guestToken = Str::random(32);
            
            // Save or get address
            $address = $this->saveGuestAddress($data);
            
            // Create order
            $order = Order::create([
                'user_id' => null,
                'email' => $data['email'],
                'guest_token' => $guestToken,
                'is_guest_order' => true,
                'address_id' => $address->id,
                'order_number' => 'ORD-' . date('Y') . '-' . strtoupper(uniqid()),
                'total' => $cartItems->sum('total'),
                'name' => $data['name'],
                'address' => $data['address'],
                'payment_method' => $data['payment_method'] ?? 'cash',
                'payment_id' => null,
                'phone' => $data['phone'],
                'payment_status' => 'pending',
                'status' => 'pending',
            ]);
            
            // Create order items
            foreach ($cartItems as $item) {
                // Validate stock availability before creating order item
                if (!$item->product) {
                    throw new \Exception("Product not found for cart item ID: {$item->id}");
                }
                
                $availableStock = $item->product->amount ?? 0;
                $requestedQuantity = $item->quantity;
                
                // Check if enough stock is available
                if ($availableStock < $requestedQuantity) {
                    $productName = $item->product->{'product_name_' . app()->getLocale()} ?? $item->product->product_name_en ?? 'Product';
                    throw new \Exception("Insufficient stock for {$productName}. Available: {$availableStock}, Requested: {$requestedQuantity}");
                }
                
                $options = is_string($item->options) ? json_decode($item->options, true) : ($item->options ?? []);
                $engineerSelected = $options['engineer_selected'] ?? false;
                $engineerPrice = $options['engineer_price'] ?? 0;
                
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity,
                    'engineer_selected' => $engineerSelected,
                    'engineer_price' => $engineerPrice,
                ]);
                
                // Safely decrement stock - ensure it doesn't go below zero
                if ($item->product && $availableStock >= $requestedQuantity) {
                    $newAmount = max(0, $availableStock - $requestedQuantity);
                    $item->product->update(['amount' => $newAmount]);
                }
            }
            
            // Clear cart
            $cookieId = \Illuminate\Support\Facades\Cookie::get('cart_id');
            Cart::where('cookie_id', $cookieId)
                ->whereNull('user_id')
                ->delete();
            
            return $order;
        });
    }

    /**
     * Save guest address for future use.
     */
    protected function saveGuestAddress($data)
    {
        // Always save guest address for account creation later
        return UserAddress::updateOrCreate(
            [
                'email' => $data['email'],
                'user_id' => null,
            ],
            [
                'name' => $data['name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city' => $data['city'] ?? null,
                'governorate' => $data['governorate'] ?? null,
                'is_default' => true,
            ]
        );
    }

    /**
     * Create account from guest order.
     */
    public function createAccountFromGuest($order, $password)
    {
        return DB::transaction(function () use ($order, $password) {
            // Check if user already exists
            $user = User::where('email', $order->email)->first();
            
            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $order->name,
                    'email' => $order->email,
                    'phone' => $order->phone,
                    'password' => Hash::make($password),
                    'type' => 'buyer',
                    'status' => 'active',
                ]);
            }
            
            // Link address to user
            if ($order->address_id) {
                $address = UserAddress::find($order->address_id);
                if ($address) {
                    $address->update([
                        'user_id' => $user->id,
                        'email' => null, // Clear guest email
                    ]);
                }
            }
            
            // Link order to user
            $order->update([
                'user_id' => $user->id,
                'is_guest_order' => false,
            ]);
            
            // Link cart items to user
            $cookieId = \Illuminate\Support\Facades\Cookie::get('cart_id');
            Cart::where('cookie_id', $cookieId)
                ->whereNull('user_id')
                ->update(['user_id' => $user->id]);
            
            return $user;
        });
    }
}

