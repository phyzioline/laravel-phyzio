<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use App\Services\TherapistPayoutService;
use Illuminate\Http\Request;

class EarningsController extends Controller
{
    protected $payoutService;

    public function __construct(TherapistPayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
    }

    public function index()
    {
        $user = auth()->user();
        $profile = \App\Models\TherapistProfile::where('user_id', $user->id)->first();

        // Get wallet summary
        $walletSummary = $this->payoutService->getWalletSummary($user->id);
        
        // Get payout history
        $payoutHistory = $this->payoutService->getTherapistPayouts($user->id);

        // 1. Home Visits Earnings
        $appointmentEarningsQuery = \App\Models\HomeVisit::where('therapist_id', $user->id)
            ->where('status', 'completed');
        
        $totalAppointmentEarnings = $appointmentEarningsQuery->sum('total_amount');
        $monthlyAppointmentEarnings = (clone $appointmentEarningsQuery)
            ->whereMonth('completed_at', now()->month)
            ->whereYear('completed_at', now()->year)
            ->sum('total_amount');
            
        // 2. Course Earnings
        $courseIds = \App\Models\Course::where('instructor_id', $user->id)->pluck('id');
        $enrollmentEarningsQuery = \App\Models\Enrollment::whereIn('course_id', $courseIds);

        $totalCourseEarnings = $enrollmentEarningsQuery->sum('paid_amount');
        $monthlyCourseEarnings = (clone $enrollmentEarningsQuery)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('paid_amount');

        // Totals
        $totalEarnings = $totalAppointmentEarnings + $totalCourseEarnings;
        $monthlyEarnings = $monthlyAppointmentEarnings + $monthlyCourseEarnings;

        // Pending Payouts (from wallet)
        $pendingPayouts = $walletSummary['pending_balance'] + $walletSummary['available_balance'];

        // Recent Transactions
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
            
        $transactions = $appointments;

        return view('web.therapist.earnings.index', compact(
            'totalEarnings', 
            'monthlyEarnings', 
            'pendingPayouts', 
            'transactions',
            'walletSummary',
            'payoutHistory',
            'profile'
        ));
    }

    /**
     * Request a payout.
     */
    public function requestPayout(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'payout_method' => 'required|in:bank_transfer,payoneer,wise',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $therapistId = auth()->id();
            $profile = \App\Models\TherapistProfile::where('user_id', $therapistId)->first();
            
            // Validate bank details if bank transfer is selected
            if ($request->payout_method === 'bank_transfer') {
                if (!$profile || !$profile->bank_name || !$profile->iban) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', 'Please complete your bank details in your profile before requesting a bank transfer payout.');
                }
            }
            
            $payout = $this->payoutService->requestPayout(
                $therapistId,
                $request->amount,
                $request->payout_method,
                $request->notes,
                $profile
            );

            return redirect()
                ->route('therapist.earnings.index')
                ->with('success', 'Payout request submitted successfully. Awaiting admin approval.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
