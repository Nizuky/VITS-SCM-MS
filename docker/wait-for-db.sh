#!/usr/bin/env sh
# wait-for-db.sh - Wait for database to be ready before proceeding
# Usage: ./wait-for-db.sh [max_attempts]

set -e

MAX_ATTEMPTS="${1:-60}"  # Default 60 attempts (2 minutes with 2s delay)
ATTEMPT=0

echo "Waiting for database connection..."

while [ $ATTEMPT -lt $MAX_ATTEMPTS ]; do
  ATTEMPT=$((ATTEMPT + 1))
  
  # Try to connect using PHP artisan db:show (most reliable)
  if php artisan db:show --quiet 2>/dev/null; then
    echo "✓ Database connection successful after $ATTEMPT attempts!"
    exit 0
  fi
  
  # Fallback: Try basic PDO connection test
  if php -r "
    try {
      \$host = getenv('DB_HOST') ?: '127.0.0.1';
      \$port = getenv('DB_PORT') ?: '3306';
      \$db = getenv('DB_DATABASE') ?: 'forge';
      \$user = getenv('DB_USERNAME') ?: 'forge';
      \$pass = getenv('DB_PASSWORD') ?: '';
      
      \$dsn = \"mysql:host=\$host;port=\$port;dbname=\$db\";
      \$pdo = new PDO(\$dsn, \$user, \$pass, [
        PDO::ATTR_TIMEOUT => 5,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      ]);
      
      \$pdo->query('SELECT 1');
      exit(0);
    } catch (Exception \$e) {
      exit(1);
    }
  " 2>/dev/null; then
    echo "✓ Database PDO connection successful after $ATTEMPT attempts!"
    exit 0
  fi
  
  if [ $ATTEMPT -eq $MAX_ATTEMPTS ]; then
    echo "✗ Database connection failed after $MAX_ATTEMPTS attempts"
    echo "Please check:"
    echo "  1. DB_HOST is correct: ${DB_HOST:-not set}"
    echo "  2. DB_PORT is correct: ${DB_PORT:-3306}"
    echo "  3. Database server is running"
    echo "  4. Firewall allows connections from this server"
    echo "  5. Database credentials are correct"
    exit 1
  fi
  
  echo "  Attempt $ATTEMPT/$MAX_ATTEMPTS failed. Retrying in 2 seconds..."
  sleep 2
done

exit 1
