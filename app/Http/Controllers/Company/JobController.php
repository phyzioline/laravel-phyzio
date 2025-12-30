<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobTemplate;
use App\Models\InterviewSchedule;
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

        try {
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }

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

    /**
     * Bulk update application statuses
     */
    public function bulkUpdateApplications(Request $request, $jobId)
    {
        $user = Auth::user();
        
        if ($user->type !== 'company') {
            return redirect()->route('dashboard.home')->with('error', 'Access denied.');
        }

        $job = Job::where('clinic_id', $user->id)
            ->where('posted_by_type', 'company')
            ->where('id', $jobId)
            ->firstOrFail();

        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:job_applications,id',
            'status' => 'required|in:pending,reviewed,interviewed,hired,rejected',
        ]);

        JobApplication::whereIn('id', $request->application_ids)
            ->where('job_id', $job->id)
            ->update(['status' => $request->status]);

        return back()->with('success', count($request->application_ids) . ' application(s) updated successfully!');
    }

    /**
     * Schedule an interview
     */
    public function scheduleInterview(Request $request, $jobId, $applicationId)
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
            'scheduled_at' => 'required|date|after:now',
            'interview_type' => 'required|in:online,in-person,phone',
            'location' => 'required_if:interview_type,in-person|nullable|string',
            'meeting_link' => 'required_if:interview_type,online|nullable|url',
            'notes' => 'nullable|string|max:1000',
        ]);

        InterviewSchedule::create([
            'job_application_id' => $application->id,
            'job_id' => $job->id,
            'therapist_id' => $application->therapist_id,
            'company_id' => $user->id,
            'scheduled_at' => $request->scheduled_at,
            'interview_type' => $request->interview_type,
            'location' => $request->location,
            'meeting_link' => $request->meeting_link,
            'notes' => $request->notes,
            'status' => 'scheduled',
        ]);

        // Update application status to interviewed
        $application->update(['status' => 'interviewed']);

        return back()->with('success', 'Interview scheduled successfully!');
    }

    /**
     * Get job templates
     */
    public function templates()
    {
        $user = Auth::user();
        
        if ($user->type !== 'company') {
            return redirect()->route('dashboard.home')->with('error', 'Access denied.');
        }

        $templates = JobTemplate::where('user_id', $user->id)->latest()->get();
        
        return view('web.company.jobs.templates', compact('templates'));
    }

    /**
     * Create job template
     */
    public function createTemplate(Request $request)
    {
        $user = Auth::user();
        
        if ($user->type !== 'company') {
            return redirect()->route('dashboard.home')->with('error', 'Access denied.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:job,training',
            'location' => 'nullable|string',
            'salary_type' => 'nullable|string',
            'salary_range' => 'nullable|string',
            'specialty' => 'nullable|array',
            'techniques' => 'nullable|array',
            'equipment' => 'nullable|array',
            'experience_level' => 'nullable|string',
            'urgency_level' => 'nullable|string',
            'openings_count' => 'integer|min:1',
            'min_years_experience' => 'nullable|integer',
            'gender_preference' => 'nullable|string',
            'license_required' => 'nullable|boolean',
        ]);

        $validated['user_id'] = $user->id;
        JobTemplate::create($validated);

        return redirect()->route('company.jobs.templates')->with('success', 'Template created successfully!');
    }

    /**
     * Create job from template
     */
    public function createFromTemplate($templateId)
    {
        $user = Auth::user();
        
        if ($user->type !== 'company') {
            return redirect()->route('dashboard.home')->with('error', 'Access denied.');
        }

        $template = JobTemplate::where('user_id', $user->id)->findOrFail($templateId);

        $job = Job::create([
            'clinic_id' => $user->id,
            'title' => $template->title,
            'description' => $template->description,
            'type' => $template->type,
            'location' => $template->location,
            'salary_type' => $template->salary_type,
            'salary_range' => $template->salary_range,
            'specialty' => $template->specialty,
            'techniques' => $template->techniques,
            'equipment' => $template->equipment,
            'experience_level' => $template->experience_level,
            'urgency_level' => $template->urgency_level,
            'openings_count' => $template->openings_count ?? 1,
            'posted_by_type' => 'company',
            'is_active' => true,
        ]);

        $job->requirements()->create([
            'min_years_experience' => $template->min_years_experience ?? 0,
            'gender_preference' => $template->gender_preference,
            'license_required' => $template->license_required ?? false,
        ]);

        return redirect()->route('company.jobs.edit', $job->id)->with('success', 'Job created from template! You can now edit and publish it.');
    }

    /**
     * Analytics dashboard
     */
    public function analytics()
    {
        $user = Auth::user();
        
        if ($user->type !== 'company') {
            return redirect()->route('dashboard.home')->with('error', 'Access denied.');
        }

        $jobs = Job::where('clinic_id', $user->id)
            ->where('posted_by_type', 'company')
            ->withCount('applications')
            ->get();

        $totalJobs = $jobs->count();
        $activeJobs = $jobs->where('is_active', true)->count();
        $totalApplications = JobApplication::whereHas('job', function($query) use ($user) {
            $query->where('clinic_id', $user->id)->where('posted_by_type', 'company');
        })->count();

        $applicationsByStatus = JobApplication::whereHas('job', function($query) use ($user) {
            $query->where('clinic_id', $user->id)->where('posted_by_type', 'company');
        })
        ->selectRaw('status, count(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status');

        $topJobs = $jobs->sortByDesc('applications_count')->take(5);

        return view('web.company.jobs.analytics', compact(
            'totalJobs',
            'activeJobs',
            'totalApplications',
            'applicationsByStatus',
            'topJobs'
        ));
    }
}

