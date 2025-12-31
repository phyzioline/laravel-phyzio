<?php

namespace App\Http\Controllers\Clinic;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends BaseClinicController
{
    public function index()
    {
        $clinic = $this->getUserClinic();
        
        // Show empty state instead of redirecting
        if (!$clinic) {
            $invoices = collect();
            $allInvoices = collect();
            $pendingPayments = 0;
            $totalRevenue = 0;
            $thisMonthRevenue = 0;
            $lastMonthRevenue = 0;
            $revenueGrowth = 0;
            $programRevenue = 0;
            $paymentMethodDistribution = [];
            $outstandingPrograms = 0;
            return view('web.clinic.billing.index', compact(
                'invoices', 
                'allInvoices',
                'pendingPayments', 
                'totalRevenue',
                'thisMonthRevenue',
                'lastMonthRevenue',
                'revenueGrowth',
                'programRevenue',
                'paymentMethodDistribution',
                'outstandingPrograms',
                'clinic'
            ));
        }

        // Get invoices from database
        $invoices = collect();
        $pendingPayments = 0;
        $totalRevenue = 0;
        $thisMonthRevenue = 0;
        $lastMonthRevenue = 0;

        // Try invoices table first
        if (\Schema::hasTable('invoices')) {
            $invoices = DB::table('invoices')
                ->join('patients', 'invoices.patient_id', '=', 'patients.id')
                ->where('patients.clinic_id', $clinic->id)
                ->select(
                    'invoices.*',
                    DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) as patient_name")
                )
                ->orderBy('invoices.created_at', 'desc')
                ->limit(50)
                ->get()
                ->map(function($invoice) {
                    return (object)[
                        'id' => $invoice->invoice_number ?? 'INV-' . $invoice->id,
                        'patient' => $invoice->patient_name ?? 'Unknown',
                        'amount' => $invoice->amount ?? 0,
                        'date' => $invoice->created_at ?? now(),
                        'status' => $invoice->status ?? 'pending',
                        'type' => 'invoice'
                    ];
                });

            $pendingPayments = DB::table('invoices')
                ->join('patients', 'invoices.patient_id', '=', 'patients.id')
                ->where('patients.clinic_id', $clinic->id)
                ->where('invoices.status', 'pending')
                ->sum('invoices.amount');

            $totalRevenue = DB::table('invoices')
                ->join('patients', 'invoices.patient_id', '=', 'patients.id')
                ->where('patients.clinic_id', $clinic->id)
                ->where('invoices.status', 'paid')
                ->sum('invoices.amount');
                
            $thisMonthRevenue = DB::table('invoices')
                ->join('patients', 'invoices.patient_id', '=', 'patients.id')
                ->where('patients.clinic_id', $clinic->id)
                ->where('invoices.status', 'paid')
                ->whereMonth('invoices.created_at', now()->month)
                ->whereYear('invoices.created_at', now()->year)
                ->sum('invoices.amount');
                
            $lastMonthRevenue = DB::table('invoices')
                ->join('patients', 'invoices.patient_id', '=', 'patients.id')
                ->where('patients.clinic_id', $clinic->id)
                ->where('invoices.status', 'paid')
                ->whereMonth('invoices.created_at', now()->subMonth()->month)
                ->whereYear('invoices.created_at', now()->subMonth()->year)
                ->sum('invoices.amount');
        }

        // If no invoices, try payments table
        if ($invoices->isEmpty() && \Schema::hasTable('payments')) {
            // Check if payments table has clinic_id column
            $hasClinicId = \Schema::hasColumn('payments', 'clinic_id');
            
            if ($hasClinicId) {
                $invoices = DB::table('payments')
                    ->where('clinic_id', $clinic->id)
                    ->leftJoin('patients', 'payments.patient_id', '=', 'patients.id')
                    ->select(
                        'payments.*',
                        DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) as patient_name")
                    )
                    ->orderBy('payments.created_at', 'desc')
                    ->limit(50)
                    ->get()
                    ->map(function($payment) {
                        return (object)[
                            'id' => 'PAY-' . $payment->id,
                            'patient' => $payment->patient_name ?? 'Unknown',
                            'amount' => $payment->amount ?? 0,
                            'date' => $payment->created_at ?? now(),
                            'status' => $payment->status ?? 'pending',
                            'type' => 'payment'
                        ];
                    });

                $pendingPayments = DB::table('payments')
                    ->where('clinic_id', $clinic->id)
                    ->where('status', 'pending')
                    ->sum('amount');

                $totalRevenue = DB::table('payments')
                    ->where('clinic_id', $clinic->id)
                    ->where('status', 'paid')
                    ->sum('amount');
                    
                $thisMonthRevenue = DB::table('payments')
                    ->where('clinic_id', $clinic->id)
                    ->where('status', 'paid')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('amount');
                    
                $lastMonthRevenue = DB::table('payments')
                    ->where('clinic_id', $clinic->id)
                    ->where('status', 'paid')
                    ->whereMonth('created_at', now()->subMonth()->month)
                    ->whereYear('created_at', now()->subMonth()->year)
                    ->sum('amount');
            } else {
                // If payments table doesn't have clinic_id, check if it has patient_id
                $hasPatientId = \Schema::hasColumn('payments', 'patient_id');
                
                if ($hasPatientId) {
                    // Join through patients table
                    $invoices = DB::table('payments')
                        ->join('patients', 'payments.patient_id', '=', 'patients.id')
                        ->where('patients.clinic_id', $clinic->id)
                        ->select(
                            'payments.*',
                            DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) as patient_name")
                        )
                        ->orderBy('payments.created_at', 'desc')
                        ->limit(50)
                        ->get()
                        ->map(function($payment) {
                            return (object)[
                                'id' => 'PAY-' . $payment->id,
                                'patient' => $payment->patient_name ?? 'Unknown',
                                'amount' => $payment->amount ?? 0,
                                'date' => $payment->created_at ?? now(),
                                'status' => $payment->status ?? 'pending',
                                'type' => 'payment'
                            ];
                        });

                    $pendingPayments = DB::table('payments')
                        ->join('patients', 'payments.patient_id', '=', 'patients.id')
                        ->where('patients.clinic_id', $clinic->id)
                        ->where('payments.status', 'pending')
                        ->sum('payments.amount');

                    $totalRevenue = DB::table('payments')
                        ->join('patients', 'payments.patient_id', '=', 'patients.id')
                        ->where('patients.clinic_id', $clinic->id)
                        ->where('payments.status', 'paid')
                        ->sum('payments.amount');
                        
                    $thisMonthRevenue = DB::table('payments')
                        ->join('patients', 'payments.patient_id', '=', 'patients.id')
                        ->where('patients.clinic_id', $clinic->id)
                        ->where('payments.status', 'paid')
                        ->whereMonth('payments.created_at', now()->month)
                        ->whereYear('payments.created_at', now()->year)
                        ->sum('payments.amount');
                        
                    $lastMonthRevenue = DB::table('payments')
                        ->join('patients', 'payments.patient_id', '=', 'patients.id')
                        ->where('patients.clinic_id', $clinic->id)
                        ->where('payments.status', 'paid')
                        ->whereMonth('payments.created_at', now()->subMonth()->month)
                        ->whereYear('payments.created_at', now()->subMonth()->year)
                        ->sum('payments.amount');
                }
            }
        }

        // Add program payments
        $programPayments = \App\Models\WeeklyProgram::where('clinic_id', $clinic->id)
            ->where('paid_amount', '>', 0)
            ->with('patient')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function($program) {
                return (object)[
                    'id' => 'PROG-' . $program->id,
                    'patient' => ($program->patient->first_name ?? '') . ' ' . ($program->patient->last_name ?? ''),
                    'amount' => $program->paid_amount,
                    'date' => $program->created_at,
                    'status' => $program->remaining_balance > 0 ? 'partial' : 'paid',
                    'type' => 'program'
                ];
            });
        
        // Merge invoices and program payments
        $allInvoices = $invoices->merge($programPayments)
            ->sortByDesc(function($item) {
                if ($item->date instanceof \Carbon\Carbon) {
                    return $item->date->timestamp;
                }
                return \Carbon\Carbon::parse($item->date)->timestamp;
            })
            ->take(50)
            ->values();
        
        // Add program revenue to totals
        $programRevenue = \App\Models\WeeklyProgram::where('clinic_id', $clinic->id)
            ->sum('paid_amount');
        
        $totalRevenue += $programRevenue;
        
        // Calculate revenue growth
        $revenueGrowth = $lastMonthRevenue > 0 
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) 
            : ($thisMonthRevenue > 0 ? 100 : 0);
        
        // Payment method distribution
        $paymentMethodDistribution = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
            ->whereNotNull('payment_method')
            ->select('payment_method', DB::raw('count(*) as count'))
            ->groupBy('payment_method')
            ->get()
            ->pluck('count', 'payment_method')
            ->toArray();
        
        // Outstanding balances from programs
        $outstandingPrograms = \App\Models\WeeklyProgram::where('clinic_id', $clinic->id)
            ->where('remaining_balance', '>', 0)
            ->sum('remaining_balance');
        
        $pendingPayments += $outstandingPrograms;
        
        return view('web.clinic.billing.index', compact(
            'invoices', 
            'allInvoices',
            'pendingPayments', 
            'totalRevenue',
            'thisMonthRevenue',
            'lastMonthRevenue',
            'revenueGrowth',
            'programRevenue',
            'paymentMethodDistribution',
            'outstandingPrograms',
            'clinic'
        ));
    }
}
