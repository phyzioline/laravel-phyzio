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
            $pendingPayments = 0;
            $totalRevenue = 0;
            return view('web.clinic.billing.index', compact('invoices', 'pendingPayments', 'totalRevenue', 'clinic'));
        }

        // Get real invoices from database
        $invoices = collect();
        $pendingPayments = 0;
        $totalRevenue = 0;

        if (\Schema::hasTable('invoices')) {
            $invoices = DB::table('invoices')
                ->where('clinic_id', $clinic->id)
                ->leftJoin('patients', 'invoices.patient_id', '=', 'patients.id')
                ->select(
                    'invoices.*',
                    DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) as patient_name")
                )
                ->orderBy('invoices.created_at', 'desc')
                ->get()
                ->map(function($invoice) {
                    return (object)[
                        'id' => $invoice->invoice_number ?? 'INV-' . $invoice->id,
                        'patient' => $invoice->patient_name ?? 'Unknown',
                        'amount' => $invoice->amount ?? 0,
                        'date' => $invoice->created_at ?? now(),
                        'status' => $invoice->status ?? 'pending'
                    ];
                });

            $pendingPayments = DB::table('invoices')
                ->where('clinic_id', $clinic->id)
                ->where('status', 'pending')
                ->sum('amount');

            $totalRevenue = DB::table('invoices')
                ->where('clinic_id', $clinic->id)
                ->where('status', 'paid')
                ->sum('amount');
        }

        // If no invoices table, try payments table
        if ($invoices->isEmpty() && \Schema::hasTable('payments')) {
            $invoices = DB::table('payments')
                ->where('clinic_id', $clinic->id)
                ->leftJoin('patients', 'payments.patient_id', '=', 'patients.id')
                ->select(
                    'payments.*',
                    DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) as patient_name")
                )
                ->orderBy('payments.created_at', 'desc')
                ->get()
                ->map(function($payment) {
                    return (object)[
                        'id' => 'PAY-' . $payment->id,
                        'patient' => $payment->patient_name ?? 'Unknown',
                        'amount' => $payment->amount ?? 0,
                        'date' => $payment->created_at ?? now(),
                        'status' => $payment->status ?? 'pending'
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
        }
        
        return view('web.clinic.billing.index', compact('invoices', 'pendingPayments', 'totalRevenue', 'clinic'));
    }
}
