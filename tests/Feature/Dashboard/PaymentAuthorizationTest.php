<?php

namespace Tests\Feature\Dashboard;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\VendorPayment;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Appointment;
use App\Models\Course;
use App\Models\Enrollment;

class PaymentAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // ensure basic roles exist
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'vendor']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'therapist']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'patient']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'buyer']);
    }

    public function test_vendor_cannot_view_other_vendor_payment()
    {
        $vendorA = User::factory()->create();
        $vendorB = User::factory()->create();
        $vendorA->assignRole('vendor');
        $vendorB->assignRole('vendor');

        $vp = VendorPayment::create([
            'vendor_id' => $vendorA->id,
            'order_id' => null,
            'product_amount' => 100,
            'quantity' => 1,
            'subtotal' => 100,
            'commission_rate' => 10,
            'commission_amount' => 10,
            'vendor_earnings' => 90,
            'status' => 'pending'
        ]);

        $this->actingAs($vendorB)
            ->getJson(route('dashboard.payments.show', $vp->id))
            ->assertStatus(403);
    }

    public function test_vendor_can_view_own_vendor_payment()
    {
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');

        $vp = VendorPayment::create([
            'vendor_id' => $vendor->id,
            'order_id' => null,
            'product_amount' => 100,
            'quantity' => 1,
            'subtotal' => 100,
            'commission_rate' => 10,
            'commission_amount' => 10,
            'vendor_earnings' => 90,
            'status' => 'pending'
        ]);

        $this->actingAs($vendor)
            ->getJson(route('dashboard.payments.show', $vp->id))
            ->assertStatus(200);
    }

    public function test_therapist_cannot_view_payment_for_another_therapist_appointment()
    {
        $therA = User::factory()->create();
        $therB = User::factory()->create();
        $therA->assignRole('therapist');
        $therB->assignRole('therapist');

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $appointment = Appointment::create([
            'therapist_id' => $therA->id,
            'patient_id' => $patient->id,
            'price' => 120,
            'status' => 'confirmed',
            'appointment_time' => now()
        ]);

        $payment = Payment::create([
            'paymentable_type' => Appointment::class,
            'paymentable_id' => $appointment->id,
            'type' => 'appointment',
            'amount' => 120,
            'currency' => 'USD',
            'status' => 'paid',
            'method' => 'card',
            'reference' => 'appt_' . time()
        ]);

        $this->actingAs($therB)
            ->get(route('dashboard.payments.detail', $payment->id))
            ->assertStatus(403);

        $this->actingAs($therA)
            ->get(route('dashboard.payments.detail', $payment->id))
            ->assertStatus(200);
    }

    public function test_course_instructor_and_student_can_view_course_payment()
    {
        $instructor = User::factory()->create();
        $student = User::factory()->create();
        $instructor->assignRole('vendor'); // instructor acts as vendor
        $student->assignRole('buyer');

        $course = Course::create([
            'title' => 'Physio 101',
            'price' => 200,
            'instructor_id' => $instructor->id,
            'level' => 'beginner'
        ]);

        // student enrollment
        Enrollment::create([
            'course_id' => $course->id,
            'user_id' => $student->id,
            'paid_amount' => 200,
            'status' => 'active',
            'enrolled_at' => now()
        ]);

        $payment = Payment::create([
            'paymentable_type' => Course::class,
            'paymentable_id' => $course->id,
            'type' => 'course',
            'amount' => 200,
            'currency' => 'USD',
            'status' => 'paid',
            'method' => 'card',
            'reference' => 'course_' . $course->id . '_' . $student->id
        ]);

        // instructor can view
        $this->actingAs($instructor)
            ->get(route('dashboard.payments.detail', $payment->id))
            ->assertStatus(200);

        // student can view
        $this->actingAs($student)
            ->get(route('dashboard.payments.detail', $payment->id))
            ->assertStatus(200);
    }
}
