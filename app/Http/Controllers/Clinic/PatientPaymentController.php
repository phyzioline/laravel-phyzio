<?php

namespace App\Http\Controllers\Clinic;

use App\Models\Patient;
use App\Models\PatientInvoice;
use App\Models\PatientPayment;
use App\Models\FinancialAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PatientPaymentController extends BaseClinicController
{
    public function index(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return view('web.clinic.payments.index', [
                'payments' => collect(),
                'stats' => [],
                'clinic' => null
            ]);
        }

        $query = PatientPayment::where('clinic_id', $clinic->id)
            ->with(['patient', 'invoice', 'receivedBy']);

        // Filters
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }
        if ($request->filled('invoice_id')) {
            $query->where('invoice_id', $request->invoice_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->orderBy('payment_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        // Statistics
        $stats = [
            'total_paid' => PatientPayment::where('clinic_id', $clinic->id)->sum('payment_amount'),
            'this_month' => PatientPayment::where('clinic_id', $clinic->id)
                ->whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('payment_amount'),
            'by_method' => PatientPayment::where('clinic_id', $clinic->id)
                ->selectRaw('payment_method, SUM(payment_amount) as total')
                ->groupBy('payment_method')
                ->pluck('total', 'payment_method')
        ];

        $patients = Patient::where('clinic_id', $clinic->id)->get();

        return view('web.clinic.payments.index', compact('payments', 'stats', 'patients', 'clinic'));
    }

    public function create(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', __('Clinic not found'));
        }

        $patients = Patient::where('clinic_id', $clinic->id)->get();
        $invoices = collect();
        
        if ($request->filled('patient_id')) {
            $invoices = PatientInvoice::where('clinic_id', $clinic->id)
                ->where('patient_id', $request->patient_id)
                ->whereIn('status', ['unpaid', 'partially_paid'])
                ->get();
        }

        return view('web.clinic.payments.create', compact('clinic', 'patients', 'invoices'));
    }

    public function store(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->back()->with('error', __('Clinic not found'));
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'invoice_id' => 'nullable|exists:patient_invoices,id',
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,pos_card,mobile_wallet,vodafone_cash,instapay',
            'notes' => 'nullable|string|max:1000',
            'receipt_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240'
        ]);

        // Verify patient belongs to clinic
        $patient = Patient::where('clinic_id', $clinic->id)
            ->findOrFail($validated['patient_id']);

        // If invoice is provided, verify it belongs to patient and clinic
        if ($validated['invoice_id']) {
            $invoice = PatientInvoice::where('clinic_id', $clinic->id)
                ->where('patient_id', $validated['patient_id'])
                ->findOrFail($validated['invoice_id']);
            
            // Check if payment exceeds remaining balance
            if ($validated['payment_amount'] > $invoice->remaining_balance) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', __('Payment amount exceeds remaining balance'));
            }
        }

        $validated['clinic_id'] = $clinic->id;
        $validated['received_by'] = Auth::id();

        // Handle file upload
        if ($request->hasFile('receipt_path')) {
            $validated['receipt_path'] = $request->file('receipt_path')->store('payment-receipts', 'public');
        }

        $payment = PatientPayment::create($validated);

        // Log audit
        FinancialAuditLog::log(
            $clinic->id,
            'created',
            'patient_payment',
            $payment->id,
            Auth::id(),
            null,
            $payment->toArray(),
            'Patient payment recorded'
        );

        // Invoice status will be updated automatically via model events

        // Handle Save & Continue
        if ($request->has('_save_continue')) {
            return redirect()->route('clinic.payments.create')
                ->with('success', __('Payment recorded successfully. You can record another payment.'));
        }

        return redirect()->route('clinic.payments.index')
            ->with('success', __('Payment recorded successfully'));
    }

    public function show($id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', __('Clinic not found'));
        }

        $payment = PatientPayment::where('clinic_id', $clinic->id)
            ->with(['patient', 'invoice', 'receivedBy'])
            ->findOrFail($id);
        
        return view('web.clinic.payments.show', compact('payment', 'clinic'));
    }

    public function edit($id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', __('Clinic not found'));
        }

        $payment = PatientPayment::where('clinic_id', $clinic->id)->findOrFail($id);
        $patients = Patient::where('clinic_id', $clinic->id)->get();
        $invoices = PatientInvoice::where('clinic_id', $clinic->id)
            ->where('patient_id', $payment->patient_id)
            ->get();
        
        return view('web.clinic.payments.edit', compact('payment', 'clinic', 'patients', 'invoices'));
    }

    public function update(Request $request, $id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->back()->with('error', __('Clinic not found'));
        }

        $payment = PatientPayment::where('clinic_id', $clinic->id)->findOrFail($id);
        $oldValues = $payment->toArray();

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'invoice_id' => 'nullable|exists:patient_invoices,id',
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,pos_card,mobile_wallet,vodafone_cash,instapay',
            'notes' => 'nullable|string|max:1000',
            'receipt_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240'
        ]);

        // Verify patient belongs to clinic
        Patient::where('clinic_id', $clinic->id)
            ->findOrFail($validated['patient_id']);

        // If invoice is provided, verify it
        if ($validated['invoice_id']) {
            $invoice = PatientInvoice::where('clinic_id', $clinic->id)
                ->where('patient_id', $validated['patient_id'])
                ->findOrFail($validated['invoice_id']);
            
            // Check if payment exceeds remaining balance (excluding current payment)
            $otherPayments = $invoice->payments()->where('id', '!=', $payment->id)->sum('payment_amount');
            $remaining = $invoice->final_amount - $otherPayments;
            
            if ($validated['payment_amount'] > $remaining) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', __('Payment amount exceeds remaining balance'));
            }
        }

        // Handle file upload
        if ($request->hasFile('receipt_path')) {
            // Delete old receipt
            if ($payment->receipt_path) {
                Storage::disk('public')->delete($payment->receipt_path);
            }
            $validated['receipt_path'] = $request->file('receipt_path')->store('payment-receipts', 'public');
        }

        $payment->update($validated);

        // Log audit
        FinancialAuditLog::log(
            $clinic->id,
            'updated',
            'patient_payment',
            $payment->id,
            Auth::id(),
            $oldValues,
            $payment->toArray(),
            'Patient payment updated'
        );

        return redirect()->route('clinic.payments.index')
            ->with('success', __('Payment updated successfully'));
    }

    public function destroy($id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->back()->with('error', __('Clinic not found'));
        }

        $payment = PatientPayment::where('clinic_id', $clinic->id)->findOrFail($id);
        $oldValues = $payment->toArray();

        // Soft delete
        $payment->delete();

        // Log audit
        FinancialAuditLog::log(
            $clinic->id,
            'deleted',
            'patient_payment',
            $payment->id,
            Auth::id(),
            $oldValues,
            null,
            'Patient payment deleted (soft delete)'
        );

        return redirect()->route('clinic.payments.index')
            ->with('success', __('Payment deleted successfully'));
    }
}

