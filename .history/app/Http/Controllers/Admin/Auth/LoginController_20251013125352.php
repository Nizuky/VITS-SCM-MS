<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('admin')->attempt(['name' => $credentials['name'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            session(['auth_guard' => 'admin']);
            $redirect = route('admin.dashboard');

            if ($request->expectsJson()) {
                return response()->json(['redirect' => $redirect]);
            }

            return redirect()->intended($redirect);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'These credentials do not match our records.'], 422);
        }

        return back()->withErrors(['name' => 'These credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // clear guard marker if present
        if ($request->session()->get('auth_guard') === 'admin') {
            $request->session()->forget('auth_guard');
        }

        // Flash a message that can be shown on the login screen (mirrors super admin behavior)
        return redirect()->route('admin.login')->with('success', 'You have been logged out.');
    }
}
