<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request)
    {
        $user = $request->user();

        // determine destination based on role
        $role = $user->role ?? 'student';

        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        if ($role === 'super_admin') {
            return redirect()->intended(route('superadmin.dashboard'));
        }

        if ($role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('student.dashboard'));
    }
}
