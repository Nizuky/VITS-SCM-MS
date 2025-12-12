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
php artisan cache:clear || true

# Verify welcome view exists before caching
if [ -f "resources/views/welcome.blade.php" ]; then
    echo "✓ welcome.blade.php found"
else
    echo "✗ WARNING: welcome.blade.php not found!"
fi

# Cache configuration (using environment variables, not database)
echo "Caching configuration..."
php artisan config:cache || true

# DO NOT cache views during build - let runtime handle it
# php artisan view:cache

# Optimize autoloader
echo "Optimizing autoloader..."
composer dump-autoload --optimize --no-dev

echo "====================================="
echo "Deploy commands completed successfully"
echo "====================================="
echo ""
echo "NOTE: Database migrations and view caching will happen"
echo "when the container starts via docker-entrypoint.sh"
echo "This ensures the database is ready before running migrations."
