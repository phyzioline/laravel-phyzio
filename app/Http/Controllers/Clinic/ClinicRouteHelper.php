<?php

namespace App\Http\Controllers\Clinic;

use App\Models\Clinic;
use Illuminate\Support\Facades\Auth;

/**
 * Helper class for clinic route operations
 * Prevents unnecessary redirects
 */
class ClinicRouteHelper
{
    /**
     * Get clinic or return null (don't redirect)
     */
    public static function getClinicOrNull($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return null;
        }

        // Try to find clinic by company_id
        try {
            return Clinic::where('company_id', $user->id)
                ->where('is_deleted', false)
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Check if user has clinic access
     */
    public static function hasClinicAccess($user = null)
    {
        $clinic = self::getClinicOrNull($user);
        return $clinic !== null;
    }
}

