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
        // Simply check if superadmin is authenticated - rely on Laravel's session handling
        if (!Auth::guard('superadmin')->check()) {
            \Log::info('SuperAdmin not authenticated, redirecting to login', [
                'url' => $request->url(),
            ]);
            
            // If this is an AJAX request, return JSON instead of redirect
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required. Please refresh the page.',
                    'unauthenticated' => true
                ], 401);
            }
            
            return redirect()->route('superadmin.login');
        }
        
        // Ensure session marker is present (for compatibility, but don't enforce it)
        if (!$request->session()->has('superadmin_session_active')) {
            $request->session()->put('superadmin_session_active', true);
            $request->session()->save();
        }

        $response = $next($request);

        // Ensure superadmin pages are not cached by browsers
        if (method_exists($response, 'headers')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}
