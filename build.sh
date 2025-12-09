#!/bin/bash
set -e

# Clear composer cache and remove vendor to force fresh install
rm -rf vendor
composer clear-cache
composer install --no-dev --prefer-dist --optimize-autoloader

# Publish Livewire assets to public directory
php artisan vendor:publish --tag=livewire:assets --force

# Ensure storage directories exist
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p storage/logs
chmod -R 775 storage bootstrap/cache

npm ci
npm run build

