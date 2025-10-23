<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;
use App\Notifications\SuperAdminPasswordChangeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function updateName(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $superAdmin = Auth::guard('superadmin')->user();
        $superAdmin->name = $request->name;
        $superAdmin->save();

        return response()->json([
            'success' => true,
            'message' => 'Name updated successfully',
            'name' => $superAdmin->name
        ]);
    }

    public function requestPasswordChange(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $superAdmin = Auth::guard('superadmin')->user();

        // Verify current password
        if (!Hash::check($request->current_password, $superAdmin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        // Generate a unique token
        $token = Str::random(60);

        // Hash the new password
        $newPasswordHash = Hash::make($request->new_password);

        // Store the token and new password hash
        DB::table('super_admin_password_change_tokens')->insert([
            'email' => $superAdmin->email,
            'token' => Hash::make($token),
            'new_password_hash' => $newPasswordHash,
            'created_at' => now(),
        ]);

        // Send verification email
        $superAdmin->notify(new SuperAdminPasswordChangeNotification($token, $request->new_password));

        return response()->json([
            'success' => true,
            'message' => 'A verification email has been sent to your email address. Please check your inbox and click the verification link to complete the password change.'
        ]);
    }

    public function verifyPasswordChange(Request $request)
    {
        $email = $request->query('email');
        $token = $request->query('token');

        if (!$request->hasValidSignature()) {
            return redirect()->route('superadmin.dashboard')
                ->with('error', 'This verification link has expired or is invalid.');
        }

        // Get all tokens for this email
        $tokens = DB::table('super_admin_password_change_tokens')
            ->where('email', $email)
            ->where('created_at', '>=', now()->subHour())
            ->get();

        $validToken = null;
        foreach ($tokens as $tokenRecord) {
            if (Hash::check($token, $tokenRecord->token)) {
                $validToken = $tokenRecord;
                break;
            }
        }

        if (!$validToken) {
            return redirect()->route('superadmin.dashboard')
                ->with('error', 'This verification link is invalid or has already been used.');
        }

        // Update the password
        $superAdmin = SuperAdmin::where('email', $email)->first();
        if ($superAdmin) {
            $superAdmin->password = $validToken->new_password_hash;
            $superAdmin->save();

            // Delete all password change tokens for this email
            DB::table('super_admin_password_change_tokens')
                ->where('email', $email)
                ->delete();

            return redirect()->route('superadmin.dashboard')
                ->with('success', 'Your password has been changed successfully!');
        }

        return redirect()->route('superadmin.dashboard')
            ->with('error', 'An error occurred. Please try again.');
    }
}
