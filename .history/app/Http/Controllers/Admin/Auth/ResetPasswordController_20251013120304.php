<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminUser;

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        return view('auth.admin-reset-password', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::broker('admin_users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (AdminUser $user, $password) use ($request) {
                $user->password = Hash::make($password);
                $user->save();
                // Login the admin using admin guard
                Auth::guard('admin')->login($user);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.dashboard')
            : back()->withErrors(['email' => [__($status)]]);
    }
}
