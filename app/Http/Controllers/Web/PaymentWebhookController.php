<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\VendorPayment;

class PaymentWebhookController extends Controller
{
    /**
     * Generic webhook handler for payment gateways.
     * Uses HMAC SHA256 signature verification with env var PAYMENT_WEBHOOK_SECRET.
     */
    public function handle(Request $request, $provider)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Signature');

        $secret = env('PAYMENT_WEBHOOK_SECRET');
        if (!$secret) {
            Log::warning('Webhook received but PAYMENT_WEBHOOK_SECRET is not configured');
            return response()->json(['error' => 'Webhook not configured'], 503);
        }

        $expected = hash_hmac('sha256', $payload, $secret);
        if (!hash_equals($expected, $signature ?? '')) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $data = $request->json()->all();

        // Accept common keys: reference, payment_reference, order_ref
        $reference = $data['reference'] ?? $data['payment_reference'] ?? $data['order_ref'] ?? null;
        if (!$reference) {
            return response()->json(['error' => 'Missing reference'], 400);
        }

        $payment = Payment::where('reference', $reference)->first();
        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // Idempotent handling: if already paid, return success
        if ($payment->status === 'paid') {
            return response()->json(['status' => 'already_paid']);
        }

        // Update payment to paid
        $payment->status = 'paid';
        $payment->method = $provider;
        $payment->meta = array_merge($payment->meta ?? [], ['webhook_payload' => $data, 'gateway' => $provider]);
        $payment->paid_at = now();
        $payment->save();

        // Link any vendor payments associated with this order/appointment
        // If this payment is for an Order
        if ($payment->paymentable_type === \App\Models\Order::class) {
            VendorPayment::where('order_id', $payment->paymentable_id)
                ->update(['payment_id' => $payment->id, 'payment_reference' => $payment->reference]);
        }

        // If appointment
        if ($payment->paymentable_type === \App\Models\Appointment::class) {
            VendorPayment::where('appointment_id', $payment->paymentable_id)
                ->update(['payment_id' => $payment->id, 'payment_reference' => $payment->reference]);
        }

        // For courses, vendor payments were created at purchase time with payment_id set; ensure references match
        VendorPayment::where('payment_id', $payment->id)->update(['payment_reference' => $payment->reference]);

        Log::info('Payment confirmed via webhook', ['payment_id' => $payment->id, 'provider' => $provider]);

        return response()->json(['status' => 'ok']);
    }
}
