<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with('clinic', 'requirements')
            ->active()
            ->whereHas('clinic', function($q) {
                // Only show jobs from verified companies
                $q->where('verification_status', 'approved')
                  ->where('profile_visibility', 'visible');
            });
        
        // Search filter
        if ($request->has('search') && $request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('requirements', 'like', "%{$search}%");
            });
        }
        
        // Location filter
        if ($request->has('location') && $request->filled('location')) {
            $location = $request->location;
            $query->where(function($q) use ($location) {
                $q->where('location', 'like', "%{$location}%")
                  ->orWhere('city', 'like', "%{$location}%");
            });
        }
        
        if (auth()->check() && auth()->user()->type === 'therapist') {
            $service = new \App\Services\MatchingService();
            $allJobs = $query->get()->map(function($job) use ($service) {
                $matchData = $service->calculateScore($job, auth()->user());
                $job->match_score = $matchData['score'];
                $job->match_breakdown = $matchData['breakdown'];
                $job->match_summary = $matchData['summary'];
                return $job;
            })->sortByDesc('match_score');

            // Manual Pagination with query string
            $page = $request->get('page', 1);
            $perPage = 10;
            $items = $allJobs->forPage($page, $perPage);
            $jobs = new \Illuminate\Pagination\LengthAwarePaginator(
                $items, 
                $allJobs->count(), 
                $perPage, 
                $page, 
                [
                    'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                    'query' => $request->query()
                ]
            );

            return view('web.pages.jobs.index', compact('jobs'));
        }

        $jobs = $query->latest()->paginate(10)->withQueryString();
        return view('web.pages.jobs.index', compact('jobs'));
    }

    public function show($id)
    {
        $job = Job::with('clinic', 'requirements')
            ->active()
            ->whereHas('clinic', function($q) {
                $q->where('verification_status', 'approved')
                  ->where('profile_visibility', 'visible');
            })
            ->findOrFail($id);
        $matchScore = 0;
        $matchBreakdown = [];
        $matchSummary = [];
        if (auth()->check() && auth()->user()->type === 'therapist') {
            $service = new \App\Services\MatchingService();
            $matchData = $service->calculateScore($job, auth()->user());
            $matchScore = $matchData['score'];
            $matchBreakdown = $matchData['breakdown'];
            $matchSummary = $matchData['summary'];
        }
        $hasApplied = auth()->check() ? $job->applications()->where('therapist_id', auth()->id())->exists() : false;
        
        return view('web.pages.jobs.show', compact('job', 'matchScore', 'matchBreakdown', 'matchSummary', 'hasApplied'));
    }

    public function apply(Request $request, $id)
    {
        $job = Job::active()
            ->whereHas('clinic', function($q) {
                $q->where('verification_status', 'approved')
                  ->where('profile_visibility', 'visible');
            })
            ->findOrFail($id);
        
        if (!auth()->check() || auth()->user()->type !== 'therapist') {
            return redirect()->route('view_login.' . app()->getLocale())->with('error', 'You must be logged in as a therapist to apply.');
        }

        if ($job->applications()->where('therapist_id', auth()->id())->exists()) {
            return back()->with('error', 'You have already applied for this job.');
        }

        // Check active application limit (Max 10)
        $activeApplicationsCount = \App\Models\JobApplication::where('therapist_id', auth()->id())
            ->whereIn('status', ['pending', 'shortlisted', 'interview_scheduled'])
            ->count();

        if ($activeApplicationsCount >= 10) {
            return back()->with('error', 'You have reached the limit of 10 active job applications. Please withdraw from an existing application or wait for a decision.');
        }

        // Calculate score
        $service = new \App\Services\MatchingService();
        $matchData = $service->calculateScore($job, auth()->user());

        \App\Models\JobApplication::create([
            'job_id' => $job->id,
            'therapist_id' => auth()->id(),
            'match_score' => $matchData['score'],
            'status' => 'pending',
            'cover_letter' => $request->cover_letter ?? null,
        ]);

        return back()->with('success', 'Application submitted successfully!');
    }
}
