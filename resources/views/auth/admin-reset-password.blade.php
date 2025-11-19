<x-layouts.auth.login-register>
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8">
        <div class="text-center space-y-2 mb-6">
            <h1 class="text-2xl font-bold text-white">{{ __('Reset Admin Password') }}</h1>
            <p class="text-sm text-white/80">{{ __('Enter your details below to reset your password') }}</p>
        </div>

        <form method="POST" action="{{ route('admin.password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}" />
            
            <!-- Admin Name -->
            <div>
                <label class="block text-sm font-medium mb-2 text-white">{{ __('Admin name') }}</label>
                <input name="name" type="text" required value="{{ old('name') }}" placeholder="{{ __('Admin name') }}" />
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium mb-2 text-white">{{ __('Email') }}</label>
                <input name="email" type="email" required value="{{ old('email', $email ?? '') }}" placeholder="admin@plv.edu.ph" />
            </div>

            <!-- New Password -->
            <div>
                <label class="block text-sm font-medium mb-2 text-white">{{ __('New Password') }}</label>
                <input name="password" type="password" required placeholder="{{ __('Password') }}" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium mb-2 text-white">{{ __('Confirm password') }}</label>
                <input name="password_confirmation" type="password" required placeholder="{{ __('Confirm password') }}" />
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="w-full scms-primary-btn">{{ __('Reset Password') }}</button>
            </div>
        </form>
        
        <x-return-to-welcome />
    </div>
</x-layouts.auth.login-register>
