
<x-layouts.auth.login-register>
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-6 sm:p-8">
        <div class="text-center space-y-2 mb-4 sm:mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-white">Student Login</h1>
            <p class="text-xs sm:text-sm text-white/80">Enter your plv email and password below to log in</p>
        </div>

        @php $role = request('role'); @endphp
        @if ($role)
            <p class="mb-4 text-center text-sm text-white/80">Logging in as: <strong>{{ $role }}</strong></p>
        @endif

        <form id="student-login-form" method="POST" action="{{ route('login') }}" class="space-y-3 sm:space-y-4">
            @csrf
            
            @if(session('status'))
                <div class="p-2 sm:p-3 rounded text-xs sm:text-sm text-white" style="background:rgba(16, 185, 129, 0.2); border-left:4px solid #10b981;">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="p-2 sm:p-3 rounded text-xs sm:text-sm text-white" style="background:rgba(239, 68, 68, 0.2); border-left:4px solid #ef4444;">{{ $errors->first() }}</div>
            @endif
            
            <div>
                <label class="block text-xs sm:text-sm font-medium mb-1.5 sm:mb-2 text-white">Email address</label>
                <input 
                    name="email" 
                    type="email" 
                    required 
                    autocomplete="email"
                    value="{{ old('email') }}"
                    placeholder="name@plv.edu.ph"
                    class="w-full"
                    style="color: #ffffff !important;"
                />
            </div>
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-1.5 sm:mb-2 gap-1">
                    <label class="block text-xs sm:text-sm font-medium text-white">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs sm:text-sm hover:underline">Forgot your password?</a>
                    @endif
                </div>
                <input 
                    name="password" 
                    type="password" 
                    required 
                    autocomplete="current-password"
                    placeholder="Password"
                    class="w-full"
                    style="color: #ffffff !important;"
                />
            </div>
            @if ($role)
                <input type="hidden" name="role" value="{{ $role }}">
            @endif
            
            <label class="inline-flex items-center gap-2 cursor-pointer text-white text-xs sm:text-sm">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded" />
                <span class="text-white">Remember me</span>
            </label>

            <button id="student-login-btn" type="submit" class="w-full scms-primary-btn text-sm sm:text-base py-2.5 sm:py-3" aria-busy="false">
                <span class="btn-text">Log in</span>
            </button>
        </form>
        
        <script>
        (function(){
            const form = document.getElementById('student-login-form');
            const btn = document.getElementById('student-login-btn');
            if (!form || !btn) return;
            
            let isSubmitting = false;
            
            form.addEventListener('submit', function(e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return;
                }
                isSubmitting = true;
                btn.disabled = true;
                btn.setAttribute('aria-busy', 'true');
                const btnText = btn.querySelector('.btn-text');
                if (btnText) btnText.textContent = 'Logging in...';
            });
        })();
        </script>

        @if (Route::has('register'))
            <div class="text-center mt-4 sm:mt-6">
                <span class="text-xs sm:text-sm text-white/80">Don't have an account?</span>
                <a href="{{ route('register') }}" class="text-xs sm:text-sm underline hover:opacity-80 ml-1">Sign up</a>
            </div>
        @endif
    </div>
</x-layouts.auth.login-register>

<style>
/* Force gray placeholders for student login inputs */
input[name="email"]::placeholder,
input[name="password"]::placeholder,
input[name="email"]::-webkit-input-placeholder,
input[name="password"]::-webkit-input-placeholder,
input[name="email"]::-moz-placeholder,
input[name="password"]::-moz-placeholder,
input[name="email"]:-ms-input-placeholder,
input[name="password"]:-ms-input-placeholder {
    color: #9ca3af !important;
    opacity: 1 !important;
}
</style>
