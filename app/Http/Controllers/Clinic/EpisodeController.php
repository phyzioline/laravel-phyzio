<?php

namespace App\Http\Controllers\Clinic;

use App\Models\EpisodeOfCare;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EpisodeController extends BaseClinicController
{
    /**
     * Display list of active episodes for this clinic.
     */
    public function index()
    {
        $clinic = $this->getUserClinic();
        
        // Don't redirect - show empty state instead
        if (!$clinic) {
            $episodes = collect();
            return view('clinic.erp.episodes.index', compact('episodes', 'clinic'));
        }

        $episodes = EpisodeOfCare::where('clinic_id', $clinic->id)
                        ->with('patient', 'primaryTherapist')
                        ->orderBy('status')
                        ->latest()
                        ->get();
                        
        // Check if view exists, use appropriate path
        $viewPath = 'web.clinic.episodes.index';
        if (!view()->exists($viewPath)) {
            $viewPath = 'clinic.erp.episodes.index';
        }
        
        return view($viewPath, compact('episodes', 'clinic'));
    }

    /**
     * Show form to create new episode (Start of Care).
     */
    public function create()
    {
        $clinic = $this->getUserClinic();
        
        // Show empty state instead of redirecting
        if (!$clinic) {
            $patients = collect();
            $therapists = collect();
            return view('clinic.erp.episodes.create', compact('patients', 'therapists', 'clinic'));
        }

        // Get patients from this clinic
        $patients = \App\Models\Patient::where('clinic_id', $clinic->id)->get();
        $therapists = User::where('type', 'therapist')->get();
        
        return view('clinic.erp.episodes.create', compact('patients', 'therapists', 'clinic'));
    }

    /**
     * Store new episode.
     */
    public function store(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return back()->with('error', 'Clinic not found.');
        }

        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'primary_therapist_id' => 'required|exists:users,id',
            'specialty' => 'required|string',
            'start_date' => 'required|date',
            'chief_complaint' => 'required|string',
            'diagnosis_icd' => 'nullable|string',
        ]);

        // Verify patient belongs to this clinic
        $patient = \App\Models\Patient::where('clinic_id', $clinic->id)
            ->findOrFail($data['patient_id']);

        $data['clinic_id'] = $clinic->id; // Use clinic ID, not Auth::id()
        $data['status'] = 'active';
        
        // Generate a default title if not provided
        if (empty($data['title'])) {
            $specialtyLabel = ucfirst($data['specialty'] ?? 'General');
            $complaintPreview = !empty($data['chief_complaint']) 
                ? substr($data['chief_complaint'], 0, 50) 
                : 'Care Episode';
            $data['title'] = $specialtyLabel . ' - ' . $complaintPreview;
        }

        $episode = EpisodeOfCare::create($data);

        return redirect()->route('clinic.episodes.show', $episode)->with('success', 'Episode of Care created.');
    }

    /**
     * Show the Clinical Dashboard for this Episode.
     * This is the heart of the ERP.
     */
    public function show(EpisodeOfCare $episode)
    {
        $clinic = $this->getUserClinic();
        
        // If no clinic, still show episode but with warning
        if (!$clinic) {
            $episode->load(['assessments', 'treatments', 'outcomes']);
            return view('clinic.erp.episodes.show', compact('episode', 'clinic'))
                ->with('warning', 'Clinic not found. Some features may be limited.');
        }

        // Verify episode belongs to this clinic
        if ($episode->clinic_id !== $clinic->id) {
            abort(403, 'Unauthorized access to this episode.');
        }

        $episode->load(['assessments', 'treatments', 'outcomes']);
        return view('clinic.erp.episodes.show', compact('episode', 'clinic'));
    }
}
