<x-layouts.auth.simple>
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8">
        <div class="text-center space-y-2 mb-6">
            <h1 class="text-2xl font-bold text-white">Super Admin Password Reset</h1>
        </div>

        @if (session('status'))
            <div class="mb-4 p-3 rounded text-sm text-white" style="background:rgba(16, 185, 129, 0.2); border-left:4px solid #10b981;">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('superadmin.password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2 text-white">Email</label>
                <flux:input name="email" type="email" required placeholder="admin@plv.edu.ph" />
            </div>

            <button type="submit" class="w-full scms-primary-btn">
                Send reset link
            </button>
        </form>

        <x-return-to-welcome />
    </div>
</x-layouts.auth.simple>
<style>
/* Force white text for super-admin-forgot-password page */
h1, h2, h3, h4, h5, h6,
p, label, span, a {
    color: #ffffff !important;
}
</style>
