# Laravel Cloud Deployment - 419 CSRF Error Fix

## ✅ What We Fixed

1. **Added CSRF Token Handling in JavaScript**
   - Configured axios to send `X-CSRF-TOKEN` header
   - Added Fetch API proxy to include CSRF token
   - Added Livewire hook to ensure CSRF token in all requests

2. **Improved Session Configuration**
   - Default session driver: `cookie` (better for Laravel Cloud)
   - Auto-enable secure cookies in production
   - Session encryption enabled for cookie driver

3. **Database Connection Wait Logic**
   - Added intelligent database connection retry (30 attempts, 60s total)
   - Graceful degradation if database unavailable

## 🚀 Laravel Cloud Environment Variables

**Copy these EXACT settings to your Laravel Cloud Custom Environment Variables:**

```env
# Application
APP_URL=https://vits-scm-ms-main-xkjcnp.laravel.cloud
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:81xdvokINT53F+fk6pW/4CID4yRF9WV1+eefG0HU1FY=

# Session Configuration (CRITICAL)
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_DOMAIN=.laravel.cloud
SESSION_SAME_SITE=lax

# Database (should be auto-configured)
DB_CONNECTION=mysql
DB_HOST=db-a08bd7ae-1588-4461-97c3-bde906d54852.ap-southeast-1.public.db.laravel.cloud
DB_PORT=3306
DB_DATABASE=main
DB_USERNAME=jsthyIbkmmrf6jnv
DB_PASSWORD=QXkqdoO9xir8FToisMwb

# Cache & Queue
CACHE_STORE=file
QUEUE_CONNECTION=sync

# Mail (if needed)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=vitsscmms@gmail.com
MAIL_PASSWORD="dgiv kmle sboi bgwo"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=vitsscmms@gmail.com
MAIL_FROM_NAME="VITS"
```

## 🔧 Deploy Commands

**Option 1: Let docker-entrypoint.sh handle migrations (RECOMMENDED)**
```bash
echo "Migrations handled by docker-entrypoint.sh"
```

**Option 2: Run migrations in deploy command**
```bash
php artisan config:clear && php artisan cache:clear && php artisan migrate --force && php artisan db:seed --force
```

## 🧪 Testing After Deployment

### 1. Test Session Persistence
Visit: `https://vits-scm-ms-main-xkjcnp.laravel.cloud/test-session.php`
- Refresh the page 2-3 times
- Counter should increment (proves sessions work)
- Check that `Session Driver` shows `cookie`
- Check that `Cookie Secure` shows `Yes`
- Check that `Cookie HttpOnly` shows `Yes`

### 2. Test Login
1. Go to login page: `https://vits-scm-ms-main-xkjcnp.laravel.cloud/login`
2. Open browser console (F12) → Network tab
3. Enter credentials and submit
4. Check for:
   - ❌ No 419 errors
   - ✅ `X-CSRF-TOKEN` header in request
   - ✅ Successful redirect to dashboard

### 3. Check Browser Console
Press F12 → Console tab
- ❌ No "CSRF token mismatch" errors
- ❌ No cookie errors
- ❌ No CORS errors

## 🐛 Troubleshooting

### Still Getting 419 Error?

1. **Clear Laravel Cloud cache** - Add to deploy commands temporarily:
   ```bash
   php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear
   ```

2. **Check browser cookies** - Clear all cookies for your domain:
   - Chrome: Settings → Privacy → Cookies → See all site data → Remove `*.laravel.cloud`
   - Firefox: Settings → Privacy → Cookies → Manage Data → Remove `*.laravel.cloud`

3. **Verify APP_URL matches exactly**:
   ```bash
   # Must include https:// and exact subdomain
   APP_URL=https://vits-scm-ms-main-xkjcnp.laravel.cloud
   ```

4. **Check SESSION_DOMAIN**:
   ```bash
   # Use .laravel.cloud (with dot) to share cookies across subdomains
   SESSION_DOMAIN=.laravel.cloud
   ```

5. **Inspect request in browser**:
   - F12 → Network tab
   - Filter by "Fetch/XHR"
   - Click on login request
   - Check "Headers" tab → Request Headers
   - Verify `X-CSRF-TOKEN` is present

### Session Not Persisting?

1. **Check session driver**:
   ```bash
   # For Laravel Cloud, use cookie
   SESSION_DRIVER=cookie
   ```

2. **Verify session cookie settings**:
   ```bash
   SESSION_SECURE_COOKIE=true  # Must be true for HTTPS
   SESSION_SAME_SITE=lax       # Recommended for auth
   ```

3. **Check browser cookie settings**:
   - Ensure third-party cookies aren't blocked
   - Ensure site isn't in "block all cookies" mode

### Database Connection Timeout?

1. **Verify database is linked** to your app cluster in Laravel Cloud dashboard
2. **Check database credentials** match exactly (copy from Laravel Cloud)
3. **Increase connection timeout** - The entrypoint now waits 60 seconds
4. **Check database cluster status** in Laravel Cloud dashboard

## 📝 Key Changes Made

### Files Modified:
- `resources/js/app.js` - Added CSRF token to axios and fetch
- `resources/views/components/layouts/auth/login-register.blade.php` - Added Livewire CSRF hook
- `config/session.php` - Changed default driver to cookie, improved security
- `docker/docker-entrypoint.sh` - Added database connection wait logic
- `app/Http/Middleware/KeepSessionAlive.php` - Added session save call

### Why Cookie Session Driver?
- **Stateless**: No file/database storage needed
- **Distributed**: Works across multiple Laravel Cloud instances
- **Secure**: Encrypted with APP_KEY
- **Fast**: No I/O operations

### Why These Environment Variables?
- `SESSION_DOMAIN=.laravel.cloud` - Allows cookies to work across Laravel Cloud subdomains
- `SESSION_SECURE_COOKIE=true` - Required for HTTPS (security)
- `SESSION_ENCRYPT=true` - Protects session data in cookies
- `APP_URL` - Must match exact domain for CSRF validation

## 🎯 Success Criteria

✅ Session test page counter increments
✅ Login succeeds without 419 error
✅ User redirected to dashboard after login
✅ Session persists across page refreshes
✅ CSRF token visible in network requests
✅ No console errors related to authentication

## 📞 Need Help?

If still experiencing issues after following this guide:
1. Share the browser console output (F12 → Console tab)
2. Share the network request details (F12 → Network → login request → Headers)
3. Confirm all environment variables are set correctly
4. Verify the session test page shows correct configuration
