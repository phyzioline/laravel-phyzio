<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_marking_order_completed_creates_payment_record()
    {
        $vendor = User::factory()->create();
        $buyer = User::factory()->create(["currency" => "USD"]);

        $product = Product::create([
            'user_id' => $vendor->id,
            'product_name_ar' => 'منتج اختبار',
            'product_name_en' => 'Test Product',
            'product_price' => 50,
            'amount' => 10,
        ]);

        // Create a simple order
        $order = Order::create([
            'user_id' => $buyer->id,
            'order_number' => 'ORD-TEST-1',
            'total' => 50,
            'name' => 'Test Buyer',
            'address' => 'Somewhere',
            'payment_method' => 'card',
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 50,
            'total' => 50,
        ]);

        // Acting as admin to mark completed
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $resp = $this->actingAs($admin)->put(route('dashboard.orders.update', $order->id), ['status' => 'completed']);

        $resp->assertRedirect();

        $this->assertDatabaseHas('payments', [
            'paymentable_type' => Order::class,
            'paymentable_id' => $order->id,
            'type' => 'order',
            'status' => 'paid',
            'original_amount' => 50,
        ]);

        $payment = \App\Models\Payment::where('paymentable_type', Order::class)
            ->where('paymentable_id', $order->id)
            ->first();

        $this->assertNotNull($payment);
        $this->assertEquals('USD', $payment->currency);
    }
}
