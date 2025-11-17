<x-layouts.auth.simple>
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
                <div class="p-3 rounded text-sm text-white" style="background:rgba(239, 68, 68, 0.2); border-left:4px solid #ef4444;">{{ session('error') }}</div>
            @endif

            <div>
                <label for="name" class="block text-sm font-medium mb-2 text-white">{{ __('Admin name') }}</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="admin name"
                    value="{{ old('name', $defaultAdminName ?? '') }}"
                    required
                    class="w-full"
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
                    <a href="{{ $forgotRoute }}" class="text-sm text-white hover:underline">
                        {{ __('Forgot your password?') }}
                    </a>
                </div>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter password"
                    required
                    class="w-full"
                />
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
</x-layouts.auth.simple>

<script>
(function(){
    const form = document.getElementById('admin-login-form');
    const btn = document.getElementById('admin-login-btn');
    const btnText = btn && btn.querySelector('.btn-text');
    const spinner = btn && btn.querySelector('.btn-spinner');

    if (!form || !btn) return;

    function setLoading(state){
        if (state){
            btn.setAttribute('disabled','disabled');
            btn.setAttribute('aria-busy','true');
            if (btnText) btnText.textContent = 'Logging in…';
            if (spinner) spinner.style.display = 'inline-block';
        } else {
            btn.removeAttribute('disabled');
            btn.setAttribute('aria-busy','false');
            if (btnText) btnText.textContent = 'Login';
            if (spinner) spinner.style.display = 'none';
        }
    }

        form.addEventListener('submit', function(e){
        // If JS enabled, intercept and submit via fetch
            e.preventDefault();
            // ensure form submits replace current tab when JS is enabled
            try { form.target = '_self'; } catch (err) {}
            setLoading(true);

        const data = new FormData(form);
        const tokenInput = document.querySelector('input[name="_token"]');
        const token = tokenInput ? tokenInput.value : null;

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: data,
            // include cookies/session even in edge cases; preserves compatibility
            credentials: 'include'
        }).then(async res => {
            // if session expired or csrf mismatch, Laravel may return 419 or 403
            if (res.status === 419 || res.status === 403) {
                // reload to refresh CSRF/session and present a fresh form
                window.location.reload();
                return Promise.reject({ message: 'Session expired. Reloading...' });
            }
            if (res.ok) return res.json();
            // try parse json error body, but handle invalid json
            try { const j = await res.json(); return Promise.reject(j); } catch (e){ return Promise.reject({ message: 'Unexpected server response' }); }
        }).then(json => {
            if (json.redirect) window.location.href = json.redirect;
            else setLoading(false);
        }).catch(err => {
            setLoading(false);
            // show error banner
            let msg = 'Invalid credentials.';
            if (err && err.message) msg = err.message;
            // if server returned validation errors, try to extract a helpful message
            if (err && err.errors) {
                const firstKey = Object.keys(err.errors)[0];
                if (firstKey) msg = err.errors[firstKey][0];
            }
            let existing = document.querySelector('.admin-error-banner');
            if (!existing){
                const d = document.createElement('div');
                d.className = 'admin-error-banner mb-3 p-3 rounded text-sm';
                d.style.background = '#fff3f2'; d.style.borderLeft = '4px solid #ef4444'; d.style.color = '#7f1d1d';
                d.textContent = msg;
                form.insertBefore(d, form.firstChild);
            } else {
                existing.textContent = msg;
            }
        });
    });
})();
</script>
