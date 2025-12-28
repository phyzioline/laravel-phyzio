<?php

namespace App\Traits;

use Illuminate\Http\RedirectResponse;

trait ChecksModuleAccess
{
    /**
     * Check if therapist has access to a specific module
     */
    protected function checkModuleAccess(string $moduleType): bool|RedirectResponse
    {
        $user = auth()->user();
        
        if ($user->type !== 'therapist') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $therapistProfile = $user->therapistProfile;
        
        if (!$therapistProfile) {
            return redirect()->route('home')->with('error', 'Therapist profile not found.');
        }

        if (!$therapistProfile->canAccessModule($moduleType)) {
            $moduleNames = [
                'home_visit' => 'Home Visit',
                'courses' => 'Courses',
                'clinic' => 'Clinic',
            ];
            
            $moduleName = $moduleNames[$moduleType] ?? $moduleType;
            
            return redirect()->back()->with('error', "You don't have access to the {$moduleName} module. Please upload the required documents and wait for admin approval.");
        }

        return true;
    }
}

