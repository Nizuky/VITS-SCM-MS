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
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = trim((string) $request->input('name'));
        $admin = SuperAdmin::where('name', $identifier)
            ->orWhere('email', $identifier)
            ->first();
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

        if (! $admin || ! $passwordOk) {
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
        $request->session()->save(); // Ensure session is saved immediately
        
        // Log successful login
        \Log::info('SuperAdmin login successful', [
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
            'session_id' => $request->session()->getId(),
            'session_marker' => $request->session()->get('superadmin_session_active'),
        ]);

        // AJAX/json request -> return JSON with redirect
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['redirect' => route('superadmin.dashboard')]);
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
