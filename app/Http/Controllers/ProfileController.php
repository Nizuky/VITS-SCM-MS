<?php

namespace App\Http\Controllers;

use App\Notifications\ProfilePasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ProfileController extends Controller
{
    public function sendPasswordResetLink(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Enforce PLV domain
        if (!preg_match('/@plv\.edu\.ph$/i', $user->email)) {
            return response()->json(['message' => 'Email must be a plv.edu.ph address'], 422);
        }

        // Create a password reset token and build a URL to the reset page with redirect back to profile
        $token = Password::broker()->createToken($user);
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]) . '&redirect=profile';

        try {
            $user->notify(new ProfilePasswordReset($resetUrl));
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to send email'], 500);
        }

        return response()->json(['sent' => true]);
    }
}
