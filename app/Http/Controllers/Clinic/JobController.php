<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::where('clinic_id', Auth::id())->latest()->get();
        return view('web.clinic.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('web.clinic.jobs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:job,training',
            'location' => 'nullable|string',
            'salary_range' => 'nullable|string',
        ]);

        $job = new Job();
        $job->clinic_id = Auth::id();
        $job->title = $request->title;
        $job->description = $request->description;
        $job->type = $request->type;
        $job->location = $request->location;
        $job->salary_range = $request->salary_range;
        $job->save();

        return redirect()->route('clinic.jobs.index')->with('success', 'Job posted successfully!');
    }

    public function destroy($id)
    {
        $job = Job::where('clinic_id', Auth::id())->where('id', $id)->firstOrFail();
        $job->delete();
        return redirect()->route('clinic.jobs.index')->with('success', 'Job deleted successfully!');
    }
}
