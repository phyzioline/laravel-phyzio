<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\EpisodeOfCare;
use App\Models\ClinicalAssessment;
use App\Services\Clinic\SpecialtyContextService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    protected $specialtyService;

    public function __construct(SpecialtyContextService $service)
    {
        $this->middleware('auth');
        $this->specialtyService = $service;
    }

    public function create(EpisodeOfCare $episode)
    {
        $schema = $this->specialtyService->getAssessmentSchema($episode->specialty);
        return view('clinic.erp.assessments.create', compact('episode', 'schema'));
    }

    public function store(Request $request, EpisodeOfCare $episode)
    {
        $request->validate(['assessment_date' => 'required|date']);

        // In a real app, strict validation of the JSON blobs against the schema would happen here
        
        ClinicalAssessment::create([
            'episode_id' => $episode->id,
            'therapist_id' => Auth::id(), // Or currently logged in user
            'assessment_date' => $request->assessment_date,
            'type' => $request->type ?? 're_eval',
            'subjective_data' => $request->subjective ?? [],
            'objective_data' => $request->objective ?? [],
            'analysis' => $request->analysis,
            'red_flags_detected' => $request->has('red_flags_detected')
        ]);

        return redirect()->route('clinic.episodes.show', $episode)->with('success', 'Assessment logged.');
    }
}
