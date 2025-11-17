<?php

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $validated = $this->validate([
            'email' => [
                'required', 
                'string', 
                'email', 
                'regex:/^[^@\s]+@plv\.edu\.ph$/i',
                'exists:users,email'
            ],
        ], [
            'email.regex' => 'Only PLV institutional email addresses (@plv.edu.ph) are allowed.',
            'email.exists' => 'No account found with this email address. Please check and try again.',
        ]);

        // Check if user account exists and is verified
        $user = User::where('email', $validated['email'])->first();
        
        if (!$user) {
            $this->addError('email', 'No account found with this email address. Please check and try again.');
            return;
        }

        if (!$user->hasVerifiedEmail()) {
            $this->addError('email', 'Your email address is not verified. Please verify your email first.');
            return;
        }

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('Password reset link has been sent to your email.'));
    }
}; ?>

<div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8">
    <style>
    /* Force white text for forgot-password page */
    h1, h2, h3, h4, h5, h6,
    p, label, span, a {
        color: #ffffff !important;
    }
    
    /* Make Flux input typeable */
    flux\:input,
    [data-flux-input],
    [data-flux-input] *,
    flux\:input * {
        pointer-events: auto !important;
    }
    
    [data-flux-input] input,
    flux\:input input {
        pointer-events: auto !important;
        user-select: text !important;
    }
    </style>
    
    <div class="text-center space-y-2 mb-6">
        <h1 class="text-2xl font-bold text-white">{{ __('Forgot password') }}</h1>
        <p class="text-sm text-white/80">{{ __('Enter your email to receive a password reset link') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" status="{{ session('status') }}" />

    <form method="POST" wire:submit="sendPasswordResetLink" class="space-y-4">
        <!-- Email Address -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white">{{ __('PLV Email Address') }}</label>
            <input
                wire:model="email"
                type="email"
                required
                autofocus
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

        <button type="submit" class="w-full scms-primary-btn" data-test="email-password-reset-link-button">
            {{ __('Email password reset link') }}
        </button>
    </form>

    <div class="text-sm text-center text-white mt-6">
        <span>{{ __('Or, return to') }}</span>
        <a href="{{ route('login') }}" wire:navigate class="font-semibold hover:underline ml-1">{{ __('log in') }}</a>
    </div>
</div>
