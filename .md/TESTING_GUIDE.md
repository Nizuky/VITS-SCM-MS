# Quick Testing Guide - Session Management

## How to Test

### 1. **Test CSRF Token Refresh**

1. Open browser DevTools (F12)
2. Go to Console tab
3. Log in to any dashboard (Student/Admin/Super Admin)
4. Enable debug mode temporarily:
   ```javascript
   SessionKeeper.config.debug = true;
   ```
5. Watch console - you should see:
   - `[SessionKeeper] CSRF token refreshed` every 10 minutes
   - `[SessionKeeper] Session pinged` every 5 minutes

### 2. **Test Auto Data Refresh**

1. Log in to dashboard
2. Open another browser tab/window
3. Make changes (e.g., create/verify/approve submissions)
4. Go back to original tab
5. Data should auto-update within 30 seconds (no page refresh needed)

### 3. **Test Session Keep-Alive**

1. Log in to dashboard
2. Leave tab open for 2+ hours (SESSION_LIFETIME was 120 minutes)
3. Come back and interact with page
4. Should NOT be logged out or get CSRF errors

### 4. **Test Tab Visibility Handling**

1. Log in to dashboard
2. Enable debug mode: `SessionKeeper.config.debug = true;`
3. Switch to another tab
4. Check console: Should see "Tab hidden - pausing timers"
5. Switch back to dashboard tab
6. Check console: Should see "Tab visible - resuming timers"

### 5. **Test Form Submission with Fresh Token**

1. Log in to dashboard
2. Leave page open for 15+ minutes (longer than CSRF refresh interval)
3. Submit a form (e.g., create submission, update profile)
4. Should succeed without CSRF token mismatch error

### 6. **Test Multi-Tab Sessions**

1. Open dashboard in Tab 1
2. Open same dashboard in Tab 2
3. Make changes in Tab 1
4. Check Tab 2 - should auto-refresh within 30 seconds
5. Both tabs should remain logged in

## Expected Behavior

✅ **Sessions persist** - No automatic logout as long as tab is open
✅ **CSRF tokens refresh** - Forms always submit successfully
✅ **Data auto-updates** - Dashboard shows latest data without refresh
✅ **Resource efficient** - Timers pause when tab is hidden
✅ **Multi-tab support** - All tabs stay synchronized

## Debug Commands

### Check Current Session Status
```javascript
// In browser console
fetch('/api/ping', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
    },
    body: '{}'
}).then(r => r.json()).then(console.log);
```

### Check Current CSRF Token
```javascript
console.log(document.querySelector('meta[name="csrf-token"]').content);
```

### Manually Trigger Data Refresh
```javascript
SessionKeeper.refreshData();
```

### Check Session Keeper Status
```javascript
console.log('CSRF Timer:', SessionKeeper.timers.csrf);
console.log('Session Timer:', SessionKeeper.timers.session);
console.log('Data Timer:', SessionKeeper.timers.data);
console.log('Config:', SessionKeeper.config);
```

### Enable Debug Mode
```javascript
SessionKeeper.config.debug = true;
```

## Network Tab Monitoring

1. Open DevTools → Network tab
2. Filter by "Fetch/XHR"
3. You should see periodic requests to:
   - `/api/refresh-csrf` (every 10 minutes)
   - `/api/ping` (every 5 minutes)
   - Dashboard data endpoints (every 30 seconds)

## Common Issues & Solutions

### Issue: Console shows "Failed to refresh CSRF token"
**Solution:** Check that `/api/refresh-csrf` route is accessible. Clear cache: `php artisan optimize:clear`

### Issue: Auto-refresh not working
**Solution:** Check that dashboard fetch functions exist (fetchRecords, fetchSubmissions, etc.)

### Issue: Too many requests
**Solution:** Increase refresh intervals in SessionKeeper.init() configuration

### Issue: Session still expires
**Solution:** 
```bash
# Check .env file
SESSION_LIFETIME=525600
SESSION_DRIVER=database

# Clear config cache
php artisan config:clear
```

## Performance Monitoring

### Monitor Server Load
```bash
# Watch Laravel logs
tail -f storage/logs/laravel.log

# Check session table size (if using database driver)
SELECT COUNT(*) FROM sessions;
```

### Browser Performance
1. Open DevTools → Performance tab
2. Record for 1 minute
3. Check CPU usage - should be minimal
4. Session keeper should not impact page performance

## Production Checklist

✅ SESSION_DRIVER=database (in .env)
✅ SESSION_LIFETIME=525600 (in .env)
✅ Debug mode disabled (SessionKeeper.config.debug = false)
✅ All caches cleared (php artisan optimize:clear)
✅ session-keeper.js loaded on all dashboards
✅ HTTPS enabled (for secure cookies)
✅ Database sessions table exists

## Rollback (If Needed)

If you need to disable the session keeper:

1. Remove `<script src="{{ asset('js/session-keeper.js') }}">` from dashboards
2. Remove `SessionKeeper.init()` scripts from dashboards
3. Restore SESSION_LIFETIME to 120 in .env
4. Clear caches: `php artisan optimize:clear`

## Support

For issues or questions, check:
1. Browser console for JavaScript errors
2. `storage/logs/laravel.log` for server errors
3. Network tab for failed API requests
4. SESSION_MANAGEMENT.md for detailed documentation
