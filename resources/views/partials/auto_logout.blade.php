@php
    // Only apply to the default web guard (students)
    $isWeb = auth('web')->check();
    // Determine if the session is set to be remembered by checking Laravel's remember-me cookie
    $rememberCookieName = \Illuminate\Support\Facades\Auth::getRecallerName();
    $isRemembered = request()->cookies->has($rememberCookieName);
@endphp
@if($isWeb)
    <script>
        (function(){
            // If "Remember me" is enabled, skip auto-logout on tab/app exit
            const remembered = Boolean(@json($isRemembered));
            if (remembered) return; // Only affect non-remembered sessions

            const logoutUrl = @json(route('logout'));
            const loginUrl = @json(route('login'));
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';

            let internalNav = false;
            let reloadIntent = false; // best-effort flag to detect user-initiated refresh

            // Detect internal same-origin navigation to avoid logging out on link clicks within app
            document.addEventListener('click', function(e){
                const a = e.target && e.target.closest ? e.target.closest('a') : null;
                if (a && a.href) {
                    try {
                        const url = new URL(a.href, window.location.href);
                        if (url.origin === window.location.origin) {
                            internalNav = true;
                        }
                    } catch (_) {}
                }
            }, true);
            document.addEventListener('submit', function(){ internalNav = true; }, true);

            // Detect common reload shortcuts to avoid auto-logout on page refresh
            document.addEventListener('keydown', function(e){
                try {
                    const key = e.key || '';
                    if (key === 'F5' || (key.toLowerCase() === 'r' && (e.ctrlKey || e.metaKey))) {
                        reloadIntent = true;
                    }
                } catch (_) {}
            }, true);

            function postLogoutKeepalive() {
                try {
                    const params = new URLSearchParams();
                    params.append('_token', csrf);
                    const body = params.toString();
                    const blob = new Blob([body], { type: 'application/x-www-form-urlencoded;charset=UTF-8' });
                    if (!navigator.sendBeacon || !navigator.sendBeacon(logoutUrl, blob)) {
                        fetch(logoutUrl, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
                            body,
                            keepalive: true
                        });
                    }
                } catch (_) { /* ignore */ }
            }

            function broadcastLogout() {
                try { localStorage.setItem('scms_force_logout', String(Date.now())); } catch(_) {}
            }

            function isReload() {
                // Heuristic: user pressed reload keys or the navigation entry indicates reload
                if (reloadIntent) return true;
                try {
                    const entries = performance && performance.getEntriesByType ? performance.getEntriesByType('navigation') : [];
                    if (entries && entries.length && entries[0].type === 'reload') return true;
                    // legacy API fallback
                    if (performance && performance.navigation && performance.navigation.type === 1) return true; // 1 === TYPE_RELOAD
                } catch (_) {}
                return false;
            }

            function handleExit() {
                if (internalNav) return; // don’t logout when navigating inside the app
                if (isReload()) return; // skip auto-logout on page refresh
                broadcastLogout();
                postLogoutKeepalive();
            }

            // Trigger on tab close or navigating away (includes reload)
            window.addEventListener('pagehide', handleExit);
            window.addEventListener('beforeunload', handleExit);

            // If another tab logs out, follow along
            window.addEventListener('storage', function(ev){
                if (ev.key === 'scms_force_logout' && ev.newValue) {
                    // Redirect to login
                    try { window.location.replace(loginUrl); } catch(_) { window.location.href = loginUrl; }
                }
            });

            // If page restored from BFCache after logout, force reload
            window.addEventListener('pageshow', function (event) {
                if (event.persisted) { window.location.reload(); }
            });
        })();
    </script>
@endif
