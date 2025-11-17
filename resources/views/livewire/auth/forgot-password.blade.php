<?php

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
        $this->validate([
            'email' => ['required', 'string', 'email', 'regex:/^[^@\s]+@plv\.edu\.ph$/i'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('A reset link will be sent if the account exists.'));
    }
}; ?>

<div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8">
    <div class="text-center space-y-2 mb-6">
        <h1 class="text-2xl font-bold text-white">{{ __('Forgot password') }}</h1>
        <p class="text-sm text-white/80">{{ __('Enter your email to receive a password reset link') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" status="{{ session('status') }}" />

    <form method="POST" wire:submit="sendPasswordResetLink" class="space-y-4">
        <!-- Email Address -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white">{{ __('Email Address') }}</label>
            <input
                wire:model="email"
                type="email"
                required
                autofocus
                placeholder="name@plv.edu.ph"
                class="w-full"
            />
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
