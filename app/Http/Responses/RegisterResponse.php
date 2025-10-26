<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     * 
     * This custom response prevents auto-login after registration
     * to avoid session conflicts with other logged-in users (admin/superadmin).
     */
    public function toResponse($request)
    {
        // IMPORTANT: Logout the user immediately after registration
        // This prevents session conflicts when admins/superadmins are logged in
        Auth::guard('web')->logout();
        
        // Redirect to login page with success message
        return redirect()->route('login')
            ->with('status', 'Registration successful! Please verify your email to login.');
    }
}
