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
}
