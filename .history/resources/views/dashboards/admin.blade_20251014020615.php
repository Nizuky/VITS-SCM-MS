<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Dashboard — VITS</title>
        <link rel="icon" href="/vitswhite.png" sizes="any">
        <style>
            :root { --header-desktop-h: 115px; --header-mobile-h: 72px; }
            body, button, input, h1, h2, h3, p, a, span { font-family: Arial, Helvetica, sans-serif; }
            #site-header { position: fixed; top: 0; left: 0; width: 100%; height: var(--header-desktop-h); z-index: 1000; display:flex; align-items:center; justify-content:center; box-shadow: 0 2px 12px rgba(0,0,0,0.12); background: rgba(255,255,255,0.9); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); transition: transform .32s cubic-bezier(.22,.9,.32,1), background .28s ease, opacity .28s ease; opacity: 0.98; -webkit-backface-visibility: hidden; backface-visibility: hidden; }
            @media (max-width: 640px) { #site-header { height: var(--header-mobile-h); } body { padding-top: var(--header-mobile-h); } }
            body { padding-top: var(--header-desktop-h); }
            .card { background: rgba(255,255,255,0.94); border-radius: 14px; padding: 22px; box-shadow: 0 8px 30px rgba(0,0,0,0.12); max-width: 980px; width: 100%; }
            .role-btn { background:#1a00ac; color:#fff; padding:8px 12px; border-radius:10px; text-decoration:none; }
        </style>
    </head>
    <body style="background-image: url('{{ asset('storage/vitsbg.png') }}'); background-repeat: no-repeat; background-position: center top; background-size: cover; background-attachment: fixed; min-height:100vh;">
        <header id="site-header">
            <img src="{{ asset('storage/vits_header.png') }}" alt="VITS Header" style="width:100%; height:100%; object-fit:cover; display:block;">
        </header>

        <main style="display:flex; align-items:center; justify-content:center; padding:48px 20px; min-height: calc(100vh - var(--header-desktop-h));">
            <div class="card" id="admin-card">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                    <div>
                        <h1 style="color:#1a00ac; font-size:22px; margin:0 0 6px 0;">Admin Dashboard</h1>
                        <p style="margin:0; color:#333;">Welcome, <strong>{{ auth('admin')->user()->name ?? 'Admin' }}</strong>. Use the controls below to manage administrative tasks.</p>
                    </div>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();" class="role-btn">Logout</a>
                    </div>
                </div>

                <hr style="margin:16px 0; border:none; height:1px; background: #ececec;">

                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:14px;">
                    <div style="background:#fff; padding:12px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                        <strong>Admin Users</strong>
                        <p style="margin:6px 0 0 0; color:#444;">Manage admin accounts and review access logs.</p>
                    </div>
                    <div style="background:#fff; padding:12px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                        <strong>Approvals</strong>
                        <p style="margin:6px 0 0 0; color:#444;">View pending approvals and system notifications.</p>
                    </div>
                    <div style="background:#fff; padding:12px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                        <strong>Settings</strong>
                        <p style="margin:6px 0 0 0; color:#444;">Server and email settings for administrative workflows.</p>
                    </div>
                </div>

                <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">@csrf</form>
            </div>
        </main>
                <script>
                (function(){
                    const logoutForm = document.getElementById('admin-logout-form');
                    if (!logoutForm) return;
                    const logoutUrl = logoutForm.action;
                    const tokenInput = logoutForm.querySelector('input[name="_token"]');
                    const csrf = tokenInput ? tokenInput.value : null;

                    function sendLogoutBeacon(){
                        if (!csrf) return false;
                        // Build urlencoded body
                        const bodyStr = `_token=${encodeURIComponent(csrf)}`;
                        try {
                            if (navigator && typeof navigator.sendBeacon === 'function'){
                                // sendBeacon requires a BodyInit; provide a Blob with proper content-type
                                const blob = new Blob([bodyStr], { type: 'application/x-www-form-urlencoded' });
                                return navigator.sendBeacon(logoutUrl, blob);
                            }
                        } catch (e) {
                            // ignore and fallback
                        }

                        // Fallback: use keepalive fetch with credentials so session cookie is sent
                        try {
                            fetch(logoutUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                                    'X-CSRF-TOKEN': csrf,
                                    'Accept': 'application/json'
                                },
                                body: bodyStr,
                                credentials: 'include',
                                keepalive: true
                            }).catch(() => {});
                            return true;
                        } catch (e) {
                            return false;
                        }
                    }

                    // When page is hidden or unloaded, attempt to logout
                    window.addEventListener('pagehide', sendLogoutBeacon, {capture:true});
                    document.addEventListener('visibilitychange', function(){ if (document.visibilityState === 'hidden') sendLogoutBeacon(); }, {capture:true});
                    window.addEventListener('beforeunload', function(){ sendLogoutBeacon(); });
                })();
                </script>
    </body>
</html>
