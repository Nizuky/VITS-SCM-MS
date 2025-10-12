<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;
use App\Notifications\SuperAdminResetLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ForgotPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->input('email');
        // Only allow the fixed email
        if ($email !== 'janarafael.sanandres@gmail.com') {
            return back()->withErrors(['email' => 'No such account.']);
        }

        $admin = SuperAdmin::where('email', $email)->first();
        if (! $admin) {
            return back()->withErrors(['email' => 'No such account.']);
        }

        // Create a temporary signed URL valid for 30 minutes
        $url = URL::temporarySignedRoute('superadmin.password.reset', now()->addMinutes(30), ['token' => 'reset-token']);

        // Notify (email) the admin with the reset link
        $admin->notify(new SuperAdminResetLink($url));

        return back()->with('status', 'A password reset link has been sent to the super admin email.');
    }
}
