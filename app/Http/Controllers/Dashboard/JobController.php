<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    // Constructor removed to fix middleware issue
    // Middleware should be handled in routes


    public function index(Request $request)
    {
        // Use 'clinic_jobs' table logic
        $data = Job::with('clinic')->latest()->paginate(10);
        return view('dashboard.jobs.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }
}
