# VITS-SCM-MS Deployment Data & Seeded Information

**Created:** December 9, 2025  
**Deployment Platform:** Laravel Cloud (Asia Pacific - Singapore)  
**Application URL:** https://vits-scm-ms-main-xkjcnp.laravel.cloud

---

## 📋 Table of Contents
1. [Environment Configuration](#environment-configuration)
2. [Database Configuration](#database-configuration)
3. [Seeded Accounts](#seeded-accounts)
4. [Session & Security Settings](#session--security-settings)
5. [Mail Configuration](#mail-configuration)
6. [Build & Deployment Process](#build--deployment-process)
7. [Database Seeders](#database-seeders)

---

## 🔧 Environment Configuration

### Application Settings
```env
APP_NAME="VITS-SCM-MS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://vits-scm-ms-main-xkjcnp.laravel.cloud
APP_KEY=base64:81xdvokINT53F+fk6pW/4CID4yRF9WV1+eefG0HU1FY=
APP_TIMEZONE=Asia/Manila
```

### Application Environment
- **Environment:** Production
- **Debug Mode:** Disabled
- **PHP Version:** 8.2+
- **Laravel Version:** 11.47.0
- **Livewire Version:** 3.7.1

---

## 🗄️ Database Configuration

### MySQL 8 Database Cluster
```env
DB_CONNECTION=mysql
DB_HOST=db-a08bd7aa-1588-4461-97c3-bde906d54852.ap-southeast-1.public.db.laravel.cloud
DB_PORT=3306
DB_DATABASE=Cloud - vits_scm_ms
DB_USERNAME=jsthylbkmmff6jnv
DB_PASSWORD=QXkqWoO9xir8FToisMWb
DB_TIMEOUT=5
```

**⚠️ CRITICAL NOTES:**
- Database name contains a space: `Cloud - vits_scm_ms`
- Host ends in `...7aa...` NOT `...7ae...`
- Username has lowercase 'l' and 'f': `jsthylbkmmff6jnv`
- Password has capital 'W': `QXkqWoO9xir8FToisMWb`

### Database Features
- **Cluster Type:** Laravel Cloud MySQL 8 Managed Database
- **Region:** Asia Pacific (Singapore)
- **Connection Timeout:** 5 seconds (optimized for fast failure)
- **Persistent Connections:** Disabled
- **SSL/TLS:** Enabled (via DB_SSL_CA if needed)
- **Character Set:** utf8mb4
- **Collation:** utf8mb4_unicode_ci

### Database Tables (Migrations)
The application includes the following database tables:
- `users` - Student accounts
- `super_admins` - Super administrator accounts
- `admin_users` - Regular administrator accounts
- `social_contracts` - Social contract templates
- `social_contract_records` - Student contract submissions
- `verifications` - Verification records
- `approvals` - Approval workflow records
- `archives` - Archived records
- `transaction_logs` - Activity/transaction logs
- `superadmin_activity_logs` - Super admin activity tracking
- `sessions` - User session data (cookie-based)
- `cache` - Application cache
- `cache_locks` - Cache locking mechanism
- `jobs` - Queued jobs
- `job_batches` - Job batch tracking
- `failed_jobs` - Failed job records
- `password_reset_tokens` - Password reset tokens
- `password_resets` - Legacy password resets

---

## 👥 Seeded Accounts

### Super Administrator Account
**Purpose:** System-wide administrative access, manages all aspects of the platform

```
Username/Name: adminAlex
Email: janarafael.sanandres@gmail.com
Password: softdev12345
Guard: superadmin
Email Verified: Yes (auto-verified on seed)
```

**Login URL:** https://vits-scm-ms-main-xkjcnp.laravel.cloud/super-admin/login

**Capabilities:**
- View all student submissions across the platform
- Verify, approve, or reject any submissions
- Manage student accounts (view, edit, delete)
- Manage support tickets
- View activity calendar and logs
- Access super admin dashboard with comprehensive statistics
- Change super admin credentials

### Regular Administrator Accounts
**Purpose:** Department/unit-level administrative access

**Admin 1:**
```
Username/Name: admin1
Email: admin1@scms.test
Password: raf12345
Guard: admin
Email Verified: Yes (auto-verified on seed)
```

**Admin 2:**
```
Username/Name: admin2
Email: admin2@scms.test
Password: dek12345
Guard: admin
Email Verified: Yes (auto-verified on seed)
```

**Login URL:** https://vits-scm-ms-main-xkjcnp.laravel.cloud/admin/login

**Capabilities:**
- View and manage student submissions for their department
- Verify or reject submissions
- View activity calendar and details
- Access admin dashboard with department statistics
- Change admin credentials

### Environment Variables for Seeding (Optional Override)
The seeders use these defaults but can be overridden via environment variables:

**Super Admin:**
```env
SUPERADMIN_EMAIL=janarafael.sanandres@gmail.com
SUPERADMIN_PASSWORD=softdev12345
SUPERADMIN_NAME="adminAlex"
```

---

## 🔐 Session & Security Settings

### Session Configuration
```env
SESSION_DRIVER=cookie
SESSION_LIFETIME=5
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_DOMAIN=.laravel.cloud
SESSION_SAME_SITE=lax
SESSION_HTTP_ONLY=true
```

### Session Features
- **Driver:** Cookie-based (optimized for Laravel Cloud distributed environment)
- **Lifetime:** 5 minutes (minimal caching for security)
- **Encryption:** Enabled (encrypted session data)
- **Secure Cookies:** HTTPS only
- **Domain:** `.laravel.cloud` (allows subdomain access)
- **SameSite:** Lax (CSRF protection while allowing external navigation)
- **HttpOnly:** Enabled (JavaScript cannot access cookies)

### CSRF Protection
- **Token Refresh:** Automatic via `axios` and `fetch` interceptors
- **Livewire Integration:** Auto-configured with CSRF token headers
- **Cookie Name:** `XSRF-TOKEN`
- **Session Token Name:** `_token`

### Authentication Guards
The application uses multiple authentication guards:

1. **Web Guard** (`auth:web`)
   - For student accounts
   - Provider: users table
   - Session-based authentication

2. **Admin Guard** (`auth:admin`)
   - For regular administrator accounts
   - Provider: admin_users table
   - Session-based authentication (no remember me)

3. **SuperAdmin Guard** (`auth:superadmin`)
   - For super administrator accounts
   - Provider: super_admins table
   - Session-based authentication (no remember me)

### Rate Limiting
- **Student Login:** 10 attempts per minute per IP
- **Admin Login:** 5 attempts per minute per IP
- **Super Admin Login:** Custom rate limiting in controller
- **API Endpoints:** 60 requests per minute (authenticated users)
- **Password Reset:** 3 attempts per minute per IP

---

## 📧 Mail Configuration

### Production SMTP Settings (Gmail)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=vitsscmms@gmail.com
MAIL_PASSWORD="dgiv kmle sboi bgwo"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=vitsscmms@gmail.com
MAIL_FROM_NAME="VITS"
```

### Development/Testing Settings (Mailtrap)
```env
MAIL_MAILER=mailtrap
MAILTRAP_HOST=smtp.mailtrap.io
MAILTRAP_PORT=2525
MAILTRAP_USERNAME=your_mailtrap_username
MAILTRAP_PASSWORD=your_mailtrap_password
MAILTRAP_ENCRYPTION=tls
MAIL_FROM_ADDRESS=vitsscmms@gmail.com
MAIL_FROM_NAME="VITS"
```

### Mail Features
- **Production Provider:** Gmail SMTP
- **Testing Provider:** Mailtrap (for development/staging)
- **Encryption:** TLS (port 587 for Gmail, 2525 for Mailtrap)
- **From Address:** vitsscmms@gmail.com
- **From Name:** VITS
- **Timeout:** Default (no custom timeout)

### Email Use Cases
1. **Student Email Verification** - Sent when students register
2. **Password Reset Links** - For all user types (students, admins, super admins)
3. **Admin Notifications** - Password change confirmations
4. **System Notifications** - Critical system alerts

### Testing Emails
To test email sending in production, ensure:
- `MAIL_MAILER=smtp` (for Gmail) or `MAIL_MAILER=mailtrap` (for testing)
- Gmail App Password is valid (if using Gmail)
- Mailtrap credentials are correct (if using Mailtrap)
- From address matches authenticated account

---

## 🚀 Build & Deployment Process

### Build Command
```bash
bash build.sh
```

**Build Script Actions:**
1. Clear all Laravel caches (config, route, view, event)
2. Install Composer dependencies (`--no-dev --optimize-autoloader`)
3. Install npm dependencies (`npm ci`)
4. Build frontend assets (`npm run build`)
5. Publish Livewire assets to `public/vendor/livewire/`
6. Optimize for production (cached configs)

### Deploy Command
```bash
echo "Migrations and seeders handled by docker-entrypoint.sh on container startup"
```

**Why?** Database operations are deferred to container startup to ensure:
- Database cluster is fully available
- No connection timeouts during deploy phase
- Proper error handling and retry logic

### Container Startup Process (docker-entrypoint.sh)

**Sequence:**
1. **Wait for database** (60 seconds with retries)
   - Checks database connectivity with `php artisan db:show`
   - Retries every 3 seconds until connected or timeout

2. **Clear caches** (if enabled)
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

3. **Run migrations** (if `RUN_MIGRATIONS=true`)
   ```bash
   php artisan migrate --force
   ```
   - Preserves existing data
   - Only adds new tables/columns as needed

4. **Run seeders** (if `RUN_SEEDERS=true`)
   ```bash
   php artisan db:seed --force
   ```
   - Creates super admin account
   - Creates regular admin account
   - Seeds any dump data from `database/seeders/dumps/`

5. **Start services**
   - PHP-FPM (FastCGI Process Manager)
   - Nginx (Web server)
   - Supervisor (Process manager)

### Environment Variables for Deployment
```env
RUN_MIGRATIONS=true
RUN_SEEDERS=true
MIGRATE_FRESH=false
```

---

## 🗂️ Cache Configuration

### Cache Settings
```env
CACHE_STORE=file
CACHE_TTL=300
CACHE_PREFIX=vits_scm_ms_cache_
```

### Cache Features
- **Driver:** File-based (suitable for single-server deployments)
- **TTL:** 300 seconds (5 minutes) for minimal caching
- **Path:** `storage/framework/cache/data`
- **Prefix:** Application-specific to prevent collisions

### Cache Clearing Strategy
The application automatically clears all caches on deployment:
- Config cache (`php artisan config:clear`)
- Route cache (`php artisan route:clear`)
- View cache (`php artisan view:clear`)
- Application cache (`php artisan cache:clear`)
- Event cache (`php artisan event:clear`)

After clearing, it rebuilds optimized caches:
- Config cache (`php artisan config:cache`)
- Route cache (`php artisan route:cache`)

### Available Cache Stores
- **file** (default) - Local filesystem cache
- **array** - In-memory cache (request lifetime only)
- **database** - Database-backed cache
- **redis** - Redis-backed cache (requires Redis setup)

---

## 🌱 Database Seeders

### Seeder Execution Order
Defined in `database/seeders/DatabaseSeeder.php`:

1. **Dump Seeders** (auto-discovered from `database/seeders/dumps/`)
   - `ApprovalsTableSeeder` - Approval records
   - `ArchivesTableSeeder` - Archived data
   - `SocialContractsTableSeeder` - Contract templates
   - `SocialContractRecordsTableSeeder` - Student submissions
   - `TransactionLogsTableSeeder` - Activity logs
   - `SuperadminActivityLogsTableSeeder` - Admin activity
   - `UsersTableSeeder` - Student accounts
   - `VerificationsTableSeeder` - Verification records
   - `SuperAdminsTableSeeder` - Historical super admin data
   
   **Skipped Tables:** Cache, Sessions, Jobs, Password Resets (system tables)

2. **Manual Seeders**
   - `SuperAdminSeeder` - Creates/updates super admin account
   - `AdminUserSeeder` - Creates/updates regular admin account

### SuperAdminSeeder Details
**File:** `database/seeders/SuperAdminSeeder.php`

```php
// Uses updateOrCreate to prevent duplicates
SuperAdmin::updateOrCreate([
    'email' => env('SUPERADMIN_EMAIL', 'janarafael.sanandres@gmail.com'),
], [
    'name' => env('SUPERADMIN_NAME', 'Super Admin'),
    'password' => Hash::make(env('SUPERADMIN_PASSWORD', 'softdev2025')),
    'email_verified_at' => now(),
]);
```

**Features:**
- Safe for re-running (idempotent)
- Environment variable driven
- Production safety check (skips if env vars missing)
- Auto-verifies email

### AdminUserSeeder Details
**File:** `database/seeders/AdminUserSeeder.php`

```php
// Uses updateOrCreate to prevent duplicates
AdminUser::updateOrCreate([
    'email' => env('ADMIN_EMAIL', 'admin@scms.test'),
], [
    'name' => env('ADMIN_NAME', 'Site Admin'),
    'password' => Hash::make(env('ADMIN_PASSWORD', 'admin123456')),
    'email_verified_at' => now(),
]);
```

**Features:**
- Safe for re-running (idempotent)
- Environment variable driven
- Production safety check (skips if env vars missing)
- Auto-verifies email

---

## 🛠️ Troubleshooting Database Connections

### Connection Timeout Fixes Applied

**Problem:** Database queries timing out during page load (SQLSTATE[HY000] [2002])

**Solutions Implemented:**

1. **Fast Timeout Configuration** (`config/database.php`)
   ```php
   'options' => [
       PDO::ATTR_TIMEOUT => env('DB_TIMEOUT', 5),  // 5 second timeout
       PDO::ATTR_PERSISTENT => false,              // No persistent connections
   ]
   ```

2. **Graceful Error Handling** (Login Routes)
   - Super admin login: Wraps `SuperAdmin::first()` in try-catch
   - Admin login: Wraps `AdminUser::first()` in try-catch
   - Falls back to `null` if database unavailable
   - Logs warning instead of crashing

3. **Database Wait Logic** (docker-entrypoint.sh)
   - Waits up to 60 seconds for database availability
   - Checks connectivity every 3 seconds
   - Prevents startup failures due to slow database cluster

### Error Handling Strategy
- **Page Load:** Non-blocking database queries with try-catch
- **API Calls:** Return proper error responses (500 with message)
- **Background Jobs:** Queue retry logic with exponential backoff
- **Migrations:** Handled during container startup with proper error logging

---

## 📊 Application Statistics

### Performance Optimizations
- **Asset Building:** Vite with code splitting and tree shaking
- **PHP Optimization:** OPcache enabled, autoloader optimized
- **Database:** Indexed foreign keys, optimized queries
- **Caching:** File-based cache (Redis available if needed)
- **Session:** Cookie-based (no database overhead)

### Security Measures
- **HTTPS Only:** All cookies marked as secure
- **CSRF Protection:** Token-based with automatic refresh
- **Password Hashing:** Bcrypt with Laravel defaults
- **Rate Limiting:** Applied to all authentication endpoints
- **Session Encryption:** All session data encrypted
- **SQL Injection Prevention:** Eloquent ORM with parameter binding
- **XSS Protection:** Blade template escaping
- **CORS:** Configured for Laravel Cloud domain

---

## 📝 Additional Notes

### Important Files
- **Build Script:** `build.sh` - Production build process
- **Entrypoint:** `docker/docker-entrypoint.sh` - Container startup logic
- **Docker Image:** Multi-stage build (Node + Composer + PHP-FPM)
- **Nginx Config:** `docker/nginx/default.conf` - Web server configuration
- **Supervisor Config:** `docker/supervisord/supervisord.conf` - Process management

### Deployment Checklist
- ✅ Environment variables configured in Laravel Cloud dashboard
- ✅ Database cluster accessible and healthy
- ✅ Build script completes successfully
- ✅ Livewire assets published to public directory
- ✅ Migrations run without errors
- ✅ Seeders create admin accounts
- ✅ Session configuration optimized for distributed environment
- ✅ CSRF protection working (no 419 errors)
- ✅ Login pages load without database timeouts
- ✅ All three guard types authenticate properly

### Support & Maintenance
- **Primary Admin:** janarafael.sanandres@gmail.com
- **System Email:** vitsscmms@gmail.com
- **Repository:** https://github.com/Nizuky/VITS-SCM-MS
- **Platform:** Laravel Cloud (managed by Laravel)

---

**Last Updated:** December 9, 2025  
**Document Version:** 1.0  
**Status:** Production Deployment Ready ✅
