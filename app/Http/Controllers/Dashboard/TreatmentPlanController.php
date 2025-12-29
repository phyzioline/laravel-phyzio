<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TreatmentPlan;
use Illuminate\Http\Request;

class TreatmentPlanController extends Controller
{
    public function __construct()
    {
        // Check admin access in constructor
        if (!auth()->check() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin'))) {
            abort(403, 'Unauthorized. This section is restricted to administrators only.');
        }
    }

    public function index()
    {
        $plans = TreatmentPlan::with(['therapist', 'patient'])->latest()->paginate(10);
        return view('dashboard.plans.index', compact('plans'));
    }

    public function create()
    {
        // Add logic to fetch therapists and patients for selection
        return view('dashboard.plans.create');
    }

    public function store(Request $request)
    {
        // Validation and storage logic
        return redirect()->route('dashboard.plans.index');
    }

    // Add show, edit, update, destroy methods as needed
}
