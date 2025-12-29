<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\EarningsTransaction;
use App\Models\User;
use Illuminate\Http\Request;

class EarningsController extends Controller
{
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
        ];

        // Get users for filter dropdown
        $users = User::whereHas('earningsTransactions')->orderBy('name')->get(['id', 'name']);

        return view('dashboard.earnings.index', compact('transactions', 'stats', 'users'));
    }

    public function show(EarningsTransaction $earningsTransaction)
    {
        $earningsTransaction->load(['user', 'sourceModel', 'payout']);
        return view('dashboard.earnings.show', compact('earningsTransaction'));
    }
}

