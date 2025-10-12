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
                return response()->json(['message' => 'Invalid credentials.'], 422);
            }

            return back()
                ->withInput($request->only('name'))
                ->withErrors(['name' => 'Invalid credentials.'])
                ->with('error', 'Invalid credentials.');
        }

        // Login via the 'superadmin' guard
        Auth::guard('superadmin')->login($admin);

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

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('superadmin.login')->with('success', 'You have been logged out.');
    }
}
