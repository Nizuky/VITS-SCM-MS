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
        // If the admin is authenticated but our session marker is missing, restore it instead of forcing logout.
        if (Auth::guard('admin')->check()) {
            if (! $request->session()->has('admin_session_active')) {
                \Log::info('Admin session marker missing, restoring it', [
                    'url' => $request->url(),
                    'session_id' => $request->session()->getId(),
                    'admin_id' => Auth::guard('admin')->id(),
                ]);
                
                // Restore the session marker instead of logging out
                $request->session()->put('admin_session_active', true);
                $request->session()->save();
            }
        } else {
            // If not authenticated, redirect to login
            \Log::info('Admin not authenticated, redirecting to login', [
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
