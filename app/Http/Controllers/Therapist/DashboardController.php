<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ensure therapist profile exists
        if (method_exists($user, 'therapistProfile')) {
            $user->load('therapistProfile');
        }
        
        $therapist = $user->therapistProfile;

        // Fallback if no profile
        if (!$therapist) {
           // In future, redirect to onboarding or show warning
           // return redirect()->route('therapist.profile.edit')->with('warning', 'Please complete your profile');
        }

        $today = now();
        
        // 1. KPI Cards Data
        // Use full class paths or import them at the top. Using full paths here for safety in replacement.
        $todaysAppointmentsQuery = \App\Models\Appointment::where('therapist_id', $user->id)
                                            ->whereDate('appointment_date', $today);
        
        $todaysAppointmentsCount = $todaysAppointmentsQuery->count();
        $todaysAppointments = $todaysAppointmentsQuery->with('patient')->orderBy('appointment_time')->get();

        // Active Patients: Unique patients in the last 6 months
        $activePatientsCount = \App\Models\Appointment::where('therapist_id', $user->id)
                                        ->where('appointment_date', '>=', now()->subMonths(6))
                                        ->distinct('patient_id')
                                        ->count('patient_id');

        // Pending Requests (Appointments with status 'pending')
        $pendingRequestsCount = \App\Models\Appointment::where('therapist_id', $user->id)
                                           ->where('status', 'pending')
                                           ->count();

        // Monthly Earnings
        $monthlyEarnings = \App\Models\Appointment::where('therapist_id', $user->id)
                                    ->where('status', 'completed')
                                    ->where('payment_status', 'paid')
                                    ->whereMonth('appointment_date', now()->month)
                                    ->sum('price');
        
        // Calculate growth vs last month
        $lastMonthEarnings = \App\Models\Appointment::where('therapist_id', $user->id)
                                    ->where('status', 'completed')
                                    ->where('payment_status', 'paid')
                                    ->whereMonth('appointment_date', now()->subMonth()->month)
                                    ->sum('price');
        
        $earningsGrowth = $lastMonthEarnings > 0 ? (($monthlyEarnings - $lastMonthEarnings) / $lastMonthEarnings) * 100 : 100;

        // 2. Chart Data (Last 7 Days)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('D'); // Mon, Tue...
            $chartData[] = \App\Models\Appointment::where('therapist_id', $user->id)
                                    ->whereDate('appointment_date', $date)
                                    ->count();
        }

        // 3. Recent Activity (Latest 5 completed appointments)
        $recentActivities = \App\Models\Appointment::where('therapist_id', $user->id)
                                    ->with('patient')
                                    ->latest('updated_at')
                                    ->take(5)
                                    ->get();

        return view('web.therapist.dashboard', [
            'therapist' => $therapist,
            'todaysAppointmentsCount' => $todaysAppointmentsCount,
            'activePatientsCount' => $activePatientsCount,
            'pendingRequestsCount' => $pendingRequestsCount,
            'monthlyEarnings' => $monthlyEarnings,
            'earningsGrowth' => $earningsGrowth,
            'todaysAppointments' => $todaysAppointments,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'recentActivities' => $recentActivities
        ]);
    }
    
    public function profile()
    {
        $user = Auth::user();
        if (method_exists($user, 'therapistProfile')) {
            $user->load('therapistProfile');
        }
        
        return view('web.therapist.profile');
    }
}
