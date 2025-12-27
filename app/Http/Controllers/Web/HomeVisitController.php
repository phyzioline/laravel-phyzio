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

        // Filter by gender (if added later)
        
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
        // Ensure user is logged in
        if (!auth()->check()) {
            return redirect()->route('view_login')->with('error', 'Please login to book a home visit');
        }

        $therapist = \App\Models\TherapistProfile::with('user')->findOrFail($id);
        return view('web.pages.home_visits.book', compact('therapist'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'therapist_id' => 'required|exists:therapist_profiles,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'location_address' => 'required|string',
            'patient_phone' => 'required|string',
        ]);

        // Get Therapist User ID
        $therapistProfile = \App\Models\TherapistProfile::findOrFail($request->therapist_id);

        $visit = new \App\Models\HomeVisit();
        $visit->patient_id = auth()->id();
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
        $userCurrency = $user->currency ?? $currencySvc->currencyForCountry($user->country_code ?? null);
        $exchangeRate = $currencySvc->getRate($baseCurrency, $userCurrency);
        $convertedAmount = $currencySvc->convert($visit->total_amount, $baseCurrency, $userCurrency);

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

        return redirect()->route('web.home_visits.success', $id);
    }

    public function success($id)
    {
        $visit = \App\Models\HomeVisit::findOrFail($id);
        return view('web.pages.home_visits.success', compact('visit'));
    }
}
