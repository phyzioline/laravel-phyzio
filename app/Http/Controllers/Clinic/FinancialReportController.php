<?php

namespace App\Http\Controllers\Clinic;

use App\Models\DailyExpense;
use App\Models\Patient;
use App\Models\PatientInvoice;
use App\Models\PatientPayment;
use App\Models\MonthlyExpenseSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialReportController extends BaseClinicController
{
    public function index(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return view('web.clinic.reports.index', [
                'clinic' => null,
                'monthlyReport' => [],
                'patientReports' => [],
                'expenseReports' => []
            ]);
        }

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        // Monthly Financial Report
        $monthlyReport = $this->getMonthlyReport($clinic->id, $month, $year);

        // Patient Payment Reports
        $patientReports = $this->getPatientReports($clinic->id, $month, $year);

        // Expense Reports
        $expenseReports = $this->getExpenseReports($clinic->id, $month, $year);

        return view('web.clinic.reports.index', compact(
            'clinic',
            'monthlyReport',
            'patientReports',
            'expenseReports',
            'month',
            'year'
        ));
    }

    protected function getMonthlyReport($clinicId, $month, $year)
    {
        // Total Revenue (from patient payments)
        $totalRevenue = PatientPayment::where('clinic_id', $clinicId)
            ->whereMonth('payment_date', $month)
            ->whereYear('payment_date', $year)
            ->sum('payment_amount');

        // Total Expenses
        $totalExpenses = DailyExpense::where('clinic_id', $clinicId)
            ->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->sum('amount');

        // Net Profit/Loss
        $netProfit = $totalRevenue - $totalExpenses;

        // Outstanding Patient Balances
        $outstandingBalances = PatientInvoice::where('clinic_id', $clinicId)
            ->with('payments')
            ->get()
            ->sum(function($invoice) {
                return $invoice->remaining_balance;
            });

        // Paid vs Unpaid Invoices
        $paidInvoices = PatientInvoice::where('clinic_id', $clinicId)
            ->where('status', 'paid')
            ->count();
        
        $unpaidInvoices = PatientInvoice::where('clinic_id', $clinicId)
            ->whereIn('status', ['unpaid', 'partially_paid'])
            ->count();

        return [
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
            'outstanding_balances' => $outstandingBalances,
            'paid_invoices' => $paidInvoices,
            'unpaid_invoices' => $unpaidInvoices
        ];
    }

    protected function getPatientReports($clinicId, $month, $year)
    {
        // Patients with outstanding balances
        $outstandingPatients = Patient::where('clinic_id', $clinicId)
            ->with(['invoices.payments'])
            ->get()
            ->filter(function($patient) {
                return $patient->remaining_balance > 0;
            })
            ->map(function($patient) {
                return [
                    'id' => $patient->id,
                    'name' => $patient->full_name,
                    'total_invoiced' => $patient->total_invoiced,
                    'total_paid' => $patient->total_paid,
                    'remaining_balance' => $patient->remaining_balance
                ];
            })
            ->sortByDesc('remaining_balance')
            ->take(20);

        // Top paying patients
        $topPayingPatients = PatientPayment::where('clinic_id', $clinicId)
            ->whereMonth('payment_date', $month)
            ->whereYear('payment_date', $year)
            ->select('patient_id', DB::raw('SUM(payment_amount) as total'))
            ->groupBy('patient_id')
            ->orderBy('total', 'desc')
            ->with('patient')
            ->take(10)
            ->get()
            ->map(function($payment) {
                return [
                    'id' => $payment->patient_id,
                    'name' => $payment->patient->full_name ?? 'Unknown',
                    'total' => $payment->total
                ];
            });

        // Payment method distribution
        $paymentMethodDistribution = PatientPayment::where('clinic_id', $clinicId)
            ->whereMonth('payment_date', $month)
            ->whereYear('payment_date', $year)
            ->select('payment_method', DB::raw('SUM(payment_amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get()
            ->pluck(null, 'payment_method');

        // Partial vs full payments statistics
        $partialPayments = PatientInvoice::where('clinic_id', $clinicId)
            ->where('status', 'partially_paid')
            ->count();
        
        $fullPayments = PatientInvoice::where('clinic_id', $clinicId)
            ->where('status', 'paid')
            ->count();

        return [
            'outstanding_patients' => $outstandingPatients,
            'top_paying_patients' => $topPayingPatients,
            'payment_method_distribution' => $paymentMethodDistribution,
            'partial_payments' => $partialPayments,
            'full_payments' => $fullPayments
        ];
    }

    protected function getExpenseReports($clinicId, $month, $year)
    {
        // Expenses by category
        $expensesByCategory = DailyExpense::where('clinic_id', $clinicId)
            ->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->select('category', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->get()
            ->pluck(null, 'category');

        // Expenses by payment method
        $expensesByMethod = DailyExpense::where('clinic_id', $clinicId)
            ->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->select('payment_method', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get()
            ->pluck('total', 'payment_method');

        return [
            'by_category' => $expensesByCategory,
            'by_method' => $expensesByMethod
        ];
    }

    public function export(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->back()->with('error', __('Clinic not found'));
        }

        $format = $request->get('format', 'excel');
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        // Get report data
        $monthlyReport = $this->getMonthlyReport($clinic->id, $month, $year);
        $patientReports = $this->getPatientReports($clinic->id, $month, $year);
        $expenseReports = $this->getExpenseReports($clinic->id, $month, $year);

        if ($format === 'pdf') {
            // PDF export logic would go here
            // You can use a package like dompdf or barryvdh/laravel-dompdf
            return redirect()->back()->with('info', __('PDF export coming soon'));
        } else {
            // Excel export logic would go here
            // You can use a package like maatwebsite/excel
            return redirect()->back()->with('info', __('Excel export coming soon'));
        }
    }
}

