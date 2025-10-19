@php
    $isAdmin = auth('admin')->check();
@endphp
@if($isAdmin)
<script>
(function(){
  if (window.__scms_admin_auto_logout_inited) return;
  window.__scms_admin_auto_logout_inited = true;

  const logoutBeaconGet = @json(route('admin.logout.beacon'));
  const logoutBeaconPost = @json(route('admin.logout.beacon.post'));
  const loginUrl = @json(route('admin.login'));

  let internalNav = false;
  let internalNavTimer = null;
  let reloadIntent = false;

  // Detect internal navigation
  document.addEventListener('click', function(e){
    const a = e.target && e.target.closest ? e.target.closest('a') : null;
    if (a && a.href) {
      try {
        const url = new URL(a.href, window.location.href);
        if (url.origin === window.location.origin) {
          internalNav = true;
          if (internalNavTimer) clearTimeout(internalNavTimer);
          internalNavTimer = setTimeout(()=>{ internalNav = false; }, 2000);
        }
      } catch (_) {}
    }
  }, true);
  document.addEventListener('submit', function(){
    internalNav = true;
    if (internalNavTimer) clearTimeout(internalNavTimer);
    internalNavTimer = setTimeout(()=>{ internalNav = false; }, 2000);
  }, true);

  // Detect reload intent
  document.addEventListener('keydown', function(e){
    try {
      const key = e.key || '';
      if (key === 'F5' || (key.toLowerCase() === 'r' && (e.ctrlKey || e.metaKey))) {
        reloadIntent = true;
      }
    } catch(_){}
  }, true);

  function isReload(){
    if (reloadIntent) return true;
    try {
      const entries = performance && performance.getEntriesByType ? performance.getEntriesByType('navigation') : [];
      if (entries && entries.length && entries[0].type === 'reload') return true;
      if (performance && performance.navigation && performance.navigation.type === 1) return true;
    } catch(_){}
    return false;
  }

  function setPendingCookie(){
    try { document.cookie = 'scms_admin_force_logout_pending=1; path=/'; } catch(_) {}
  }

  function broadcastLogout(){
    try { localStorage.setItem('scms_admin_force_logout', String(Date.now())); } catch(_) {}
  }

  function postLogoutKeepalive(){
    try {
      if (navigator.sendBeacon) {
        const blob = new Blob([''], { type: 'application/x-www-form-urlencoded;charset=UTF-8' });
        navigator.sendBeacon(logoutBeaconPost, blob);
      }
      const bust = Date.now().toString();
      fetch(`${logoutBeaconGet}?_=${bust}`, { method: 'GET', keepalive: true, cache: 'no-store', credentials: 'same-origin' }).catch(()=>{});
    } catch(_) {}
  }

  function handleExit(){
    if (internalNav) return;
    if (isReload()) return;
    try { localStorage.setItem('scms_admin_force_logout_pending', '1'); } catch(_) {}
    setPendingCookie();
    broadcastLogout();
    postLogoutKeepalive();
  }

  window.addEventListener('pagehide', handleExit);
  window.addEventListener('beforeunload', handleExit);
  // No logout on tab switch (we avoid visibilitychange here)

  // Cross-tab sync: if another tab logs out, go to admin login
  window.addEventListener('storage', function(ev){
    if (ev.key === 'scms_admin_force_logout' && ev.newValue) {
      try { window.location.replace(loginUrl); } catch(_) { window.location.href = loginUrl; }
    }
  });

  // BFCache restore safety
  window.addEventListener('pageshow', function (e) { if (e.persisted) window.location.reload(); });

  // Enforce pending logout on next load if unload beacons failed
  (function enforcePending(){
    try {
      const pending = localStorage.getItem('scms_admin_force_logout_pending');
      const cookiePending = (document.cookie || '').includes('scms_admin_force_logout_pending=1');
      if (pending === '1' || cookiePending) {
        postLogoutKeepalive();
        try { localStorage.removeItem('scms_admin_force_logout_pending'); } catch(_) {}
        try { document.cookie = 'scms_admin_force_logout_pending=; Max-Age=0; path=/'; } catch(_) {}
        try { window.location.replace(loginUrl); } catch(_) { window.location.href = loginUrl; }
      }
    } catch(_) {}
  })();
})();
</script>
@endif
