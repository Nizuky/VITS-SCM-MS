#!/usr/bin/env sh
# startup-health-check.sh - Fast health check on startup
# Exits immediately if critical issues are found

set -e

echo "Running startup health check..."

# Check 1: Environment variables
echo "✓ Checking environment variables..."
if [ -z "${DB_HOST:-}" ]; then
    echo "❌ CRITICAL: DB_HOST not set"
    exit 1
fi
if [ -z "${DB_DATABASE:-}" ]; then
    echo "❌ CRITICAL: DB_DATABASE not set"
    exit 1
fi
if [ -z "${DB_USERNAME:-}" ]; then
    echo "❌ CRITICAL: DB_USERNAME not set"
    exit 1
fi
if [ -z "${DB_PASSWORD:-}" ]; then
    echo "❌ CRITICAL: DB_PASSWORD not set"
    exit 1
fi
echo "  DB_HOST: ${DB_HOST}"
echo "  DB_DATABASE: ${DB_DATABASE}"
echo "  DB_USERNAME: ${DB_USERNAME}"

# Check 2: Quick DNS test
echo "✓ Checking DNS resolution..."
if ! getent hosts "$DB_HOST" >/dev/null 2>&1; then
    echo "❌ CRITICAL: Cannot resolve DB_HOST: $DB_HOST"
    echo "   This hostname does not exist or DNS is not working"
    exit 1
fi
echo "  Hostname resolves successfully"

# Check 3: Quick port check (if nc available)
if command -v nc >/dev/null 2>&1; then
    echo "✓ Checking port connectivity..."
    if ! nc -z -w 3 "$DB_HOST" "${DB_PORT:-3306}" 2>/dev/null; then
        echo "❌ CRITICAL: Cannot connect to ${DB_HOST}:${DB_PORT:-3306}"
        echo "   Firewall may be blocking connections"
        echo "   OR database server is not running"
        exit 1
    fi
    echo "  Port ${DB_PORT:-3306} is reachable"
fi

# Check 4: Quick PHP PDO test
echo "✓ Checking PHP PDO connection..."
php -r "
set_time_limit(10);
\$start = microtime(true);
try {
    \$pdo = new PDO(
        'mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: '3306') . ';dbname=' . getenv('DB_DATABASE'),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD'),
        [
            PDO::ATTR_TIMEOUT => 5,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
    \$elapsed = round((microtime(true) - \$start) * 1000, 2);
    echo \"  Connection successful in \$elapsed ms\n\";
    exit(0);
} catch (PDOException \$e) {
    \$elapsed = round((microtime(true) - \$start) * 1000, 2);
    echo \"❌ CRITICAL: Database connection failed after \$elapsed ms\n\";
    echo \"Error: \" . \$e->getMessage() . \"\n\";
    exit(1);
}
"

if [ $? -ne 0 ]; then
    echo ""
    echo "=========================================="
    echo "STARTUP HEALTH CHECK FAILED"
    echo "=========================================="
    echo "Database connection is not working."
    echo ""
    echo "Run this command to diagnose:"
    echo "  /usr/local/bin/test-db-connection.sh"
    echo ""
    echo "Or run Laravel's diagnostic:"
    echo "  php artisan db:verify-config"
    echo ""
    exit 1
fi

echo ""
echo "✅ All health checks passed!"
echo ""
exit 0
