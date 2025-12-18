<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\TherapistProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Payment;
use Tests\TestCase;

class AppointmentPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_processing_payment_creates_payment_record_with_conversion()
    {
        $user = User::factory()->create(["currency" => "USD"]);
        $therapist = User::factory()->create();

        $profile = TherapistProfile::create([
            'user_id' => $therapist->id,
            'specialization' => 'physiotherapy',
            'status' => 'approved',
            'home_visit_rate' => 100,
        ]);

        $appointment = Appointment::create([
            'patient_id' => $user->id,
            'therapist_id' => $therapist->id,
            'appointment_date' => now()->toDateString(),
            'appointment_time' => now()->toDateTimeString(),
            'location_address' => 'Test Address',
            'price' => 100,
            'status' => 'pending',
            'payment_status' => 'pending'
        ]);

        $resp = $this->actingAs($user)->post(route('web.appointments.process_payment', $appointment->id), [
            'payment_method' => 'card'
        ]);

        $resp->assertRedirect(route('web.appointments.success', $appointment->id));

        $this->assertDatabaseHas('payments', [
            'paymentable_type' => Appointment::class,
            'paymentable_id' => $appointment->id,
            'type' => 'appointment',
            'status' => 'paid',
            'original_amount' => 100,
        ]);

        $payment = Payment::where('paymentable_type', Appointment::class)
            ->where('paymentable_id', $appointment->id)
            ->first();

        $this->assertNotNull($payment);
        $this->assertEquals('USD', $payment->currency);
        $this->assertGreaterThan(0, $payment->amount);
    }
}
