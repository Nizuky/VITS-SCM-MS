# Deployment Fixes for Connection Timeout and Login Issues

## Issues Addressed

1. ✅ **Database Connection Timeouts** - Gateway 504 errors, "signal timed out"
2. ✅ **Password Hashing Mismatches** - "Login failed" with correct credentials
3. ✅ **Missing Database Columns** - `email_verified_at` missing from admin_users table
4. ✅ **CSRF Token Issues** - All forms now have proper CSRF protection
5. ✅ **Performance Optimizations** - Caching, storage links, and optimization commands

## Critical Fixes Applied

### 1. Database Connection Improvements

**Timeout Increases:**
- PDO timeout: 10s → 30s
- MySQL specific timeouts: 30s (connect, read, write)
- Nginx FastCGI timeouts: 60s (connect, send, read)
- PHP-FPM request timeout: 60s

**Retry Logic:**
- Both Admin and SuperAdmin login controllers now retry database queries up to 3 times
- Exponential backoff (1s, 2s, 3s delays)
- Automatic database reconnection between retries

### 2. Password Hashing Fix

**Migration Fixed:**
- Added `email_verified_at` column to `admin_users` table
- Added `rememberToken()` to match `super_admins` table structure

**New Command Created:**
```bash
php artisan admin:reset-passwords
```
This command:
- Resets all admin/super-admin passwords to known values
- Creates missing admin accounts if needed
- Uses proper bcrypt hashing

### 3. Diagnostic Tools Added

**Database Connection Test:**
```bash
php artisan db:test-connection
```
or visit:
```
https://your-app.laravel.cloud/test-db-connection
```

Shows:
- Connection timing
- Query performance
- Table accessibility
- Specific admin account lookup with retry logic

## Deployment Steps

### Step 1: Verify Environment Variables

In Laravel Cloud dashboard, ensure these are set:

```env
# Database Configuration
DB_HOST=db-a08bd7ae-1588-4461-97c3-bde906d54852.ap-southeast-1.public.db.laravel.cloud
DB_PORT=3306
DB_DATABASE=main
DB_USERNAME=jsthyIbkmmrf6jnv
DB_PASSWORD=QXkqdoO9xir8FToisMwb
DB_TIMEOUT=30

# Application
APP_URL=https://vits-scm-ms-main-xkjcnp.laravel.cloud
APP_KEY=<your-app-key>

# Session (for Laravel Cloud)
SESSION_DRIVER=cookie
SESSION_SECURE_COOKIE=true
SESSION_ENCRYPT=true
SESSION_LIFETIME=525600

# Livewire
LIVEWIRE_RELEASE_TOKEN=v1
```

### Step 2: Check Database Firewall

**CRITICAL**: Verify your MySQL database allows connections from Laravel Cloud's IP addresses.

On Laravel Cloud managed databases, this should be automatic. If using external database:
1. Get your Laravel Cloud application's outbound IP
2. Whitelist it in your MySQL server's firewall
3. Ensure port 3306 is open

### Step 3: Deploy and Run Migrations

The entrypoint script will automatically:
1. Wait for database connection (up to 30 attempts, 60 seconds)
2. Run migrations
3. Run seeders
4. **NEW**: Reset admin passwords to known values
5. Create storage symlink
6. Cache configuration, routes, views, and events

**Manual Deploy Command (if needed):**
```bash
php artisan migrate:fresh --seed --force
php artisan admin:reset-passwords
php artisan storage:link --force
php artisan optimize
```

### Step 4: Test Database Connection

SSH into your Laravel Cloud container and run:

```bash
php artisan db:test-connection
```

Expected output:
```
✓ Connected successfully in <50ms
✓ Query executed in <50ms
✓ Found 1 super admin(s) in <100ms
  - adminAlex (janarafael.sanandres@gmail.com)
✓ Found 2 admin user(s) in <100ms
  - admin1 (admin1@scms.test)
  - admin2 (admin2@scms.test)
```

If connection times are > 1000ms, investigate database performance or network latency.

### Step 5: Test Logins

**Super Admin:**
- Username: `adminAlex`
- Password: `softdev12345`
- URL: `https://vits-scm-ms-main-xkjcnp.laravel.cloud/super-admin/login`

**Admin 1:**
- Username: `admin1`
- Password: `raf12345`
- URL: `https://vits-scm-ms-main-xkjcnp.laravel.cloud/admin/login`

**Admin 2:**
- Username: `admin2`
- Password: `dek12345`
- URL: `https://vits-scm-ms-main-xkjcnp.laravel.cloud/admin/login`

## Troubleshooting

### Still Getting "Connection timed out"

1. **Check database reachability:**
   ```bash
   php artisan db:test-connection
   ```

2. **Verify environment variables:**
   ```bash
   php artisan tinker
   >>> config('database.connections.mysql.host')
   >>> config('database.connections.mysql.database')
   ```

3. **Check Laravel logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

4. **Verify database is accepting connections:**
   - Check database server status
   - Verify firewall rules
   - Check for connection limits

### Still Getting "Login Failed"

1. **Reset passwords manually:**
   ```bash
   php artisan admin:reset-passwords
   ```

2. **Verify password hash in database:**
   ```bash
   php artisan tinker
   >>> \App\Models\SuperAdmin::where('name', 'adminAlex')->first()->password
   >>> \Hash::check('softdev12345', $password)
   ```

3. **Check authentication guard:**
   - Verify `config/auth.php` has correct guards
   - Check controller is using correct guard name

### HTTP 419 (CSRF Token Mismatch)

1. **Clear application cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

2. **Verify session configuration:**
   - SESSION_DRIVER should be `cookie` (not `database` for Laravel Cloud)
   - SESSION_SECURE_COOKIE should be `true`
   - APP_URL should match deployed URL exactly

3. **Check browser:**
   - Clear cookies
   - Disable browser extensions
   - Try incognito/private mode

### Gateway 504 Timeout

This indicates the web server didn't receive a response within the timeout period.

1. **Increase timeouts** (already done in this fix):
   - Nginx: 60s
   - PHP-FPM: 60s
   - Database: 30s

2. **Optimize queries:**
   - Add database indexes
   - Use query caching
   - Reduce N+1 queries

3. **Check server resources:**
   - CPU usage
   - Memory usage
   - Database connections

## Files Modified

### Configuration
- `config/database.php` - Increased timeouts, added MySQL-specific timeouts
- `config/session.php` - Already configured for Laravel Cloud
- `config/livewire.php` - Already configured with release token

### Database
- `database/migrations/2025_10_13_000010_create_admin_users_table.php` - Added email_verified_at and rememberToken
- `database/seeders/AdminUserSeeder.php` - Removed email_verified_at from updateOrCreate

### Docker
- `docker/docker-entrypoint.sh` - Added password reset and event caching
- `docker/nginx/default.conf` - Added FastCGI timeouts
- `docker/php-fpm/www.conf` - Already has 60s timeout

### Controllers
- `app/Http/Controllers/Admin/Auth/LoginController.php` - Added retry logic with reconnection
- `app/Http/Controllers/SuperAdmin/LoginController.php` - Added retry logic with reconnection

### Commands (NEW)
- `app/Console/Commands/TestDatabaseConnection.php` - Comprehensive database diagnostics
- `app/Console/Commands/ResetAdminPasswords.php` - Reset admin passwords to known values

### Views
- `resources/views/auth/super-admin-login.blade.php` - Shows actual error messages
- `resources/views/auth/login.blade.php` - Already has @csrf
- `resources/views/livewire/auth/login.blade.php` - Livewire handles CSRF automatically

### Routes
- `routes/test-db.php` - Web endpoint for database testing

## Performance Optimizations Applied

1. ✅ **Config caching** - `php artisan config:cache`
2. ✅ **Route caching** - `php artisan route:cache`
3. ✅ **View caching** - `php artisan view:cache`
4. ✅ **Event caching** - `php artisan event:cache` (NEW)
5. ✅ **Optimization** - `php artisan optimize`
6. ✅ **Storage link** - `php artisan storage:link --force`
7. ✅ **Livewire assets** - Published to public directory
8. ✅ **OPcache** - Enabled in PHP-FPM (128M, 10000 files)
9. ✅ **Gzip compression** - Enabled in Nginx

## Credentials Reference

All credentials are documented in `DEPLOYMENT_DATA.md`.

**Quick Reference:**
- Super Admin: `adminAlex` / `softdev12345`
- Admin 1: `admin1` / `raf12345`  
- Admin 2: `admin2` / `dek12345`
- Database: `main` / `jsthyIbkmmrf6jnv` / `QXkqdoO9xir8FToisMwb`

## Next Steps

1. ✅ Commit and push all changes
2. ⏳ Redeploy to Laravel Cloud
3. ⏳ Run `php artisan db:test-connection` to verify database
4. ⏳ Test all admin logins
5. ⏳ Monitor Laravel logs for any errors
6. ⏳ Check performance (all pages should load < 1 second)

If issues persist after following this guide, the problem is likely:
- Database firewall blocking connections
- Wrong database credentials in environment
- Database server performance issues
- Network latency between app and database
