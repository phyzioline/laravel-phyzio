<?php

namespace App\Services\Ai;

use App\Models\Job;
use App\Models\User;
use App\Models\Course;
use App\Models\Skill;
use Illuminate\Support\Collection;

class SkillGapService
{
    /**
     * Calculate the match percentage between a therapist and a job.
     */
    public function calculateMatchScore(Job $job, User $therapist): array
    {
        $job->load('skills');
        
        // If job has no skill requirements, return 100% or 0% depending on logic (usually 100 for open apply)
        if ($job->skills->isEmpty()) {
            return [
                'score' => 100,
                'missing_skills' => collect(),
                'matched_skills' => collect()
            ];
        }

        // Get therapist's verified skills
        // Assuming therapist has a 'skills' relation through 'skill_verifications' or 'therapistProfile.skills'
        $therapistSkills = $therapist->therapistProfile->verifiedSkills->pluck('id')->toArray(); 

        $requiredSkills = $job->skills;
        $totalWeight = $requiredSkills->count();
        $matchedCount = 0;
        $missingSkills = collect();
        $matchedSkills = collect();

        foreach ($requiredSkills as $jobSkill) {
            if (in_array($jobSkill->id, $therapistSkills)) {
                $matchedCount++;
                $matchedSkills->push($jobSkill);
            } else {
                $missingSkills->push($jobSkill);
            }
        }

        $score = ($matchedCount / $totalWeight) * 100;

        return [
            'score' => round($score),
            'missing_skills' => $missingSkills,
            'matched_skills' => $matchedSkills
        ];
    }

    /**
     * Recommend courses that teach the missing skills.
     */
    public function recommendCourses(Collection $missingSkills): Collection
    {
        if ($missingSkills->isEmpty()) {
            return collect();
        }

        $missingSkillIds = $missingSkills->pluck('id');

        // Find courses that teach these skills
        // Requires Course -> matches -> Skills
        return Course::whereHas('skills', function($q) use ($missingSkillIds) {
            $q->whereIn('skills.id', $missingSkillIds);
        })->with('skills')->get()->map(function($course) use ($missingSkillIds) {
            // Add metadata about which gap this course fills
            $course->fills_gaps = $course->skills->whereIn('id', $missingSkillIds)->values();
            return $course;
        });
    }
}
