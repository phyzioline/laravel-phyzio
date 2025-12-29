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

        // Get earnings transactions by source
        $earningsQuery = \App\Models\EarningsTransaction::where('user_id', $user->id);
        
        // 1. Home Visits Earnings
        $homeVisitEarnings = (clone $earningsQuery)->bySource('home_visit');
        $totalHomeVisitEarnings = $homeVisitEarnings->sum('net_earnings');
        $monthlyHomeVisitEarnings = (clone $homeVisitEarnings)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('net_earnings');
        $homeVisitAvailable = (clone $homeVisitEarnings)->byStatus('available')->sum('net_earnings');
        $homeVisitPending = (clone $homeVisitEarnings)->byStatus('pending')->sum('net_earnings');
            
        // 2. Course Earnings
        $courseEarnings = (clone $earningsQuery)->bySource('course');
        $totalCourseEarnings = $courseEarnings->sum('net_earnings');
        $monthlyCourseEarnings = (clone $courseEarnings)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('net_earnings');
        $courseAvailable = (clone $courseEarnings)->byStatus('available')->sum('net_earnings');
        $coursePending = (clone $courseEarnings)->byStatus('pending')->sum('net_earnings');

        // 3. Clinic Earnings (if therapist works at clinic)
        $clinicEarnings = (clone $earningsQuery)->bySource('clinic');
        $totalClinicEarnings = $clinicEarnings->sum('net_earnings');
        $monthlyClinicEarnings = (clone $clinicEarnings)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('net_earnings');
        $clinicAvailable = (clone $clinicEarnings)->byStatus('available')->sum('net_earnings');
        $clinicPending = (clone $clinicEarnings)->byStatus('pending')->sum('net_earnings');

        // Totals
        $totalEarnings = $totalHomeVisitEarnings + $totalCourseEarnings + $totalClinicEarnings;
        $monthlyEarnings = $monthlyHomeVisitEarnings + $monthlyCourseEarnings + $monthlyClinicEarnings;

        // Pending Payouts (from wallet)
        $pendingPayouts = $walletSummary['pending_balance'] + $walletSummary['available_balance'];

        // Recent Transactions from all sources
        $recentTransactions = \App\Models\EarningsTransaction::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get()
            ->map(function($trx) {
                $sourceName = match($trx->source) {
                    'home_visit' => __('Home Visit'),
                    'course' => __('Course'),
                    'clinic' => __('Clinic'),
                    default => __('Other')
                };
                
                return (object)[
                    'id' => '#EARN-' . $trx->id,
                    'date' => $trx->created_at->format('M d, Y'),
                    'source' => $sourceName,
                    'amount' => $trx->net_earnings,
                    'status' => ucfirst($trx->status),
                    'source_type' => $trx->source
                ];
            });

        return view('web.therapist.earnings.index', compact(
            'totalEarnings', 
            'monthlyEarnings', 
            'pendingPayouts', 
            'walletSummary',
            'payoutHistory',
            'profile',
            // Separated earnings by source
            'totalHomeVisitEarnings',
            'monthlyHomeVisitEarnings',
            'homeVisitAvailable',
            'homeVisitPending',
            'totalCourseEarnings',
            'monthlyCourseEarnings',
            'courseAvailable',
            'coursePending',
            'totalClinicEarnings',
            'monthlyClinicEarnings',
            'clinicAvailable',
            'clinicPending',
            'recentTransactions'
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
