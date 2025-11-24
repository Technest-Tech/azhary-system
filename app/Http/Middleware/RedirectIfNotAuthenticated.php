<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $guard = null): Response
    {
        // If no guard is specified, check both admin and teacher guards
        if ($guard === null) {
            if (!Auth::guard('admin')->check() && !Auth::guard('teacher')->check()) {
                return redirect('/admin/login');
            }
        } else {
            // Check specific guard
            if (!Auth::guard($guard)->check()) {
                return redirect('/admin/login');
            }
        }

        return $next($request);
    }
}
