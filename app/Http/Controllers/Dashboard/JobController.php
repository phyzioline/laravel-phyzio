<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:jobs-index', ['only' => ['index','store']]);
        $this->middleware('permission:jobs-create', ['only' => ['create','store']]);
        $this->middleware('permission:jobs-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:jobs-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        // Use 'clinic_jobs' table logic
        $data = Job::with('clinic')->latest()->paginate(10);
        return view('dashboard.jobs.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }
}
