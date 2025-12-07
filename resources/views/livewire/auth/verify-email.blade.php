<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        try {
            Auth::user()->sendEmailVerificationNotification();
            Session::flash('status', 'verification-link-sent');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send verification email', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            Session::flash('status', 'verification-send-failed');
        }
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(): void
    {
        \Illuminate\Support\Facades\Auth::logout();
        try { session()->invalidate(); } catch (\Throwable $e) {}
        try { session()->regenerateToken(); } catch (\Throwable $e) {}
        $this->redirect('/', navigate: true);
    }

    /**
     * Handle the component's rendering hook.
     */
    public function rendering(View $view): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            // Use navigate: false to force full page reload for proper dashboard styling
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: false);

            return;
        }
    }
}; ?>

<div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8">
    <div class="text-center space-y-2 mb-6">
        <h1 class="text-2xl font-bold text-white">{{ __('Verify Email') }}</h1>
        <p class="text-sm text-white/80">
            {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 rounded text-sm text-white" style="background:rgba(16, 185, 129, 0.2); border-left:4px solid #10b981;">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="flex flex-col space-y-4">
        <button wire:click="sendVerification" class="w-full scms-primary-btn">
            {{ __('Resend verification email') }}
        </button>
        
        <button type="button" class="text-sm text-white hover:underline" wire:click="logout" data-test="logout-button">
            {{ __('Log out') }}
        </button>
    </div>
    
    <x-return-to-welcome />
</div>
<style>
/* Force white text for verify-email page */
h1, h2, h3, h4, h5, h6,
p, label, span, a, button {
    color: #ffffff !important;
}
</style>
