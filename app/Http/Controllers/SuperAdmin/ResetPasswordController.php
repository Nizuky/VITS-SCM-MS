<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class ResetPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Validate signed URL
        if (! $request->hasValidSignature()) {
            return abort(403, 'Invalid or expired link.');
        }

        $email = $request->input('email');
        if ($email !== 'janarafael.sanandres@gmail.com') {
            return back()->withErrors(['email' => 'Invalid email.']);
        }

        $admin = SuperAdmin::where('email', $email)->firstOrFail();

        $admin->password = Hash::make($request->input('password'));
        $admin->save();

        return redirect()->route('superadmin.login')->with('status', 'Password updated. Please login.');
    }
}
