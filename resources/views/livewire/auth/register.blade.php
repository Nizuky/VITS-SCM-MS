<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $student_id = '';
    
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'regex:/^\d{2}-\d{4}$/', 'unique:' . User::class],
            // require institutional email domain only
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class, 'regex:/^[^@\s]+@plv\.edu\.ph$/i'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ], [
            'email.regex' => 'The email field format is invalid. Use plv email account.',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        // send custom verification email
        if (method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification();
        }

    // Do not auto-login after registration. User must verify email first.
    Session::flash('status', 'Registration successful — please check your email for a verification link.');

    $this->redirect(route('login', absolute: false), navigate: true);
    }
}; ?>

<div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8">
    <div class="text-center space-y-2 mb-6">
        <h1 class="text-2xl font-bold text-white">{{ __('Student Sign up') }}</h1>
        <p class="text-sm text-white/80">{{ __('Enter your details below to create your account') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" status="{{ session('status') }}" />

    <form method="POST" wire:submit="register" class="space-y-4">
        <!-- Name -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white">{{ __('Name') }}</label>
            <input
                wire:model="name"
                type="text"
                required
                autofocus
                autocomplete="name"
                placeholder="{{ __('Full name') }}"
                class="w-full"
            />
            <p class="mt-1 text-xs text-white/70">
                Format: Surname, First Name Middle Initial
            </p>
        </div>

        <!-- Student ID -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white">{{ __('Student ID') }}</label>
            <input
                wire:model="student_id"
                type="text"
                required
                placeholder="00-0000"
                class="w-full"
            />
        </div>

        <!-- Email Address -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white">{{ __('Email address') }}</label>
            <input
                wire:model="email"
                type="email"
                required
                autocomplete="email"
                placeholder="name@plv.edu.ph"
                class="w-full"
            />
        </div>

        <!-- Password -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white">{{ __('Password') }}</label>
            <input
                wire:model="password"
                type="password"
                required
                autocomplete="new-password"
                placeholder="{{ __('Password') }}"
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
            <button type="submit" class="w-full scms-primary-btn" data-test="register-user-button">
                {{ __('Sign up') }}
            </button>
        </div>
    </form>

    <div class="text-sm text-center text-white mt-6">
        <span>{{ __('Already have an account?') }}</span>
        <a href="{{ route('login') }}" wire:navigate class="font-semibold hover:underline ml-1">{{ __('Log in') }}</a>
    </div>
</div>
