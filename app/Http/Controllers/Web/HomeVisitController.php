<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeVisitController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\TherapistProfile::with('user')
            ->where('status', 'approved')
            ->whereHas('user', function($q) {
                $q->where('verification_status', 'approved')
                  ->where('profile_visibility', 'visible');
            });

        // Filter by specialization
        if ($request->has('specialization') && $request->specialization != '') {
            $query->where('specialization', 'LIKE', '%' . $request->specialization . '%');
        }

        // Filter by area
        if ($request->has('area') && $request->area != '') {
            $query->whereJsonContains('available_areas', $request->area);
        }

        // Filter by gender
        if ($request->has('gender') && $request->gender != '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('gender', $request->gender);
            });
        }
        
        // Filter by availability (this would require availability schedule - simplified for now)
        // For now, we'll just filter therapists who have availability set
        
        // Filter by minimum rating
        if ($request->has('min_rating') && $request->min_rating != '') {
            $query->where('rating', '>=', $request->min_rating);
        }
        
        $therapists = $query->paginate(12);
        
        // Get unique specializations and areas for filters (only verified therapists)
        $specializations = \App\Models\TherapistProfile::where('status', 'approved')
            ->whereHas('user', function($q) {
                $q->where('verification_status', 'approved')
                  ->where('profile_visibility', 'visible');
            })
            ->distinct()
            ->pluck('specialization');
            
        // For areas, we might need to collect them from JSON, but for now let's hardcode common ones or extract
        $areas = ['Nasr City', 'New Cairo', 'Maadi', 'Giza', 'Dokki', 'Mohandessin', 'Zamalek', 'Heliopolis', 'Sheikh Zayed', '6th of October'];

        return view('web.pages.home_visits.index', compact('therapists', 'specializations', 'areas'));
    }

    public function show($id)
    {
        $therapist = \App\Models\TherapistProfile::with(['user', 'homeVisits' => function($q) {
            $q->where('status', 'completed');
        }])->whereHas('user', function($q) {
            $q->where('verification_status', 'approved')
              ->where('profile_visibility', 'visible');
        })->findOrFail($id);

        return view('web.pages.home_visits.show', compact('therapist'));
    }

    public function book($id)
    {
        // Allow both logged-in and guest users to book
        $therapist = \App\Models\TherapistProfile::with('user')->findOrFail($id);
        return view('web.pages.home_visits.book', compact('therapist'));
    }

    public function store(Request $request)
    {
        // Validation rules - different for guests vs logged-in users
        $rules = [
            'therapist_id' => 'required|exists:therapist_profiles,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'location_address' => 'required|string',
        ];

        if (auth()->check()) {
            // Logged-in user - phone is optional (can use account phone)
            $rules['patient_phone'] = 'nullable|string';
        } else {
            // Guest user - require name, email, and phone
            $rules['patient_name'] = 'required|string|max:255';
            $rules['patient_email'] = 'required|email|max:255';
            $rules['patient_phone'] = 'required|string|max:20';
        }

        $request->validate($rules);

        // Get Therapist User ID
        $therapistProfile = \App\Models\TherapistProfile::findOrFail($request->therapist_id);

        $visit = new \App\Models\HomeVisit();
        
        // Set patient_id if logged in, otherwise null for guest
        if (auth()->check()) {
            $visit->patient_id = auth()->id();
            $visit->is_guest_booking = false;
        } else {
            $visit->patient_id = null;
            $visit->is_guest_booking = true;
            $visit->guest_name = $request->patient_name;
            $visit->guest_email = $request->patient_email;
            $visit->guest_phone = $request->patient_phone;
        }
        
        $visit->therapist_id = $therapistProfile->user_id;
        $visit->scheduled_at = $request->appointment_date . ' ' . $request->appointment_time;
        $visit->address = $request->location_address;
        $visit->patient_notes = $request->patient_notes;
        $visit->total_amount = $therapistProfile->home_visit_rate;
        $visit->status = 'pending'; // Waiting for therapist approval
        $visit->payment_status = 'pending';
        $visit->save();

        // Redirect to payment page
        return redirect()->route('web.home_visits.payment', $visit->id);
    }

    public function payment($id)
    {
        $visit = \App\Models\HomeVisit::with(['therapist', 'patient'])->findOrFail($id);
        
        // If already paid, redirect to success
        if ($visit->payment_status == 'paid') {
            return redirect()->route('web.home_visits.success', $id);
        }

        return view('web.pages.home_visits.payment', compact('visit'));
    }

    public function processPayment(Request $request, $id)
    {
        $visit = \App\Models\HomeVisit::findOrFail($id);
        $user = auth()->user();

        // Simulate Payment Processing (Replace with Paymob/Stripe later)
        $visit->payment_status = 'paid';
        $visit->payment_method = $request->payment_method; // 'card' or 'cash'
        $visit->save();

        // Record payment in payments table with currency conversion
        $currencySvc = new \App\Services\CurrencyService();
        $baseCurrency = config('app.currency', 'EGP');
        
        // For guest bookings, use default currency or detect from phone/email if possible
        if ($user) {
            $userCurrency = $user->currency ?? $currencySvc->currencyForCountry($user->country_code ?? null);
        } else {
            // Guest booking - use default currency (EGP)
            $userCurrency = $baseCurrency;
        }
        
        $exchangeRate = $currencySvc->getRate($baseCurrency, $userCurrency);
        $convertedAmount = $currencySvc->convertFromTo($visit->total_amount, $baseCurrency, $userCurrency);

        \App\Models\Payment::create([
            'paymentable_type' => \App\Models\HomeVisit::class,
            'paymentable_id' => $visit->id,
            'type' => 'home_visit',
            'amount' => $convertedAmount,
            'currency' => $userCurrency,
            'status' => 'paid',
            'method' => $visit->payment_method,
            'reference' => 'visit_' . $visit->id . '_' . time(),
            'original_amount' => $visit->total_amount,
            'original_currency' => $baseCurrency,
            'exchange_rate' => $exchangeRate,
            'exchanged_at' => now(),
        ]);

        // Add earnings to therapist wallet when payment is processed
        if ($visit->status === 'completed' && $visit->total_amount > 0) {
            $payoutService = app(\App\Services\TherapistPayoutService::class);
            $payoutService->addEarnings($visit->therapist_id, $visit->total_amount, 14, 'home_visit'); // 14-day hold
        }

        return redirect()->route('web.home_visits.success', $id);
    }

    public function success($id)
    {
        $visit = \App\Models\HomeVisit::findOrFail($id);
        return view('web.pages.home_visits.success', compact('visit'));
    }
}
