<?php

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    // Optional student ID to further identify account (format: 23-3402)
    public string $student_id = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        $user = $this->validateCredentials();

        // Defense-in-depth: regenerate session ID before switching auth state
        try { Session::regenerate(); } catch (\Throwable $e) { /* ignore */ }

        // If the user's email is not verified yet, log them in non-persistently
        // and send them to the verification prompt where they can resend the link.
        if (! $user->hasVerifiedEmail()) {
            Auth::guard('web')->login($user, false); // explicitly use web guard for students
            RateLimiter::clear($this->throttleKey());
            Session::put('remembered', false);
            Session::regenerate();

            // Ensure no stale remember cookie keeps the user logged in after browser close
            try {
                $recaller = Auth::guard('web')->getRecallerName();
                // Ensure we forget the cookie with the same path/domain as it was set
                Cookie::queue(Cookie::forget(
                    $recaller,
                    config('session.path', '/'),
                    config('session.domain')
                ));
            } catch (\Throwable $e) { /* ignore */ }

            $this->redirect(route('verification.prompt'), navigate: true);
            return;
        }

        if (Features::canManageTwoFactorAuthentication() && $user->hasEnabledTwoFactorAuthentication()) {
            Session::put([
                'login.id' => $user->getKey(),
                'login.remember' => $this->remember,
            ]);

            $this->redirect(route('two-factor.login'), navigate: true);

            return;
        }

        // If remember is NOT checked, proactively clear any stale recaller cookie before new login
        if (! $this->remember) {
            try {
                $recaller = Auth::guard('web')->getRecallerName();
                Cookie::queue(Cookie::forget(
                    $recaller,
                    config('session.path', '/'),
                    config('session.domain')
                ));
            } catch (\Throwable $e) { /* ignore */ }
        }

        Auth::guard('web')->login($user, $this->remember); // explicitly use web guard for students
        
        // If remember is NOT checked, ensure any existing remember cookie is cleared
        if (! $this->remember) {
            try {
                $recaller = Auth::guard('web')->getRecallerName();
                Cookie::queue(Cookie::forget(
                    $recaller,
                    config('session.path', '/'),
                    config('session.domain')
                ));
            } catch (\Throwable $e) { /* ignore */ }
        }
        
        // Mark session with active guard and remember choice
        session(['auth_guard' => 'web']);
        Session::put('remembered', (bool) $this->remember);

        RateLimiter::clear($this->throttleKey());
        // Regenerate again after login per best practices
        try { Session::regenerate(); } catch (\Throwable $e) { /* ignore */ }

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Validate the user's credentials.
     */
    protected function validateCredentials(): User
    {
        // Use the web guard explicitly for student authentication
        $guard = Auth::guard('web');
        
        // Try to retrieve the user by email first. If student_id provided, include it in the query.
        $credentials = ['email' => $this->email];
        if (! empty($this->student_id)) {
            $credentials['student_id'] = $this->student_id;
        }

        $user = $guard->getProvider()->retrieveByCredentials(array_merge($credentials, ['password' => $this->password]));

        if (! $user || ! $guard->getProvider()->validateCredentials($user, ['password' => $this->password])) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return $user;
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>
<div class="flex flex-col gap-6 scms-login">
    <x-auth-header :title="__('Student Login')" :description="__('Enter your plv  email, student number, and password below to log in')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autofocus
            autocomplete="email"
            placeholder="name@plv.edu.ph"
        />

        <!-- Student ID (optional) -->
        <flux:input
            wire:model="student_id"
            :label="__('Student ID (optional)')"
            type="text"
            placeholder="00-0000"
        />

        <!-- Password -->
        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            @if (Route::has('password.request'))
                <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                    {{ __('Forgot your password?') }}
                </flux:link>
            @endif
        </div>

        <!-- Remember Me (native checkbox for consistent styling) -->
        <div class="scms-remember select-none">
            <label for="remember" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember" type="checkbox" wire:model="remember" class="h-4 w-4 rounded-4" />
                <span>{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full scms-primary-btn" data-test="login-button">
                {{ __('Log in') }}
            </flux:button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Don\'t have an account?') }}</span>
            <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
        </div>
    @endif
    <style>
    /* Scoped overrides for login page */
    .scms-login .scms-primary-btn {
        background-color: #6D28D9 !important; /* primary purple */
        color: #ffffff !important;
        border-color: transparent !important;
    }
    .scms-login .scms-primary-btn:hover {
        background-color: #5B21B6 !important; /* darker hover */
    }
    .scms-login .scms-primary-btn:focus {
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(109, 40, 217, 0.35) !important;
    }

    /* Force the checkbox check/track to use our brand color */
    .scms-login input[type="checkbox"] {
        accent-color: #6D28D9; /* modern browsers */
    }

    /* Fallback for environments where accent-color isn't applied */
    @supports not (accent-color: #000) {
        .scms-login input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 16px; height: 16px;
            border: 2px solid #9ca3af; /* gray-400 */
            background-color: #ffffff;
            border-radius: 0.25rem !important;
            display: inline-grid; place-content: center;
            cursor: pointer;
            transition: background-color .2s ease, border-color .2s ease, box-shadow .2s ease;
        }
        .scms-login input[type="checkbox"]:focus { outline: none; box-shadow: 0 0 0 2px rgba(109,40,217,0.35); }
        .scms-login input[type="checkbox"]:checked { background-color: #6D28D9 !important; border-color: #6D28D9 !important; }
        .scms-login input[type="checkbox"]:checked::after {
            content: "";
            width: 0.25rem; height: 0.5rem;
            border: solid #ffffff; border-width: 0 2px 2px 0; transform: rotate(45deg);
        }
    }

    /* Dark scheme tweaks for the fallback */
    @media (prefers-color-scheme: dark) {
        @supports not (accent-color: #000) {
            .scms-login input[type="checkbox"] { background-color: #111827; border-color: #374151; }
            .scms-login input[type="checkbox"]:focus { box-shadow: 0 0 0 2px rgba(109,40,217,0.45); }
        }
    }

    /* Optional: ensure label next to checkbox inherits readable color */
    .scms-login label { color: inherit; }
    </style>
</div>
