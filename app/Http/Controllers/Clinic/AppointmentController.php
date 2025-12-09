<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\ClinicAppointment;
use App\Models\Patient;
use App\Models\User; // Assuming therapists are Users
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display the calendar view.
     */
    public function index(Request $request)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $appointments = ClinicAppointment::with(['patient', 'therapist'])
                        ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
                        ->get();

        $patients = Patient::all(); 
        $therapists = User::where('type', 'therapist')->get(); // Adjust based on user types

        return view('web.clinic.appointments.index', compact('appointments', 'startOfWeek', 'patients', 'therapists'));
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'therapist_id' => 'nullable|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'type' => 'required|string'
        ]);

        $start = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);
        $end = $start->copy()->addHour(); // Default 1 hour duration

        $appointment = new ClinicAppointment();
        $appointment->clinic_id = 1; // Default or Auth::user()->clinic_id
        $appointment->patient_id = $request->patient_id;
        $appointment->therapist_id = $request->therapist_id;
        $appointment->start_time = $start;
        $appointment->end_time = $end;
        $appointment->type = $request->type;
        $appointment->status = 'scheduled';
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment scheduled successfully.');
    }
}
