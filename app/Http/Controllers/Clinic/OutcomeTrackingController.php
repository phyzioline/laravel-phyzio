<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\EpisodeOfCare;
use App\Models\OutcomeMeasure;
use App\Models\ClinicalAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutcomeTrackingController extends BaseClinicController
{
    /**
     * Show outcome tracking for an episode
     */
    public function index(EpisodeOfCare $episode)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic || $episode->clinic_id !== $clinic->id) {
            abort(403);
        }

        // Get all outcome measures for this episode
        $outcomeMeasures = OutcomeMeasure::where('episode_id', $episode->id)
            ->orderBy('assessed_at', 'asc')
            ->get()
            ->groupBy('measure_name');

        // Get assessments for timeline
        $assessments = $episode->assessments()
            ->with('outcomeMeasures')
            ->orderBy('assessment_date', 'asc')
            ->get();

        // Prepare chart data
        $chartData = $this->prepareChartData($outcomeMeasures, $episode->specialty);

        return view('web.clinic.outcome-tracking.index', compact(
            'episode',
            'outcomeMeasures',
            'assessments',
            'chartData'
        ));
    }

    /**
     * Show progression chart for a specific outcome measure
     */
    public function showMeasure(EpisodeOfCare $episode, $measureName)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic || $episode->clinic_id !== $clinic->id) {
            abort(403);
        }

        $measures = OutcomeMeasure::where('episode_id', $episode->id)
            ->where('measure_name', $measureName)
            ->orderBy('assessed_at', 'asc')
            ->get();

        return view('web.clinic.outcome-tracking.measure', compact('episode', 'measures', 'measureName'));
    }

    /**
     * Prepare chart data for outcome measures
     */
    protected function prepareChartData($outcomeMeasures, $specialty)
    {
        $chartData = [];

        foreach ($outcomeMeasures as $measureName => $measures) {
            $data = [];
            $labels = [];

            foreach ($measures as $measure) {
                $labels[] = $measure->assessed_at->format('M d, Y');
                $data[] = $measure->total_score ?? $measure->percentage ?? 0;
            }

            $chartData[$measureName] = [
                'labels' => $labels,
                'data' => $data,
                'measure_name' => $measureName
            ];
        }

        return $chartData;
    }
}

