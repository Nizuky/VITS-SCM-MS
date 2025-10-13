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
    public function showResetForm(\Illuminate\Http\Request $request, $token)
    {
        // If the reset link includes the email as a query parameter, pass it to the view to prefill the form.
        $email = $request->query('email');
        return view('auth.admin-reset-password', ['token' => $token, 'email' => $email]);
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
                // Update password and login. We already validated that an AdminUser exists with the
                // provided email and name before calling the broker, so no need to re-check here.
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
