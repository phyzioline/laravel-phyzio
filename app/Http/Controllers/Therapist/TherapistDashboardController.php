<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TherapistProfile;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TherapistDashboardController extends Controller
{
    public function index()
    {
        $therapist = TherapistProfile::where('user_id', Auth::id())->first();

        // If not onboarding, redirect (optional logic)
        // if (!$therapist || $therapist->status == 'new') return redirect()->route('therapist.onboarding.step1');

        $today = Carbon::today();

        // 1. KPI Cards Data
        $todaysAppointmentsCount = Appointment::where('therapist_id', $therapist->id)
                                            ->whereDate('appointment_date', $today)
                                            ->count();
        
        $activePatientsCount = Patient::where('therapist_id', $therapist->id)->where('status', 'active')->count();
        
        $completedSessionsWeek = Appointment::where('therapist_id', $therapist->id)
                                            ->where('status', 'completed')
                                            ->whereBetween('appointment_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                            ->count();
        
        $monthlyEarnings = $therapist->total_earnings; // Assuming this is aggregated elsewhere or calculated here

        // 2. Today's Timeline
        $todaysAppointments = Appointment::where('therapist_id', $therapist->id)
                                         ->whereDate('appointment_date', $today)
                                         ->orderBy('appointment_time')
                                         ->get();

        // 3. Active Patients List (Limit 5)
        $activePatients = Patient::where('therapist_id', $therapist->id)
                                 ->where('status', 'active')
                                 ->take(5)
                                 ->get();

        // 4. Pending Tasks
        $pendingNotes = Appointment::where('therapist_id', $therapist->id)
                                   ->where('status', 'completed')
                                   ->where('notes_completed', false)
                                   ->count();

        return view('web.therapist.dashboard', compact(
            'therapist', 
            'todaysAppointmentsCount', 
            'activePatientsCount', 
            'completedSessionsWeek', 
            'monthlyEarnings',
            'todaysAppointments',
            'activePatients',
            'pendingNotes'
        ));
    }
}
