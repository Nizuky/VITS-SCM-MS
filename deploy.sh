#!/bin/bash
set -e

echo "====================================="
echo "Running Laravel Cloud Deploy Commands"
echo "====================================="

# Clear all Laravel caches (without database access)
echo "Clearing application caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan event:clear || true

# Cache configuration (using environment variables, not database)
echo "Caching configuration..."
php artisan config:cache || true

# Optimize autoloader
echo "Optimizing autoloader..."
composer dump-autoload --optimize --no-dev

echo "====================================="
echo "Deploy commands completed successfully"
echo "====================================="
echo ""
echo "NOTE: Database migrations and seeders will run automatically"
echo "when the container starts via docker-entrypoint.sh"
echo "This ensures the database is ready before running migrations."
