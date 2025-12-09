<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Load therapist profile if it exists
        $user = Auth::user();
        $user->load('therapistProfile');
        
        // Get today's appointments for this therapist
        $todaysAppointments = \App\Models\Appointment::where('therapist_id', $user->id)
            ->whereDate('appointment_date', today())
            ->with(['patient'])
            ->orderBy('appointment_time')
            ->get();
        
        $todaysAppointmentsCount = $todaysAppointments->count();
        
        // Get active patients (distinct patients from recent appointments)
        $recentPatientIds = \App\Models\Appointment::where('therapist_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->where('appointment_date', '>=', now()->subMonths(3))
            ->distinct()
            ->pluck('patient_id');
        
        $activePatients = \App\Models\User::whereIn('id', $recentPatientIds)->take(10)->get();
        $activePatientsCount = $recentPatientIds->count();
        
        // Pending notes - appointments completed but no therapist notes
        $pendingNotes = \App\Models\Appointment::where('therapist_id', $user->id)
            ->where('status', 'completed')
            ->whereNull('therapist_notes')
            ->count();
        
        // Monthly earnings - sum of completed appointments this month
        $monthlyEarnings = \App\Models\Appointment::where('therapist_id', $user->id)
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereMonth('appointment_date', now()->month)
            ->whereYear('appointment_date', now()->year)
            ->sum('price');
        
        return view('web.therapist.dashboard', compact(
            'todaysAppointments',
            'todaysAppointmentsCount',
            'activePatients',
            'activePatientsCount',
            'pendingNotes',
            'monthlyEarnings'
        ));
    }
    
    public function profile()
    {
        $user = Auth::user();
        $user->load('therapistProfile');
        
        return view('web.therapist.profile');
    }
}
