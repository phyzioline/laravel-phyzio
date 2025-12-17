<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;

class JobController extends Controller
{
    public function index()
    {
        $query = Job::with('clinic', 'requirements')->active();
        
        if (auth()->check() && auth()->user()->type === 'therapist') {
            $service = new \App\Services\MatchingService();
            $allJobs = $query->get()->map(function($job) use ($service) {
                $job->match_score = $service->calculateScore($job, auth()->user());
                return $job;
            })->sortByDesc('match_score');

            // Manual Pagination
            $page = Request::get('page', 1);
            $perPage = 10;
            $items = $allJobs->forPage($page, $perPage);
            $jobs = new \Illuminate\Pagination\LengthAwarePaginator(
                $items, 
                $allJobs->count(), 
                $perPage, 
                $page, 
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );

            return view('web.pages.jobs.index', compact('jobs'));
        }

        $jobs = $query->latest()->paginate(10);
        return view('web.pages.jobs.index', compact('jobs'));
    }

    public function show($id)
    {
        $job = Job::with('clinic', 'requirements')->active()->findOrFail($id);
        $matchScore = 0;
        if (auth()->check() && auth()->user()->type === 'therapist') {
            $service = new \App\Services\MatchingService();
            $matchScore = $service->calculateScore($job, auth()->user());
        }
        $hasApplied = auth()->check() ? $job->applications()->where('therapist_id', auth()->id())->exists() : false;
        
        return view('web.pages.jobs.show', compact('job', 'matchScore', 'hasApplied'));
    }

    public function apply(Request $request, $id)
    {
        $job = Job::active()->findOrFail($id);
        
        if (!auth()->check() || auth()->user()->type !== 'therapist') {
            return redirect()->route('view_login')->with('error', 'You must be logged in as a therapist to apply.');
        }

        if ($job->applications()->where('therapist_id', auth()->id())->exists()) {
            return back()->with('error', 'You have already applied for this job.');
        }

        // Calculate score
        $service = new \App\Services\MatchingService();
        $score = $service->calculateScore($job, auth()->user());

        \App\Models\JobApplication::create([
            'job_id' => $job->id,
            'therapist_id' => auth()->id(),
            'match_score' => $score,
            'status' => 'pending',
            'cover_letter' => $request->cover_letter ?? null,
        ]);

        return back()->with('success', 'Application submitted successfully!');
    }
}
