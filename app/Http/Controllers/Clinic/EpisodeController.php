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
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found. Please contact support.');
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
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
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
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }

        // Verify episode belongs to this clinic
        if ($episode->clinic_id !== $clinic->id) {
            abort(403, 'Unauthorized access to this episode.');
        }

        $episode->load(['assessments', 'treatments', 'outcomes']);
        return view('clinic.erp.episodes.show', compact('episode', 'clinic'));
    }
}
