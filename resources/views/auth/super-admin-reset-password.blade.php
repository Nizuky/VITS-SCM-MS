<x-layouts.auth.simple>
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8">
        <div class="text-center space-y-2 mb-6">
            <h1 class="text-2xl font-bold text-white">Reset Super Admin Password</h1>
        </div>

        <form method="POST" action="{{ route('superadmin.password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}" />
            
            <div>
                <label class="block text-sm font-medium mb-2 text-white">Email</label>
                <input name="email" type="email" value="janarafael.sanandres@gmail.com" required readonly class="w-full" />
            </div>

            <div>
                <label class="block text-sm font-medium mb-2 text-white">New Password</label>
                <input name="password" type="password" required class="w-full" />
            </div>

            <div>
                <label class="block text-sm font-medium mb-2 text-white">Confirm Password</label>
                <input name="password_confirmation" type="password" required class="w-full" />
            </div>

            <button type="submit" class="w-full scms-primary-btn">
                Reset Password
            </button>
        </form>

        <x-return-to-welcome />
    </div>
</x-layouts.auth.simple>
<style>
/* Force white text for super-admin-reset-password page */
h1, h2, h3, h4, h5, h6,
p, label, span, a {
    color: #ffffff !important;
}
</style>
