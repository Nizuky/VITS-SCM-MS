<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Notifications\AdminPasswordChangeNotification;
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

        $admin = Auth::guard('admin')->user();
        $admin->name = $request->name;
        $admin->save();

        return response()->json([
            'success' => true,
            'message' => 'Name updated successfully',
            'name' => $admin->name
        ]);
    }

    public function requestPasswordChange(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $admin = Auth::guard('admin')->user();

        // Verify current password
        if (!Hash::check($request->current_password, $admin->password)) {
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
        DB::table('admin_password_change_tokens')->insert([
            'email' => $admin->email,
            'token' => Hash::make($token),
            'new_password_hash' => $newPasswordHash,
            'created_at' => now(),
        ]);

        // Send verification email
        $admin->notify(new AdminPasswordChangeNotification($token, $request->new_password));

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
            return redirect()->route('admin.dashboard')
                ->with('error', 'This verification link has expired or is invalid.');
        }

        // Get all tokens for this email
        $tokens = DB::table('admin_password_change_tokens')
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
            return redirect()->route('admin.dashboard')
                ->with('error', 'This verification link is invalid or has already been used.');
        }

        // Update the password
        $admin = AdminUser::where('email', $email)->first();
        if ($admin) {
            \Log::info('Verifying password change', [
                'email' => $email,
                'old_password_hash' => $admin->password,
                'new_password_hash_from_token' => $validToken->new_password_hash,
                'token_created_at' => $validToken->created_at
            ]);
            
            // Use direct DB update to bypass any Eloquent mutators or observers
            $updated = DB::table('admin_users')
                ->where('email', $email)
                ->update([
                    'password' => $validToken->new_password_hash,
                    'updated_at' => now()
                ]);
            
            \Log::info('Password update result', [
                'rows_affected' => $updated,
                'email' => $email
            ]);
            
            // Verify the update by re-querying
            $updatedAdmin = DB::table('admin_users')->where('email', $email)->first();
            \Log::info('Verified new password', [
                'new_password_hash_in_db' => $updatedAdmin->password,
                'matches_expected' => ($updatedAdmin->password === $validToken->new_password_hash)
            ]);

            // Delete all password change tokens for this email
            DB::table('admin_password_change_tokens')
                ->where('email', $email)
                ->delete();

            return redirect()->route('admin.dashboard')
                ->with('success', 'Your password has been changed successfully!');
        }

        return redirect()->route('admin.dashboard')
            ->with('error', 'An error occurred. Please try again.');
    }
}
