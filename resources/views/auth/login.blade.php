
<x-layouts.auth.simple>
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8">
        <div class="text-center space-y-2 mb-6">
            <h1 class="text-2xl font-bold text-white">Student Login</h1>
            <p class="text-sm text-white/80">Enter your plv email and password below to log in</p>
        </div>

        @php $role = request('role'); @endphp
        @if ($role)
            <p class="mb-4 text-center text-sm text-white/80">Logging in as: <strong>{{ $role }}</strong></p>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2 text-white">Email address</label>
                <input 
                    name="email" 
                    type="email" 
                    required 
                    placeholder="name@plv.edu.ph"
                    class="w-full"
                />
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-white">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-white hover:underline">Forgot your password?</a>
                    @endif
                </div>
                <input 
                    name="password" 
                    type="password" 
                    required 
                    placeholder="Password"
                    class="w-full"
                />
            </div>
            @if ($role)
                <input type="hidden" name="role" value="{{ $role }}">
            @endif
            
            <label class="inline-flex items-center gap-2 cursor-pointer text-white">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded" />
                <span class="text-white">Remember me</span>
            </label>

            <button type="submit" class="w-full scms-primary-btn">Log in</button>
        </form>

        @if (Route::has('register'))
            <div class="text-center mt-6">
                <span class="text-sm text-white/80">Don't have an account?</span>
                <a href="{{ route('register') }}" class="text-sm text-white underline hover:opacity-80 ml-1">Sign up</a>
            </div>
        @endif
    </div>
</x-layouts.auth.simple>
<style>
/* Force white text for auth/login page */
h1, h2, h3, h4, h5, h6,
p, label, span, a {
    color: #ffffff !important;
}
</style>
