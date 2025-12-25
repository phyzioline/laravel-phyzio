<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->type !== 'company') {
            return redirect()->route('dashboard.home')->with('error', 'Access denied.');
        }

        $jobs = Job::where('clinic_id', $user->id)
            ->where('posted_by_type', 'company')
            ->withCount('applications')
            ->latest()
            ->get();
        
        return view('web.company.jobs.index', compact('jobs'));
    }

    public function applicants($id)
    {
        $user = Auth::user();
        
        if ($user->type !== 'company') {
            return redirect()->route('dashboard.home')->with('error', 'Access denied.');
        }

        $job = Job::where('clinic_id', $user->id)
            ->where('posted_by_type', 'company')
            ->where('id', $id)
            ->with(['applications.therapist.therapistProfile'])
            ->firstOrFail();
        
        return view('web.company.jobs.applicants', compact('job'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if ($user->type !== 'company') {
            return redirect()->route('dashboard.home')->with('error', 'Access denied.');
        }

        if ($user->status !== 'active') {
            return redirect()->route('company.dashboard')->with('error', 'Your account is pending approval. You cannot post jobs yet.');
        }

        $specialties = ['Orthopedic', 'Neurological', 'Pediatric', 'Sports', 'Geriatric', 'Women Health', 'Cardiopulmonary'];
        $techniques = ['Manual Therapy', 'Dry Needling', 'Cupping', 'Kinesiotaping', 'Electrotherapy', 'Exercise Therapy'];
        $equipment = ['Ultrasound', 'TENS', 'Laser', 'Shockwave', 'Treadmill', 'Balance Board'];

        return view('web.company.jobs.create', compact('specialties', 'techniques', 'equipment'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user->type !== 'company') {
            return redirect()->route('dashboard.home')->with('error', 'Access denied.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:job,training',
            'location' => 'nullable|string',
            'salary_type' => 'required|string',
            'salary_range' => 'nullable|string',
            'specialty' => 'nullable|array',
            'techniques' => 'nullable|array',
            'equipment' => 'nullable|array',
            'experience_level' => 'required|string',
            'urgency_level' => 'required|string',
            'openings_count' => 'integer|min:1',
            'min_years_experience' => 'nullable|integer',
            'gender_preference' => 'nullable|string',
            'license_required' => 'nullable|boolean',
        ]);

        $job = Job::create([
            'clinic_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'location' => $request->location,
            'salary_type' => $request->salary_type,
            'salary_range' => $request->salary_range,
            'specialty' => $request->specialty,
            'techniques' => $request->techniques,
            'equipment' => $request->equipment,
            'experience_level' => $request->experience_level,
            'urgency_level' => $request->urgency_level,
            'openings_count' => $request->openings_count ?? 1,
            'posted_by_type' => 'company',
            'is_active' => true,
        ]);

        $job->requirements()->create([
            'min_years_experience' => $request->min_years_experience ?? 0,
            'gender_preference' => $request->gender_preference,
            'license_required' => $request->has('license_required'),
        ]);

        return redirect()->route('company.jobs.index')->with('success', 'Job posted successfully!');
    }

    public function edit($id)
    {
        $user = Auth::user();
        
        if ($user->type !== 'company') {
            return redirect()->route('dashboard.home')->with('error', 'Access denied.');
        }

        $job = Job::where('clinic_id', $user->id)
            ->where('posted_by_type', 'company')
            ->where('id', $id)
            ->with('requirements')
            ->firstOrFail();

        $specialties = ['Orthopedic', 'Neurological', 'Pediatric', 'Sports', 'Geriatric', 'Women Health', 'Cardiopulmonary'];
        $techniques = ['Manual Therapy', 'Dry Needling', 'Cupping', 'Kinesiotaping', 'Electrotherapy', 'Exercise Therapy'];
        $equipment = ['Ultrasound', 'TENS', 'Laser', 'Shockwave', 'Treadmill', 'Balance Board'];

        return view('web.company.jobs.edit', compact('job', 'specialties', 'techniques', 'equipment'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if ($user->type !== 'company') {
            return redirect()->route('dashboard.home')->with('error', 'Access denied.');
        }

        $job = Job::where('clinic_id', $user->id)
            ->where('posted_by_type', 'company')
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:job,training',
            'location' => 'nullable|string',
            'salary_type' => 'required|string',
            'salary_range' => 'nullable|string',
            'specialty' => 'nullable|array',
            'techniques' => 'nullable|array',
            'equipment' => 'nullable|array',
            'experience_level' => 'required|string',
            'urgency_level' => 'required|string',
            'openings_count' => 'integer|min:1',
            'is_active' => 'nullable|boolean',
            'min_years_experience' => 'nullable|integer',
            'gender_preference' => 'nullable|string',
            'license_required' => 'nullable|boolean',
        ]);

        $job->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'location' => $request->location,
            'salary_type' => $request->salary_type,
            'salary_range' => $request->salary_range,
            'specialty' => $request->specialty,
            'techniques' => $request->techniques,
            'equipment' => $request->equipment,
            'experience_level' => $request->experience_level,
            'urgency_level' => $request->urgency_level,
            'openings_count' => $request->openings_count ?? 1,
            'is_active' => $request->has('is_active'),
        ]);

        if ($job->requirements) {
            $job->requirements->update([
                'min_years_experience' => $request->min_years_experience ?? 0,
                'gender_preference' => $request->gender_preference,
                'license_required' => $request->has('license_required'),
            ]);
        } else {
            $job->requirements()->create([
                'min_years_experience' => $request->min_years_experience ?? 0,
                'gender_preference' => $request->gender_preference,
                'license_required' => $request->has('license_required'),
            ]);
        }

        return redirect()->route('company.jobs.index')->with('success', 'Job updated successfully!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        
        if ($user->type !== 'company') {
            return redirect()->route('dashboard.home')->with('error', 'Access denied.');
        }

        $job = Job::where('clinic_id', $user->id)
            ->where('posted_by_type', 'company')
            ->where('id', $id)
            ->firstOrFail();
        
        $job->delete();
        
        return redirect()->route('company.jobs.index')->with('success', 'Job deleted successfully!');
    }

    public function updateApplicationStatus(Request $request, $jobId, $applicationId)
    {
        $user = Auth::user();
        
        if ($user->type !== 'company') {
            return redirect()->route('dashboard.home')->with('error', 'Access denied.');
        }

        $job = Job::where('clinic_id', $user->id)
            ->where('posted_by_type', 'company')
            ->where('id', $jobId)
            ->firstOrFail();

        $application = JobApplication::where('job_id', $job->id)
            ->where('id', $applicationId)
            ->firstOrFail();

        $request->validate([
            'status' => 'required|in:pending,reviewed,interviewed,hired,rejected',
        ]);

        $application->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Application status updated successfully!');
    }
}

