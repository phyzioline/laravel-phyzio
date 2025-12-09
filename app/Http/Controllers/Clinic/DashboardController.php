<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\ClinicAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // $clinic = Clinic::where('company_id', Auth::id())->first(); // Assuming single clinic for now or tied to user
        $user = Auth::user();

        // 1. Key Metrics Cards
        $totalPatients = Patient::count();
        $activePlans = \DB::table('treatment_plans')->where('status', 'active')->count(); // Using DB builder if model not ready
        $todayAppointments = ClinicAppointment::whereDate('start_time', today())->count();
        $completedToday = ClinicAppointment::whereDate('start_time', today())->where('status', 'completed')->count();
        $outstandingPayments = \DB::table('invoices')->where('status', 'pending')->sum('amount');
        
        // 2. Appointments Timeline (Next 5)
        $timeline = ClinicAppointment::with(['patient', 'therapist'])
                        ->where('start_time', '>=', now())
                        ->orderBy('start_time', 'asc')
                        ->take(5)
                        ->get();

        // 3. Performance Chart Data (Mock for now, or aggregation)
        $monthlyPerformance = [
            'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            'data' => [12, 19, 3, 5] // Replace with real aggregation
        ];

        return view('web.clinic.dashboard', compact(
            'totalPatients', 
            'activePlans', 
            'todayAppointments', 
            'completedToday', 
            'outstandingPayments',
            'timeline',
            'monthlyPerformance'
        ));
    }
}
