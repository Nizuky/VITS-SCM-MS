<x-layouts.auth.login-register>
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8">
        <div class="text-center space-y-2 mb-6">
            <h1 class="text-2xl font-bold text-white">{{ __('Admin Login') }}</h1>
        </div>

        <form id="admin-login-form" method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
            @csrf
            @if(session('success'))
                <div class="p-3 rounded text-sm text-white" style="background:rgba(16, 185, 129, 0.2); border-left:4px solid #10b981;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="p-3 rounded text-sm text-white" style="background:rgba(239, 68, 68, 0.2); border-left:4px solid #ef4444;">Invalid information</div>
            @endif

            <div>
                <label for="name" class="block text-sm font-medium mb-2 text-white">{{ __('Admin name') }}</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter admin name"
                    value="{{ old('name', $defaultAdminName ?? '') }}"
                    required
                    class="w-full"
                    style="color: #ffffff !important;"
                />
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="block text-sm font-medium text-white">{{ __('Password') }}</label>
                    @php
                        $forgotRoute = null;
                        if (\Illuminate\Support\Facades\Route::has('admin.password.request')) {
                            $forgotRoute = route('admin.password.request');
                        } elseif (\Illuminate\Support\Facades\Route::has('password.request')) {
                            $forgotRoute = route('password.request');
                        } else {
                            $forgotRoute = '#';
                        }
                    @endphp
                </div>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter password"
                    required
                    class="w-full"
                    style="color: #ffffff !important;"
                />
                <div class="flex justify-end mt-1 mb-4">
                         <a href="{{ $forgotRoute }}" class="text-sm hover:underline">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
            </div>

            <button 
                id="admin-login-btn"
                type="submit" 
                class="w-full scms-primary-btn"
                aria-busy="false">
                <span class="btn-text">{{ __('Login') }}</span>
            </button>
        </form>

        <x-return-to-welcome />
    </div>
</x-layouts.auth.login-register>

<script>
(function(){
    const form = document.getElementById('admin-login-form');
    const btn = document.getElementById('admin-login-btn');
    const btnText = btn && btn.querySelector('.btn-text');
    const spinner = btn && btn.querySelector('.btn-spinner');
    
    let isSubmitting = false;
    let rateLimitRetryAfter = 0;

    if (!form || !btn) return;
    
    // Debounce to prevent rapid submissions
    let submitTimeout = null;

    function setLoading(state){
        if (state){
            isSubmitting = true;
            btn.setAttribute('disabled','disabled');
            btn.setAttribute('aria-busy','true');
            if (btnText) btnText.textContent = 'Logging in…';
            if (spinner) spinner.style.display = 'inline-block';
        } else {
            isSubmitting = false;
            btn.removeAttribute('disabled');
            btn.setAttribute('aria-busy','false');
            if (btnText) btnText.textContent = 'Login';
            if (spinner) spinner.style.display = 'none';
        }
    }
    
    function showError(message) {
        let existing = document.querySelector('.admin-error-banner');
        if (!existing){
            const d = document.createElement('div');
            d.className = 'admin-error-banner mb-3 p-3 rounded text-sm text-white';
            d.style.background = 'rgba(239, 68, 68, 0.2)';
            d.style.borderLeft = '4px solid #ef4444';
            d.textContent = message;
            form.insertBefore(d, form.firstChild);
        } else {
            existing.textContent = message;
        }
    }

    form.addEventListener('submit', function(e){
        e.preventDefault();
        
        // Check rate limit
        if (rateLimitRetryAfter > Date.now()) {
            const seconds = Math.ceil((rateLimitRetryAfter - Date.now()) / 1000);
            showError(`Too many attempts. Please wait ${seconds} seconds.`);
            return;
        }
        
        // Prevent double submission
        if (isSubmitting) return;
        
        // Clear previous errors
        const existingError = document.querySelector('.admin-error-banner');
        if (existingError) existingError.remove();
        
        // Debounce submissions
        if (submitTimeout) clearTimeout(submitTimeout);
        submitTimeout = setTimeout(() => {
            setLoading(true);
            
            const data = new FormData(form);
            const tokenInput = document.querySelector('input[name="_token"]');
            const token = tokenInput ? tokenInput.value : null;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: data,
                credentials: 'same-origin',
                signal: AbortSignal.timeout(10000) // 10 second timeout
            }).then(async res => {
                // Handle rate limiting
                if (res.status === 429) {
                    const json = await res.json();
                    rateLimitRetryAfter = Date.now() + 60000; // 1 minute
                    throw new Error(json.message || 'Too many login attempts. Please try again later.');
                }
                
                // Session expired or CSRF mismatch
                if (res.status === 419 || res.status === 403) {
                    window.location.reload();
                    return Promise.reject({ message: 'Session expired. Reloading...' });
                }
                
                if (res.ok) return res.json();
                
                // Parse error response
                try {
                    const j = await res.json();
                    return Promise.reject(j);
                } catch (e) {
                    return Promise.reject({ message: 'Unexpected server response' });
                }
            }).then(json => {
                if (json.redirect) {
                    // Clear rate limit on success
                    rateLimitRetryAfter = 0;
                    window.location.href = json.redirect;
                } else {
                    setLoading(false);
                }
            }).catch(err => {
                setLoading(false);
                const msg = err.message || 'Invalid credentials. Please try again.';
                showError(msg);
            });
        }, 300); // 300ms debounce
    });
})();
</script>

<style>
/* Force gray placeholders for admin login inputs */
#name::placeholder,
#password::placeholder,
#name::-webkit-input-placeholder,
#password::-webkit-input-placeholder,
#name::-moz-placeholder,
#password::-moz-placeholder,
#name:-ms-input-placeholder,
#password:-ms-input-placeholder {
    color: #9ca3af !important;
    opacity: 1 !important;
}
</style>
