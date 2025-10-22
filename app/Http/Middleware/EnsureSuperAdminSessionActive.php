<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSuperAdminSessionActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // If the superadmin is authenticated but our session marker is missing, force logout.
        if (Auth::guard('superadmin')->check()) {
            if (! $request->session()->has('superadmin_session_active')) {
                \Log::warning('SuperAdmin session marker missing, forcing logout', [
                    'url' => $request->url(),
                    'session_id' => $request->session()->getId(),
                    'superadmin_id' => Auth::guard('superadmin')->id(),
                    'all_session_data' => $request->session()->all(),
                ]);
                Auth::guard('superadmin')->logout();
                try { $request->session()->invalidate(); } catch (\Throwable $e) {}
                try { $request->session()->regenerateToken(); } catch (\Throwable $e) {}
                return redirect()->route('superadmin.login')->with('error', 'Session expired. Please login again.');
            }
        } else {
            // If not authenticated, redirect to login
            \Log::info('SuperAdmin not authenticated, redirecting to login', [
                'url' => $request->url(),
            ]);
            return redirect()->route('superadmin.login');
        }

        $response = $next($request);

        // Ensure superadmin pages are not cached by browsers so back/refresh won't show stale auth content.
        if (method_exists($response, 'headers')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}
