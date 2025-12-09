#!/bin/bash
set -e

# Convert .env to Unix line endings if it exists (fix Windows CRLF issues)
if [ -f .env ]; then
    dos2unix .env 2>/dev/null || sed -i 's/\r$//' .env 2>/dev/null || true
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
npm cache clean --force 2>/dev/null || true

npm ci
npm run build

