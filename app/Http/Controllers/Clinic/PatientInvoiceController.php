<?php

namespace App\Http\Controllers\Clinic;

use App\Models\Patient;
use App\Models\PatientInvoice;
use App\Models\FinancialAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientInvoiceController extends BaseClinicController
{
    public function index(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return view('web.clinic.invoices.index', [
                'invoices' => collect(),
                'stats' => [],
                'clinic' => null
            ]);
        }

        $query = PatientInvoice::where('clinic_id', $clinic->id)
            ->with(['patient', 'payments']);

        // Filters
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        $invoices = $query->orderBy('invoice_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        // Statistics
        $stats = [
            'total_invoiced' => PatientInvoice::where('clinic_id', $clinic->id)->sum('final_amount'),
            'total_paid' => PatientInvoice::where('clinic_id', $clinic->id)
                ->with('payments')
                ->get()
                ->sum(function($invoice) {
                    return $invoice->total_paid;
                }),
            'outstanding' => PatientInvoice::where('clinic_id', $clinic->id)
                ->with('payments')
                ->get()
                ->sum(function($invoice) {
                    return $invoice->remaining_balance;
                }),
            'by_status' => PatientInvoice::where('clinic_id', $clinic->id)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
        ];

        $patients = Patient::where('clinic_id', $clinic->id)->get();

        return view('web.clinic.invoices.index', compact('invoices', 'stats', 'patients', 'clinic'));
    }

    public function create(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', __('Clinic not found'));
        }

        $patients = Patient::where('clinic_id', $clinic->id)->get();
        
        // Get pre-selected patient_id from query parameter
        $selectedPatientId = $request->get('patient_id');
        
        return view('web.clinic.invoices.create', compact('clinic', 'patients', 'selectedPatientId'));
    }

    public function store(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->back()->with('error', __('Clinic not found'));
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'treatment_plan' => 'nullable|string|max:500',
            'total_cost' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'invoice_date' => 'required|date|before_or_equal:today',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Verify patient belongs to clinic
        Patient::where('clinic_id', $clinic->id)
            ->findOrFail($validated['patient_id']);

        $validated['clinic_id'] = $clinic->id;
        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['final_amount'] = $validated['total_cost'] - $validated['discount'];
        $validated['status'] = 'unpaid';

        $invoice = PatientInvoice::create($validated);

        // Log audit
        FinancialAuditLog::log(
            $clinic->id,
            'created',
            'patient_invoice',
            $invoice->id,
            Auth::id(),
            null,
            $invoice->toArray(),
            'Patient invoice created'
        );

        return redirect()->route('clinic.invoices.index')
            ->with('success', __('Invoice created successfully'));
    }

    public function show($id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', __('Clinic not found'));
        }

        $invoice = PatientInvoice::where('clinic_id', $clinic->id)
            ->with(['patient', 'payments.receivedBy'])
            ->findOrFail($id);
        
        return view('web.clinic.invoices.show', compact('invoice', 'clinic'));
    }

    public function edit($id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', __('Clinic not found'));
        }

        $invoice = PatientInvoice::where('clinic_id', $clinic->id)->findOrFail($id);
        $patients = Patient::where('clinic_id', $clinic->id)->get();
        
        return view('web.clinic.invoices.edit', compact('invoice', 'clinic', 'patients'));
    }

    public function update(Request $request, $id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->back()->with('error', __('Clinic not found'));
        }

        $invoice = PatientInvoice::where('clinic_id', $clinic->id)->findOrFail($id);
        $oldValues = $invoice->toArray();

        // Prevent editing if fully paid
        if ($invoice->status === 'paid') {
            return redirect()->back()
                ->with('error', __('Cannot edit a fully paid invoice'));
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'treatment_plan' => 'nullable|string|max:500',
            'total_cost' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'invoice_date' => 'required|date|before_or_equal:today',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Verify patient belongs to clinic
        Patient::where('clinic_id', $clinic->id)
            ->findOrFail($validated['patient_id']);

        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['final_amount'] = $validated['total_cost'] - $validated['discount'];
        
        // Recalculate status based on payments
        $invoice->update($validated);
        $invoice->updateStatus();

        // Log audit
        FinancialAuditLog::log(
            $clinic->id,
            'updated',
            'patient_invoice',
            $invoice->id,
            Auth::id(),
            $oldValues,
            $invoice->toArray(),
            'Patient invoice updated'
        );

        return redirect()->route('clinic.invoices.index')
            ->with('success', __('Invoice updated successfully'));
    }

    public function destroy($id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->back()->with('error', __('Clinic not found'));
        }

        $invoice = PatientInvoice::where('clinic_id', $clinic->id)->findOrFail($id);
        $oldValues = $invoice->toArray();

        // Prevent deletion if has payments
        if ($invoice->payments()->count() > 0) {
            return redirect()->back()
                ->with('error', __('Cannot delete invoice with existing payments'));
        }

        // Soft delete
        $invoice->delete();

        // Log audit
        FinancialAuditLog::log(
            $clinic->id,
            'deleted',
            'patient_invoice',
            $invoice->id,
            Auth::id(),
            $oldValues,
            null,
            'Patient invoice deleted (soft delete)'
        );

        return redirect()->route('clinic.invoices.index')
            ->with('success', __('Invoice deleted successfully'));
    }
}

