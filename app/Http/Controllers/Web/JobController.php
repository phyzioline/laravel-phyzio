<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::where('is_active', true)->latest()->paginate(10);
        return view('web.pages.jobs.index', compact('jobs'));
    }

    public function show($id)
    {
        $job = Job::where('is_active', true)->findOrFail($id);
        return view('web.pages.jobs.show', compact('job'));
    }
}
