<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        // Get the authenticated user's clinic
        $clinic = auth()->user()->clinic;
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard.' . app()->getLocale())
                ->with('error', __('Clinic not found'));
        }
        
        // Get patient invoices for this clinic
        $invoices = \App\Models\PatientInvoice::where('clinic_id', $clinic->id)
            ->with('patient')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('web.clinic.invoices.index', compact('invoices', 'clinic'));
    }
}
