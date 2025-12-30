<?php

namespace App\Services;

use App\Models\Job;
use App\Models\TherapistProfile;
use App\Models\User;

class MatchingService
{
    /**
     * Calculate match score between a job and a therapist.
     * Now returns detailed breakdown for transparency.
     * 
     * @param Job $job
     * @param User $therapist
     * @return array ['score' => float, 'breakdown' => array]
     */
    public function calculateScore(Job $job, User $therapist)
    {
        $score = 0;
        $breakdown = [];
        $profile = $therapist->therapistProfile;

        if (!$profile) {
            return [
                'score' => 0,
                'breakdown' => ['error' => 'No therapist profile found']
            ];
        }

        // 1. Specialty Match (30%)
        $jobSpecialties = $job->specialty ?? [];
        $therapistSpecialties = is_array($profile->specialization) ? $profile->specialization : explode(',', $profile->specialization);
        $therapistSpecialties = array_map('trim', $therapistSpecialties);

        $specialtyMatches = array_intersect($jobSpecialties, $therapistSpecialties);
        if (!empty($specialtyMatches)) {
            $score += 30;
            $breakdown['specialty'] = [
                'points' => 30,
                'max' => 30,
                'matches' => $specialtyMatches,
                'status' => 'matched'
            ];
        } else {
            $breakdown['specialty'] = [
                'points' => 0,
                'max' => 30,
                'required' => $jobSpecialties,
                'therapist_has' => $therapistSpecialties,
                'status' => 'no_match'
            ];
        }

        // 2. Skills Match (30%)
        $jobTechniques = $job->techniques ?? [];
        $therapistSkills = $profile->skills_matrix ?? [];
        
        $matchedSkills = 0;
        $matchedSkillsList = [];
        
        if (count($jobTechniques) > 0) {
            foreach ($jobTechniques as $tech) {
                if (isset($therapistSkills[$tech]) || in_array($tech, array_keys($therapistSkills))) {
                    $matchedSkills++;
                    $matchedSkillsList[] = $tech;
                }
            }
            $skillsScore = $matchedSkills > 0 ? 30 * ($matchedSkills / count($jobTechniques)) : 0;
            $score += $skillsScore;
            
            $breakdown['skills'] = [
                'points' => round($skillsScore, 1),
                'max' => 30,
                'matched' => $matchedSkills,
                'required' => count($jobTechniques),
                'match_rate' => round(($matchedSkills / count($jobTechniques)) * 100, 1) . '%',
                'matched_skills' => $matchedSkillsList,
                'status' => $matchedSkills > 0 ? 'partial_match' : 'no_match'
            ];
        } else {
            $score += 30;
            $breakdown['skills'] = [
                'points' => 30,
                'max' => 30,
                'status' => 'no_requirements'
            ];
        }

        // 3. Location (10%)
        $locationMatch = stripos($job->location, $profile->location ?? '') !== false;
        if ($locationMatch) {
            $score += 10;
            $breakdown['location'] = [
                'points' => 10,
                'max' => 10,
                'job_location' => $job->location,
                'therapist_location' => $profile->location,
                'status' => 'matched'
            ];
        } else {
            $breakdown['location'] = [
                'points' => 0,
                'max' => 10,
                'job_location' => $job->location,
                'therapist_location' => $profile->location ?? 'Not specified',
                'status' => 'no_match'
            ];
        }

        // 4. Experience (10%)
        $minExperience = $job->requirements->min_years_experience ?? 0;
        $therapistExperience = $profile->years_experience ?? 0;
        
        if ($therapistExperience >= $minExperience) {
            $score += 10;
            $breakdown['experience'] = [
                'points' => 10,
                'max' => 10,
                'required' => $minExperience . ' years',
                'therapist_has' => $therapistExperience . ' years',
                'status' => 'qualified'
            ];
        } else {
            $breakdown['experience'] = [
                'points' => 0,
                'max' => 10,
                'required' => $minExperience . ' years',
                'therapist_has' => $therapistExperience . ' years',
                'shortfall' => ($minExperience - $therapistExperience) . ' years',
                'status' => 'insufficient'
            ];
        }

        // 5. Gender Preference (10%)
        $genderPref = $job->requirements->gender_preference ?? 'no_preference';
        $therapistGender = $therapist->gender ?? $profile->gender ?? null;
        
        if (empty($genderPref) || $genderPref === 'no_preference' || $genderPref === $therapistGender) {
            $score += 10;
            $breakdown['gender'] = [
                'points' => 10,
                'max' => 10,
                'status' => 'matched'
            ];
        } else {
            $breakdown['gender'] = [
                'points' => 0,
                'max' => 10,
                'preferred' => $genderPref,
                'therapist' => $therapistGender ?? 'Not specified',
                'status' => 'no_match'
            ];
        }

        // 6. Availability (10%) - Bonus points
        // Future: Check therapist schedule availability
        $breakdown['availability'] = [
            'points' => 10,
            'max' => 10,
            'status' => 'assumed_available',
            'note' => 'Schedule integration pending'
        ];
        $score += 10;

        $finalScore = min($score, 100);
        
        return [
            'score' => $finalScore,
            'breakdown' => $breakdown,
            'summary' => $this->generateSummary($breakdown, $finalScore)
        ];
    }

    /**
     * Generate human-readable summary of match score.
     */
    private function generateSummary(array $breakdown, float $score): array
    {
        $strengths = [];
        $weaknesses = [];
        
        foreach ($breakdown as $category => $details) {
            if (isset($details['status'])) {
                if (in_array($details['status'], ['matched', 'qualified', 'no_requirements'])) {
                    $strengths[] = ucfirst($category) . ': ' . $details['points'] . '/' . $details['max'];
                } elseif (in_array($details['status'], ['no_match', 'insufficient'])) {
                    $weaknesses[] = ucfirst($category) . ': ' . $details['points'] . '/' . $details['max'];
                }
            }
        }

        return [
            'overall' => $score >= 70 ? 'Excellent Match' : ($score >= 50 ? 'Good Match' : 'Fair Match'),
            'strengths' => $strengths,
            'weaknesses' => $weaknesses,
            'recommendation' => $score >= 70 ? 'Highly Recommended' : ($score >= 50 ? 'Recommended' : 'Consider with caution')
        ];
    }
}
