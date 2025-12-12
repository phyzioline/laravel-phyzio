<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Mock data for company-analytics.html
        // Will pass data for charts
        $monthlyRevenue = [12000, 15000, 18000, 22000, 25000, 24000];
        $patientGrowth = [50, 60, 80, 100, 120, 150];
        
        return view('web.clinic.analytics.index', compact('monthlyRevenue', 'patientGrowth'));
    }
}
