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

        // Load therapist schedules for working hours
        $schedules = \App\Models\TherapistSchedule::where('therapist_id', $therapist->user_id)
            ->where('is_active', true)
            ->get()
            ->groupBy('day_of_week');

        // Calculate next available slot
        $nextAvailableSlot = null;
        if ($schedules->count() > 0) {
            $today = now();
            for ($i = 0; $i < 7; $i++) {
                $checkDate = $today->copy()->addDays($i);
                $dayName = strtolower($checkDate->format('l'));
                if ($schedules->has($dayName)) {
                    $daySchedule = $schedules->get($dayName)->first();
                    $startTime = \Carbon\Carbon::parse($daySchedule->start_time);
                    if ($i == 0 && now()->format('H:i') < $startTime->format('H:i')) {
                        $nextAvailableSlot = $checkDate->copy()->setTimeFromTimeString($daySchedule->start_time);
                        break;
                    } elseif ($i > 0) {
                        $nextAvailableSlot = $checkDate->copy()->setTimeFromTimeString($daySchedule->start_time);
                        break;
                    }
                }
            }
        }

        return view('web.pages.home_visits.show', compact('therapist', 'schedules', 'nextAvailableSlot'));
    }

    public function book($id)
    {
        // Allow both logged-in and guest users to book
        $therapist = \App\Models\TherapistProfile::with('user')->findOrFail($id);
        
        // Load therapist availability schedule
        $schedules = \App\Models\TherapistSchedule::where('therapist_id', $therapist->user_id)
            ->where('is_active', true)
            ->get()
            ->groupBy('day_of_week');
        
        // Load existing bookings (to exclude booked slots)
        $existingBookings = \App\Models\HomeVisit::where('therapist_id', $therapist->user_id)
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'rejected')
            ->where('scheduled_at', '>=', now())
            ->get()
            ->map(function($visit) {
                return [
                    'date' => $visit->scheduled_at->format('Y-m-d'),
                    'time' => $visit->scheduled_at->format('H:i:s'),
                ];
            });
        
        // Generate available time slots for the next 30 days
        $availableSlots = $this->generateAvailableSlots($therapist->user_id, $schedules, $existingBookings);
        
        // Flatten schedules for view (grouped by day)
        $schedulesFlat = \App\Models\TherapistSchedule::where('therapist_id', $therapist->user_id)
            ->where('is_active', true)
            ->get()
            ->groupBy('day_of_week');
        
        return view('web.pages.home_visits.book', compact('therapist', 'schedules', 'availableSlots', 'existingBookings'));
    }
    
    /**
     * Generate available time slots based on therapist schedule and existing bookings.
     */
    protected function generateAvailableSlots($therapistId, $schedules, $existingBookings)
    {
        $slots = [];
        $startDate = now();
        $endDate = now()->addDays(30);
        
        // Group existing bookings by date for quick lookup
        $bookedSlotsByDate = $existingBookings->groupBy('date');
        
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dayName = strtolower($date->format('l')); // 'sunday', 'monday', etc.
            
            // Check if therapist works on this day
            if (!isset($schedules[$dayName]) || $schedules[$dayName]->isEmpty()) {
                continue; // Skip days therapist doesn't work
            }
            
            // Get schedule for this day
            $daySchedule = $schedules[$dayName]->first();
            $startTime = \Carbon\Carbon::parse($daySchedule->start_time);
            $endTime = \Carbon\Carbon::parse($daySchedule->end_time);
            $slotDuration = $daySchedule->slot_duration ?? 30; // Default 30 minutes
            
            // Generate time slots for this day
            $currentTime = $startTime->copy();
            $dateString = $date->format('Y-m-d');
            $bookedSlots = $bookedSlotsByDate->get($dateString, collect());
            
            while ($currentTime->copy()->addMinutes($slotDuration) <= $endTime) {
                $timeString = $currentTime->format('H:i:s');
                
                // Check if this slot is already booked
                $isBooked = $bookedSlots->contains(function($booking) use ($timeString, $slotDuration) {
                    $bookingTime = \Carbon\Carbon::parse($booking['time']);
                    $slotStart = \Carbon\Carbon::parse($timeString);
                    $slotEnd = $slotStart->copy()->addMinutes($slotDuration);
                    
                    // Check if booking overlaps with this slot
                    return $bookingTime >= $slotStart && $bookingTime < $slotEnd;
                });
                
                // Check if slot is in the past or too soon (less than 2 hours)
                $slotDateTime = $date->copy()->setTimeFromTimeString($timeString);
                $isValid = !$slotDateTime->isPast() && $slotDateTime->diffInHours(now()) >= 2;
                
                if (!$isBooked && $isValid) {
                    $slots[$dateString][] = [
                        'time' => $currentTime->format('H:i'),
                        'time_24' => $currentTime->format('H:i:s'),
                        'display' => $currentTime->format('g:i A'),
                    ];
                }
                
                $currentTime->addMinutes($slotDuration);
            }
        }
        
        return $slots;
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
        $requestedDateTime = \Carbon\Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);

        // ========================================
        // CRITICAL: Check Therapist Availability
        // ========================================
        // 1. Check for overlapping bookings
        $hasConflict = \App\Models\HomeVisit::where('therapist_id', $therapistProfile->user_id)
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'rejected')
            ->where(function ($query) use ($requestedDateTime) {
                // Check if new appointment overlaps with existing ones (assuming 1 hour duration)
                $start = $requestedDateTime;
                $end = $requestedDateTime->copy()->addHour();
                
                $query->whereBetween('appointment_time', [$start->format('H:i:s'), $end->format('H:i:s')])
                      ->where('appointment_date', $start->toDateString());
            })
            ->exists();

        if ($hasConflict) {
            return back()->withErrors([
                'appointment_time' => __('This therapist is already booked at this time.')
            ])->withInput();
        }

        // 2. Check therapist schedule
        $dayName = strtolower($requestedDateTime->format('l')); // 'sunday', 'monday', etc.
        
        $therapistSchedule = \App\Models\TherapistSchedule::where('therapist_id', $therapistProfile->user_id)
            ->where('day_of_week', $dayName)
            ->where('is_active', true)
            ->first();

        if (!$therapistSchedule) {
             return back()->withErrors([
                'appointment_date' => __('Therapist does not work on ' . ucfirst($dayName) . 's')
            ])->withInput();
        }

        if ($therapistSchedule) {
            $requestedTime = $requestedDateTime->format('H:i:s');
            
            // Check if requested time falls within therapist's working hours
            if ($requestedTime < $therapistSchedule->start_time || $requestedTime > $therapistSchedule->end_time) {
                return back()->withErrors([
                    'appointment_time' => __('This therapist is not available at this time. Working hours: :start - :end', [
                        'start' => \Carbon\Carbon::parse($therapistSchedule->start_time)->format('g:i A'),
                        'end' => \Carbon\Carbon::parse($therapistSchedule->end_time)->format('g:i A')
                    ])
                ])->withInput();
            }
        }

        // 3. Check if appointment is in the past
        if ($requestedDateTime->isPast()) {
            return back()->withErrors([
                'appointment_date' => __('Cannot book appointments in the past.')
            ])->withInput();
        }

        // 4. Check if appointment is too soon (require at least 2 hours notice)
        if ($requestedDateTime->diffInHours(now()) < 2) {
            return back()->withErrors([
                'appointment_date' => __('Please book at least 2 hours in advance.')
            ])->withInput();
        }

        // ========================================
        // End Availability Checking
        // ========================================

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

        // NOTE: Earnings will be added when visit is marked as 'completed'
        // See Therapist/HomeVisitController::complete() method
        // This ensures therapist only gets paid after service delivery

        return redirect()->route('web.home_visits.success', $id);
    }

    public function success($id)
    {
        $visit = \App\Models\HomeVisit::findOrFail($id);
        return view('web.pages.home_visits.success', compact('visit'));
    }
}
