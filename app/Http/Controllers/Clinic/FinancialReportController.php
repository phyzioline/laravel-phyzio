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
        
        // Profit Margin Percentage
        $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;

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
        
        // Previous month comparison
        $prevMonth = $month - 1;
        $prevYear = $year;
        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear = $year - 1;
        }
        
        $prevMonthRevenue = PatientPayment::where('clinic_id', $clinicId)
            ->whereMonth('payment_date', $prevMonth)
            ->whereYear('payment_date', $prevYear)
            ->sum('payment_amount');
        
        $prevMonthExpenses = DailyExpense::where('clinic_id', $clinicId)
            ->whereMonth('expense_date', $prevMonth)
            ->whereYear('expense_date', $prevYear)
            ->sum('amount');
        
        $prevMonthProfit = $prevMonthRevenue - $prevMonthExpenses;
        
        // Calculate changes
        $revenueChange = $prevMonthRevenue > 0 ? (($totalRevenue - $prevMonthRevenue) / $prevMonthRevenue) * 100 : 0;
        $expenseChange = $prevMonthExpenses > 0 ? (($totalExpenses - $prevMonthExpenses) / $prevMonthExpenses) * 100 : 0;
        $profitChange = $prevMonthProfit != 0 ? (($netProfit - $prevMonthProfit) / abs($prevMonthProfit)) * 100 : 0;

        return [
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
            'profit_margin' => $profitMargin,
            'outstanding_balances' => $outstandingBalances,
            'paid_invoices' => $paidInvoices,
            'unpaid_invoices' => $unpaidInvoices,
            'previous_month' => [
                'revenue' => $prevMonthRevenue,
                'expenses' => $prevMonthExpenses,
                'profit' => $prevMonthProfit
            ],
            'changes' => [
                'revenue' => $revenueChange,
                'expenses' => $expenseChange,
                'profit' => $profitChange
            ]
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

    /**
     * Daily Cash Report (Printable)
     */
    public function dailyCashReport(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->back()->with('error', __('Clinic not found'));
        }

        $date = $request->get('date', now()->format('Y-m-d'));
        $reportDate = \Carbon\Carbon::parse($date);

        // Get all payments for the day
        $payments = PatientPayment::where('clinic_id', $clinic->id)
            ->whereDate('payment_date', $reportDate)
            ->with(['patient', 'invoice'])
            ->orderBy('payment_date', 'asc')
            ->get();

        // Group by payment method
        $paymentsByMethod = $payments->groupBy('payment_method');
        
        // Calculate totals by method
        $totalsByMethod = [];
        foreach ($paymentsByMethod as $method => $methodPayments) {
            $totalsByMethod[$method] = [
                'count' => $methodPayments->count(),
                'total' => $methodPayments->sum('payment_amount'),
                'payments' => $methodPayments
            ];
        }

        // Total cash collected
        $totalCash = $payments->sum('payment_amount');
        
        // Cash payments only (for cash drawer reconciliation)
        $cashPayments = $payments->where('payment_method', 'cash')->sum('payment_amount');
        
        // Get expenses for the day (cash expenses)
        $cashExpenses = DailyExpense::where('clinic_id', $clinic->id)
            ->whereDate('expense_date', $reportDate)
            ->where('payment_method', 'cash')
            ->sum('amount');

        // Net cash (collected - expenses)
        $netCash = $cashPayments - $cashExpenses;

        return view('web.clinic.reports.daily-cash', compact(
            'clinic',
            'reportDate',
            'payments',
            'totalsByMethod',
            'totalCash',
            'cashPayments',
            'cashExpenses',
            'netCash'
        ));
    }

    /**
     * Export financial report to Excel/CSV
     */
    public function export(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->back()->with('error', __('Clinic not found'));
        }

        $format = $request->get('format', 'csv');
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $monthName = \Carbon\Carbon::create($year, $month, 1)->format('F Y');

        // Get report data
        $monthlyReport = $this->getMonthlyReport($clinic->id, $month, $year);
        $patientReports = $this->getPatientReports($clinic->id, $month, $year);
        $expenseReports = $this->getExpenseReports($clinic->id, $month, $year);

        if ($format === 'pdf') {
            // Simple HTML to PDF using browser print
            return view('web.clinic.reports.export-pdf', compact(
                'clinic',
                'monthlyReport',
                'patientReports',
                'expenseReports',
                'month',
                'year',
                'monthName'
            ));
        } else {
            // CSV Export
            $filename = 'financial_report_' . $month . '_' . $year . '_' . date('YmdHis') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($clinic, $monthlyReport, $patientReports, $expenseReports, $monthName) {
                $file = fopen('php://output', 'w');
                
                // BOM for Excel UTF-8 support
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Header
                fputcsv($file, ['Financial Report - ' . $monthName]);
                fputcsv($file, ['Clinic: ' . $clinic->name]);
                fputcsv($file, ['Generated: ' . now()->format('Y-m-d H:i:s')]);
                fputcsv($file, []); // Empty row
                
                // Summary
                fputcsv($file, ['SUMMARY']);
                fputcsv($file, ['Total Revenue', number_format($monthlyReport['total_revenue'] ?? 0, 2) . ' EGP']);
                fputcsv($file, ['Total Expenses', number_format($monthlyReport['total_expenses'] ?? 0, 2) . ' EGP']);
                fputcsv($file, ['Net Profit', number_format($monthlyReport['net_profit'] ?? 0, 2) . ' EGP']);
                fputcsv($file, ['Profit Margin', number_format($monthlyReport['profit_margin'] ?? 0, 2) . '%']);
                fputcsv($file, ['Outstanding Balances', number_format($monthlyReport['outstanding_balances'] ?? 0, 2) . ' EGP']);
                fputcsv($file, []); // Empty row
                
                // Expenses by Category
                fputcsv($file, ['EXPENSES BY CATEGORY']);
                fputcsv($file, ['Category', 'Amount (EGP)', 'Count']);
                if (isset($expenseReports['by_category'])) {
                    foreach ($expenseReports['by_category'] as $category => $data) {
                        fputcsv($file, [
                            ucfirst(str_replace('_', ' ', $category)),
                            number_format($data->total ?? 0, 2),
                            $data->count ?? 0
                        ]);
                    }
                }
                fputcsv($file, []); // Empty row
                
                // Top Paying Patients
                fputcsv($file, ['TOP PAYING PATIENTS']);
                fputcsv($file, ['Patient', 'Total Paid (EGP)']);
                if (isset($patientReports['top_paying_patients'])) {
                    foreach ($patientReports['top_paying_patients'] as $patient) {
                        fputcsv($file, [
                            $patient['name'],
                            number_format($patient['total'], 2)
                        ]);
                    }
                }
                fputcsv($file, []); // Empty row
                
                // Outstanding Patients
                fputcsv($file, ['OUTSTANDING BALANCES']);
                fputcsv($file, ['Patient', 'Total Invoiced', 'Total Paid', 'Remaining Balance']);
                if (isset($patientReports['outstanding_patients'])) {
                    foreach ($patientReports['outstanding_patients'] as $patient) {
                        fputcsv($file, [
                            $patient['name'],
                            number_format($patient['total_invoiced'], 2),
                            number_format($patient['total_paid'], 2),
                            number_format($patient['remaining_balance'], 2)
                        ]);
                    }
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
    }
}

