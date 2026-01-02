<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\EpisodeOfCare;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentPlanController extends BaseClinicController
{
    /**
     * Show treatment plan for episode
     */
    public function index(EpisodeOfCare $episode)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic || $episode->clinic_id !== $clinic->id) {
            abort(403);
        }

        $treatments = $episode->treatments()
            ->orderBy('session_date', 'asc')
            ->get();

        // Group by treatment type
        $treatmentGroups = $treatments->groupBy('treatment_type');

        return view('web.clinic.treatment-plans.index', compact('episode', 'treatments', 'treatmentGroups'));
    }

    /**
     * Create new treatment session
     */
    public function create(EpisodeOfCare $episode)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic || $episode->clinic_id !== $clinic->id) {
            abort(403);
        }

        return view('web.clinic.treatment-plans.create', compact('episode'));
    }

    /**
     * Store treatment session
     */
    public function store(Request $request, EpisodeOfCare $episode)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic || $episode->clinic_id !== $clinic->id) {
            abort(403);
        }

        $validated = $request->validate([
            'session_date' => 'required|date',
            'treatment_type' => 'required|string',
            'exercises' => 'nullable|array',
            'manual_therapy' => 'nullable|array',
            'modalities' => 'nullable|array',
            'notes' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1'
        ]);

        $treatment = Treatment::create([
            'episode_id' => $episode->id,
            'therapist_id' => Auth::id(),
            'session_date' => $validated['session_date'],
            'treatment_type' => $validated['treatment_type'],
            'treatment_data' => [
                'exercises' => $validated['exercises'] ?? [],
                'manual_therapy' => $validated['manual_therapy'] ?? [],
                'modalities' => $validated['modalities'] ?? []
            ],
            'notes' => $validated['notes'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null
        ]);

        return redirect()->route('clinic.episodes.treatment-plans.index', $episode)
            ->with('success', __('Treatment session recorded successfully.'));
    }
}
