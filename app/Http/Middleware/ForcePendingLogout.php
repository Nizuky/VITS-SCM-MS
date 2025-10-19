<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcePendingLogout
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            $pending = $request->cookies->get('scms_force_logout_pending');
            if ($pending === '1') {
                // Clear the cookie on response
                $clear = cookie('scms_force_logout_pending', '', -1, config('session.path', '/'), config('session.domain'));
                if (method_exists($response, 'withCookie')) {
                    $response->withCookie($clear);
                }
                // If user is authenticated on web guard, log them out server-side
                if (Auth::guard('web')->check()) {
                    Auth::guard('web')->logout();
                    try { $request->session()->invalidate(); } catch (\Throwable $e) {}
                    try { $request->session()->regenerateToken(); } catch (\Throwable $e) {}
                }
            }
        } catch (\Throwable $e) { /* ignore */ }

        return $response;
    }
}
