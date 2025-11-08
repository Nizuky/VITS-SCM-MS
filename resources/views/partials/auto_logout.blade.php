@php
    // Only apply to the default web guard (students)
    $isWeb = auth('web')->check();
    // Determine Remember Me via BOTH sources:
    // 1) Session flag set during login (immediate, reliable for this session)
    // 2) Recaller cookie set by Laravel (persists across browser restarts)
    $guard = \Illuminate\Support\Facades\Auth::guard('web');
    $rememberCookieName = $guard instanceof \Illuminate\Auth\SessionGuard ? $guard->getRecallerName() : 'remember_web_' . sha1(static::class);
    $isRememberedCookie = request()->cookies->has($rememberCookieName);
    $isRememberedSession = (bool) session('remembered', null);
    $isRemembered = $isRememberedCookie || $isRememberedSession;
@endphp
@if($isWeb)
    <script>
        (function(){
            // Idempotent guard to avoid double-binding if partial is included twice
            if (window.__scms_auto_logout_inited) return;
            window.__scms_auto_logout_inited = true;

            // If "Remember me" is enabled, skip auto-logout on tab/app exit
            const remembered = Boolean(@json($isRemembered));
            if (remembered) return; // Only affect non-remembered sessions

            const logoutUrl = @json(route('logout'));
            const logoutBeaconUrl = @json(route('logout.beacon'));
            const loginUrl = @json(route('login'));
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';

            let internalNav = false;
            let __scms_internalNavTimer = null;
            let reloadIntent = false; // best-effort flag to detect user-initiated refresh

            // Detect internal same-origin navigation to avoid logging out on link clicks within app
            document.addEventListener('click', function(e){
                const a = e.target && e.target.closest ? e.target.closest('a') : null;
                if (a && a.href) {
                    try {
                        const url = new URL(a.href, window.location.href);
                        if (url.origin === window.location.origin) {
                            internalNav = true;
                            if (__scms_internalNavTimer) clearTimeout(__scms_internalNavTimer);
                            __scms_internalNavTimer = setTimeout(() => { internalNav = false; }, 2000);
                        }
                    } catch (_) {}
                }
            }, true);
            document.addEventListener('submit', function(){
                internalNav = true;
                if (__scms_internalNavTimer) clearTimeout(__scms_internalNavTimer);
                __scms_internalNavTimer = setTimeout(() => { internalNav = false; }, 2000);
            }, true);

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
                    // Try POST first because sendBeacon uses POST and is most reliable on unload
                    if (navigator.sendBeacon) {
                        const data = new URLSearchParams();
                        // No CSRF required on beacon.post route
                        const blob = new Blob([data.toString()], { type: 'application/x-www-form-urlencoded;charset=UTF-8' });
                        navigator.sendBeacon(@json(route('logout.beacon.post')), blob);
                    }
                    // Also issue a GET keepalive as a secondary path
                    const bust = Date.now().toString();
                    fetch(`${logoutBeaconUrl}?_=${bust}`, { method: 'GET', keepalive: true, cache: 'no-store', credentials: 'same-origin' }).catch(()=>{});
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

            function setPendingCookie() {
                try {
                    // Session cookie (no expires) so it disappears with browser
                    document.cookie = 'scms_force_logout_pending=1; path=/';
                } catch (_) {}
            }

            function markPendingAndLogout() {
                try { localStorage.setItem('scms_force_logout_pending', '1'); } catch(_) {}
                setPendingCookie();
                broadcastLogout();
                postLogoutKeepalive();
            }

            function handleExit() {
                if (internalNav) return; // don’t logout when navigating inside the app
                if (isReload()) return; // skip auto-logout on page refresh
                markPendingAndLogout();
            }

            // Trigger on tab close or navigating away (reloads are skipped by isReload())
            window.addEventListener('pagehide', handleExit);
            window.addEventListener('beforeunload', handleExit);
            // Do NOT logout on tab switch; only handle unload/navigation via pagehide/beforeunload above.

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

            // If the user returns to the tab and the session already ended server-side, redirect to login.
            // We test session liveness by calling a cheap endpoint that requires auth (records index).
            async function checkSessionAlive() {
                try {
                    const r = await fetch(@json(url('/api/social-contract/records')), {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'Cache-Control': 'no-cache' }
                    });
                    if (r.status === 401) {
                        try { window.location.replace(loginUrl); } catch(_) { window.location.href = loginUrl; }
                    }
                } catch (_) { /* ignore network errors */ }
            }
            document.addEventListener('visibilitychange', function(){
                if (document.visibilityState === 'visible') {
                    checkSessionAlive();
                }
            });
            window.addEventListener('focus', checkSessionAlive);

            // As a last resort, if we detect back/forward navigation via bfcache restore then immediate 401, redirect
            (function(){
                let restored = false;
                window.addEventListener('pageshow', function (e) { if (e.persisted) restored = true; });
                document.addEventListener('visibilitychange', function(){
                    if (document.visibilityState === 'visible' && restored) {
                        checkSessionAlive();
                        restored = false;
                    }
                });
            })();

            // If a previous unload couldn't reach the server, ensure logout on next load
            (function immediatePendingLogout(){
                try {
                    const pending = localStorage.getItem('scms_force_logout_pending');
                    const cookiePending = (document.cookie || '').includes('scms_force_logout_pending=1');
                    if (pending === '1' || cookiePending) {
                        // Attempt logout now, then clear the flag and redirect
                        postLogoutKeepalive();
                        try { localStorage.removeItem('scms_force_logout_pending'); } catch(_) {}
                        try { document.cookie = 'scms_force_logout_pending=; Max-Age=0; path=/'; } catch(_) {}
                        try { window.location.replace(loginUrl); } catch(_) { window.location.href = loginUrl; }
                    }
                } catch (_) { /* ignore */ }
            })();
        })();
    </script>
@endif
