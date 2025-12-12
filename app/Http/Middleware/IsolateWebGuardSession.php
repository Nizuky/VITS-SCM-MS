<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsolateWebGuardSession
{
    /**
     * Handle an incoming request.
     *
     * This middleware ensures that web guard authentication doesn't interfere
     * with admin or superadmin sessions by managing authentication state carefully.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only run this check on registration POST requests
        if (!$request->is('register') || !$request->isMethod('POST')) {
            return $next($request);
        }
        
        try {
            // Store the current auth states for admin/superadmin before processing registration
            $wasAdminLoggedIn = Auth::guard('admin')->check();
            $wasSuperAdminLoggedIn = Auth::guard('superadmin')->check();
            
            // Store their user IDs and session markers
            $adminUserId = $wasAdminLoggedIn ? Auth::guard('admin')->id() : null;
            $superAdminUserId = $wasSuperAdminLoggedIn ? Auth::guard('superadmin')->id() : null;
            $adminSessionMarker = session('admin_session_active');
            $superAdminSessionMarker = session('superadmin_session_active');
            
            // Process the request (Fortify will create user and auto-login on web guard)
            $response = $next($request);
            
            // After the request, ensure admin/superadmin are still authenticated
            // This handles cases where Fortify might have inadvertently affected their sessions
            if ($wasAdminLoggedIn && !Auth::guard('admin')->check()) {
                // Admin was logged out during registration - restore their session
                if ($adminUserId) {
                    $adminUser = \App\Models\AdminUser::find($adminUserId);
                    if ($adminUser) {
                        Auth::guard('admin')->login($adminUser, true);
                        if ($adminSessionMarker) {
                            session(['admin_session_active' => true]);
                        }
                    }
                }
            }
            
            if ($wasSuperAdminLoggedIn && !Auth::guard('superadmin')->check()) {
                // SuperAdmin was logged out during registration - restore their session
                if ($superAdminUserId) {
                    $superAdminUser = \App\Models\SuperAdmin::find($superAdminUserId);
                    if ($superAdminUser) {
                        Auth::guard('superadmin')->login($superAdminUser, true);
                        if ($superAdminSessionMarker) {
                            session(['superadmin_session_active' => true]);
                        }
                    }
                }
            }
            
            return $response;
            
        } catch (\Exception $e) {
            // Database unavailable, just continue with the request
            return $next($request);
        }
    }
}
                }
            }
        }
        
        if ($wasSuperAdminLoggedIn && !Auth::guard('superadmin')->check()) {
            // Super admin was logged out during registration - restore their session
            if ($superAdminUserId) {
                $superAdminUser = \App\Models\SuperAdmin::find($superAdminUserId);
                if ($superAdminUser) {
                    Auth::guard('superadmin')->login($superAdminUser, true);
                    if ($superAdminSessionMarker) {
                        session(['superadmin_session_active' => true]);
                    }
                }
            }
        }
        
        return $response;
    }
}
