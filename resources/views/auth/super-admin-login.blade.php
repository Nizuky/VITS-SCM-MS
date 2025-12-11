<x-layouts.auth.login-register>
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-6 sm:p-8">
        <div class="text-center space-y-2 mb-4 sm:mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-white">Super Admin Login</h1>
        </div>

        <form id="superadmin-login-form" method="POST" action="{{ route('superadmin.login.submit') }}" class="space-y-3 sm:space-y-4">
            @csrf
            @if(session('success'))
                <div class="p-2 sm:p-3 rounded text-xs sm:text-sm text-white" style="background:rgba(16, 185, 129, 0.2); border-left:4px solid #10b981;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="p-2 sm:p-3 rounded text-xs sm:text-sm text-white" style="background:rgba(239, 68, 68, 0.2); border-left:4px solid #ef4444;">Invalid information</div>
            @endif

            <div>
                <label for="name" class="block text-xs sm:text-sm font-medium mb-1.5 sm:mb-2 text-white">Admin name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="admin name"
                    value="{{ old('name', $defaultAdminName ?? '') }}"
                    required
                    class="w-full text-sm sm:text-base"
                />
            </div>

            <div>
                <label for="password" class="block text-xs sm:text-sm font-medium mb-1.5 sm:mb-2 text-white">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter password"
                    required
                    class="w-full text-sm sm:text-base"
                />
            </div>

            <button 
                id="superadmin-login-btn"
                type="submit" 
                class="w-full scms-primary-btn text-sm sm:text-base py-2.5 sm:py-3"
                aria-busy="false">
                <span class="btn-text">Login</span>
            </button>
        </form>

        <x-return-to-welcome />
    </div>
</x-layouts.auth.login-register>

<script>
// Auto-logout super admin session when page is closed or navigated away
// CRITICAL: Super admins should NEVER have persistent sessions
let isInternalNavigation = false;

// Track internal navigation (form submissions within the site)
const superAdminForm = document.getElementById('superadmin-login-form');
if (superAdminForm) {
    superAdminForm.addEventListener('submit', function() {
        isInternalNavigation = true;
    });
}

function superAdminLogout() {
    if (!isInternalNavigation) {
        // Send logout beacon - works even as page is unloading
        if (navigator.sendBeacon) {
            navigator.sendBeacon('{{ route("superadmin.logout") }}', new FormData());
        } else {
            // Fallback for older browsers
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route("superadmin.logout") }}', false); // synchronous
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.send();
        }
    }
}

// Fire on all possible exit events
window.addEventListener('beforeunload', superAdminLogout);
window.addEventListener('pagehide', superAdminLogout);
window.addEventListener('unload', superAdminLogout);

// Handle browser back button
window.addEventListener('popstate', function() {
    superAdminLogout();
});

    (function(){
        const form = document.getElementById('superadmin-login-form');
        const btn = document.getElementById('superadmin-login-btn');
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
            const token = tokenInput ? tokenInput.value : '';

            fetch(form.action, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: data,
                credentials: 'same-origin'
            }).then(res => {
                if (res.ok) return res.json();
                // Try to get error message from response
                return res.json().then(j => Promise.reject(j)).catch(() => {
                    // If JSON parsing fails, reject with status text
                    return Promise.reject({ message: res.statusText || 'Login failed' });
                });
            }).then(json => {
                if (json.redirect) {
                    // Small delay to ensure session cookie is set by browser
                    setTimeout(function() {
                        window.location.href = json.redirect;
                    }, 100);
                } else {
                    setLoading(false);
                }
            }).catch(err => {
                setLoading(false);
                // show error banner with actual error message from server
                let msg = 'Login failed';
                
                // Try to get the actual error message from the server response
                if (err && err.message) {
                    msg = err.message;
                } else if (typeof err === 'string') {
                    msg = err;
                }
                
                console.error('SuperAdmin login error:', err); // Log for debugging
                
                let existing = document.querySelector('.superadmin-error-banner');
                if (!existing){
                    const d = document.createElement('div');
                    d.className = 'superadmin-error-banner mb-3 p-3 rounded text-sm text-white';
                    d.style.background = 'rgba(239, 68, 68, 0.2)';
                    d.style.borderLeft = '4px solid #ef4444';
                    d.textContent = msg;
                    form.insertBefore(d, form.firstChild);
                } else {
                    existing.textContent = msg;
                }
            });
        });
    })();
</script>