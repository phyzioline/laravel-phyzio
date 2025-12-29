<?php

namespace App\Http\Controllers\Clinic;

use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class JobController extends BaseClinicController
{
    public function index()
    {
        $clinic = $this->getUserClinic();
        
        // Show empty state instead of redirecting
        if (!$clinic) {
            $jobs = collect();
            return view('web.clinic.jobs.index', compact('jobs', 'clinic'));
        }

        $jobs = Job::where('clinic_id', $clinic->id)
                    ->withCount('applications')
                    ->latest()
                    ->get();
        return view('web.clinic.jobs.index', compact('jobs', 'clinic'));
    }

    public function applicants($id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return back()->with('error', 'Clinic not found.');
        }

        $job = Job::where('clinic_id', $clinic->id)
                    ->with('applications.therapist.therapistProfile')
                    ->findOrFail($id);
        return view('web.clinic.jobs.applicants', compact('job', 'clinic'));
    }

    public function create()
    {
        $clinic = $this->getUserClinic();
        
        if (Auth::user()->status !== 'active') {
            return redirect()->route('clinic.dashboard')->with('error', 'Your account is pending approval. You cannot post jobs yet.');
        }

        // Show form even if no clinic
        $specialties = ['Orthopedic', 'Neurological', 'Pediatric', 'Sports', 'Geriatric', 'Women Health', 'Cardiopulmonary'];
        $techniques = ['Manual Therapy', 'Dry Needling', 'Cupping', 'Kinesiotaping', 'Electrotherapy', 'Exercise Therapy'];
        $equipment = ['Ultrasound', 'TENS', 'Laser', 'Shockwave', 'Treadmill', 'Balance Board'];

        return view('web.clinic.jobs.create', compact('specialties', 'techniques', 'equipment', 'clinic'));
    }

    public function store(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return back()->with('error', 'Clinic not found. Please contact support to set up your clinic.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:job,part-time,contract,training',
            'location' => 'required|string',
            'salary_type' => 'required|string',
            'salary_range' => 'nullable|string',
            'specialty' => 'nullable|array',
            'techniques' => 'nullable|array',
            'equipment' => 'nullable|array',
            'experience_level' => 'required|string',
            'urgency_level' => 'required|string',
            'openings_count' => 'nullable|integer|min:1',
            // Requirements
            'min_years_experience' => 'nullable|integer',
            'gender_preference' => 'nullable|string',
            'license_required' => 'nullable|boolean',
        ], [
            'title.required' => 'Job title is required.',
            'description.required' => 'Job description is required.',
            'type.required' => 'Job type is required.',
            'location.required' => 'Location is required.',
            'salary_type.required' => 'Salary type is required.',
            'experience_level.required' => 'Experience level is required.',
            'urgency_level.required' => 'Urgency level is required.',
        ]);

        try {
            $job = Job::create([
                'clinic_id' => $clinic->id,
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
                'posted_by_type' => Auth::user()->type === 'clinic' ? 'clinic' : 'company',
            ]);

            $job->requirements()->create([
                'min_years_experience' => $request->min_years_experience ?? 0,
                'gender_preference' => $request->gender_preference,
                'license_required' => $request->has('license_required'),
            ]);

            return redirect()->route('clinic.jobs.index')->with('success', 'Job posted successfully!');
        } catch (\Exception $e) {
            \Log::error('Job creation error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create job. Please try again.');
        }
    }

    public function destroy($id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return back()->with('error', 'Clinic not found.');
        }

        $job = Job::where('clinic_id', $clinic->id)->where('id', $id)->firstOrFail();
        $job->delete();
        return redirect()->route('clinic.jobs.index')->with('success', 'Job deleted successfully!');
    }
}
