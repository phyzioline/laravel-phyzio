<?php

namespace App\Services;

use App\Models\Job;
use App\Models\TherapistProfile;
use App\Models\User;

class MatchingService
{
    /**
     * Calculate match score between a job and a therapist.
     * 
     * @param Job $job
     * @param User $therapist
     * @return float
     */
    public function calculateScore(Job $job, User $therapist)
    {
        $score = 0;
        $profile = $therapist->therapistProfile;

        if (!$profile) {
            return 0;
        }

        // 1. Specialty Match (30%)
        // Assuming job->specialty is array of strings and profile->specialization is string or array
        // We'll normalize to array
        $jobSpecialties = $job->specialty ?? [];
        $therapistSpecialties = is_array($profile->specialization) ? $profile->specialization : explode(',', $profile->specialization);
        $therapistSpecialties = array_map('trim', $therapistSpecialties);

        if (!empty(array_intersect($jobSpecialties, $therapistSpecialties))) {
            $score += 30;
        }

        // 2. Skills Match (30%) - Placeholder logic based on 'techniques' vs 'skills_matrix'
        // If profile has ANY of the job techniques in their matrix
        $jobTechniques = $job->techniques ?? [];
        $therapistSkills = $profile->skills_matrix ?? []; // array of key => score
        
        // Simple check: if key exists in therapist skills
        $matchedSkills = 0;
        if (count($jobTechniques) > 0) {
            foreach ($jobTechniques as $tech) {
                // Normalize string for key check if needed, but assuming direct match for now
                if (isset($therapistSkills[$tech]) || in_array($tech, array_keys($therapistSkills))) {
                    $matchedSkills++;
                }
            }
            if ($matchedSkills > 0) {
                $score += 30 * ($matchedSkills / count($jobTechniques));
            }
        } else {
            // No techniques required? Free points? Or neutral. Let's give full if no requirements.
            $score += 30;
        }

        // 3. Location (10%)
        if (stripos($job->location, $profile->location ?? '') !== false) { // Simple string match
            $score += 10;
        }

        // 4. Experience (10%)
        if ($profile->years_experience >= ($job->requirements->min_years_experience ?? 0)) {
            $score += 10;
        }

        // 5. Gender Preference (10%)
        if (empty($job->requirements->gender_preference) || 
            $job->requirements->gender_preference === 'no_preference' || 
            $job->requirements->gender_preference === ($therapist->gender ?? $profile->gender)) {
            $score += 10;
        }

        // 6. Salary (10%) - Optional check
        // ...

        return min($score, 100);
    }
}
