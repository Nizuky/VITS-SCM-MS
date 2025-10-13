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
            $request->only('name', 'email', 'password', 'password_confirmation', 'token'),
            function (AdminUser $user, $password) use ($request) {
                // Update password and save. Do NOT auto-login here — we'll require the admin to sign in
                // explicitly after resetting their password for security and clarity.
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            // Ensure no admin session is active, then redirect to admin login with a success message
            try { Auth::guard('admin')->logout(); } catch (\Throwable $e) {}
            return redirect()->route('admin.login')->with('success', 'Your password has been reset. Please log in with your new password.');
        }

        return back()->withErrors(['email' => [__($status)]]);
    }
}
