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
                id="name-input"
                oninput="formatName(this); generateEmail();"
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
                maxlength="7"
                id="student-id-input"
                class="w-full"
                oninput="formatStudentId(this)"
            />
            @error('student_id')
                <p class="mt-2 text-sm text-white" style="background: rgba(239, 68, 68, 0.2); padding: 0.5rem; border-radius: 0.5rem; border-left: 4px solid #ef4444;">
                    {{ $message }}
                </p>
            @enderror
            <p id="student-id-error" class="mt-2 text-sm text-white" style="background: rgba(239, 68, 68, 0.2); padding: 0.5rem; border-radius: 0.5rem; border-left: 4px solid #ef4444; display: none;">
                Invalid student number. Year must be between 2022-{{ date('Y') }}.
            </p>
        </div>

        <!-- Email Address -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white">{{ __('PLV Email address') }}</label>
            <div class="relative">
                <input
                    type="text"
                    required
                    autocomplete="off"
                    placeholder="PLV Email address"
                    id="email-input"
                    class="w-full pr-32"
                    oninput="formatRegisterEmail(this)"
                    onpaste="handleRegisterEmailPaste(event)"
                />
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-white/50 pointer-events-none select-none">@plv.edu.ph</span>
            </div>
            @error('email')
                <p class="mt-2 text-sm text-white" style="background: rgba(239, 68, 68, 0.2); padding: 0.5rem; border-radius: 0.5rem; border-left: 4px solid #ef4444;">
                    {{ $message }}
                </p>
            @enderror
            <p class="mt-1 text-xs text-white/70">
                Auto-generated from your name (email part is editable)
            </p>
        </div>

        <!-- Password -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white">{{ __('Password') }}</label>
            <div class="relative">
                <input
                    wire:model="password"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="{{ __('Password') }}"
                    class="w-full pr-10"
                    id="password-input"
                />
                <button
                    type="button"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-white/70 hover:text-white focus:outline-none toggle-password z-10 cursor-pointer"
                    data-input="password-input"
                    style="pointer-events: auto;"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-2 text-sm text-white" style="background: rgba(239, 68, 68, 0.2); padding: 0.5rem; border-radius: 0.5rem; border-left: 4px solid #ef4444;">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white">{{ __('Confirm password') }}</label>
            <div class="relative">
                <input
                    wire:model="password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="{{ __('Confirm password') }}"
                    class="w-full pr-10"
                    id="password-confirmation-input"
                />
                <button
                    type="button"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-white/70 hover:text-white focus:outline-none toggle-password z-10 cursor-pointer"
                    data-input="password-confirmation-input"
                    style="pointer-events: auto;"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
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
    <x-return-to-welcome />
</div>

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

function formatName(input) {
    // Remove all characters except letters, spaces, dots, and commas
    let value = input.value.replace(/[^a-zA-Z\s.,]/g, '');
    
    // Capitalize first letter of each word
    value = value.replace(/\b\w/g, function(l){ return l.toUpperCase() });
    
    // Update input value
    input.value = value;
    
    // Trigger Livewire update
    input.dispatchEvent(new Event('input'));
}

function formatStudentId(input) {
    const errorMsg = document.getElementById('student-id-error');
    
    // Remove all non-numeric characters
    let value = input.value.replace(/\D/g, '');
    
    // Get current year's last 2 digits
    const currentYear = new Date().getFullYear();
    const maxYearDigits = parseInt(currentYear.toString().slice(-2));
    
    // Validate first two digits (year must be between 22 and current year)
    if (value.length >= 2) {
        const yearDigits = parseInt(value.substring(0, 2));
        if (yearDigits < 22 || yearDigits > maxYearDigits) {
            // Show error and clear field after a brief delay
            if (errorMsg) errorMsg.style.display = 'block';
            setTimeout(() => {
                input.value = '';
                input.dispatchEvent(new Event('input'));
            }, 500);
            return;
        } else {
            // Hide error if valid
            if (errorMsg) errorMsg.style.display = 'none';
        }
    }
    
    // Limit to 6 digits
    if (value.length > 6) {
        value = value.substring(0, 6);
    }
    
    // Add dash after 2nd digit
    if (value.length > 2) {
        value = value.substring(0, 2) + '-' + value.substring(2);
    }
    
    // Update input value
    input.value = value;
    
    // Trigger Livewire update
    input.dispatchEvent(new Event('input'));
}

function formatRegisterEmail(input) {
    let value = input.value;
    
    // Remove @ if present
    if (value.includes('@')) {
        value = value.split('@')[0];
    }
    
    // Remove all characters except letters (no numbers or special characters)
    value = value.replace(/[^a-zA-Z]/g, '');
    
    input.value = value;
    
    // Update Livewire with full email
    if (value) {
        const component = input.closest('[wire\\:id]');
        if (component && window.Livewire) {
            window.Livewire.find(component.getAttribute('wire:id')).set('email', value + '@plv.edu.ph');
        }
    }
}

function handleRegisterEmailPaste(event) {
    event.preventDefault();
    const pastedText = event.clipboardData.getData('text');
    const input = event.target;
    
    // If pasted text contains @, extract only the username part
    let username = pastedText;
    if (pastedText.includes('@')) {
        username = pastedText.split('@')[0];
    }
    
    // Remove all characters except letters (no numbers or special characters)
    username = username.replace(/[^a-zA-Z]/g, '');
    
    input.value = username;
    
    // Update Livewire with full email
    if (username) {
        const component = input.closest('[wire\\:id]');
        if (component && window.Livewire) {
            window.Livewire.find(component.getAttribute('wire:id')).set('email', username + '@plv.edu.ph');
        }
    }
}

function generateEmail() {
    const nameInput = document.getElementById('name-input');
    const emailInput = document.getElementById('email-input');
    
    if (!nameInput || !emailInput) return;
    
    let name = nameInput.value.trim();
    if (!name) {
        emailInput.value = '';
        return;
    }
    
    // Remove dots and extra spaces
    name = name.replace(/\./g, '').replace(/\s+/g, ' ').trim();
    
    let surname = '';
    let otherNames = [];
    
    // Check if there's a comma (Format: Surname, First Name Middle Initial)
    if (name.includes(',')) {
        const parts = name.split(',');
        surname = parts[0].trim();
        // Get everything after the comma
        const namesAfterComma = parts.slice(1).join(' ').trim();
        otherNames = namesAfterComma.split(' ').filter(part => part.length > 0);
    } else {
        // No comma, assume first part is surname
        const parts = name.split(' ').filter(part => part.length > 0);
        if (parts.length > 0) {
            surname = parts[0];
            otherNames = parts.slice(1);
        }
    }
    
    if (!surname && otherNames.length === 0) {
        emailInput.value = '';
        return;
    }
    
    // Combine: firstname + middlename + surname (all lowercase, no spaces)
    // Exclude middle initials (1-2 characters)
    let emailPrefix = '';
    
    // Add other names first (first name, middle name, etc.) but skip initials (1-2 chars)
    otherNames.forEach(name => {
        // Only include names longer than 2 characters (skip initials)
        if (name.length > 2) {
            emailPrefix += name.toLowerCase();
        }
    });
    
    // Add surname last
    emailPrefix += surname.toLowerCase();
    
    // Remove any remaining special characters
    emailPrefix = emailPrefix.replace(/[^a-z0-9]/g, '');
    
    // Set only the username part in the display
    emailInput.value = emailPrefix;
    
    // Update Livewire with the full email
    const component = emailInput.closest('[wire\\:id]');
    if (component && window.Livewire && emailPrefix) {
        window.Livewire.find(component.getAttribute('wire:id')).set('email', emailPrefix + '@plv.edu.ph');
    }
}

// Generate email on page load if name exists
document.addEventListener('DOMContentLoaded', function() {
    generateEmail();
});
</script>
