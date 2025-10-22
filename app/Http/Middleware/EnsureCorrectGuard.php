<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCorrectGuard
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $guard = 'web'): Response
    {
        // If the user is authenticated on a different guard, log them out from that guard
        if ($guard === 'web') {
            // For web guard, ensure user is not authenticated on admin/superadmin guards
            if (Auth::guard('admin')->check()) {
                Auth::guard('admin')->logout();
            }
            if (Auth::guard('superadmin')->check()) {
                Auth::guard('superadmin')->logout();
            }
        }

        return $next($request);
    }
}
