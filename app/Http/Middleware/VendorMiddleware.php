<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('view_login')
                ->with('error', 'Please login to access vendor dashboard');
        }

        $user = Auth::user();

        // Check if user is a vendor
        if ($user->type !== 'vendor') {
            abort(403, 'Access denied. Vendor account required.');
        }

        // Check if vendor account is active
        if ($user->status !== 'active') {
            return redirect()->route('home')
                ->with('error', 'Your vendor account is inactive. Please contact support.');
        }

        return $next($request);
    }
}
