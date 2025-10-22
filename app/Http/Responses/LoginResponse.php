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

        // Determine destination based on which guard authenticated the user
        // Check if user is authenticated via admin guard
        if ($request->user('admin')) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // Check if user is authenticated via superadmin guard
        if ($request->user('superadmin')) {
            return redirect()->intended(route('superadmin.dashboard'));
        }

        // Default to student dashboard for web guard
        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        return redirect()->intended(route('dashboard'));
    }
}
