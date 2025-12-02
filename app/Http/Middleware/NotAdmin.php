<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      if (Auth::check() && !Auth::user()->roles->isEmpty()) {
        return redirect()->route('Admin.home')->with('error', 'لا يمكنك الوصول إلى هذه الصفحة.');
    }
        return $next($request);
    }
}
