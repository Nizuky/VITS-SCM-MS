<?php
    // Only apply to the default web guard (students)
    $isWeb = auth('web')->check();
?>
<?php if($isWeb): ?>
    <script>
        (function(){
            const remembered = Boolean(<?php echo json_encode(session('remembered', false), 512) ?>);
            if (remembered) return; // Only affect non-remembered sessions

            const logoutUrl = <?php echo json_encode(route('logout'), 15, 512) ?>;
            const loginUrl = <?php echo json_encode(route('login'), 15, 512) ?>;
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';

            let internalNav = false;
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

            function handleExit() {
                if (internalNav) return; // don’t logout when navigating inside the app
                broadcastLogout();
                postLogoutKeepalive();
            }

            // Trigger on tab close or navigating away
            window.addEventListener('pagehide', handleExit);
            window.addEventListener('beforeunload', handleExit);
            document.addEventListener('visibilitychange', function(){
                if (document.visibilityState === 'hidden') handleExit();
            });

            // If another tab logs out, follow along
            window.addEventListener('storage', function(ev){
                if (ev.key === 'scms_force_logout' && ev.newValue) {
                    // Best effort logout then go to login
                    postLogoutKeepalive();
                    try { window.location.replace(loginUrl); } catch(_) { window.location.href = loginUrl; }
                }
            });

            // If page restored from BFCache after logout, force reload
            window.addEventListener('pageshow', function (event) {
                if (event.persisted) { window.location.reload(); }
            });
        })();
    </script>
<?php endif; ?>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/partials/auto_logout.blade.php ENDPATH**/ ?>