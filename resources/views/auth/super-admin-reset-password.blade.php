<x-layouts.auth.login-register>
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-5 sm:p-8">
        <div class="text-center space-y-2 mb-4 sm:mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-white">Reset Super Admin Password</h1>
        </div>

        <form method="POST" action="{{ route('superadmin.password.update') }}" class="space-y-3 sm:space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}" />
            
            <div>
                <label class="block text-xs sm:text-sm font-medium mb-1.5 sm:mb-2 text-white">Email</label>
                <input name="email" type="email" value="janarafael.sanandres@gmail.com" required readonly class="w-full text-sm sm:text-base" />
            </div>

            <div>
                <label class="block text-xs sm:text-sm font-medium mb-1.5 sm:mb-2 text-white">New Password</label>
                <input name="password" type="password" required class="w-full text-sm sm:text-base" />
            </div>

            <div>
                <label class="block text-xs sm:text-sm font-medium mb-1.5 sm:mb-2 text-white">Confirm Password</label>
                <input name="password_confirmation" type="password" required class="w-full text-sm sm:text-base" />
            </div>

            <button type="submit" class="w-full scms-primary-btn text-sm sm:text-base py-2.5 sm:py-3">
                Reset Password
            </button>
        </form>

        <x-return-to-welcome />
    </div>
</x-layouts.auth.login-register>
