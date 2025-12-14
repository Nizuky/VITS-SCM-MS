#!/bin/bash
set -e

# CRITICAL: Convert .env to Unix line endings FIRST (fix Windows CRLF issues)
# This must run before anything else that might source .env
if [ -f .env ]; then
    echo "Converting .env to Unix line endings..."
    # Try multiple methods to ensure conversion happens
    if command -v dos2unix &> /dev/null; then
        dos2unix .env
    elif command -v sed &> /dev/null; then
        sed -i 's/\r$//' .env
    else
        # Fallback: use tr to remove carriage returns
        tr -d '\r' < .env > .env.tmp && mv .env.tmp .env
    fi
    echo ".env line endings converted successfully"
fi

# Clear composer cache and remove vendor to force fresh install
rm -rf vendor
composer clear-cache
composer install --no-dev --prefer-dist --optimize-autoloader

# Publish Livewire assets to public directory
php artisan vendor:publish --tag=livewire:assets --force

# Ensure public/vendor/livewire directory exists and is writable
mkdir -p public/vendor/livewire
chmod -R 775 public/vendor

# Ensure storage directories exist
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p storage/logs
chmod -R 775 storage bootstrap/cache

# Clear npm cache and node_modules to force fresh build
rm -rf node_modules
rm -rf .vite
rm -rf dist
rm -rf node_modules/.vite
npm cache clean --force 2>/dev/null || true

# Explicitly remove terser if it somehow got installed
npm uninstall terser --no-save 2>/dev/null || true

# Log FULL vite.config.js to verify correct configuration
echo "=== FULL VITE CONFIG ==="
cat vite.config.js
echo "========================="

npm ci
npm run build

# Clear all Laravel caches to ensure fresh deployment
echo "Clearing all Laravel caches..."
php artisan view:clear || true
php artisan cache:clear || true
php artisan config:clear || true
php artisan route:clear || true
php artisan event:clear || true

echo "Running optimize (without config cache)..."
php artisan optimize --skip-config || true

# NOTE: Database migrations and seeding are NOT run during build
# They should be run separately after deployment via Laravel Cloud's post-deployment hooks
# or manually via SSH/console to avoid timeout issues during the build process
echo "Build completed successfully!"

