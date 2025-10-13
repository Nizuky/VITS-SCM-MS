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
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Ensure the submitted name matches an AdminUser record for the given email
        $admin = AdminUser::where('email', $request->input('email'))->where('name', $request->input('name'))->first();
        if (! $admin) {
            return back()->withErrors(['email' => ['No admin found matching that name and email.']]);
        }

        $status = Password::broker('admin_users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (AdminUser $user, $password) use ($request) {
                // Extra safety: prevent changing a different admin's password by verifying name
                if ($user->name !== $request->input('name')) {
                    // Return an error by throwing an exception — the broker will treat this as a failed reset
                    throw new \Exception('Admin name mismatch during password reset');
                }

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
