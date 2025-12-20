<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class SetCurrencyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('currency')) {
            // Priority 1: User Profile
            if (auth()->check() && auth()->user()->country_code) {
                // Simple mapping, ideally this is a shared helper
                $map = [
                     'EG' => 'EGP', 'US' => 'USD', 'SA' => 'SAR', 
                     'AE' => 'AED', 'KW' => 'KWD'
                ];
                $code = strtoupper(auth()->user()->country_code);
                if (isset($map[$code])) {
                    Session::put('currency', $map[$code]);
                }
            } else {
                // Priority 2: IP Based (Simplified for now, can add GeoIP package later)
                // Default to EGP
                Session::put('currency', config('currency.default', 'EGP'));
            }
        }

        return $next($request);
    }
}
