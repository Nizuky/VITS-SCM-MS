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

        // Capitalize first letter of each word in the name
        $validated['name'] = mb_convert_case($validated['name'], MB_CASE_TITLE, 'UTF-8');
        
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
    <style>
    /* Force white text for register page */
    h1, h2, h3, h4, h5, h6,
    p, label, span, a {
        color: #ffffff !important;
    }
    </style>
    
    <div class="text-center space-y-2 mb-6">
        <h1 class="text-2xl font-bold text-white">{{ __('Student Sign up') }}</h1>
        <p class="text-sm text-white/80">{{ __('Enter your details below to create your account') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" status="{{ session('status') }}" />

    <form method="POST" wire:submit.prevent="register" class="space-y-4">
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
            @error('name')
                <p class="mt-2 text-sm text-white" style="background: rgba(239, 68, 68, 0.2); padding: 0.5rem; border-radius: 0.5rem; border-left: 4px solid #ef4444;">
                    {{ $message }}
                </p>
            @enderror
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
                pattern="\d{2}-\d{4}"
                title="Format: 00-0000"
                class="w-full"
            />
            @error('student_id')
                <p class="mt-2 text-sm text-white" style="background: rgba(239, 68, 68, 0.2); padding: 0.5rem; border-radius: 0.5rem; border-left: 4px solid #ef4444;">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Email Address -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white">{{ __('PLV Email address') }}</label>
            <input
                wire:model="email"
                type="email"
                required
                autocomplete="email"
                placeholder="name@plv.edu.ph"
                pattern="[^@\s]+@plv\.edu\.ph"
                title="Please use your PLV institutional email (@plv.edu.ph)"
                class="w-full"
            />
            @error('email')
                <p class="mt-2 text-sm text-white" style="background: rgba(239, 68, 68, 0.2); padding: 0.5rem; border-radius: 0.5rem; border-left: 4px solid #ef4444;">
                    {{ $message }}
                </p>
            @enderror
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
            @error('password')
                <p class="mt-2 text-sm text-white" style="background: rgba(239, 68, 68, 0.2); padding: 0.5rem; border-radius: 0.5rem; border-left: 4px solid #ef4444;">
                    {{ $message }}
                </p>
            @enderror
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
            @error('password_confirmation')
                <p class="mt-2 text-sm text-white" style="background: rgba(239, 68, 68, 0.2); padding: 0.5rem; border-radius: 0.5rem; border-left: 4px solid #ef4444;">
                    {{ $message }}
                </p>
            @enderror
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
