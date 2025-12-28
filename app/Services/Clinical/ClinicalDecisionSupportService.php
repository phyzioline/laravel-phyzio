<?php

namespace App\Services\Clinical;

use App\Models\ClinicalNote;
use Illuminate\Support\Facades\Log;

class ClinicalDecisionSupportService
{
    /**
     * Get clinical recommendations based on note content
     * 
     * @param ClinicalNote $note
     * @return array ['recommendations' => [], 'warnings' => [], 'suggestions' => []]
     */
    public function getRecommendations(ClinicalNote $note): array
    {
        $recommendations = [];
        $warnings = [];
        $suggestions = [];

        // Analyze note content based on specialty
        switch ($note->specialty) {
            case 'ortho':
                $recommendations = array_merge($recommendations, $this->getOrthopedicRecommendations($note));
                break;
            case 'pediatric':
                $recommendations = array_merge($recommendations, $this->getPediatricRecommendations($note));
                break;
            case 'neuro':
                $recommendations = array_merge($recommendations, $this->getNeurologicalRecommendations($note));
                break;
            case 'sports':
                $recommendations = array_merge($recommendations, $this->getSportsRecommendations($note));
                break;
        }

        // General recommendations
        $recommendations = array_merge($recommendations, $this->getGeneralRecommendations($note));

        return [
            'recommendations' => $recommendations,
            'warnings' => $warnings,
            'suggestions' => $suggestions
        ];
    }

    /**
     * Get orthopedic-specific recommendations
     */
    protected function getOrthopedicRecommendations(ClinicalNote $note): array
    {
        $recommendations = [];

        // Check for pain scale
        if ($note->outcome_measures && isset($note->outcome_measures['pain_scale'])) {
            $painScale = $note->outcome_measures['pain_scale'];
            if ($painScale >= 7) {
                $recommendations[] = [
                    'type' => 'warning',
                    'message' => 'High pain scale (≥7). Consider pain management interventions.',
                    'evidence_level' => 'moderate'
                ];
            }
        }

        // Check for ROM limitations
        if ($note->objective && stripos($note->objective, 'limited') !== false) {
            $recommendations[] = [
                'type' => 'suggestion',
                'message' => 'ROM limitations noted. Consider progressive ROM exercises.',
                'evidence_level' => 'strong'
            ];
        }

        return $recommendations;
    }

    /**
     * Get pediatric-specific recommendations
     */
    protected function getPediatricRecommendations(ClinicalNote $note): array
    {
        $recommendations = [];

        // Check for developmental concerns
        if ($note->subjective && (stripos($note->subjective, 'delay') !== false || stripos($note->subjective, 'milestone') !== false)) {
            $recommendations[] = [
                'type' => 'suggestion',
                'message' => 'Consider developmental milestone assessment and play-based interventions.',
                'evidence_level' => 'strong'
            ];
        }

        return $recommendations;
    }

    /**
     * Get neurological-specific recommendations
     */
    protected function getNeurologicalRecommendations(ClinicalNote $note): array
    {
        $recommendations = [];

        // Check for balance concerns
        if ($note->objective && (stripos($note->objective, 'balance') !== false || stripos($note->objective, 'fall') !== false)) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Balance concerns identified. Consider balance training and fall prevention strategies.',
                'evidence_level' => 'strong'
            ];
        }

        return $recommendations;
    }

    /**
     * Get sports medicine-specific recommendations
     */
    protected function getSportsRecommendations(ClinicalNote $note): array
    {
        $recommendations = [];

        // Check for return to sport considerations
        if ($note->plan && stripos($note->plan, 'return') !== false) {
            $recommendations[] = [
                'type' => 'suggestion',
                'message' => 'Consider sport-specific functional testing before return to play.',
                'evidence_level' => 'strong'
            ];
        }

        return $recommendations;
    }

    /**
     * Get general recommendations applicable to all specialties
     */
    protected function getGeneralRecommendations(ClinicalNote $note): array
    {
        $recommendations = [];

        // Check if plan is missing
        if (empty($note->plan)) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Treatment plan is missing. Please add plan of care.',
                'evidence_level' => 'required'
            ];
        }

        // Check if assessment is too brief
        if ($note->assessment && strlen($note->assessment) < 50) {
            $recommendations[] = [
                'type' => 'suggestion',
                'message' => 'Assessment section appears brief. Consider adding more clinical interpretation.',
                'evidence_level' => 'moderate'
            ];
        }

        // Check for outcome measures
        if (empty($note->outcome_measures)) {
            $recommendations[] = [
                'type' => 'suggestion',
                'message' => 'Consider adding outcome measures for tracking progress.',
                'evidence_level' => 'moderate'
            ];
        }

        return $recommendations;
    }

    /**
     * Validate note completeness based on specialty and note type
     * 
     * @param ClinicalNote $note
     * @return array ['complete' => bool, 'missing_fields' => []]
     */
    public function validateCompleteness(ClinicalNote $note): array
    {
        $missingFields = [];

        // Required fields for all notes
        if (empty($note->subjective)) {
            $missingFields[] = 'Subjective';
        }
        if (empty($note->objective)) {
            $missingFields[] = 'Objective';
        }
        if (empty($note->assessment)) {
            $missingFields[] = 'Assessment';
        }
        if (empty($note->plan)) {
            $missingFields[] = 'Plan';
        }

        // Specialty-specific requirements
        if ($note->note_type === 'evaluation') {
            if (empty($note->chief_complaint)) {
                $missingFields[] = 'Chief Complaint';
            }
        }

        return [
            'complete' => empty($missingFields),
            'missing_fields' => $missingFields
        ];
    }

    /**
     * Get evidence-based treatment suggestions
     * 
     * @param string $specialty
     * @param string $diagnosis
     * @return array
     */
    public function getTreatmentSuggestions(string $specialty, ?string $diagnosis = null): array
    {
        $suggestions = [];

        // This would typically query an evidence-based guidelines database
        // For now, return specialty-specific suggestions

        switch ($specialty) {
            case 'ortho':
                $suggestions = [
                    'Therapeutic exercise (97110)',
                    'Manual therapy (97140)',
                    'Neuromuscular reeducation (97112)'
                ];
                break;
            case 'neuro':
                $suggestions = [
                    'Gait training (97116)',
                    'Balance training',
                    'Functional activities (97530)'
                ];
                break;
            case 'pediatric':
                $suggestions = [
                    'Play-based interventions',
                    'Developmental activities',
                    'Family-centered care'
                ];
                break;
        }

        return $suggestions;
    }
}

