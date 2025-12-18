<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Payment;
use App\Models\VendorPayment;

class WebhookPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure secret
        putenv('PAYMENT_WEBHOOK_SECRET=test_secret');
    }

    public function test_webhook_updates_payment_to_paid()
    {
        $payment = Payment::factory()->create(['status' => 'pending', 'reference' => 'ref-123']);

        $payload = ['reference' => 'ref-123', 'status' => 'captured', 'amount' => (string)$payment->amount];
        $sig = hash_hmac('sha256', json_encode($payload), env('PAYMENT_WEBHOOK_SECRET'));

        $this->postJson(route('webhooks.payments', ['provider' => 'fakegateway']), $payload, ['X-Signature' => $sig])
            ->assertStatus(200)
            ->assertJson(['status' => 'ok']);

        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'status' => 'paid']);
    }

    public function test_invalid_signature_is_rejected()
    {
        $payment = Payment::factory()->create(['status' => 'pending', 'reference' => 'ref-456']);

        $payload = ['reference' => 'ref-456', 'status' => 'captured'];
        $this->postJson(route('webhooks.payments', ['provider' => 'fakegateway']), $payload, ['X-Signature' => 'bad'])
            ->assertStatus(403);
    }

    public function test_idempotent_webhook_calls_are_safe()
    {
        $payment = Payment::factory()->create(['status' => 'pending', 'reference' => 'ref-777']);

        $payload = ['reference' => 'ref-777', 'status' => 'captured'];
        $sig = hash_hmac('sha256', json_encode($payload), env('PAYMENT_WEBHOOK_SECRET'));

        $this->postJson(route('webhooks.payments', ['provider' => 'fakegateway']), $payload, ['X-Signature' => $sig])
            ->assertStatus(200);

        // Second identical callback
        $this->postJson(route('webhooks.payments', ['provider' => 'fakegateway']), $payload, ['X-Signature' => $sig])
            ->assertStatus(200)
            ->assertJson(['status' => 'already_paid']);
    }

    public function test_webhook_links_vendor_payment_by_order()
    {
        // Create an order and link payment to it
        $customer = \App\Models\User::factory()->create();
        $order = \App\Models\Order::create(['user_id' => $customer->id, 'total' => 100, 'status' => 'pending']);

        $payment = Payment::factory()->create(['status' => 'pending', 'reference' => 'ord-1', 'paymentable_type' => \App\Models\Order::class, 'paymentable_id' => $order->id]);

        $vendor = \App\Models\User::factory()->create();
        $vendor->assignRole('vendor');
        VendorPayment::create(['vendor_id' => $vendor->id, 'order_id' => $order->id, 'product_amount' => 100, 'quantity' => 1, 'subtotal' => 100, 'commission_rate' => 10, 'commission_amount' => 10, 'vendor_earnings' => 90, 'status' => 'pending']);

        $payload = ['reference' => 'ord-1', 'status' => 'captured'];
        $sig = hash_hmac('sha256', json_encode($payload), env('PAYMENT_WEBHOOK_SECRET'));

        $this->postJson(route('webhooks.payments', ['provider' => 'fakegateway']), $payload, ['X-Signature' => $sig])
            ->assertStatus(200);

        $this->assertDatabaseHas('vendor_payments', ['order_id' => $order->id, 'payment_reference' => 'ord-1']);
    }
}
