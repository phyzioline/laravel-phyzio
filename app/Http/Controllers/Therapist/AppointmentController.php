<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HomeVisit;
use App\Services\HomeVisit\HomeVisitService;

class AppointmentController extends Controller
{
    protected $visitService;

    public function __construct(HomeVisitService $visitService)
    {
        // No middleware needed here as it's likely handled by routes or parent
        $this->visitService = $visitService;
    }

    public function index()
    {
        // 1. Clinical Appointments
        $appointments = \App\Models\Appointment::where('therapist_id', auth()->id())
            ->with(['patient'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        $upcoming = $appointments->where('status', 'scheduled')->where('appointment_date', '>=', now()->toDateString());
        $past = $appointments->where('appointment_date', '<', now()->toDateString());
        $cancelled = $appointments->where('status', 'cancelled');
        $completed = $appointments->where('status', 'completed');

        // 2. Home Visits (Merged Logic)
        $activeVisit = HomeVisit::where('therapist_id', auth()->id())
                        ->whereIn('status', ['accepted', 'on_way', 'in_session'])
                        ->with('patient')
                        ->first();
                        
        $availableVisits = HomeVisit::where('status', 'requested')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('web.therapist.appointments', compact(
            'appointments', 'upcoming', 'past', 'cancelled', 'completed',
            'activeVisit', 'availableVisits'
        ));
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
