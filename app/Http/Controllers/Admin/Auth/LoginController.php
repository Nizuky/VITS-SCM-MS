<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Try to get default admin name, but don't fail if database is unavailable
        $defaultName = null;
        try {
            $admin = \App\Models\AdminUser::first();
            $defaultName = $admin ? $admin->name : null;
        } catch (\Throwable $e) {
            // Database not ready yet or connection issue - just use null
            \Log::warning('Could not fetch AdminUser for login page: ' . $e->getMessage());
        }
        
        return view('auth.admin-login', ['defaultAdminName' => $defaultName]);
    }

    public function login(Request $request)
    {
        // Rate limiting: 5 attempts per minute per IP
        $key = 'admin-login:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            
            \Log::warning('Admin login rate limit exceeded', [
                'ip' => $request->ip(),
                'retry_after' => $seconds
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Too many login attempts. Please try again in {$seconds} seconds."
                ], 429);
            }
            
            throw ValidationException::withMessages([
                'name' => ["Too many login attempts. Please try again in {$seconds} seconds."]
            ]);
        }
        
        $credentials = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('admin')->attempt(['name' => $credentials['name'], 'password' => $credentials['password']], false)) {
            // Clear rate limiter on successful login
            RateLimiter::clear($key);
            
            // Regenerate CSRF token (not the entire session) to prevent session fixation
            $request->session()->regenerateToken();
            
            // Mark this session as an active admin session
            // IMPORTANT: never use remember_me for admins - always expire on tab close
            $request->session()->put('auth_guard', 'admin');
            $request->session()->put('admin_session_active', true);
            $request->session()->put('last_activity', time());
            $request->session()->save();
            
            // Log successful login
            \Log::info('Admin login successful', [
                'admin_id' => Auth::guard('admin')->id(),
                'admin_name' => $credentials['name'],
                'ip' => $request->ip(),
                'session_id' => $request->session()->getId()
            ]);
            
            $redirect = route('admin.dashboard');

            if ($request->expectsJson()) {
                return response()->json(['redirect' => $redirect, 'success' => true]);
            }

            return redirect()->intended($redirect);
        }
        
        // Increment rate limiter on failed attempt
        RateLimiter::hit($key, 60);
        
        \Log::warning('Admin login failed', [
            'name' => $credentials['name'],
            'ip' => $request->ip()
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'These credentials do not match our records.'], 422);
        }

        return back()->withErrors(['name' => 'These credentials do not match our records.'])->withInput($request->only('name'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // clear guard marker if present
        if ($request->session()->get('auth_guard') === 'admin') {
            $request->session()->forget('auth_guard');
            $request->session()->forget('admin_session_active');
        }

        // Flash a message that can be shown on the login screen (mirrors super admin behavior)
        return redirect()->route('admin.login')->with('success', 'You have been logged out.');
    }
}
