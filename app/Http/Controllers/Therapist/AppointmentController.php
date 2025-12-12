<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('web.therapist.appointments');
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
