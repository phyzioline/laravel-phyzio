<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EarningsController extends Controller
{
    public function index()
    {
        // Mock data for earnings
        $totalEarnings = 12500.00;
        $monthlyEarnings = 2450.00;
        $pendingPayouts = 450.00;
        
        $transactions = collect([
            (object)['id' => '#TRX-9876', 'date' => 'Dec 23, 2024', 'patient' => 'John Doe', 'service' => 'Cardiology Consultation', 'amount' => 150.00, 'status' => 'Completed'],
            (object)['id' => '#TRX-9875', 'date' => 'Dec 22, 2024', 'patient' => 'Sarah Miller', 'service' => 'Follow-up', 'amount' => 100.00, 'status' => 'Pending'],
        ]);

        return view('web.therapist.earnings.index', compact('totalEarnings', 'monthlyEarnings', 'pendingPayouts', 'transactions'));
    }
}
