# Deployment Guide (preserve existing database data)

This document describes safe steps to prepare and deploy this Laravel application to a production server while preserving the current database contents. **It does not run seeders** and avoids destructive database commands. Always backup the database before making any changes.

Prerequisites
- A production server (Linux recommended) with PHP 8.x, Composer, Node.js, npm/yarn, and access to the project's repo.
- A database server reachable from the host.
- Proper file ownership/permissions for the web server user.

Important principle
- Do NOT run `php artisan migrate:fresh`, `php artisan migrate:refresh`, or any seeder commands that reset data.
- If schema updates are required, run `php artisan migrate --force` (this updates schema and preserves data). Back up before migrating.

Quick safe deployment steps (Linux / Bash)

1. Backup the database (example for MySQL):

```bash
mysqldump -h DB_HOST -P DB_PORT -u DB_USER -p'DB_PASSWORD' DB_DATABASE > /tmp/db-backup-$(date +%F-%T).sql
```

2. Pull latest code on server (or deploy from CI):

```bash
git pull origin main
```

3. Install PHP dependencies (production mode):

```bash
composer install --no-dev --optimize-autoloader --prefer-dist
```

4. Install JS dependencies and build assets:

```bash
npm ci
npm run build
```

5. Environment and keys

- Ensure `.env` is set to production values. Do not overwrite the `.env` file from a template unless you know what you're doing.
- If an `APP_KEY` is missing, generate one with `php artisan key:generate` (this only affects encryption keys, not DB data).

6. (Optional) Run migrations only if schema changed (preserve data):

```bash
php artisan migrate --force
```

7. Create storage symlink (if not present):

```bash
php artisan storage:link
```

8. Cache and optimize framework files:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

9. Restart queue workers and other services if used:

```bash
php artisan queue:restart
sudo systemctl restart php-fpm.service
sudo supervisorctl restart all
```

Windows / PowerShell notes
- On Windows servers, the same steps apply, but use PowerShell commands. A helpful `scripts/deploy.ps1` is included that automates safe build steps and optionally runs migrations when you explicitly allow it.

Rollback and backups
- Always keep a recent DB dump before applying schema changes.
- Keep backups of `storage` and `public/storage` if users upload files.

CI / Automation tips
- In CI pipelines use environment-specific `.env` values injected by the CI provider.
- Build assets in CI and upload them to the server or artifact store, or run asset build on the server.
- Use zero-downtime deployment strategy (e.g., symlink releases) if you have high availability needs.

Security & permissions
- Ensure `storage/` and `bootstrap/cache/` are writable by the web server user.
- Remove `.env` and other sensitive files from public web roots.

What this repo's `scripts/deploy.ps1` does
- Installs PHP & JS dependencies, builds assets, runs caching commands.
- Does NOT run seeders.
- Will run `php artisan migrate --force` only if you pass `-RunMigrations` to the script.
- Can attempt a DB backup if `-BackupDB` is passed and `mysqldump` is available.

If you want, I can adapt this doc for automated CI/CD (GitHub Actions, Azure DevOps, etc.) or craft a Linux `deploy.sh` and systemd/supervisor configs.
