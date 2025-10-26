<?php

namespace App\Http\Controllers;

use App\Notifications\ProfilePasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;

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

        // Validate incoming intended password fields (current, new, confirm).
        // This endpoint is called from the profile page when the user enters a new password.
        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', PasswordRule::defaults()],
            'password_confirmation' => ['required', 'string'],
        ]);

        // Verify the current password matches the user's existing password
        if (! Hash::check($data['current_password'], (string) $user->password)) {
            return response()->json([
                'message' => 'The current password is incorrect.',
                'errors' => ['current_password' => ['The current password is incorrect.']]
            ], 422);
        }

        // Create a password reset token and build a URL to the reset page with redirect back to profile
        $token = Password::broker()->createToken($user);
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]) . '&redirect=profile&auto=1';

        // Store the intended new password encrypted, keyed by the reset token, with a short TTL (match password broker expiry)
        try {
            $ttlMinutes = (int) (config('auth.passwords.users.expire', 60));
            $encrypted = Crypt::encryptString($data['password']);
            Cache::put('profile:new_password:'.$token, $encrypted, now()->addMinutes(max(1, $ttlMinutes)));
        } catch (\Throwable $e) {
            \Log::error('Failed to cache intended password for profile reset', [
                'user_id' => $user->getKey(),
                'error' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Failed to initiate password reset. Please try again.'], 500);
        }

        try {
            // Send synchronously to avoid requiring a queue worker
            $user->notify(new ProfilePasswordReset($resetUrl));
        } catch (\Throwable $e) {
            \Log::error('Profile reset email failed', ['user_id' => $user->getKey(), 'email' => $user->email, 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to send email'], 500);
        }

        return response()->json(['sent' => true, 'email' => $user->email]);
    }
    
    public function update(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validate the request - name is optional, password fields are optional
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'min:3', 'max:255'],
            'current_password' => ['required_with:password', 'string'],
            'password' => ['sometimes', 'string', 'confirmed', PasswordRule::defaults()],
            'password_confirmation' => ['required_with:password', 'string'],
        ]);

        $updated = false;
        $updatedFields = [];

        // Update name if provided
        if (isset($data['name']) && $data['name'] !== $user->name) {
            $user->name = $data['name'];
            $updated = true;
            $updatedFields[] = 'name';
        }

        // Update password if provided
        if (isset($data['password'])) {
            // Verify the current password matches
            if (! Hash::check($data['current_password'], (string) $user->password)) {
                return response()->json([
                    'message' => 'The current password is incorrect.',
                    'errors' => ['current_password' => ['The current password is incorrect.']]
                ], 422);
            }

            $user->password = Hash::make($data['password']);
            $updated = true;
            $updatedFields[] = 'password';
        }

        if (!$updated) {
            return response()->json(['message' => 'No changes detected.'], 422);
        }

        try {
            $user->save();
            
            \Log::info('User profile updated', [
                'user_id' => $user->id,
                'updated_fields' => $updatedFields,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully.',
                'name' => $user->name,
                'updated_fields' => $updatedFields,
            ]);
        } catch (\Throwable $e) {
            \Log::error('Failed to update user profile', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Failed to update profile. Please try again.'], 500);
        }
    }
}
