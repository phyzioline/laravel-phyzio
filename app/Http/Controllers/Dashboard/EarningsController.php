<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\EarningsTransaction;
use App\Models\User;
use App\Services\EarningsSettlementService;
use Illuminate\Http\Request;

class EarningsController extends Controller
{
    protected $settlementService;

    public function __construct(EarningsSettlementService $settlementService)
    {
        $this->settlementService = $settlementService;
    }

    public function index(Request $request)
    {
        $query = EarningsTransaction::with('user');

        // Filter by source
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(50);

        // Statistics
        $stats = [
            'total_earnings' => EarningsTransaction::sum('net_earnings'),
            'total_platform_fees' => EarningsTransaction::sum('platform_fee'),
            'home_visit_earnings' => EarningsTransaction::bySource('home_visit')->sum('net_earnings'),
            'course_earnings' => EarningsTransaction::bySource('course')->sum('net_earnings'),
            'clinic_earnings' => EarningsTransaction::bySource('clinic')->sum('net_earnings'),
            'pending_earnings' => EarningsTransaction::byStatus('pending')->sum('net_earnings'),
            'available_earnings' => EarningsTransaction::byStatus('available')->sum('net_earnings'),
            'on_hold_earnings' => EarningsTransaction::byStatus('on_hold')->sum('net_earnings'),
        ];

        // Settlement statistics
        $settlementStats = $this->settlementService->getSettlementStats();

        // Get users for filter dropdown
        $users = User::whereHas('earningsTransactions')->orderBy('name')->get(['id', 'name']);

        return view('dashboard.earnings.index', compact('transactions', 'stats', 'users', 'settlementStats'));
    }

    public function show(EarningsTransaction $earningsTransaction)
    {
        $earningsTransaction->load(['user', 'sourceModel', 'payout']);
        return view('dashboard.earnings.show', compact('earningsTransaction'));
    }

    /**
     * Process settlements manually (admin action)
     */
    public function processSettlements(Request $request)
    {
        $result = $this->settlementService->processSettlements();
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Processed {$result['settled_count']} settlements",
                'data' => $result,
            ]);
        }
        
        return redirect()->back()->with('success', "Processed {$result['settled_count']} settlements. Total amount: " . number_format($result['total_amount'], 2));
    }

    /**
     * Manually settle a specific transaction
     */
    public function manualSettle(Request $request, EarningsTransaction $earningsTransaction)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $success = $this->settlementService->manualSettle($earningsTransaction->id, $request->notes);
        
        if ($success) {
            return redirect()->back()->with('success', __('Transaction settled successfully.'));
        }
        
        return redirect()->back()->with('error', __('Failed to settle transaction.'));
    }

    /**
     * Put transaction on hold
     */
    public function putOnHold(Request $request, EarningsTransaction $earningsTransaction)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $success = $this->settlementService->putOnHold($earningsTransaction->id, $request->reason);
        
        if ($success) {
            return redirect()->back()->with('success', __('Transaction put on hold successfully.'));
        }
        
        return redirect()->back()->with('error', __('Failed to put transaction on hold.'));
    }

    /**
     * Release transaction from hold
     */
    public function releaseFromHold(EarningsTransaction $earningsTransaction)
    {
        $success = $this->settlementService->releaseFromHold($earningsTransaction->id);
        
        if ($success) {
            return redirect()->back()->with('success', __('Transaction released from hold successfully.'));
        }
        
        return redirect()->back()->with('error', __('Failed to release transaction from hold.'));
    }
}

