<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EarningsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Home Visits Earnings
        $appointmentEarningsQuery = \App\Models\HomeVisit::where('therapist_id', $user->id)
            ->where('status', 'completed');
        
        $totalAppointmentEarnings = $appointmentEarningsQuery->sum('total_amount');
        $monthlyAppointmentEarnings = (clone $appointmentEarningsQuery)
            ->whereMonth('completed_at', now()->month)
            ->whereYear('completed_at', now()->year)
            ->sum('total_amount');
            
        // 2. Course Earnings
        // Get courses taught by this user
        $courseIds = \App\Models\Course::where('instructor_id', $user->id)->pluck('id');
        $enrollmentEarningsQuery = \App\Models\Enrollment::whereIn('course_id', $courseIds); // Assuming paid_amount exists and status is completed/active

        $totalCourseEarnings = $enrollmentEarningsQuery->sum('paid_amount');
        $monthlyCourseEarnings = (clone $enrollmentEarningsQuery)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('paid_amount');

        // Totals
        $totalEarnings = $totalAppointmentEarnings + $totalCourseEarnings;
        $monthlyEarnings = $monthlyAppointmentEarnings + $monthlyCourseEarnings;

        // Pending Payouts (Assumption: Earnings not yet withdrawn/paid out)
        // For now, let's assume 'pending' status on appointments/enrollments implies pending payout or use a Wallet model if available. 
        // If not, we'll placeholder this or calculate based on payment_status = 'pending'
        $pendingAppointmentEarnings = \App\Models\HomeVisit::where('therapist_id', $user->id)
            ->where('status', 'completed')
            ->where('payment_status', 'pending') // Assuming such field exists or logic needs it
            ->sum('total_amount');
             
        $pendingPayouts = $pendingAppointmentEarnings; // + Course pending if applicable

        // Recent Transactions (Merge Visits and Enrollments)
        $appointments = \App\Models\HomeVisit::where('therapist_id', $user->id)
            ->latest('scheduled_at')
            ->take(5)
            ->get()
            ->map(function($appt) {
                return (object)[
                    'id' => '#VST-' . $appt->id,
                    'date' => $appt->scheduled_at ? $appt->scheduled_at->format('M d, Y') : 'N/A',
                    'patient' => optional($appt->patient)->name ?? 'Guest',
                    'service' => 'Home Visit',
                    'amount' => $appt->total_amount,
                    'status' => $appt->status
                ];
            });
            
        // You can merge with recent enrollments if desired
        $transactions = $appointments;

        return view('web.therapist.earnings.index', compact('totalEarnings', 'monthlyEarnings', 'pendingPayouts', 'transactions'));
    }
}
