<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KeepSessionAlive
{
    /**
     * Handle an incoming request.
     *
     * This middleware prevents session timeout by updating the session
     * activity timestamp on every request while the user is authenticated.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check all guards for authenticated users
        if (auth()->guard('web')->check() || 
            auth()->guard('admin')->check() || 
            auth()->guard('superadmin')->check()) {
            
            // Update last activity timestamp
            session()->put('last_keep_alive', now());
        }
        
        return $next($request);
    }
}
