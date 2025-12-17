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
        $user = Auth::user();
        $therapist = $user->therapistProfile;

        // Fallback if no profile
        if (!$therapist) {
           return redirect()->route('therapist.profile.edit')->with('warning', 'Please complete your profile');
        }

        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // 1. KPI Cards Data
        $todaysAppointmentsQuery = Appointment::where('therapist_id', $user->id)
                                            ->whereDate('appointment_date', $today);
        
        $todaysAppointmentsCount = $todaysAppointmentsQuery->count();
        $todaysAppointments = $todaysAppointmentsQuery->with('patient')->orderBy('appointment_time')->get();

        // Active Patients: Unique patients in the last 6 months
        $activePatientsCount = Appointment::where('therapist_id', $user->id)
                                        ->where('appointment_date', '>=', Carbon::now()->subMonths(6))
                                        ->distinct('patient_id')
                                        ->count('patient_id');

        // Pending Requests (Appointments with status 'pending')
        $pendingRequestsCount = Appointment::where('therapist_id', $user->id)
                                           ->where('status', 'pending')
                                           ->count();

        // Monthly Earnings
        $monthlyEarnings = Appointment::where('therapist_id', $user->id)
                                    ->where('status', 'completed')
                                    ->where('payment_status', 'paid')
                                    ->whereMonth('appointment_date', Carbon::now()->month)
                                    ->sum('price');
        
        // Calculate growth vs last month
        $lastMonthEarnings = Appointment::where('therapist_id', $user->id)
                                    ->where('status', 'completed')
                                    ->where('payment_status', 'paid')
                                    ->whereMonth('appointment_date', Carbon::now()->subMonth()->month)
                                    ->sum('price');
        
        $earningsGrowth = $lastMonthEarnings > 0 ? (($monthlyEarnings - $lastMonthEarnings) / $lastMonthEarnings) * 100 : 100;

        // 2. Chart Data (Last 7 Days)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('D'); // Mon, Tue...
            $chartData[] = Appointment::where('therapist_id', $user->id)
                                    ->whereDate('appointment_date', $date)
                                    ->count();
        }

        // 3. Recent Activity (Latest 5 completed appointments)
        $recentActivities = Appointment::where('therapist_id', $user->id)
                                    ->with('patient')
                                    ->latest('updated_at')
                                    ->take(5)
                                    ->get();

        return view('web.therapist.dashboard', compact(
            'therapist', 
            'todaysAppointmentsCount', 
            'activePatientsCount', 
            'pendingRequestsCount',
            'monthlyEarnings', 
            'earningsGrowth',
            'todaysAppointments',
            'chartLabels',
            'chartData',
            'recentActivities' // passed but seemingly used for "New Patient" static text previously
        ));
    }
}
