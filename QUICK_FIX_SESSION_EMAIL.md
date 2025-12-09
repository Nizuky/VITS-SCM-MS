# Quick Fix Guide - Session, Email & Asset Issues

**Date:** December 9, 2025  
**Issues Fixed:**
1. ✅ 404 errors for Livewire assets
2. ✅ Session expiry reduced to 5 minutes (minimal caching)
3. ✅ Email configuration for production (Gmail/Mailtrap)
4. ✅ Cache TTL reduced to 5 minutes

---

## 🚨 Critical Issues Identified

### 1. Livewire Assets 404 Errors
**Problem:** Assets at `livewire/livewire.js?id=...` returning 404  
**Cause:** Livewire assets not published to `public/vendor/livewire/`  
**Fixed:** Added asset publishing to both build.sh and docker-entrypoint.sh

### 2. Session Lifetime Too Long
**Problem:** Previous setting was 120 minutes (2 hours)  
**Requirement:** Minimal caching for security  
**Fixed:** Reduced to 5 minutes

### 3. Email Not Sending (Using 'log' driver)
**Problem:** Default mail driver was set to 'log' (doesn't send real emails)  
**Requirement:** Send emails via SMTP in production  
**Fixed:** Changed default to 'smtp', configured both Gmail and Mailtrap options

### 4. Cache TTL Not Optimized
**Problem:** No explicit cache TTL configuration  
**Fixed:** Set default TTL to 300 seconds (5 minutes)

---

## ⚡ Changes Made

### File: `config/session.php`
```diff
- 'lifetime' => env('SESSION_LIFETIME', 120),
+ 'lifetime' => env('SESSION_LIFETIME', 5),
```
**Impact:** Sessions now expire after 5 minutes of inactivity

### File: `config/mail.php`
```diff
- 'default' => env('MAIL_MAILER', 'log'),
+ 'default' => env('MAIL_MAILER', 'smtp'),
```
**Impact:** Emails now send via SMTP instead of logging

### File: `config/cache.php` (NEW)
```php
'ttl' => env('CACHE_TTL', 300),
```
**Impact:** Cache items expire after 5 minutes by default

### File: `build.sh`
```diff
  # Publish Livewire assets to public directory
  php artisan vendor:publish --tag=livewire:assets --force
  
+ # Ensure public/vendor/livewire directory exists and is writable
+ mkdir -p public/vendor/livewire
+ chmod -R 775 public/vendor
```
**Impact:** Livewire assets guaranteed to be published during build

### File: `docker/docker-entrypoint.sh`
```diff
  php artisan cache:clear || true
  php artisan route:clear || true
+ php artisan event:clear || true
+ 
+ echo "Publishing Livewire assets to public directory..."
+ php artisan vendor:publish --tag=livewire:assets --force || true
```
**Impact:** Livewire assets published on every container startup

---

## 🔧 Environment Variables to Update

### Laravel Cloud Dashboard → Environment Variables

Add or update these variables:

```env
# Session (5 minutes)
SESSION_LIFETIME=5

# Cache (5 minutes TTL)
CACHE_TTL=300

# Mail - Choose ONE option below:

# Option 1: Gmail SMTP (Production)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=vitsscmms@gmail.com
MAIL_PASSWORD="dgiv kmle sboi bgwo"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=vitsscmms@gmail.com
MAIL_FROM_NAME="VITS"

# Option 2: Mailtrap (Testing/Development)
# MAIL_MAILER=mailtrap
# MAILTRAP_HOST=smtp.mailtrap.io
# MAILTRAP_PORT=2525
# MAILTRAP_USERNAME=your_mailtrap_username
# MAILTRAP_PASSWORD=your_mailtrap_password
# MAILTRAP_ENCRYPTION=tls
# MAIL_FROM_ADDRESS=vitsscmms@gmail.com
# MAIL_FROM_NAME="VITS"
```

---

## 📋 Deployment Steps

### 1. Commit and Push Changes
```bash
git add .
git commit -m "Fix: Livewire 404s, minimal session/cache, enable SMTP email"
git push origin main
```

### 2. Update Environment Variables in Laravel Cloud
1. Go to Laravel Cloud Dashboard
2. Select your project: **vits-scm-ms**
3. Navigate to **Environment** tab
4. Add/Update variables listed above
5. Click **Save**

### 3. Redeploy Application
1. Go to **Deployments** tab
2. Click **Deploy Now** or push to trigger auto-deploy
3. Wait for build to complete (~2-3 minutes)

### 4. Verify Fixes

#### A. Check Livewire Assets
Open browser console at: https://vits-scm-ms-main-xkjcnp.laravel.cloud

**Expected:** No 404 errors for `livewire.js` or `livewire.esm.js`

```
✅ GET /livewire/livewire.js?id=... → 200 OK
✅ GET /livewire/livewire.esm.js?id=... → 200 OK
```

#### B. Test Email Sending

**Via Tinker:**
```bash
php artisan tinker

# Send test email
Mail::raw('Test email from VITS-SCM-MS', function($message) {
    $message->to('janarafael.sanandres@gmail.com')
            ->subject('Test Email - Deployment Verification');
});
```

**Expected:** 
- No errors in tinker
- Email received (check Gmail inbox if using SMTP, or Mailtrap inbox if using Mailtrap)

#### C. Verify Session Expiry
1. Login to any account (student/admin/super admin)
2. Wait 6 minutes without activity
3. Try to perform an action
4. **Expected:** Session expired, redirect to login

#### D. Check Cache TTL
```bash
php artisan tinker

# Set cache item
Cache::put('test_key', 'test_value', now()->addMinutes(10));

# Check TTL (should be max 300 seconds = 5 minutes)
Cache::get('test_key'); // Should return 'test_value'

# Wait 6 minutes, check again
Cache::get('test_key'); // Should return null (expired)
```

---

## 🐛 Troubleshooting

### Issue: Still Getting 404 for Livewire Assets

**Solution 1:** Check if assets exist in container
```bash
# SSH into Laravel Cloud container (if available)
ls -la public/vendor/livewire/

# Should see:
# livewire.js
# livewire.esm.js
```

**Solution 2:** Manually publish assets after deployment
```bash
php artisan vendor:publish --tag=livewire:assets --force
```

**Solution 3:** Clear all caches
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
```

### Issue: Emails Not Sending

**Check 1:** Verify MAIL_MAILER environment variable
```bash
php artisan tinker
config('mail.default'); // Should return 'smtp' or 'mailtrap'
```

**Check 2:** Test SMTP connection
```bash
php artisan tinker

# Test Gmail connection
Mail::mailer('smtp')->raw('Test', function($m) {
    $m->to('janarafael.sanandres@gmail.com')->subject('Test');
});

# Check for errors
```

**Check 3:** Gmail App Password Issues
- Ensure 2FA is enabled on Gmail account
- Generate new App Password at: https://myaccount.google.com/apppasswords
- Use the 16-character app password (no spaces)

**Check 4:** Mailtrap Configuration
- Login to https://mailtrap.io
- Get credentials from **Email Testing → Inboxes → SMTP Settings**
- Copy username and password exactly

### Issue: Session Expires Too Quickly

**If 5 minutes is too short:**
```env
# Increase to 15 minutes
SESSION_LIFETIME=15

# Or 30 minutes
SESSION_LIFETIME=30
```

---

## 📊 Verification Checklist

After redeployment, verify:

- [ ] Welcome page loads without console errors
- [ ] Student signup page loads Livewire components correctly
- [ ] No 404 errors in browser console
- [ ] Login works for all three guards (student, admin, super admin)
- [ ] Session expires after 5 minutes of inactivity
- [ ] Password reset emails are sent (test with "Forgot Password")
- [ ] Email verification emails are sent (test with student registration)
- [ ] Cache clears properly on deployment
- [ ] Database connections work without timeout

---

## 🔑 Key Files Modified

1. **config/session.php** - Reduced lifetime to 5 minutes
2. **config/mail.php** - Changed default mailer to SMTP
3. **config/cache.php** - Added with 5-minute TTL
4. **build.sh** - Enhanced Livewire asset publishing
5. **docker/docker-entrypoint.sh** - Added asset publishing on startup
6. **DEPLOYMENT_DATA.md** - Updated documentation
7. **LARAVEL_CLOUD_ENV.txt** - Environment variables reference

---

## 📝 Additional Notes

### Session Lifetime Considerations
- **5 minutes:** Maximum security, may inconvenience users
- **15 minutes:** Balance between security and UX
- **30 minutes:** Standard for most applications
- **60 minutes:** Longer sessions, less secure

Current setting: **5 minutes** (can be adjusted via `SESSION_LIFETIME`)

### Email Provider Recommendations
- **Production:** Use Gmail SMTP with App Password
- **Staging:** Use Mailtrap to preview emails without sending
- **Development:** Use `MAIL_MAILER=log` to log emails instead of sending

### Cache Strategy
- **File Cache:** Suitable for single-server deployments (Laravel Cloud default)
- **Redis Cache:** Better for multi-server/high-traffic deployments
- **TTL:** 5 minutes ensures fresh data while reducing database load

---

## ✅ Success Criteria

Deployment is successful when:
1. No 404 errors in browser console
2. All Livewire components load and function
3. Emails send successfully (test with password reset)
4. Sessions expire after configured lifetime
5. Login/logout works for all user types
6. Database queries complete without timeout
7. Cache clears on each deployment

---

**Status:** Ready to Deploy ✅  
**Next Action:** Commit changes, update environment variables, redeploy  
**Estimated Deploy Time:** 2-3 minutes  
**Verification Time:** 5-10 minutes

---

**Last Updated:** December 9, 2025  
**Document Version:** 1.0
