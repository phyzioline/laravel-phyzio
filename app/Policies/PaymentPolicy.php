<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use App\Models\VendorPayment;
use App\Models\Order;
use App\Models\HomeVisit;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Payment $payment)
    {
        // Admins can see everything
        if ($user->hasRole('admin')) {
            return true;
        }

        $type = $payment->paymentable_type;
        $id = $payment->paymentable_id;

        switch ($type) {
            case Order::class:
                $order = Order::find($id);
                if (!$order) return false;
                if ($order->user_id === $user->id) return true; // customer
                // vendor can see if they have a vendor payment for this order
                return VendorPayment::where('order_id', $order->id)->where('vendor_id', $user->id)->exists();

            case HomeVisit::class:
                $visit = HomeVisit::find($id);
                if (!$visit) return false;
                return $visit->therapist_id === $user->id || $visit->patient_id === $user->id;

            case Course::class:
                $course = Course::find($id);
                if (!$course) return false;
                // instructor
                if ($course->instructor_id === $user->id) return true;
                // student -> check enrollment
                return Enrollment::where('course_id', $course->id)->where('user_id', $user->id)->exists();

            default:
                return false;
        }
    }
}
