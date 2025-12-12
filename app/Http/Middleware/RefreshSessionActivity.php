<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to refresh session activity timestamp
 * This ensures sessions stay alive as long as the user is active
 */
class RefreshSessionActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Update last activity timestamp in session
        // Wrapped in try-catch to handle session storage failures gracefully
        try {
            if ($request->hasSession()) {
                $request->session()->put('last_activity', time());
            }
        } catch (\Exception $e) {
            // Log error but don't block the request
            \Log::warning('Failed to update session activity', [
                'error' => $e->getMessage(),
                'url' => $request->url()
            ]);
        }

        return $next($request);
    }
}
