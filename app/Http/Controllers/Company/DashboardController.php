<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use App\Models\JobApplication;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ensure user is a company
        if ($user->type !== 'company') {
            return redirect()->route('dashboard.home')->with('error', 'Access denied.');
        }

        // Get company statistics
        $totalJobs = Job::where('clinic_id', $user->id)
            ->where('posted_by_type', 'company')
            ->count();
        
        $activeJobs = Job::where('clinic_id', $user->id)
            ->where('posted_by_type', 'company')
            ->where('is_active', true)
            ->count();
        
        $totalApplications = JobApplication::whereHas('job', function($query) use ($user) {
            $query->where('clinic_id', $user->id)
                  ->where('posted_by_type', 'company');
        })->count();
        
        $pendingApplications = JobApplication::whereHas('job', function($query) use ($user) {
            $query->where('clinic_id', $user->id)
                  ->where('posted_by_type', 'company');
        })->where('status', 'pending')->count();
        
        // Recent jobs
        $recentJobs = Job::where('clinic_id', $user->id)
            ->where('posted_by_type', 'company')
            ->withCount('applications')
            ->latest()
            ->limit(5)
            ->get();
        
        // Recent applications
        $recentApplications = JobApplication::whereHas('job', function($query) use ($user) {
            $query->where('clinic_id', $user->id)
                  ->where('posted_by_type', 'company');
        })
        ->with(['job', 'therapist.therapistProfile'])
        ->latest()
        ->limit(5)
        ->get();

        return view('web.company.dashboard', compact(
            'totalJobs',
            'activeJobs',
            'totalApplications',
            'pendingApplications',
            'recentJobs',
            'recentApplications'
        ));
    }
}

