<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = \App\Models\Appointment::where('therapist_id', auth()->id())
            ->with(['patient', 'service'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        $upcoming = $appointments->where('status', 'scheduled')->where('appointment_date', '>=', now()->toDateString());
        $past = $appointments->where('appointment_date', '<', now()->toDateString());
        $cancelled = $appointments->where('status', 'cancelled');
        $completed = $appointments->where('status', 'completed');

        return view('web.therapist.appointments', compact('appointments', 'upcoming', 'past', 'cancelled', 'completed'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Logic to update appointment status
        return redirect()->back()->with('success', 'Appointment status updated.');
    }

    public function show($id)
    {
        // Mock appointment details
        $appointment = (object)[
            'id' => $id,
            'patient_name' => 'John Doe',
            'type' => 'Initial Consultation',
            'date' => '2024-12-12',
            'time' => '10:00 AM',
            'status' => 'Confirmed',
            'notes' => 'Patient complains of knee pain.'
        ];
        return view('web.therapist.appointments.show', compact('appointment'));
    }
}
