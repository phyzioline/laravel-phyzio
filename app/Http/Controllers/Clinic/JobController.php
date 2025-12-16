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
        $jobs = Job::where('clinic_id', Auth::id())
                    ->withCount('applications')
                    ->latest()
                    ->get();
        return view('web.clinic.jobs.index', compact('jobs'));
    }

    public function applicants($id)
    {
        $job = Job::where('clinic_id', Auth::id())->with('applications.therapist.therapistProfile')->findOrFail($id);
        return view('web.clinic.jobs.applicants', compact('job'));
    }

    public function create()
    {
        if (Auth::user()->status !== 'active') {
            return redirect()->route('clinic.dashboard')->with('error', 'Your account is pending approval. You cannot post jobs yet.');
        }

        $specialties = ['Orthopedic', 'Neurological', 'Pediatric', 'Sports', 'Geriatric', 'Women Health', 'Cardiopulmonary'];
        $techniques = ['Manual Therapy', 'Dry Needling', 'Cupping', 'Kinesiotaping', 'Electrotherapy', 'Exercise Therapy'];
        $equipment = ['Ultrasound', 'TENS', 'Laser', 'Shockwave', 'Treadmill', 'Balance Board'];

        return view('web.clinic.jobs.create', compact('specialties', 'techniques', 'equipment'));
    }

    public function store(Request $request)
    {
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
            // Requirements
            'min_years_experience' => 'nullable|integer',
            'gender_preference' => 'nullable|string',
            'license_required' => 'nullable|boolean',
        ]);

        $job = Job::create([
            'clinic_id' => Auth::id(),
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
    }

    public function destroy($id)
    {
        $job = Job::where('clinic_id', Auth::id())->where('id', $id)->firstOrFail();
        $job->delete();
        return redirect()->route('clinic.jobs.index')->with('success', 'Job deleted successfully!');
    }
}
