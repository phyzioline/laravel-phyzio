<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AutoLogoutMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $minutes  Minutes of inactivity before logout (default: 30)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, int $minutes = 30)
    {
        if (Auth::check()) {
            $lastActivity = Session::get('last_activity');
            $now = now();

            // Check if last activity exists and if timeout has passed
            if ($lastActivity && $now->diffInMinutes($lastActivity) >= $minutes) {
                // Log the auto-logout
                \App\Models\ActivityLog::log('auto_logout', null, null, null, 'User automatically logged out due to inactivity');

                Auth::logout();
                Session::flush();

                return redirect()->route('login')
                    ->with('warning', __('Your session has expired due to inactivity. Please login again.'));
            }

            // Update last activity time
            Session::put('last_activity', $now);
        }

        return $next($request);
    }
}

