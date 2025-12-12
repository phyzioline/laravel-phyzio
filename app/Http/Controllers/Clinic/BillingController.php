<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index()
    {
        // Mock billing data
        $invoices = collect([
            (object)['id' => 'INV-2024-001', 'patient' => 'John Doe', 'amount' => 150.00, 'date' => '2024-12-10', 'status' => 'Paid'],
            (object)['id' => 'INV-2024-002', 'patient' => 'Jane Smith', 'amount' => 200.00, 'date' => '2024-12-11', 'status' => 'Pending'],
        ]);
        return view('web.clinic.billing.index', compact('invoices'));
    }
}
