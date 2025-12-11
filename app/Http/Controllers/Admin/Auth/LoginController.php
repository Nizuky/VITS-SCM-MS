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
        // Don't query database on login page to avoid timeouts
        // Default name will be empty - user types their own name
        return view('auth.admin-login', ['defaultAdminName' => null]);
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

        try {
            // Attempt login with retry logic for database connection
            $maxRetries = 3;
            $retryDelay = 1000000; // 1 second in microseconds
            $loginAttempt = false;
            $lastException = null;
            
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                    $loginAttempt = Auth::guard('admin')->attempt([
                        'name' => $credentials['name'], 
                        'password' => $credentials['password']
                    ], false);
                    break; // Success, exit retry loop
                } catch (\PDOException $e) {
                    $lastException = $e;
                    \Log::warning("Admin login database query attempt {$attempt} failed", [
                        'error' => $e->getMessage(),
                        'name' => $credentials['name'],
                        'attempt' => $attempt,
                        'ip' => $request->ip()
                    ]);
                    
                    if ($attempt < $maxRetries) {
                        // Wait before retrying (exponential backoff)
                        usleep($retryDelay * $attempt);
                        
                        // Try to reconnect
                        try {
                            \DB::reconnect();
                        } catch (\Exception $reconnectException) {
                            \Log::warning('Database reconnect failed', [
                                'error' => $reconnectException->getMessage()
                            ]);
                        }
                    }
                }
            }
            
            // If all retries failed, throw the last exception
            if (!$loginAttempt && $lastException !== null) {
                throw $lastException;
            }

            if ($loginAttempt) {
                // Clear rate limiter on successful login
                RateLimiter::clear($key);
                
                // Regenerate CSRF token (not the entire session) to prevent session fixation
                $request->session()->regenerateToken();
                
                // Mark this session as an active admin session
                // IMPORTANT: never use remember_me for admins - always expire on tab close
                $request->session()->put('auth_guard', 'admin');
                $request->session()->put('admin_session_active', true);
                $request->session()->put('last_activity', time());
                $request->session()->put('remembered', false); // Explicitly disable remember me
                
                // Ensure no remember cookie exists
                try {
                    $guard = Auth::guard('admin');
                    if (method_exists($guard, 'getRecallerName')) {
                        $recaller = $guard->getRecallerName();
                        \Cookie::queue(\Cookie::forget(
                            $recaller,
                            config('session.path', '/'),
                            config('session.domain')
                        ));
                    }
                } catch (\Throwable $e) { /* ignore */ }
                
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
        } catch (\PDOException $e) {
            // Database connection error
            \Log::error('Admin login database error', [
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unable to connect to the database. Please try again in a moment.'
                ], 503);
            }
            
            return back()->withErrors([
                'name' => 'Unable to connect to the database. Please try again in a moment.'
            ])->withInput($request->only('name'));
        } catch (\Exception $e) {
            // General error
            \Log::error('Admin login error', [
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'An error occurred during login. Please try again.'
                ], 500);
            }
            
            return back()->withErrors([
                'name' => 'An error occurred during login. Please try again.'
            ])->withInput($request->only('name'));
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
