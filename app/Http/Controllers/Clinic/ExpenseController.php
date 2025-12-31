<?php

namespace App\Http\Controllers\Clinic;

use App\Models\DailyExpense;
use App\Models\MonthlyExpenseSummary;
use App\Models\FinancialAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ExpenseController extends BaseClinicController
{
    public function index(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return view('web.clinic.expenses.index', [
                'expenses' => collect(),
                'stats' => [],
                'clinic' => null
            ]);
        }

        $query = DailyExpense::where('clinic_id', $clinic->id);

        // Filters
        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $expenses = $query->with('creator')->orderBy('expense_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        // Statistics
        $stats = [
            'total_expenses' => DailyExpense::where('clinic_id', $clinic->id)->sum('amount'),
            'this_month' => DailyExpense::where('clinic_id', $clinic->id)
                ->whereMonth('expense_date', now()->month)
                ->whereYear('expense_date', now()->year)
                ->sum('amount'),
            'last_month' => DailyExpense::where('clinic_id', $clinic->id)
                ->whereMonth('expense_date', now()->subMonth()->month)
                ->whereYear('expense_date', now()->subMonth()->year)
                ->sum('amount'),
            'by_category' => DailyExpense::where('clinic_id', $clinic->id)
                ->select('category', DB::raw('sum(amount) as total'))
                ->groupBy('category')
                ->pluck('total', 'category')
        ];

        return view('web.clinic.expenses.index', compact('expenses', 'stats', 'clinic'));
    }

    public function create()
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', __('Clinic not found'));
        }

        return view('web.clinic.expenses.create', compact('clinic'));
    }

    public function store(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->back()->with('error', __('Clinic not found'));
        }

        $validated = $request->validate([
            'expense_date' => 'required|date|before_or_equal:today',
            'category' => 'required|in:rent,salaries,utilities,medical_supplies,equipment_maintenance,marketing,transportation,miscellaneous',
            'description' => 'required|string|max:1000',
            'payment_method' => 'required|in:cash,bank_transfer,pos_card,mobile_wallet',
            'amount' => 'required|numeric|min:0.01',
            'vendor_supplier' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240'
        ]);

        $validated['clinic_id'] = $clinic->id;
        $validated['created_by'] = Auth::id();

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('expense-attachments', 'public');
        }

        $expense = DailyExpense::create($validated);

        // Log audit
        FinancialAuditLog::log(
            $clinic->id,
            'created',
            'daily_expense',
            $expense->id,
            Auth::id(),
            null,
            $expense->toArray(),
            'Daily expense created'
        );

        // Recalculate monthly summary
        MonthlyExpenseSummary::calculateForMonth(
            $clinic->id,
            $expense->expense_date->year,
            $expense->expense_date->month
        );

        return redirect()->route('clinic.expenses.index')
            ->with('success', __('Expense recorded successfully'));
    }

    public function show($id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', __('Clinic not found'));
        }

        $expense = DailyExpense::where('clinic_id', $clinic->id)->findOrFail($id);
        
        return view('web.clinic.expenses.show', compact('expense', 'clinic'));
    }

    public function edit($id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', __('Clinic not found'));
        }

        $expense = DailyExpense::where('clinic_id', $clinic->id)->findOrFail($id);
        
        return view('web.clinic.expenses.edit', compact('expense', 'clinic'));
    }

    public function update(Request $request, $id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->back()->with('error', __('Clinic not found'));
        }

        $expense = DailyExpense::where('clinic_id', $clinic->id)->findOrFail($id);
        
        $oldValues = $expense->toArray();

        $validated = $request->validate([
            'expense_date' => 'required|date|before_or_equal:today',
            'category' => 'required|in:rent,salaries,utilities,medical_supplies,equipment_maintenance,marketing,transportation,miscellaneous',
            'description' => 'required|string|max:1000',
            'payment_method' => 'required|in:cash,bank_transfer,pos_card,mobile_wallet',
            'amount' => 'required|numeric|min:0.01',
            'vendor_supplier' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240'
        ]);

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment
            if ($expense->attachment) {
                Storage::disk('public')->delete($expense->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('expense-attachments', 'public');
        }

        $expense->update($validated);

        // Log audit
        FinancialAuditLog::log(
            $clinic->id,
            'updated',
            'daily_expense',
            $expense->id,
            Auth::id(),
            $oldValues,
            $expense->toArray(),
            'Daily expense updated'
        );

        // Recalculate monthly summary
        MonthlyExpenseSummary::calculateForMonth(
            $clinic->id,
            $expense->expense_date->year,
            $expense->expense_date->month
        );

        return redirect()->route('clinic.expenses.index')
            ->with('success', __('Expense updated successfully'));
    }

    public function destroy($id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->back()->with('error', __('Clinic not found'));
        }

        $expense = DailyExpense::where('clinic_id', $clinic->id)->findOrFail($id);
        
        $oldValues = $expense->toArray();

        // Soft delete
        $expense->delete();

        // Log audit
        FinancialAuditLog::log(
            $clinic->id,
            'deleted',
            'daily_expense',
            $expense->id,
            Auth::id(),
            $oldValues,
            null,
            'Daily expense deleted (soft delete)'
        );

        // Recalculate monthly summary
        MonthlyExpenseSummary::calculateForMonth(
            $clinic->id,
            $expense->expense_date->year,
            $expense->expense_date->month
        );

        return redirect()->route('clinic.expenses.index')
            ->with('success', __('Expense deleted successfully'));
    }

    public function analytics()
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return view('web.clinic.expenses.analytics', [
                'clinic' => null,
                'monthlyTrend' => [],
                'categoryDistribution' => [],
                'dailyComparison' => [],
                'yearOverYear' => []
            ]);
        }

        // Monthly expense trend (last 12 months)
        $monthlyTrend = DailyExpense::where('clinic_id', $clinic->id)
            ->selectRaw('YEAR(expense_date) as year, MONTH(expense_date) as month, SUM(amount) as total')
            ->where('expense_date', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Category distribution (current month)
        $categoryDistribution = DailyExpense::where('clinic_id', $clinic->id)
            ->whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');

        // Daily comparison (current month)
        $dailyComparison = DailyExpense::where('clinic_id', $clinic->id)
            ->whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->selectRaw('DAY(expense_date) as day, SUM(amount) as total')
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->pluck('total', 'day');

        // Year-over-year growth
        $currentYear = DailyExpense::where('clinic_id', $clinic->id)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');
        
        $lastYear = DailyExpense::where('clinic_id', $clinic->id)
            ->whereYear('expense_date', now()->subYear()->year)
            ->sum('amount');
        
        $yearOverYear = [
            'current' => $currentYear,
            'last' => $lastYear,
            'growth' => $lastYear > 0 ? (($currentYear - $lastYear) / $lastYear) * 100 : 0
        ];

        return view('web.clinic.expenses.analytics', compact(
            'clinic',
            'monthlyTrend',
            'categoryDistribution',
            'dailyComparison',
            'yearOverYear'
        ));
    }
}

