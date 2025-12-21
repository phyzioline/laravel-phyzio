<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetDashboardLocale
{
    /**
     * Handle an incoming request for dashboard routes
     * Sets locale from session instead of URL
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from session, default to 'en'
        $locale = session('locale', config('app.locale', 'en'));
        
        // Validate locale
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }
        
        // Set application locale
        app()->setLocale($locale);
        
        return $next($request);
    }
}

