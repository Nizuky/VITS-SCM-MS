<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Auth\Events\Verified;

class VerifyEmailController extends Controller
{
    /**
     * Verify a user's email address using the signed URL.
     * This implementation works even if the user is not currently authenticated.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $id = $request->route('id');
        $hash = $request->route('hash');

        $user = User::findOrFail($id);

        // Validate the hash matches the expected email hash
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
