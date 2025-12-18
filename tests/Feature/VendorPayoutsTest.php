<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Payment;
use App\Models\VendorPayment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorPayoutsTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_payments_linked_after_order_completion()
    {
        $vendor = User::factory()->create();
        $buyer = User::factory()->create(["currency" => "USD"]);

        $product = Product::create([
            'user_id' => $vendor->id,
            'product_name_ar' => 'منتج',
            'product_name_en' => 'Product',
            'product_price' => 100,
            'amount' => 5,
        ]);

        $order = Order::create([
            'user_id' => $buyer->id,
            'order_number' => 'ORD-TEST-2',
            'total' => 100,
            'name' => 'Buyer',
            'address' => 'Somewhere',
            'payment_method' => 'card',
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        $item = $order->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 100,
            'total' => 100,
        ]);

        // Calculate vendor payments (normally happens on order creation)
        (new \App\Services\Web\OrderService())->calculateVendorPayments($order);

        // Ensure vendor payment exists and is pending
        $vp = VendorPayment::where('order_id', $order->id)->first();
        $this->assertNotNull($vp);
        $this->assertEquals('pending', $vp->status);

        // Mark order completed as admin
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $resp = $this->actingAs($admin)->put(route('dashboard.orders.update', $order->id), ['status' => 'completed']);
        $resp->assertRedirect();

        // Payment should be created and vendor payment linked
        $payment = Payment::where('paymentable_type', Order::class)->where('paymentable_id', $order->id)->first();
        $this->assertNotNull($payment);

        $vp = VendorPayment::where('order_id', $order->id)->first();
        $this->assertEquals($payment->id, $vp->payment_id);
        $this->assertEquals('paid', $vp->status);
    }

    public function test_course_purchase_creates_vendor_payment_linked()
    {
        $instructor = User::factory()->create();
        $course = \App\Models\Course::create([
            'instructor_id' => $instructor->id,
            'title' => 'Test Course',
            'price' => 200,
            'status' => 'published',
            'level' => 'beginner',
        ]);

        $student = User::factory()->create(["currency" => "USD"]);

        $resp = $this->actingAs($student)->post(route('web.courses.purchase', $course->id), ['payment_method' => 'card']);
        $resp->assertRedirect();

        $payment = Payment::where('paymentable_type', \App\Models\Course::class)->where('paymentable_id', $course->id)->first();
        $this->assertNotNull($payment);

        $vp = VendorPayment::where('payment_id', $payment->id)->first();
        $this->assertNotNull($vp);
        $this->assertEquals($instructor->id, $vp->vendor_id);
    }
}
