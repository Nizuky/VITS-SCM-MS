<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        // Rate limiting: 5 attempts per minute per IP
        $key = 'superadmin-login:' . $request->ip();
        
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($key);
            
            \Log::warning('SuperAdmin login rate limit exceeded', [
                'ip' => $request->ip(),
                'retry_after' => $seconds
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => "Too many login attempts. Please try again in {$seconds} seconds."
                ], 429);
            }
            
            return back()->withErrors([
                'name' => "Too many login attempts. Please try again in {$seconds} seconds."
            ]);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string',
        ]);

        try {
            $identifier = trim((string) $request->input('name'));
            
            // Retry logic for database queries (up to 3 attempts)
            $maxRetries = 3;
            $retryDelay = 1000000; // 1 second in microseconds
            $admin = null;
            $lastException = null;
            
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                    // Wrap database query with retry logic
                    $admin = SuperAdmin::where('name', $identifier)
                        ->orWhere('email', $identifier)
                        ->first();
                    break; // Success, exit retry loop
                } catch (\PDOException $e) {
                    $lastException = $e;
                    \Log::warning("SuperAdmin login database query attempt {$attempt} failed", [
                        'error' => $e->getMessage(),
                        'identifier' => $identifier,
                        'attempt' => $attempt
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
            if ($admin === null && $lastException !== null) {
                throw $lastException;
            }
                
            $passwordOk = false;
            if ($admin) {
                $stored = (string) $admin->password;
                $provided = (string) $request->input('password');
                $providedTrim = trim($provided);
                $storedTrim = trim($stored);

                // if stored value looks like bcrypt
                if (preg_match('/^\$2y\$|^\$2a\$|^\$2b\$/', $storedTrim)) {
                    try {
                        $passwordOk = Hash::check($providedTrim, $storedTrim);
                    } catch (\RuntimeException $e) {
                        // fall through to legacy checks
                        $passwordOk = false;
                    }
                } else {
                    // legacy plaintext exact match
                    if ($providedTrim === $storedTrim) {
                        $passwordOk = true;
                    }

                    // legacy md5 match (common old pattern)
                    if (! $passwordOk && strlen($storedTrim) === 32 && ctype_xdigit($storedTrim)) {
                        if (md5($providedTrim) === $storedTrim) {
                            $passwordOk = true;
                        }
                    }

                    // if legacy matched, upgrade to bcrypt
                    if ($passwordOk) {
                        $admin->password = Hash::make($providedTrim);
                        $admin->save();
                    }
                }
            }
        } catch (\PDOException $e) {
            // Database connection error
            \Log::error('SuperAdmin login database error', [
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Unable to connect to the database. Please try again in a moment.'
                ], 503);
            }
            
            return back()->withErrors([
                'name' => 'Unable to connect to the database. Please try again in a moment.'
            ])->withInput($request->only('name'));
        } catch (\Exception $e) {
            // General error
            \Log::error('SuperAdmin login error', [
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'An error occurred during login. Please try again.'
                ], 500);
            }
            
            return back()->withErrors([
                'name' => 'An error occurred during login. Please try again.'
            ])->withInput($request->only('name'));
        }

        if (! $admin || ! $passwordOk) {
            // Increment rate limiter on failed attempt
            \Illuminate\Support\Facades\RateLimiter::hit($key, 60);
            
            // Log failed attempt for debugging
            \Log::warning('SuperAdmin login failed', [
                'identifier' => $identifier,
                'ip' => $request->ip(),
            ]);
            // AJAX/json request -> return JSON error
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Unknown user or invalid password.'], 422);
            }

            return back()
                ->withInput($request->only('name'))
                ->withErrors(['name' => 'Unknown user or invalid password.'])
                ->with('error', 'Unknown user or invalid password.');
        }
        
        // Clear rate limiter on successful login
        \Illuminate\Support\Facades\RateLimiter::clear($key);

        // Login via the 'superadmin' guard
        // IMPORTANT: never use remember_me for super admins - always expire on tab close
        Auth::guard('superadmin')->login($admin, false);
        
        // Regenerate CSRF token (not the entire session) to prevent session fixation
        // Using regenerate() can cause session loss issues
        $request->session()->regenerateToken();
        
        // Mark session with active guard to avoid ambiguity with other guards/remember cookies
        $request->session()->put('auth_guard', 'superadmin');
        $request->session()->put('superadmin_session_active', true);
        $request->session()->put('last_activity', time());
        
        // Force immediate session write to prevent race conditions
        $request->session()->save();
        
        // Additional safety: ensure session driver has written the data
        // This is especially important for file-based sessions
        if (method_exists($request->session()->getHandler(), 'gc')) {
            // Force the session handler to sync
            usleep(50000); // 50ms delay to ensure file write completes
        }
        
        // Log successful login
        \Log::info('SuperAdmin login successful', [
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
            'session_id' => $request->session()->getId(),
            'session_marker' => $request->session()->get('superadmin_session_active'),
            'auth_check' => Auth::guard('superadmin')->check(),
        ]);

        // AJAX/json request -> return JSON with redirect
        if ($request->ajax() || $request->wantsJson()) {
            // Add a small delay before responding to ensure session is fully written
            return response()->json([
                'redirect' => route('superadmin.dashboard'),
                'session_ready' => true
            ]);
        }

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Welcome back, ' . $admin->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::guard('superadmin')->logout();

        // Remove guard marker and destroy session
        $request->session()->forget('auth_guard');
        $request->session()->forget('superadmin_session_active');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('superadmin.login')->with('success', 'You have been logged out.');
    }
}
