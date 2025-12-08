#!/usr/bin/env bash
set -euo pipefail

# Deployment helper: run on the server at the project root (/var/www/html)
# Usage: sudo -u www-data bash scripts/deploy_helper.sh

echo "Running deployment helper..."

# Ensure correct PHP and composer are in PATH
command -v php >/dev/null 2>&1 || { echo "php not found"; exit 1; }
command -v composer >/dev/null 2>&1 || { echo "composer not found"; exit 1; }

# Install composer dependencies (production)
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts

# Run any needed post-install scripts (if you rely on scripts in composer.json)
composer run-script post-install-cmd --no-interaction || true

# Generate optimized autoload files
composer dump-autoload -o

# Clear and cache framework files
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan event:clear
php artisan optimize

# Ensure storage and bootstrap cache perms for webserver user (adjust as needed)
if id -u www-data >/dev/null 2>&1; then
  chown -R www-data:www-data storage bootstrap/cache || true
fi

echo "Deployment helper finished."
