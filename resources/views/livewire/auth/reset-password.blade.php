<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;
    // Ensure a plain, normalized email (avoid Stringable and case issues)
    $raw = (string) request()->query('email', '');
    $this->email = Str::lower(trim($raw));

    // If this reset was initiated from profile, try to prefill the intended
    // new password cached under the token. This allows the user to simply
    // confirm via the email link without retyping the password.
    try {
        $enc = Cache::get('profile:new_password:'.$this->token);
        if (is_string($enc) && strlen($enc) > 0) {
            $pwd = Crypt::decryptString($enc);
            $this->password = $pwd;
            $this->password_confirmation = $pwd;
        }
    } catch (\Throwable $e) { /* ignore cache/decrypt errors */ }

    // If auto=1 is present and we have a prefilled password, auto-submit the reset.
    try {
        $auto = (string) request()->query('auto', '');
        if ($auto === '1' && $this->password && $this->password_confirmation) {
            // Defer to next tick so Livewire has bound properties
            $this->dispatch('$refresh');
        }
    } catch (\Throwable $e) { /* ignore */ }
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email', 'regex:/^[^@\s]+@plv\.edu\.ph$/i'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::broker()->reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Let the 'hashed' cast on the User model hash the password automatically
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status !== Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        // Optional: if already authenticated, keep session; else sign in and redirect appropriately
        try {
            if (!Auth::check()) {
                $user = \App\Models\User::where('email', $this->email)->first();
                if ($user) { Auth::login($user, false); }
            }
        } catch (\Throwable $e) { /* ignore */ }

        $redirect = request()->string('redirect');
        // Clean up any cached intended password for this token
        try { Cache::forget('profile:new_password:'.$this->token); } catch (\Throwable $e) { /* ignore */ }
        if ($redirect === 'profile') {
            $this->redirect(route('dashboard', absolute: false).'#profile', navigate: true);
            return;
        }

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8">
    <div class="text-center space-y-2 mb-6">
        <h1 class="text-2xl font-bold text-white">{{ __('Reset password') }}</h1>
        <p class="text-sm text-white/80">{{ __('Please enter your new password below') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" status="{{ session('status') }}" />

    <form method="POST" wire:submit="resetPassword" class="space-y-4">
        <!-- Email Address -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white">{{ __('Email') }}</label>
            <input
                wire:model="email"
                type="email"
                required
                autocomplete="email"
                readonly
                class="w-full"
            />
        </div>

        <!-- Password -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white">{{ __('New Password') }}</label>
            <input
                wire:model="password"
                type="password"
                required
                autocomplete="new-password"
                placeholder="{{ __('New Password') }}"
                class="w-full"
            />
        </div>

        <!-- Confirm Password -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white">{{ __('Confirm password') }}</label>
            <input
                wire:model="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                placeholder="{{ __('Confirm password') }}"
                class="w-full"
            />
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" class="w-full scms-primary-btn" data-test="reset-password-button">
                {{ __('Reset password') }}
            </button>
        </div>
    </form>
</div>
<style>
/* Force white text for reset-password page */
h1, h2, h3, h4, h5, h6,
p, label, span, a {
    color: #ffffff !important;
}
</style>

<script>
// Optional: if the server indicated auto=1 and the inputs were prefilled, auto-click the submit button.
document.addEventListener('DOMContentLoaded', function(){
    try {
        const params = new URLSearchParams(window.location.search);
        if (params.get('auto') === '1') {
            // Small delay to allow Livewire rendering
            setTimeout(() => {
                const btn = document.querySelector('[data-test="reset-password-button"]');
                if (btn) btn.click();
            }, 150);
        }
    } catch (_) {}
});
</script>
