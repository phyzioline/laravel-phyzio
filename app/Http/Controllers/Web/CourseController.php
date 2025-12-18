<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query()->where('status', 'published')->with('instructor');

        // Search
        if ($request->has('search') && $request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('instructor', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Category Filter
        if ($request->has('category') && $request->filled('category')) {
            $query->where('category_id', $request->category); // Ensure category_id exists in migration or use generic field if mocking
        }

        // Level Filter
        if ($request->has('level') && $request->filled('level')) {
            $query->where('level', $request->level);
        }
        
        // Language Filter
        if ($request->has('language') && $request->filled('language')) {
            $query->where('language', $request->language);
        }

        // Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'rating':
                    // Assuming aggregation or relation for ratings
                    $query->withCount('reviews')->orderBy('reviews_count', 'desc'); 
                    break;
                case 'newest':
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $courses = $query->paginate(12)->withQueryString();
        
        // Featured Courses 
        $featuredCourses = Course::where('status', 'published')
                                 ->whereNotNull('thumbnail')
                                 ->inRandomOrder()
                                 ->take(5)
                                 ->get();
                                 
        // Categories (Mock or Fetch if model exists)
        $categories = collect([
            (object)['id' => 1, 'name' => 'Orthopedics'],
            (object)['id' => 2, 'name' => 'Neurology'],
            (object)['id' => 3, 'name' => 'Pediatrics'],
            (object)['id' => 4, 'name' => 'Manual Therapy'],
            (object)['id' => 5, 'name' => 'Sports Medicine'],
        ]);

        return view('web.courses.index', compact('courses', 'featuredCourses', 'categories'));
    }

    public function show($id)
    {
        $course = Course::with(['modules.units', 'instructor', 'enrollments', 'skills', 'clinicalCases'])->findOrFail($id);
        
        // Check if user is enrolled
        $isEnrolled = false;
        if(auth()->check()) {
            $isEnrolled = $course->enrollments()->where('student_id', auth()->id())->exists();
        }

        return view('web.courses.show', compact('course', 'isEnrolled'));
    }

    public function purchase(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect()->route('view_login')->with('error', 'Please login to purchase the course');
        }

        $course = Course::findOrFail($id);
        $user = auth()->user();

        // Prevent double-enroll
        $already = \App\Models\Enrollment::where('course_id', $course->id)
                    ->where('user_id', $user->id)
                    ->exists();
        if ($already) {
            return redirect()->route('web.courses.show', $course->id)->with('message', ['type' => 'info', 'text' => 'You are already enrolled in this course.']);
        }

        // Simulate payment success (replace with gateway flow)
        $currencySvc = new \App\Services\CurrencyService();
        $baseCurrency = config('app.currency', 'EGP');
        $userCurrency = $user->currency ?? $currencySvc->currencyForCountry($user->country ?? null);
        $rate = $currencySvc->getRate($baseCurrency, $userCurrency);
        $converted = $currencySvc->convert($course->price ?? 0, $baseCurrency, $userCurrency);

        // Create enrollment
        $enrollment = \App\Models\Enrollment::create([
            'course_id' => $course->id,
            'user_id' => $user->id,
            'paid_amount' => $converted,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        // Create Payment record
        $payment = \App\Models\Payment::create([
            'paymentable_type' => \App\Models\Course::class,
            'paymentable_id' => $course->id,
            'type' => 'course',
            'amount' => $converted,
            'currency' => $userCurrency,
            'status' => 'paid',
            'method' => $request->payment_method ?? 'card',
            'reference' => 'course_' . $course->id . '_' . $user->id . '_' . time(),
            'original_amount' => $course->price ?? 0,
            'original_currency' => $baseCurrency,
            'exchange_rate' => $rate,
            'exchanged_at' => now(),
        ]);

        // Create vendor payment entry for the instructor (apply default commission)
        $defaultCommissionRate = 15.00; // 15%
        $subtotal = $converted;
        $commissionAmount = ($subtotal * $defaultCommissionRate) / 100;
        $vendorEarnings = $subtotal - $commissionAmount;

        \App\Models\VendorPayment::create([
            'vendor_id' => $course->instructor_id,
            'order_id' => null,
            'order_item_id' => null,
            'product_amount' => $course->price ?? 0,
            'quantity' => 1,
            'subtotal' => $subtotal,
            'commission_rate' => $defaultCommissionRate,
            'commission_amount' => $commissionAmount,
            'vendor_earnings' => $vendorEarnings,
            'status' => 'pending',
            'payment_id' => $payment->id,
            'payment_reference' => $payment->reference,
        ]);

        return redirect()->route('web.courses.show', $course->id)->with('message', ['type' => 'success', 'text' => 'Enrollment successful!']);
    }
}

