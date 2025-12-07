<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $appointments = Appointment::where('therapist_id', $user->id)
            ->with('patient')
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);
            
        return view('therapist.appointments.index', compact('appointments'));
    }

    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::where('therapist_id', auth()->id())->findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        $appointment->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Appointment status updated.');
    }
}
