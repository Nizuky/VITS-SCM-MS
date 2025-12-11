#!/usr/bin/env sh
set -euo pipefail

# Default port if not supplied
: ${PORT:=80}

# Template nginx config if template exists
if [ -f /etc/nginx/conf.d/default.template ]; then
  echo "Templating nginx config with PORT=${PORT}"
  export PORT
  # envsubst is provided by gettext-base in the image
  envsubst '${PORT}' < /etc/nginx/conf.d/default.template > /etc/nginx/conf.d/default.conf
fi

# Write Aiven CA if provided via env (raw PEM or base64)
CERT_PATH="/etc/ssl/certs/aiven-ca.pem"
if [ -n "${AIVEN_CA_CERT:-}" ]; then
  mkdir -p "$(dirname "$CERT_PATH")"
  printf '%s\n' "$AIVEN_CA_CERT" > "$CERT_PATH"
  chmod 644 "$CERT_PATH"
  echo "Wrote AIVEN CA to $CERT_PATH"
elif [ -n "${AIVEN_CA_B64:-}" ]; then
  mkdir -p "$(dirname "$CERT_PATH")"
  printf '%s' "$AIVEN_CA_B64" | base64 -d > "$CERT_PATH"
  chmod 644 "$CERT_PATH"
  echo "Wrote AIVEN CA (from base64) to $CERT_PATH"
fi

# Ensure php-fpm runtime dir exists
if [ -d /run/php ] || [ -S /run/php/php-fpm.sock ]; then
  mkdir -p /run/php || true
fi

# Ensure all Laravel storage directories exist (safety net)
mkdir -p /var/www/html/storage/framework/cache/data || true
mkdir -p /var/www/html/storage/framework/sessions || true
mkdir -p /var/www/html/storage/framework/views || true
mkdir -p /var/www/html/storage/logs || true
mkdir -p /var/www/html/storage/app/public || true
mkdir -p /var/www/html/bootstrap/cache || true
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Run Laravel artisan tasks if php is available
if command -v php >/dev/null 2>&1; then
  # CRITICAL: Run startup health check first
  echo "=========================================="
  echo "STARTUP HEALTH CHECK"
  echo "=========================================="
  if [ -f /usr/local/bin/startup-health-check.sh ]; then
    /usr/local/bin/startup-health-check.sh || {
      echo "=========================================="
      echo "HEALTH CHECK FAILED - DATABASE UNREACHABLE"
      echo "=========================================="
      echo "Database connection is not working."
      echo "Application will NOT function properly."
      echo ""
      echo "To diagnose, SSH into container and run:"
      echo "  php artisan env:show-db"
      echo "  /usr/local/bin/test-db-connection.sh"
      echo "  php artisan db:verify-config"
      echo ""
      echo "CONTINUING ANYWAY to allow diagnosis..."
      echo "Check Laravel Cloud environment variables!"
      echo ""
    }
  fi
  echo ""
  
  echo "Clearing all caches..."
  # Clear all caches first to ensure fresh start
  php artisan view:clear || true
  php artisan config:clear || true
  php artisan cache:clear || true
  php artisan route:clear || true
  php artisan event:clear || true
  
  echo "Publishing Livewire assets to public directory..."
  # Ensure Livewire assets are available
  php artisan vendor:publish --tag=livewire:assets --force || true
  
  echo "Optimizing application for production..."
  # Cache all configuration, routes, and views for maximum performance
  php artisan config:cache || true
  php artisan route:cache || true
  php artisan view:cache || true
  
  # Run optimize command for additional performance boost
  php artisan optimize || true

  # Ensure public storage symlink exists (force recreate if broken)
  echo "Creating storage symlink..."
  rm -f /var/www/html/public/storage 2>/dev/null || true
  php artisan storage:link --force || true
  
  # Ensure storage/app/public exists for uploaded files
  mkdir -p /var/www/html/storage/app/public || true
  chown -R www-data:www-data /var/www/html/storage/app/public 2>/dev/null || true
  
  # Additional performance optimizations
  echo "Running performance optimizations..."
  php artisan event:cache || true

  # Wait for database if DB_HOST is set (with timeout)
  if [ -n "${DB_HOST:-}" ] && [ "${DB_HOST}" != "127.0.0.1" ] && [ "${DB_HOST}" != "localhost" ]; then
    echo "Waiting for database connection to ${DB_HOST}:${DB_PORT:-3306}..."
    
    # Use the dedicated wait-for-db script
    if [ -f /usr/local/bin/wait-for-db.sh ]; then
      /usr/local/bin/wait-for-db.sh 60 || {
        echo "ERROR: Database connection failed after waiting"
        echo "Proceeding anyway - migrations will fail if database is unreachable"
      }
    else
      # Fallback to old method if script not found
      MAX_TRIES=60
      TRY=0
      while [ $TRY -lt $MAX_TRIES ]; do
        if php artisan db:show --quiet 2>/dev/null; then
          echo "Database connection successful!"
          break
        fi
        TRY=$((TRY + 1))
        if [ $TRY -eq $MAX_TRIES ]; then
          echo "Warning: Database not ready after $MAX_TRIES attempts. Continuing without migrations."
          break
        fi
        echo "Attempt $TRY/$MAX_TRIES failed. Waiting 2 seconds..."
        sleep 2
      done
    fi
  fi

  # Run migrations: control with env vars
  # RUN_MIGRATIONS=true (default: true)
  # RUN_SEEDERS=true (default: true) - run seeders after migrations
  # MIGRATE_FRESH=true to run migrate:fresh --seed
  RUN_MIGRATIONS=${RUN_MIGRATIONS:-true}
  RUN_SEEDERS=${RUN_SEEDERS:-true}
  MIGRATE_FRESH=${MIGRATE_FRESH:-false}
  
  if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running migrations..."
    if [ "$MIGRATE_FRESH" = "true" ]; then
      php artisan migrate:fresh --seed --force || true
    else
      php artisan migrate --force || {
        echo "ERROR: Migrations failed!"
        echo "Check database connection and permissions."
      }
      
      # Verify migrations completed successfully
      echo "Verifying database tables..."
      php /var/www/html/scripts/verify_migrations.php || {
        echo "WARNING: Some tables are missing. Check migration logs."
      }
      
      # Run seeders separately if not using migrate:fresh
      if [ "$RUN_SEEDERS" = "true" ]; then
        echo "Running database seeders..."
        php artisan db:seed --force || {
          echo "WARNING: Seeders failed. Admin accounts may not exist."
        }
        
        # Reset admin passwords to ensure they work
        echo "Resetting admin passwords to known values..."
        php artisan admin:reset-passwords || {
          echo "WARNING: Password reset failed."
        }
      fi
    fi
  fi
fi

# Finally, exec supervisord to run php-fpm and nginx
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
