<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\EpisodeOfCare;
use App\Models\ClinicalAssessment;
use App\Models\OutcomeMeasure;
use Illuminate\Http\Request;

class SpecialtyReportController extends BaseClinicController
{
    /**
     * Generate Coach Report (Sports specialty)
     */
    public function coachReport(Patient $patient)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic || $patient->clinic_id !== $clinic->id) {
            abort(403);
        }

        // Get sports-related episodes
        $episodes = $patient->episodes()
            ->where('specialty', 'sports')
            ->with(['assessments.outcomeMeasures'])
            ->get();

        // Get return-to-play progress
        $rtpProgress = $this->calculateRTPProgress($episodes);

        // Get performance metrics
        $performanceMetrics = $this->getPerformanceMetrics($episodes);

        return view('web.clinic.reports.coach', compact(
            'patient',
            'episodes',
            'rtpProgress',
            'performanceMetrics'
        ));
    }

    /**
     * Generate Parent Report (Pediatric specialty)
     */
    public function parentReport(Patient $patient)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic || $patient->clinic_id !== $clinic->id) {
            abort(403);
        }

        // Get pediatric episodes
        $episodes = $patient->episodes()
            ->where('specialty', 'pediatric')
            ->with(['assessments.outcomeMeasures'])
            ->get();

        // Get milestone progress
        $milestoneProgress = $this->getMilestoneProgress($episodes);

        // Get developmental scores
        $developmentalScores = $this->getDevelopmentalScores($episodes);

        return view('web.clinic.reports.parent', compact(
            'patient',
            'episodes',
            'milestoneProgress',
            'developmentalScores'
        ));
    }

    /**
     * Generate Family Report (Geriatric specialty)
     */
    public function familyReport(Patient $patient)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic || $patient->clinic_id !== $clinic->id) {
            abort(403);
        }

        // Get geriatric episodes
        $episodes = $patient->episodes()
            ->where('specialty', 'geriatric')
            ->with(['assessments.outcomeMeasures'])
            ->get();

        // Get fall risk assessment
        $fallRisk = $this->getFallRisk($episodes);

        // Get functional independence scores
        $functionalScores = $this->getFunctionalScores($episodes);

        return view('web.clinic.reports.family', compact(
            'patient',
            'episodes',
            'fallRisk',
            'functionalScores'
        ));
    }

    /**
     * Calculate Return-to-Play progress
     */
    protected function calculateRTPProgress($episodes)
    {
        $phases = ['acute', 'subacute', 'sport_specific', 'return_to_play'];
        $currentPhase = 'acute';
        $progress = 0;

        foreach ($episodes as $episode) {
            foreach ($episode->assessments as $assessment) {
                $rtpMeasures = $assessment->outcomeMeasures()
                    ->where('measure_name', 'like', '%Return to Play%')
                    ->first();
                
                if ($rtpMeasures) {
                    $score = $rtpMeasures->percentage ?? 0;
                    if ($score >= 100) {
                        $currentPhase = 'return_to_play';
                        $progress = 100;
                    } elseif ($score >= 75) {
                        $currentPhase = 'sport_specific';
                        $progress = 75;
                    } elseif ($score >= 50) {
                        $currentPhase = 'subacute';
                        $progress = 50;
                    }
                }
            }
        }

        return [
            'current_phase' => $currentPhase,
            'progress' => $progress,
            'phases' => $phases
        ];
    }

    /**
     * Get performance metrics
     */
    protected function getPerformanceMetrics($episodes)
    {
        $metrics = [];

        foreach ($episodes as $episode) {
            foreach ($episode->assessments as $assessment) {
                $verticalJump = $assessment->outcomeMeasures()
                    ->where('measure_name', 'Vertical Jump')
                    ->first();
                
                $agility = $assessment->outcomeMeasures()
                    ->where('measure_name', 'Agility Time')
                    ->first();

                if ($verticalJump || $agility) {
                    $metrics[] = [
                        'date' => $assessment->assessment_date,
                        'vertical_jump' => $verticalJump ? $verticalJump->total_score : null,
                        'agility_time' => $agility ? $agility->total_score : null
                    ];
                }
            }
        }

        return $metrics;
    }

    /**
     * Get milestone progress
     */
    protected function getMilestoneProgress($episodes)
    {
        $milestones = [];
        
        foreach ($episodes as $episode) {
            foreach ($episode->assessments as $assessment) {
                $milestoneData = $assessment->objective_data['milestones'] ?? null;
                if ($milestoneData) {
                    $milestones[] = [
                        'date' => $assessment->assessment_date,
                        'milestones' => $milestoneData
                    ];
                }
            }
        }

    /**
     * Get developmental scores
     */
    protected function getDevelopmentalScores($episodes)
    {
        $scores = [];

        foreach ($episodes as $episode) {
            foreach ($episode->assessments as $assessment) {
                $gmfm = $assessment->outcomeMeasures()
                    ->where('measure_name', 'GMFM')
                    ->first();
                
                $peabody = $assessment->outcomeMeasures()
                    ->where('measure_name', 'Peabody')
                    ->first();

                if ($gmfm || $peabody) {
                    $scores[] = [
                        'date' => $assessment->assessment_date,
                        'gmfm' => $gmfm ? $gmfm->percentage : null,
                        'peabody' => $peabody ? $peabody->percentage : null
                    ];
                }
            }
        }

        return $scores;
    }

    /**
     * Get fall risk assessment
     */
    protected function getFallRisk($episodes)
    {
        $latestRisk = null;
        $riskHistory = [];

        foreach ($episodes as $episode) {
            foreach ($episode->assessments as $assessment) {
                $morse = $assessment->outcomeMeasures()
                    ->where('measure_name', 'Morse Fall Scale')
                    ->first();
                
                if ($morse) {
                    $score = $morse->total_score ?? 0;
                    $riskLevel = $score <= 24 ? 'Low' : ($score <= 44 ? 'Moderate' : 'High');
                    
                    $riskHistory[] = [
                        'date' => $assessment->assessment_date,
                        'score' => $score,
                        'risk_level' => $riskLevel
                    ];

                    if (!$latestRisk || $assessment->assessment_date > $latestRisk['date']) {
                        $latestRisk = [
                            'date' => $assessment->assessment_date,
                            'score' => $score,
                            'risk_level' => $riskLevel
                        ];
                    }
                }
            }
        }

        return [
            'latest' => $latestRisk,
            'history' => $riskHistory
        ];
    }

    /**
     * Get functional independence scores
     */
    protected function getFunctionalScores($episodes)
    {
        $scores = [];

        foreach ($episodes as $episode) {
            foreach ($episode->assessments as $assessment) {
                $tug = $assessment->outcomeMeasures()
                    ->where('measure_name', 'TUG')
                    ->first();
                
                $berg = $assessment->outcomeMeasures()
                    ->where('measure_name', 'Berg Balance')
                    ->first();

                if ($tug || $berg) {
                    $scores[] = [
                        'date' => $assessment->assessment_date,
                        'tug' => $tug ? $tug->total_score : null,
                        'berg_balance' => $berg ? $berg->total_score : null
                    ];
                }
            }
        }

        return $scores;
    }
}

