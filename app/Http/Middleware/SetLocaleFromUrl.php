<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromUrl
{
    /**
     * Handle an incoming request.
     * Sets locale from URL segment (en/ar) before routes are matched
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        $segments = explode('/', $path);
        
        // Check if first segment is a valid locale
        if (!empty($segments[0]) && in_array($segments[0], ['en', 'ar'])) {
            $locale = $segments[0];
            app()->setLocale($locale);
            session(['locale' => $locale]);
            // Set the locale in LaravelLocalization package so setLocale() returns correct prefix
            \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale($locale);
        } else {
            // For non-localized routes (like /dashboard/*), use session or default
            // Don't set locale for dashboard routes
            if (!str_starts_with($path, 'dashboard')) {
                $locale = session('locale', config('app.locale', 'en'));
                app()->setLocale($locale);
                \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale($locale);
            }
        }
        
        return $next($request);
    }
}

