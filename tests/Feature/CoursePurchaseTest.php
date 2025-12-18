<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoursePurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchasing_course_creates_enrollment_and_payment()
    {
        $instructor = User::factory()->create();
        $course = Course::create([
            'instructor_id' => $instructor->id,
            'title' => 'Test Course',
            'price' => 200,
            'status' => 'published',
            'level' => 'beginner',
        ]);

        $student = User::factory()->create(["currency" => "USD"]);

        $resp = $this->actingAs($student)->post(route('web.courses.purchase', $course->id), ['payment_method' => 'card']);

        $resp->assertRedirect(route('web.courses.show', $course->id));

        $this->assertDatabaseHas('enrollments', [
            'course_id' => $course->id,
            'user_id' => $student->id,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('payments', [
            'paymentable_type' => Course::class,
            'paymentable_id' => $course->id,
            'type' => 'course',
            'status' => 'paid',
            'original_amount' => 200,
        ]);

        $payment = Payment::where('paymentable_type', Course::class)->where('paymentable_id', $course->id)->first();
        $this->assertNotNull($payment);
        $this->assertEquals('USD', $payment->currency);
    }
}
