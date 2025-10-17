<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        // We'll show a form telling admins that the reset will be sent to the shared email on file.
        return view('auth.admin-forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'name' => ['required', 'string'],
        ]);

        // Find the specific admin user by email and name
        $admin = \App\Models\AdminUser::where('email', $request->input('email'))
            ->where('name', $request->input('name'))
            ->first();

        if (! $admin) {
            return back()->withErrors(['email' => 'No admin found matching that name and email.']);
        }

        // Create a password reset token for this admin and send the mailable/notification
        $token = Password::broker('admin_users')->createToken($admin);

        // Build a reset URL that includes token, email and name so the reset form can prefill fields
        $url = url(route('admin.password.reset', ['token' => $token, 'email' => $admin->email, 'name' => $admin->name], false));

        try {
            \Illuminate\Support\Facades\Mail::to($admin->email)->send(new \App\Mail\AdminResetLinkMail($url));
            return back()->with(['status' => 'Reset link sent to admin email.']);
        } catch (\Throwable $e) {
            // Fallback to broker send to be safe
            $status = Password::broker('admin_users')->sendResetLink($request->only('email'));
            return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
        }
    }
}
