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

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        $user = $this->validateCredentials();

        // IMPORTANT: Only regenerate CSRF token, NOT session ID
        // Regenerating session ID causes browser to lose session tracking
        request()->session()->regenerateToken();

        // If the user's email is not verified yet, log them in non-persistently
        // and send them to the verification prompt where they can resend the link.
        if (! $user->hasVerifiedEmail()) {
            Auth::guard('web')->login($user, false); // explicitly use web guard for students
            RateLimiter::clear($this->throttleKey());
            Session::put('remembered', false);

            // Ensure no stale remember cookie keeps the user logged in after browser close
            try {
                $guard = Auth::guard('web');
                if (method_exists($guard, 'getRecallerName')) {
                    $recaller = $guard->getRecallerName();
                    // Ensure we forget the cookie with the same path/domain as it was set
                    Cookie::queue(Cookie::forget(
                        $recaller,
                        config('session.path', '/'),
                        config('session.domain')
                    ));
                }
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
                $guard = Auth::guard('web');
                if (method_exists($guard, 'getRecallerName')) {
                    $recaller = $guard->getRecallerName();
                    Cookie::queue(Cookie::forget(
                        $recaller,
                        config('session.path', '/'),
                        config('session.domain')
                    ));
                }
            } catch (\Throwable $e) { /* ignore */ }
        }

        Auth::guard('web')->login($user, $this->remember); // explicitly use web guard for students
        
        // If remember is NOT checked, ensure any existing remember cookie is cleared
        if (! $this->remember) {
            try {
                $guard = Auth::guard('web');
                if (method_exists($guard, 'getRecallerName')) {
                    $recaller = $guard->getRecallerName();
                    Cookie::queue(Cookie::forget(
                        $recaller,
                        config('session.path', '/'),
                        config('session.domain')
                    ));
                }
            } catch (\Throwable $e) { /* ignore */ }
        }
        
        // Mark session with active guard and remember choice
        session(['auth_guard' => 'web']);
        Session::put('remembered', (bool) $this->remember);

        RateLimiter::clear($this->throttleKey());

        // Use navigate: false to force full page reload instead of SPA navigation
        // This ensures the dashboard CSS loads fresh without auth layout artifacts
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: false);
    }

    /**
     * Validate the user's credentials.
     */
    protected function validateCredentials(): User
    {
        // Use the web guard explicitly for student authentication
        $guard = Auth::guard('web');
        
        // First, check if user exists in database
        $user = User::where('email', $this->email)->first();
        
        if (!$user) {
            RateLimiter::hit($this->throttleKey());
            
            throw ValidationException::withMessages([
                'email' => 'No account found with this email address.',
            ]);
        }
        
        // Try to retrieve the user by email and validate password
        $credentials = ['email' => $this->email];

        // Check if guard has getProvider method (SessionGuard does, Guard interface doesn't)
        if (method_exists($guard, 'getProvider')) {
            $provider = $guard->getProvider();
            $user = $provider->retrieveByCredentials(array_merge($credentials, ['password' => $this->password]));

            if (! $user || ! $provider->validateCredentials($user, ['password' => $this->password])) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'email' => 'Invalid information',
                ]);
            }

            return $user;
        }

        // Fallback: attempt authentication directly
        if (! Auth::guard('web')->attempt(['email' => $this->email, 'password' => $this->password], false)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Invalid information',
            ]);
        }

        return Auth::guard('web')->user();
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
<div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8 scms-login">
    <div class="text-center space-y-2 mb-6">
        <h1 class="text-2xl font-bold text-white">{{ __('Student Login') }}</h1>
        <p class="text-sm text-white/80">{{ __('Enter your plv email and password below to log in') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" status="{{ session('status') }}" />

    <form method="POST" wire:submit="login" class="space-y-4">
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="p-3 rounded text-sm text-white" style="background:rgba(239, 68, 68, 0.2); border-left:4px solid #ef4444;">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif
        
        <!-- Email Address -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white">{{ __('PLV Email address') }}</label>
            <div class="relative">
                <input
                    wire:model="email"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="Enter your PLV email (e.g., student@plv.edu.ph)"
                    class="w-full"
                    id="login-email-input"
                />
            </div>
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium text-white">{{ __('Password') }}</label>
            </div>
            <div class="relative">
                <input
                    wire:model="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="{{ __('Password') }}"
                    class="w-full pr-10"
                    id="login-password-input"
                />
                <button
                    type="button"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-white/70 hover:text-white focus:outline-none toggle-password z-10 cursor-pointer"
                    data-input="login-password-input"
                    style="pointer-events: auto;"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
            <div class="flex justify-end mt-1 mb-4">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" wire:navigate class="text-sm hover:underline">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            </div>
        </div>

        <!-- Remember Me -->
        <div class="scms-remember select-none">
            <label for="remember" class="inline-flex items-center gap-2 cursor-pointer text-white">
                <input id="remember" type="checkbox" wire:model="remember" class="h-4 w-4 rounded" />
                <span class="text-sm">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" class="w-full scms-primary-btn" data-test="login-button">
                {{ __('Log in') }}
            </button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="text-sm text-center text-white mt-6">
            <span>{{ __('Don\'t have an account?') }}</span>
            <a href="{{ route('register') }}" wire:navigate class="font-semibold hover:underline ml-1 hover:text-[#8c4cf2] ">{{ __('Sign up') }}</a>
        </div>
    @endif
    
    <script>
    // Password toggle
    document.addEventListener('click', function(e) {
        if (e.target.closest('.toggle-password')) {
            const button = e.target.closest('.toggle-password');
            const inputId = button.getAttribute('data-input');
            const input = document.getElementById(inputId);
            const svg = button.querySelector('svg');
            
            if (input && svg) {
                if (input.type === 'password') {
                    input.type = 'text';
                    svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />';
                } else {
                    input.type = 'password';
                    svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
                }
            }
        }
    });
    </script>
    
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
    
    /* Force white text for login page */
    .scms-login h1, 
    .scms-login h2, 
    .scms-login h3, 
    .scms-login h4, 
    .scms-login h5, 
    .scms-login h6,
    .scms-login p,
    .scms-login label,
    .scms-login span,
    .scms-login a {
        color: #ffffff !important;
    }
    </style>
    <x-return-to-welcome />
</div>
