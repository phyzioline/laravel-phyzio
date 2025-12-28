<?php

namespace App\Http\Controllers\Clinic;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends BaseClinicController
{
    public function index()
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }

        // Get real notifications from database
        $notifications = collect();
        
        // Recent appointments
        $recentAppointments = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
            ->with('patient')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($appointment) {
                return (object)[
                    'title' => 'New Appointment Scheduled',
                    'message' => ($appointment->patient->first_name ?? 'Patient') . ' scheduled an appointment',
                    'time' => $appointment->created_at->diffForHumans(),
                    'type' => 'info',
                    'link' => route('clinic.appointments.index')
                ];
            });
        $notifications = $notifications->merge($recentAppointments);

        // Recent programs
        $recentPrograms = \App\Models\WeeklyProgram::where('clinic_id', $clinic->id)
            ->with('patient')
            ->latest()
            ->take(3)
            ->get()
            ->map(function($program) {
                return (object)[
                    'title' => 'New Treatment Program',
                    'message' => $program->program_name . ' created',
                    'time' => $program->created_at->diffForHumans(),
                    'type' => 'success',
                    'link' => route('clinic.programs.show', $program->id)
                ];
            });
        $notifications = $notifications->merge($recentPrograms);

        // Sort by time
        $notifications = $notifications->sortByDesc(function($notification) {
            return $notification->time;
        })->values();

        return view('web.clinic.notifications.index', compact('notifications', 'clinic'));
    }
}
