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

        // Option 1: User has direct clinic relationship
        if (method_exists($user, 'clinic') && $user->clinic) {
            return $user->clinic;
        }

        // Option 2: User is company and has clinics
        if ($user->type === 'company' && method_exists($user, 'clinics')) {
            return $user->clinics()->first();
        }

        // Option 3: Find clinic by company_id matching user_id
        return Clinic::where('company_id', $user->id)->first();
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

