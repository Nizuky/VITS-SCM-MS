# Laravel Cloud Session Configuration Fix

## Problem
Getting "Connection refused" error when trying to access sessions table on Laravel Cloud.

## Root Cause
The `SESSION_DRIVER` environment variable on Laravel Cloud is set to `database` but the database connection isn't available during certain requests, or the sessions table doesn't exist.

## Solution

### Step 1: Update Environment Variable on Laravel Cloud

Go to your Laravel Cloud project settings and set:

```
SESSION_DRIVER=cookie
```

**IMPORTANT:** Delete or remove any `SESSION_DRIVER=database` environment variable.

### Step 2: Clear Config Cache

After updating the environment variable, you need to clear the config cache. You can do this by:

1. **Option A - Trigger a new deployment:**
   - Push any small change to your repository
   - Laravel Cloud will redeploy and clear caches automatically

2. **Option B - SSH into container and run:**
   ```bash
   php artisan config:clear
   php artisan config:cache
   ```

### Step 3: Verify

After redeployment, the session driver will use cookies instead of the database, and the error should be resolved.

## Why Cookie Driver?

For Laravel Cloud (stateless deployment), the `cookie` driver is recommended because:
- No database dependency for sessions
- Works across multiple containers/instances
- Simpler and more reliable for cloud deployments
- Automatically encrypted for security

## Alternative: Database Driver (if needed)

If you absolutely need database sessions:

1. Ensure the `sessions` table exists by running:
   ```bash
   php artisan session:table
   php artisan migrate
   ```

2. Set environment variable:
   ```
   SESSION_DRIVER=database
   ```

3. Ensure database connection is working before sessions are accessed

But for most use cases, **cookie driver is recommended for Laravel Cloud**.
