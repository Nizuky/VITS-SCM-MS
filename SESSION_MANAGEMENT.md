# Session & CSRF Token Management System

## Overview
This system keeps user sessions alive and CSRF tokens fresh to prevent re-login and token mismatch errors while maintaining security and efficiency.

## Features

### 1. **Automatic CSRF Token Refresh**
- Refreshes CSRF tokens every 10 minutes
- Updates all forms and AJAX requests automatically
- Prevents "CSRF token mismatch" errors

### 2. **Session Keep-Alive**
- Pings server every 5 minutes to keep session active
- Session lifetime set to 1 year (525600 minutes)
- Sessions persist across browser restarts
- Automatically tracks last activity

### 3. **Auto Data Refresh**
- Refreshes dashboard data every 30 seconds
- No full page reload required
- Updates submissions, students, and statistics automatically

### 4. **Smart Tab Management**
- Pauses refresh timers when tab is hidden (saves resources)
- Immediately refreshes when tab becomes visible again
- Optimized for battery and performance

### 5. **Multi-Guard Support**
- Works for Student dashboard (web guard)
- Works for Admin dashboard (admin guard)
- Works for Super Admin dashboard (superadmin guard)

## How It Works

### Client-Side (session-keeper.js)

The Session Keeper JavaScript module runs on every dashboard page and:

1. **Refreshes CSRF Token**: Calls `/api/refresh-csrf` every 10 minutes
2. **Pings Session**: Calls `/api/ping` every 5 minutes to keep session alive
3. **Auto-Refreshes Data**: Calls dashboard-specific data fetch functions every 30 seconds
4. **Intercepts AJAX**: Automatically adds fresh CSRF token to all POST/PUT/DELETE/PATCH requests
5. **Updates Forms**: Updates all `<input name="_token">` fields with fresh tokens

### Server-Side

1. **Session Configuration** (`config/session.php`):
   - Driver: database (recommended for production)
   - Lifetime: 525600 minutes (1 year)
   - Expire on close: false (session persists)

2. **API Routes** (`routes/web.php`):
   - `GET /api/refresh-csrf` - Returns fresh CSRF token
   - `POST /api/ping` - Updates session activity timestamp

3. **Middleware** (`RefreshSessionActivity`):
   - Runs on every request
   - Updates `last_activity` timestamp in session
   - Keeps session alive indefinitely as long as user is active

## Configuration

### Session Settings (.env)
```env
SESSION_DRIVER=database
SESSION_LIFETIME=525600
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
```

### JavaScript Configuration

Each dashboard initializes Session Keeper with custom settings:

```javascript
SessionKeeper.init({
    debug: false,                    // Enable console logging
    autoRefreshEnabled: true,        // Enable auto data refresh
    dataRefreshInterval: 30 * 1000,  // 30 seconds
    onDataRefresh: function() {
        // Dashboard-specific refresh logic
        fetchSubmissions();
        fetchDashboardStats();
    }
});
```

## Dashboard-Specific Implementation

### Student Dashboard
- Refreshes: `fetchRecords()`
- Interval: 30 seconds

### Admin Dashboard
- Refreshes: `fetchSubmissions()`, `fetchDashboardStats()`
- Interval: 30 seconds

### Super Admin Dashboard
- Refreshes: `fetchSubmissions()`, `fetchStudents()`, `fetchDashboardStats()`
- Interval: 30 seconds

## Security Features

1. **CSRF Protection**: All requests still require valid CSRF token
2. **Session Validation**: Server validates session on every request
3. **Guard Isolation**: Each user type (student/admin/superadmin) uses separate guard
4. **Automatic Cleanup**: Old sessions are garbage collected by Laravel
5. **HTTP Only Cookies**: Session cookies are HTTP-only and secure

## Performance Optimization

1. **Tab Visibility Detection**: Pauses timers when tab is hidden
2. **Efficient Polling**: Uses minimal server resources
3. **Database Sessions**: Better for multi-server environments
4. **Conditional Refresh**: Only refreshes when tab is active

## Production Safety

✅ **Secure**: Maintains CSRF protection and session validation
✅ **Efficient**: Minimal server load with smart polling
✅ **Scalable**: Works with database session driver
✅ **Reliable**: Handles network errors gracefully
✅ **User-Friendly**: No visible interruptions or re-logins

## Troubleshooting

### Issue: Still getting CSRF token mismatch
**Solution**: Clear browser cache and refresh page. Check that `session-keeper.js` is loaded.

### Issue: Session expires after long idle time
**Solution**: Ensure SESSION_LIFETIME is set to 525600 in .env and config cache is cleared.

### Issue: Auto-refresh not working
**Solution**: Check browser console for errors. Ensure fetch functions are defined in dashboard.

### Issue: Multiple tabs cause conflicts
**Solution**: Each tab maintains its own timers. Session is shared across tabs via cookies.

## Commands

```bash
# Clear config cache
php artisan config:clear

# Clear view cache
php artisan view:clear

# Clear all caches
php artisan optimize:clear
```

## Browser Compatibility

- ✅ Chrome/Edge (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Mobile browsers

## API Endpoints

### GET /api/refresh-csrf
Returns fresh CSRF token for the current session.

**Response:**
```json
{
    "token": "ABC123..."
}
```

### POST /api/ping
Keeps session alive by updating last activity timestamp.

**Response:**
```json
{
    "status": "ok",
    "timestamp": 1698345678,
    "guard": "web",
    "session_id": "xyz789..."
}
```

## Best Practices

1. ✅ Keep session-keeper.js loaded on all authenticated pages
2. ✅ Use database session driver in production
3. ✅ Monitor server logs for session-related errors
4. ✅ Test auto-refresh with network throttling
5. ✅ Adjust refresh intervals based on your needs

## Customization

### Change Refresh Intervals

Edit the dashboard initialization:

```javascript
SessionKeeper.init({
    csrfRefreshInterval: 15 * 60 * 1000,    // 15 minutes
    sessionKeepAliveInterval: 10 * 60 * 1000, // 10 minutes
    dataRefreshInterval: 60 * 1000,          // 1 minute
    // ...
});
```

### Disable Auto-Refresh

```javascript
SessionKeeper.init({
    autoRefreshEnabled: false
});
```

### Manual Refresh

```javascript
// Manually refresh data
SessionKeeper.refreshData();

// Manually refresh CSRF token
SessionKeeper.refreshCsrfToken();

// Manually ping session
SessionKeeper.pingSession();
```

## Files Modified

1. `public/js/session-keeper.js` - Main JavaScript module
2. `routes/web.php` - API routes for CSRF refresh and ping
3. `config/session.php` - Session configuration
4. `app/Http/Middleware/RefreshSessionActivity.php` - Session activity middleware
5. `bootstrap/app.php` - Middleware registration
6. `resources/views/dashboards/student.blade.php` - Student dashboard integration
7. `resources/views/dashboards/admin.blade.php` - Admin dashboard integration
8. `resources/views/dashboards/super_admin.blade.php` - Super admin dashboard integration

## Conclusion

This system provides a production-ready, secure, and efficient way to keep sessions alive and data fresh without interrupting the user experience. Users can work uninterrupted as long as their browser tab remains open, with automatic session and token management happening seamlessly in the background.
