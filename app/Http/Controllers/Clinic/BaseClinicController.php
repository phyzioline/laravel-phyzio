<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use Illuminate\Support\Facades\Auth;

/**
 * Base controller for all clinic controllers
 * Provides common methods for getting clinic from authenticated user
 */
abstract class BaseClinicController extends Controller
{
    /**
     * Get user's clinic
     * Adjust this method based on your actual user-clinic relationship
     */
    protected function getUserClinic($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return null;
        }

        // Option 1: User has direct clinic relationship (if method exists)
        if (method_exists($user, 'clinic')) {
            try {
                $clinic = $user->clinic;
                if ($clinic) {
                    return $clinic;
                }
            } catch (\Exception $e) {
                // Relationship doesn't exist, continue
            }
        }

        // Option 2: User is company and has clinics relationship
        if ($user->type === 'company' && method_exists($user, 'clinics')) {
            try {
                $clinic = $user->clinics()->first();
                if ($clinic) {
                    return $clinic;
                }
            } catch (\Exception $e) {
                // Relationship doesn't exist, continue
            }
        }

        // Option 3: Find clinic by company_id matching user_id (MOST COMMON)
        try {
            $clinic = Clinic::where('company_id', $user->id)
                ->where('is_deleted', false)
                ->first();
            
            if ($clinic) {
                return $clinic;
            }
        } catch (\Exception $e) {
            \Log::error('Error finding clinic', [
                'user_id' => $user->id,
                'user_type' => $user->type,
                'error' => $e->getMessage()
            ]);
        }

        // Option 4: Try to find any clinic for this user (fallback)
        try {
            return Clinic::where('company_id', $user->id)->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get clinic ID from authenticated user
     */
    protected function getClinicId()
    {
        $clinic = $this->getUserClinic();
        return $clinic ? $clinic->id : null;
    }
}

