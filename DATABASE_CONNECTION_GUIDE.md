# Database Connection Troubleshooting Guide

## Current Issue: SQLSTATE[HY000] [2002] Connection timed out

This error means the application **cannot establish a TCP connection** to the MySQL database server within the timeout period.

## Root Causes (In Order of Probability)

### 1. ❌ Wrong DB_HOST Environment Variable (90% of cases)

**Problem:** The application is trying to connect to the wrong database server address.

**Laravel Cloud Database Format:**
```
db-{unique-id}.{region}.public.db.laravel.cloud
```

**Your Database:**
```
DB_HOST=db-a08bd7ae-1588-4461-97c3-bde906d54852.ap-southeast-1.public.db.laravel.cloud
DB_PORT=3306
DB_DATABASE=main
DB_USERNAME=jsthyIbkmmrf6jnv
DB_PASSWORD=QXkqdoO9xir8FToisMwb
```

**How to Fix:**
1. Go to Laravel Cloud Dashboard → Your App → Environment
2. Verify **exact** values match above
3. Pay attention to:
   - No extra spaces before/after values
   - Exact capitalization
   - Complete hostname (don't truncate)
   - Correct region (`ap-southeast-1`)

### 2. 🔥 Database Firewall Blocking Connections

**Problem:** The MySQL server firewall is not allowing connections from your Laravel Cloud application.

**For Laravel Cloud Managed Database:**
- This should be automatic
- Verify in Dashboard → Database → Allowed IPs
- Your app's IP should be whitelisted automatically

**For External MySQL:**
- Whitelist Laravel Cloud's outbound IPs
- Ensure port 3306 is open
- Check security groups / firewall rules

**How to Test:**
From within your deployed container, run:
```bash
# Try to connect manually
mysql -h db-a08bd7ae-1588-4461-97c3-bde906d54852.ap-southeast-1.public.db.laravel.cloud \
      -P 3306 \
      -u jsthyIbkmmrf6jnv \
      -p main

# Or test with telnet
telnet db-a08bd7ae-1588-4461-97c3-bde906d54852.ap-southeast-1.public.db.laravel.cloud 3306
```

### 3. 🐌 Network Latency / Database Slow Start

**Problem:** Database takes too long to respond, causing timeout.

**How to Fix:**
- ✅ Already implemented: Increased timeouts to 30s
- ✅ Already implemented: Retry logic (3 attempts)
- ✅ Already implemented: Wait-for-db script (up to 2 minutes)

### 4. 💾 Database Server Down / Overloaded

**Problem:** The MySQL server itself is not running or unresponsive.

**How to Check:**
- Laravel Cloud Dashboard → Database → Status
- Should show "Running" or "Active"
- Check CPU / Memory usage
- Check for maintenance windows

### 5. 🔐 Wrong Database Credentials

**Problem:** Username, password, or database name is incorrect.

**Note:** This would show **"Access denied"** not "Connection timed out", but worth checking.

**Verify in Laravel Cloud Dashboard:**
- Database name: `main`
- Username: `jsthyIbkmmrf6jnv`
- Password: `QXkqdoO9xir8FToisMwb` (check for copy/paste errors)

## Diagnostic Commands

After deploying the latest changes, run these commands:

### 1. Verify Environment Configuration
```bash
php artisan db:verify-config
```

This will show:
- All database settings loaded from environment
- DNS resolution test
- Actual connection test with timing
- Specific troubleshooting steps based on error type

### 2. Test Database Connection
```bash
php artisan db:test-connection
```

This will:
- Test basic PDO connection
- Run simple queries
- Test access to admin tables
- Show connection timing for each operation

### 3. Check Environment Variables
```bash
php artisan tinker
>>> env('DB_HOST')
>>> env('DB_DATABASE')
>>> config('database.connections.mysql.host')
>>> config('database.connections.mysql.database')
```

Expected output:
```
"db-a08bd7ae-1588-4461-97c3-bde906d54852.ap-southeast-1.public.db.laravel.cloud"
"main"
"db-a08bd7ae-1588-4461-97c3-bde906d54852.ap-southeast-1.public.db.laravel.cloud"
"main"
```

### 4. Test Manual Connection (from container)
```bash
mysql -h $DB_HOST -P $DB_PORT -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE -e "SELECT 1"
```

If this works but Laravel doesn't, it's a PHP PDO configuration issue.
If this fails, it's a network/firewall issue.

## Quick Fix Checklist

Run through this checklist in order:

- [ ] **Step 1:** Verify DB_HOST in Laravel Cloud environment matches exactly
- [ ] **Step 2:** Check database status in Laravel Cloud dashboard (should be "Running")
- [ ] **Step 3:** Run `php artisan db:verify-config` to see exact error
- [ ] **Step 4:** Check firewall/security groups allow port 3306
- [ ] **Step 5:** Verify database credentials are correct
- [ ] **Step 6:** Check application logs: `tail -f storage/logs/laravel.log`
- [ ] **Step 7:** Try restarting the database service
- [ ] **Step 8:** Try restarting the application container

## What The Fixes Do

### Increased Timeouts
```
PDO Timeout: 30 seconds (was 10s)
MySQL Connect Timeout: 30 seconds
MySQL Read/Write Timeout: 30 seconds
Nginx FastCGI Timeout: 60 seconds
PHP-FPM Request Timeout: 60 seconds
```

This gives slow/distant databases more time to respond.

### Retry Logic (LoginControllers)
```php
// Tries up to 3 times with exponential backoff
for ($attempt = 1; $attempt <= 3; $attempt++) {
    try {
        $admin = SuperAdmin::where('name', $identifier)->first();
        break; // Success
    } catch (\PDOException $e) {
        if ($attempt < 3) {
            sleep(1 * $attempt); // Wait 1s, 2s, 3s
            DB::reconnect();     // Reconnect
        }
    }
}
```

Handles temporary network glitches.

### Database Wait Script (docker-entrypoint.sh)
```bash
# Waits up to 2 minutes for database before starting app
/usr/local/bin/wait-for-db.sh 60
```

Prevents "app started before database ready" race condition.

## Expected Behavior After Fix

### If Database Connection is Fixed:

**Startup logs should show:**
```
Waiting for database connection to db-a08bd7ae-...laravel.cloud:3306...
✓ Database connection successful after 1 attempts!
Running migrations...
Migration table created successfully.
Migrated: 2025_10_12_000020_create_super_admins_table
...
Running database seeders...
Resetting admin passwords to known values...
✓ Super Admin 'adminAlex' password reset to: softdev12345
✓ All admin passwords have been reset successfully!
```

**Login should work immediately:**
- No "signal timed out"
- No "500 Server Error"
- No "419 Page Expired" (unless browser cache issue)
- Admin login completes in < 1 second

### If Database Connection is Still Failing:

**You'll see:**
```
Waiting for database connection to db-a08bd7ae-...laravel.cloud:3306...
  Attempt 1/60 failed. Retrying in 2 seconds...
  Attempt 2/60 failed. Retrying in 2 seconds...
  ...
✗ Database connection failed after 60 attempts
Please check:
  1. DB_HOST is correct: db-a08bd7ae-...laravel.cloud
  2. DB_PORT is correct: 3306
  3. Database server is running
  4. Firewall allows connections from this server
  5. Database credentials are correct
```

**Then you need to:**
1. Contact Laravel Cloud support
2. Verify database service is running in dashboard
3. Check if there are any platform-wide issues

## Environment Variables to Set in Laravel Cloud

Copy these **exact** values to your Laravel Cloud environment:

```env
# Database - CRITICAL - Must be EXACT
DB_CONNECTION=mysql
DB_HOST=db-a08bd7ae-1588-4461-97c3-bde906d54852.ap-southeast-1.public.db.laravel.cloud
DB_PORT=3306
DB_DATABASE=main
DB_USERNAME=jsthyIbkmmrf6jnv
DB_PASSWORD=QXkqdoO9xir8FToisMwb
DB_TIMEOUT=30

# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://vits-scm-ms-main-xkjcnp.laravel.cloud
APP_KEY=<your-app-key>

# Session - for Laravel Cloud
SESSION_DRIVER=cookie
SESSION_LIFETIME=525600
SESSION_SECURE_COOKIE=true
SESSION_ENCRYPT=true

# Cache
CACHE_DRIVER=file

# Livewire
LIVEWIRE_RELEASE_TOKEN=v1
```

## Common Mistakes

❌ **Using `localhost` or `127.0.0.1` for DB_HOST**
- This connects to the app container's local MySQL (which doesn't exist)
- Must use the full external hostname

❌ **Extra whitespace in environment variables**
```env
DB_HOST= db-a08bd7ae... ← WRONG (space before hostname)
DB_HOST=db-a08bd7ae...  ← CORRECT
```

❌ **Wrong region in hostname**
```
db-{id}.us-east-1.public.db.laravel.cloud  ← WRONG region
db-{id}.ap-southeast-1.public.db.laravel.cloud  ← CORRECT
```

❌ **Truncated hostname**
```
DB_HOST=db-a08bd7ae  ← WRONG (incomplete)
DB_HOST=db-a08bd7ae-1588-4461-97c3-bde906d54852.ap-southeast-1.public.db.laravel.cloud  ← CORRECT
```

## Next Steps

1. ✅ All code fixes are committed
2. ⏳ **Deploy to Laravel Cloud** (redeploy your application)
3. ⏳ **Verify environment variables** in Laravel Cloud dashboard
4. ⏳ **Check deployment logs** for database connection success/failure
5. ⏳ **Run diagnostic commands** if connection still fails
6. ⏳ **Test admin logins** once database connects

## Support

If database connection still fails after:
- Verifying all environment variables are exact
- Confirming database is running in dashboard
- Running all diagnostic commands

Then contact **Laravel Cloud Support** with:
- Output of `php artisan db:verify-config`
- Deployment logs showing connection failures
- Screenshot of environment variables (hide password)
