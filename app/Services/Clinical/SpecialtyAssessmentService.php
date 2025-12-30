<?php

namespace App\Services\Clinical;

use App\Models\ClinicalAssessment;
use App\Models\EpisodeOfCare;
use App\Models\PTSpecialtyConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpecialtyAssessmentService
{
    /**
     * Get assessment components for a specialty
     */
    public function getAssessmentComponents(string $specialtyKey): array
    {
        $config = PTSpecialtyConfig::getByKey($specialtyKey);
        
        if (!$config) {
            return $this->getDefaultComponents($specialtyKey);
        }

        return $config->assessment_components ?? $this->getDefaultComponents($specialtyKey);
    }

    /**
     * Get outcome measures for a specialty
     */
    public function getOutcomeMeasures(string $specialtyKey): array
    {
        $config = PTSpecialtyConfig::getByKey($specialtyKey);
        
        if (!$config) {
            return \App\Models\OutcomeMeasure::getMeasuresBySpecialty($specialtyKey);
        }

        return $config->outcome_measures ?? \App\Models\OutcomeMeasure::getMeasuresBySpecialty($specialtyKey);
    }

    /**
     * Get tools and devices for a specialty
     */
    public function getToolsDevices(string $specialtyKey): array
    {
        $config = PTSpecialtyConfig::getByKey($specialtyKey);
        
        if (!$config) {
            return $this->getDefaultTools($specialtyKey);
        }

        return $config->tools_devices ?? $this->getDefaultTools($specialtyKey);
    }

    /**
     * Save specialty-specific assessment data
     */
    public function saveSpecialtyAssessment(
        ClinicalAssessment $assessment,
        EpisodeOfCare $episode,
        string $specialtyKey,
        array $data
    ): bool {
        try {
            DB::beginTransaction();

            $therapistId = auth()->id();
            $assessedAt = now();

            switch ($specialtyKey) {
                case 'musculoskeletal':
                    $this->saveMusculoskeletalData($assessment, $episode, $data, $therapistId, $assessedAt);
                    break;
                case 'neurological':
                    $this->saveNeurologicalData($assessment, $episode, $data, $therapistId, $assessedAt);
                    break;
                case 'cardiopulmonary':
                    $this->saveCardiopulmonaryData($assessment, $episode, $data, $therapistId, $assessedAt);
                    break;
                case 'pediatric':
                    $this->savePediatricData($assessment, $episode, $data, $therapistId, $assessedAt);
                    break;
                case 'geriatric':
                    $this->saveGeriatricData($assessment, $episode, $data, $therapistId, $assessedAt);
                    break;
                case 'sports':
                    $this->saveSportsData($assessment, $episode, $data, $therapistId, $assessedAt);
                    break;
                case 'womens_health':
                    $this->savePelvicFloorData($assessment, $episode, $data, $therapistId, $assessedAt);
                    break;
                case 'pain_management':
                    $this->savePainManagementData($assessment, $episode, $data, $therapistId, $assessedAt);
                    break;
            }

            // Save universal data (pain, outcome measures, functional scores)
            $this->saveUniversalData($assessment, $episode, $data, $therapistId, $assessedAt);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving specialty assessment: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Save musculoskeletal/orthopedic data
     */
    protected function saveMusculoskeletalData($assessment, $episode, $data, $therapistId, $assessedAt): void
    {
        // Joint ROM
        if (isset($data['joint_rom']) && is_array($data['joint_rom'])) {
            foreach ($data['joint_rom'] as $rom) {
                \App\Models\JointROMMeasurement::create([
                    'assessment_id' => $assessment->id,
                    'episode_id' => $episode->id,
                    'joint_name' => $rom['joint_name'] ?? '',
                    'movement' => $rom['movement'] ?? '',
                    'type' => $rom['type'] ?? 'AROM',
                    'degrees' => $rom['degrees'] ?? null,
                    'end_feel' => $rom['end_feel'] ?? null,
                    'notes' => $rom['notes'] ?? null,
                    'measured_at' => $assessedAt,
                    'therapist_id' => $therapistId,
                ]);
            }
        }

        // Muscle Strength
        if (isset($data['muscle_strength']) && is_array($data['muscle_strength'])) {
            foreach ($data['muscle_strength'] as $strength) {
                \App\Models\MuscleStrengthGrade::create([
                    'assessment_id' => $assessment->id,
                    'episode_id' => $episode->id,
                    'muscle_group' => $strength['muscle_group'] ?? '',
                    'method' => $strength['method'] ?? 'MMT',
                    'grade' => $strength['grade'] ?? null,
                    'force_value' => $strength['force_value'] ?? null,
                    'side' => $strength['side'] ?? 'bilateral',
                    'notes' => $strength['notes'] ?? null,
                    'measured_at' => $assessedAt,
                    'therapist_id' => $therapistId,
                ]);
            }
        }

        // Special Orthopedic Tests
        if (isset($data['special_tests']) && is_array($data['special_tests'])) {
            foreach ($data['special_tests'] as $test) {
                \App\Models\SpecialOrthopedicTest::create([
                    'assessment_id' => $assessment->id,
                    'episode_id' => $episode->id,
                    'test_name' => $test['test_name'] ?? '',
                    'body_region' => $test['body_region'] ?? '',
                    'result' => $test['result'] ?? null,
                    'findings' => $test['findings'] ?? null,
                    'side' => $test['side'] ?? null,
                    'performed_at' => $assessedAt,
                    'therapist_id' => $therapistId,
                ]);
            }
        }

        // Posture Analysis
        if (isset($data['posture'])) {
            \App\Models\PostureAnalysis::create([
                'assessment_id' => $assessment->id,
                'episode_id' => $episode->id,
                'view' => $data['posture']['view'] ?? 'all',
                'deviations' => $data['posture']['deviations'] ?? null,
                'alignment_notes' => $data['posture']['alignment_notes'] ?? null,
                'photo_references' => $data['posture']['photo_references'] ?? null,
                'assessed_at' => $assessedAt,
                'therapist_id' => $therapistId,
            ]);
        }
    }

    /**
     * Save neurological data
     */
    protected function saveNeurologicalData($assessment, $episode, $data, $therapistId, $assessedAt): void
    {
        if (isset($data['neurological'])) {
            \App\Models\NeurologicalAssessment::create([
                'assessment_id' => $assessment->id,
                'episode_id' => $episode->id,
                'tone_scale' => $data['neurological']['tone_scale'] ?? null,
                'reflexes' => $data['neurological']['reflexes'] ?? null,
                'coordination_tests' => $data['neurological']['coordination_tests'] ?? null,
                'balance_score' => $data['neurological']['balance_score'] ?? null,
                'gait_analysis' => $data['neurological']['gait_analysis'] ?? null,
                'sensory_testing' => $data['neurological']['sensory_testing'] ?? null,
                'functional_level' => $data['neurological']['functional_level'] ?? null,
                'assessed_at' => $assessedAt,
                'therapist_id' => $therapistId,
            ]);
        }

        // Gait Analysis
        if (isset($data['gait'])) {
            \App\Models\GaitAnalysis::create([
                'assessment_id' => $assessment->id,
                'episode_id' => $episode->id,
                'gait_speed' => $data['gait']['gait_speed'] ?? null,
                'cadence' => $data['gait']['cadence'] ?? null,
                'step_length' => $data['gait']['step_length'] ?? null,
                'stride_length' => $data['gait']['stride_length'] ?? null,
                'gait_pattern' => $data['gait']['gait_pattern'] ?? null,
                'deviations' => $data['gait']['deviations'] ?? null,
                'assistive_devices' => $data['gait']['assistive_devices'] ?? null,
                'assessed_at' => $assessedAt,
                'therapist_id' => $therapistId,
            ]);
        }
    }

    /**
     * Save cardiopulmonary data
     */
    protected function saveCardiopulmonaryData($assessment, $episode, $data, $therapistId, $assessedAt): void
    {
        if (isset($data['cardiopulmonary'])) {
            \App\Models\CardiopulmonaryAssessment::create([
                'assessment_id' => $assessment->id,
                'episode_id' => $episode->id,
                'heart_rate' => $data['cardiopulmonary']['heart_rate'] ?? null,
                'blood_pressure_systolic' => $data['cardiopulmonary']['blood_pressure_systolic'] ?? null,
                'blood_pressure_diastolic' => $data['cardiopulmonary']['blood_pressure_diastolic'] ?? null,
                'respiratory_rate' => $data['cardiopulmonary']['respiratory_rate'] ?? null,
                'oxygen_saturation' => $data['cardiopulmonary']['oxygen_saturation'] ?? null,
                'chest_expansion' => $data['cardiopulmonary']['chest_expansion'] ?? null,
                'breath_sounds' => $data['cardiopulmonary']['breath_sounds'] ?? null,
                'dyspnea_scale' => $data['cardiopulmonary']['dyspnea_scale'] ?? null,
                'six_minute_walk_distance' => $data['cardiopulmonary']['six_minute_walk_distance'] ?? null,
                'exercise_tolerance' => $data['cardiopulmonary']['exercise_tolerance'] ?? null,
                'on_oxygen' => $data['cardiopulmonary']['on_oxygen'] ?? false,
                'assessed_at' => $assessedAt,
                'therapist_id' => $therapistId,
            ]);
        }
    }

    /**
     * Save pediatric data
     */
    protected function savePediatricData($assessment, $episode, $data, $therapistId, $assessedAt): void
    {
        if (isset($data['pediatric'])) {
            \App\Models\PediatricAssessment::create([
                'assessment_id' => $assessment->id,
                'episode_id' => $episode->id,
                'developmental_history' => $data['pediatric']['developmental_history'] ?? null,
                'birth_history' => $data['pediatric']['birth_history'] ?? null,
                'gross_motor_milestones' => $data['pediatric']['gross_motor_milestones'] ?? null,
                'primitive_reflexes' => $data['pediatric']['primitive_reflexes'] ?? null,
                'postural_reactions' => $data['pediatric']['postural_reactions'] ?? null,
                'gmfm_score' => $data['pediatric']['gmfm_score'] ?? null,
                'pdms_score' => $data['pediatric']['pdms_score'] ?? null,
                'age_adjusted_progress' => $data['pediatric']['age_adjusted_progress'] ?? null,
                'assessed_at' => $assessedAt,
                'therapist_id' => $therapistId,
            ]);
        }
    }

    /**
     * Save geriatric data
     */
    protected function saveGeriatricData($assessment, $episode, $data, $therapistId, $assessedAt): void
    {
        if (isset($data['geriatric'])) {
            \App\Models\GeriatricAssessment::create([
                'assessment_id' => $assessment->id,
                'episode_id' => $episode->id,
                'fall_history_count' => $data['geriatric']['fall_history_count'] ?? 0,
                'fall_circumstances' => $data['geriatric']['fall_circumstances'] ?? null,
                'berg_balance_score' => $data['geriatric']['berg_balance_score'] ?? null,
                'tinetti_score' => $data['geriatric']['tinetti_score'] ?? null,
                'tug_time' => $data['geriatric']['tug_time'] ?? null,
                'gait_speed' => $data['geriatric']['gait_speed'] ?? null,
                'mobility_aids' => $data['geriatric']['mobility_aids'] ?? null,
                'independence_score' => $data['geriatric']['independence_score'] ?? null,
                'fall_risk_level' => $data['geriatric']['fall_risk_level'] ?? null,
                'assessed_at' => $assessedAt,
                'therapist_id' => $therapistId,
            ]);
        }
    }

    /**
     * Save sports performance data
     */
    protected function saveSportsData($assessment, $episode, $data, $therapistId, $assessedAt): void
    {
        if (isset($data['sports'])) {
            \App\Models\SportsPerformanceAssessment::create([
                'assessment_id' => $assessment->id,
                'episode_id' => $episode->id,
                'sport_type' => $data['sports']['sport_type'] ?? null,
                'sport_specific_demands' => $data['sports']['sport_specific_demands'] ?? null,
                'hop_test_distance' => $data['sports']['hop_test_distance'] ?? null,
                'limb_symmetry_index' => $data['sports']['limb_symmetry_index'] ?? null,
                'agility_time' => $data['sports']['agility_time'] ?? null,
                'power_output' => $data['sports']['power_output'] ?? null,
                'rtp_status' => $data['sports']['rtp_status'] ?? null,
                'performance_metrics' => $data['sports']['performance_metrics'] ?? null,
                'assessed_at' => $assessedAt,
                'therapist_id' => $therapistId,
            ]);
        }
    }

    /**
     * Save pelvic floor data
     */
    protected function savePelvicFloorData($assessment, $episode, $data, $therapistId, $assessedAt): void
    {
        if (isset($data['pelvic_floor'])) {
            \App\Models\PelvicFloorAssessment::create([
                'assessment_id' => $assessment->id,
                'episode_id' => $episode->id,
                'pelvic_strength_grade' => $data['pelvic_floor']['pelvic_strength_grade'] ?? null,
                'endurance_seconds' => $data['pelvic_floor']['endurance_seconds'] ?? null,
                'continence_status' => $data['pelvic_floor']['continence_status'] ?? null,
                'bladder_diary_summary' => $data['pelvic_floor']['bladder_diary_summary'] ?? null,
                'pain_mapping' => $data['pelvic_floor']['pain_mapping'] ?? null,
                'pfdi_score' => $data['pelvic_floor']['pfdi_score'] ?? null,
                'pfiq_score' => $data['pelvic_floor']['pfiq_score'] ?? null,
                'postpartum_status' => $data['pelvic_floor']['postpartum_status'] ?? null,
                'assessed_at' => $assessedAt,
                'therapist_id' => $therapistId,
            ]);
        }
    }

    /**
     * Save pain management data
     */
    protected function savePainManagementData($assessment, $episode, $data, $therapistId, $assessedAt): void
    {
        if (isset($data['pain_management'])) {
            \App\Models\PainManagementAssessment::create([
                'assessment_id' => $assessment->id,
                'episode_id' => $episode->id,
                'pain_type' => $data['pain_management']['pain_type'] ?? null,
                'pain_sensitization_findings' => $data['pain_management']['pain_sensitization_findings'] ?? null,
                'psychosocial_factors' => $data['pain_management']['psychosocial_factors'] ?? null,
                'pain_catastrophizing_score' => $data['pain_management']['pain_catastrophizing_score'] ?? null,
                'pain_profile' => $data['pain_management']['pain_profile'] ?? null,
                'chronicity_level' => $data['pain_management']['chronicity_level'] ?? null,
                'treatment_response' => $data['pain_management']['treatment_response'] ?? null,
                'assessed_at' => $assessedAt,
                'therapist_id' => $therapistId,
            ]);
        }
    }

    /**
     * Save universal data (pain, outcome measures, functional scores, vitals)
     */
    protected function saveUniversalData($assessment, $episode, $data, $therapistId, $assessedAt): void
    {
        // Pain Assessment
        if (isset($data['pain'])) {
            \App\Models\PainAssessment::create([
                'assessment_id' => $assessment->id,
                'episode_id' => $episode->id,
                'scale_type' => $data['pain']['scale_type'] ?? 'NPRS',
                'score' => $data['pain']['score'] ?? null,
                'location' => $data['pain']['location'] ?? null,
                'quality' => $data['pain']['quality'] ?? null,
                'aggravating_factors' => $data['pain']['aggravating_factors'] ?? null,
                'relieving_factors' => $data['pain']['relieving_factors'] ?? null,
                'timing' => $data['pain']['timing'] ?? null,
                'assessed_at' => $assessedAt,
                'therapist_id' => $therapistId,
            ]);
        }

        // Outcome Measures
        if (isset($data['outcome_measures']) && is_array($data['outcome_measures'])) {
            foreach ($data['outcome_measures'] as $measure) {
                \App\Models\OutcomeMeasure::create([
                    'assessment_id' => $assessment->id,
                    'episode_id' => $episode->id,
                    'measure_name' => $measure['measure_name'] ?? '',
                    'specialty_key' => $measure['specialty_key'] ?? null,
                    'scores' => $measure['scores'] ?? null,
                    'total_score' => $measure['total_score'] ?? null,
                    'percentage' => $measure['percentage'] ?? null,
                    'interpretation' => $measure['interpretation'] ?? null,
                    'assessment_type' => $measure['assessment_type'] ?? 'initial',
                    'assessed_at' => $assessedAt,
                    'therapist_id' => $therapistId,
                ]);
            }
        }

        // Functional Scores
        if (isset($data['functional_scores']) && is_array($data['functional_scores'])) {
            foreach ($data['functional_scores'] as $functional) {
                \App\Models\FunctionalScore::create([
                    'assessment_id' => $assessment->id,
                    'episode_id' => $episode->id,
                    'functional_task' => $functional['functional_task'] ?? '',
                    'category' => $functional['category'] ?? 'ADL',
                    'score' => $functional['score'] ?? null,
                    'limitations' => $functional['limitations'] ?? null,
                    'assistive_devices_used' => $functional['assistive_devices_used'] ?? null,
                    'assessed_at' => $assessedAt,
                    'therapist_id' => $therapistId,
                ]);
            }
        }

        // Vital Signs
        if (isset($data['vital_signs'])) {
            \App\Models\VitalSignsLog::create([
                'assessment_id' => $assessment->id,
                'episode_id' => $episode->id,
                'heart_rate' => $data['vital_signs']['heart_rate'] ?? null,
                'blood_pressure_systolic' => $data['vital_signs']['blood_pressure_systolic'] ?? null,
                'blood_pressure_diastolic' => $data['vital_signs']['blood_pressure_diastolic'] ?? null,
                'respiratory_rate' => $data['vital_signs']['respiratory_rate'] ?? null,
                'oxygen_saturation' => $data['vital_signs']['oxygen_saturation'] ?? null,
                'temperature' => $data['vital_signs']['temperature'] ?? null,
                'notes' => $data['vital_signs']['notes'] ?? null,
                'recorded_at' => $assessedAt,
                'therapist_id' => $therapistId,
            ]);
        }
    }

    /**
     * Get default assessment components by specialty
     */
    protected function getDefaultComponents(string $specialtyKey): array
    {
        $components = [
            'musculoskeletal' => [
                'subjective' => ['pain', 'mechanism_of_injury', 'functional_limitations'],
                'objective' => ['posture', 'rom', 'strength', 'special_tests'],
            ],
            'neurological' => [
                'subjective' => ['functional_independence', 'fatigue', 'balance_confidence'],
                'objective' => ['tone', 'reflexes', 'coordination', 'balance', 'gait'],
            ],
            'cardiopulmonary' => [
                'subjective' => ['dyspnea', 'exercise_tolerance'],
                'objective' => ['vital_signs', 'chest_expansion', 'breath_sounds', 'oxygen_saturation'],
            ],
            'pediatric' => [
                'subjective' => ['parent_report', 'developmental_history', 'birth_history'],
                'objective' => ['gross_motor_milestones', 'primitive_reflexes', 'postural_reactions'],
            ],
            'geriatric' => [
                'subjective' => ['fall_history', 'functional_independence'],
                'objective' => ['balance', 'strength', 'gait_speed'],
            ],
            'sports' => [
                'subjective' => ['sport_specific_demands'],
                'objective' => ['power', 'agility', 'symmetry_tests'],
            ],
            'womens_health' => [
                'subjective' => ['bladder_bowel_diary', 'pain_mapping'],
                'objective' => ['pelvic_floor_strength', 'endurance'],
            ],
            'pain_management' => [
                'subjective' => ['pain_sensitization', 'psychosocial_factors'],
                'objective' => ['pain_profile', 'chronicity_level'],
            ],
        ];

        return $components[$specialtyKey] ?? [];
    }

    /**
     * Get default tools by specialty
     */
    protected function getDefaultTools(string $specialtyKey): array
    {
        $tools = [
            'musculoskeletal' => ['goniometer', 'dynamometer', 'posture_grid', 'taping_tools'],
            'neurological' => ['reflex_hammer', 'balance_board', 'gait_mat', 'parallel_bars'],
            'cardiopulmonary' => ['pulse_oximeter', 'spirometer', 'incentive_spirometer'],
            'pediatric' => ['pediatric_balance_tools', 'therapy_balls', 'sensory_toys'],
            'geriatric' => ['walkers', 'canes', 'balance_trainers'],
            'sports' => ['force_plates', 'agility_ladders', 'plyometric_equipment'],
            'womens_health' => ['biofeedback', 'pelvic_floor_trainers'],
            'pain_management' => ['TENS', 'dry_needling_tools'],
        ];

        return $tools[$specialtyKey] ?? [];
    }
}

