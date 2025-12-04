<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\TherapistProfile::with('user')->where('status', 'approved');

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
        
        // Get unique specializations and areas for filters
        $specializations = \App\Models\TherapistProfile::where('status', 'approved')
            ->distinct()
            ->pluck('specialization');
            
        // For areas, we might need to collect them from JSON, but for now let's hardcode common ones or extract
        $areas = ['Nasr City', 'New Cairo', 'Maadi', 'Giza', 'Dokki', 'Mohandessin', 'Zamalek', 'Heliopolis', 'Sheikh Zayed', '6th of October'];

        return view('web.pages.appointments.index', compact('therapists', 'specializations', 'areas'));
    }

    public function show($id)
    {
        $therapist = \App\Models\TherapistProfile::with(['user', 'appointments' => function($q) {
            $q->where('status', 'completed');
        }])->findOrFail($id);

        return view('web.pages.appointments.show', compact('therapist'));
    }

    public function book($id)
    {
        // Ensure user is logged in
        if (!auth()->check()) {
            return redirect()->route('view_login')->with('error', 'Please login to book an appointment');
        }

        $therapist = \App\Models\TherapistProfile::with('user')->findOrFail($id);
        return view('web.pages.appointments.book', compact('therapist'));
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

        $appointment = new \App\Models\Appointment();
        $appointment->patient_id = auth()->id();
        $appointment->therapist_id = $therapistProfile->user_id;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->appointment_time = $request->appointment_date . ' ' . $request->appointment_time;
        $appointment->location_address = $request->location_address;
        $appointment->patient_notes = $request->patient_notes;
        $appointment->price = $therapistProfile->home_visit_rate;
        $appointment->status = 'pending'; // Waiting for therapist approval
        $appointment->payment_status = 'pending';
        $appointment->save();

        // Redirect to payment page
        return redirect()->route('web.appointments.payment', $appointment->id);
    }

    public function payment($id)
    {
        $appointment = \App\Models\Appointment::with(['therapist', 'patient'])->findOrFail($id);
        
        // If already paid, redirect to success
        if ($appointment->payment_status == 'paid') {
            return redirect()->route('web.appointments.success', $id);
        }

        return view('web.pages.appointments.payment', compact('appointment'));
    }

    public function processPayment(Request $request, $id)
    {
        $appointment = \App\Models\Appointment::findOrFail($id);
        
        // Simulate Payment Processing (Replace with Paymob/Stripe later)
        $appointment->payment_status = 'paid';
        $appointment->payment_method = $request->payment_method; // 'card' or 'cash'
        $appointment->save();

        return redirect()->route('web.appointments.success', $id);
    }

    public function success($id)
    {
        $appointment = \App\Models\Appointment::findOrFail($id);
        return view('web.pages.appointments.success', compact('appointment'));
    }
}
