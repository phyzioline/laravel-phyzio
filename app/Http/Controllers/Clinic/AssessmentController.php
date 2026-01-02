<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\EpisodeOfCare;
use App\Models\ClinicalAssessment;
use App\Models\AssessmentTemplate;
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

    /**
     * Show template selection
     */
    public function selectTemplate(EpisodeOfCare $episode)
    {
        $templates = AssessmentTemplate::getSystemTemplates()
            ->merge(AssessmentTemplate::getClinicTemplates($episode->clinic_id ?? null));
        
        return view('web.clinic.assessments.select-template', compact('episode', 'templates'));
    }

    /**
     * Create assessment with template
     */
    public function create(EpisodeOfCare $episode, Request $request)
    {
        $template = null;
        $schema = null;
        $specialty = $episode->specialty ?? 'orthopedic';
        
        // If template ID provided, load template
        if ($request->has('template_id')) {
            $template = AssessmentTemplate::findOrFail($request->template_id);
            $template->incrementUsage();
            $specialty = $template->specialty ?? $specialty;
        } else {
            // Fallback to specialty schema
            $schema = $this->specialtyService->getAssessmentSchema($specialty);
        }
        
        // Get previous assessment for comparison
        $previousAssessment = $episode->assessments()
            ->where('type', '!=', 'discharge')
            ->latest('assessment_date')
            ->first();
        
        return view('web.clinic.assessments.create', compact('episode', 'schema', 'template', 'specialty', 'previousAssessment'));
    }

    public function store(Request $request, EpisodeOfCare $episode)
    {
        $request->validate([
            'assessment_date' => 'required|date',
            'type' => 'required|in:initial,re_eval,discharge'
        ]);

        // Create assessment
        $assessment = ClinicalAssessment::create([
            'episode_id' => $episode->id,
            'therapist_id' => Auth::id(),
            'assessment_date' => $request->assessment_date,
            'type' => $request->type ?? 're_eval',
            'subjective_data' => $request->subjective ?? [],
            'objective_data' => $request->objective ?? [],
            'analysis' => $request->analysis,
            'red_flags_detected' => $request->has('red_flags_detected')
        ]);

        // Save specialty-specific data
        $specialty = $episode->specialty ?? 'orthopedic';
        $this->saveSpecialtyAssessmentData($assessment, $specialty, $request);

        // Save outcome measures
        if ($request->has('outcome_measures')) {
            $this->saveOutcomeMeasures($assessment, $episode, $request->outcome_measures);
        }

        return redirect()->route('clinic.episodes.show', $episode)
            ->with('success', __('Assessment logged successfully.'));
    }

    /**
     * Save specialty-specific assessment data
     */
    protected function saveSpecialtyAssessmentData($assessment, $specialty, $request)
    {
        // This will be handled by SpecialtyAssessmentService
        // For now, we'll store in objective_data JSON
        // In production, use the proper specialty-specific tables
    }

    /**
     * Save outcome measures
     */
    protected function saveOutcomeMeasures($assessment, $episode, $measures)
    {
        if (!is_array($measures)) return;

        foreach ($measures as $measure) {
            if (isset($measure['measure_name']) && isset($measure['score'])) {
                \App\Models\OutcomeMeasure::create([
                    'assessment_id' => $assessment->id,
                    'episode_id' => $episode->id,
                    'measure_name' => $measure['measure_name'],
                    'specialty_key' => $episode->specialty,
                    'total_score' => $measure['score'],
                    'percentage' => $measure['percentage'] ?? null,
                    'scores' => $measure['scores'] ?? null,
                    'interpretation' => $measure['interpretation'] ?? null,
                    'assessment_type' => $assessment->type,
                    'assessed_at' => $assessment->assessment_date,
                    'therapist_id' => $assessment->therapist_id
                ]);
            }
        }
    }
}
