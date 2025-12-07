<x-layouts.auth.login-register>
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-5 sm:p-8">
        <div class="text-center space-y-2 mb-4 sm:mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-white">Super Admin Password Reset</h1>
        </div>

        @if (session('status'))
            <div class="mb-3 sm:mb-4 p-2 sm:p-3 rounded text-xs sm:text-sm text-white" style="background:rgba(16, 185, 129, 0.2); border-left:4px solid #10b981;">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('superadmin.password.email') }}" class="space-y-3 sm:space-y-4">
            @csrf
            <div>
                <label class="block text-xs sm:text-sm font-medium mb-1.5 sm:mb-2 text-white">Email</label>
                <flux:input name="email" type="email" required placeholder="admin@plv.edu.ph" />
            </div>

            <button type="submit" class="w-full scms-primary-btn text-sm sm:text-base py-2.5 sm:py-3">
                Send reset link
            </button>
        </form>

        <x-return-to-welcome />
    </div>
</x-layouts.auth.login-register>
