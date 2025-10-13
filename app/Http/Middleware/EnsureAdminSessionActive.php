<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdminSessionActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // If the admin is authenticated but our session marker is missing, force logout.
        if (Auth::guard('admin')->check() && ! $request->session()->has('admin_session_active')) {
            Auth::guard('admin')->logout();
            try { $request->session()->invalidate(); } catch (\Throwable $e) {}
            try { $request->session()->regenerateToken(); } catch (\Throwable $e) {}
            return redirect()->route('admin.login');
        }

        $response = $next($request);

        // Ensure admin pages are not cached by browsers so back/refresh won't show stale auth content.
        if (method_exists($response, 'headers')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}
