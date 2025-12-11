#!/usr/bin/env sh
# test-db-from-shell.sh - Manual database connectivity test
# Run this from within your Laravel Cloud container to diagnose connection issues

echo "========================================="
echo "Database Connection Diagnostic Test"
echo "========================================="
echo ""

# Read environment variables
DB_HOST="${DB_HOST:-not_set}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-not_set}"
DB_USERNAME="${DB_USERNAME:-not_set}"

echo "Environment Variables:"
echo "  DB_HOST: $DB_HOST"
echo "  DB_PORT: $DB_PORT"
echo "  DB_DATABASE: $DB_DATABASE"
echo "  DB_USERNAME: $DB_USERNAME"
echo ""

# Test 1: DNS Resolution
echo "Test 1: DNS Resolution"
echo "----------------------------------------"
if [ "$DB_HOST" = "not_set" ]; then
    echo "❌ DB_HOST not set in environment"
    exit 1
fi

echo "Resolving $DB_HOST..."
IP=$(getent hosts "$DB_HOST" | awk '{ print $1 }')
if [ -z "$IP" ]; then
    echo "❌ DNS resolution failed - host not found"
    echo "   This usually means:"
    echo "   - DB_HOST value is incorrect"
    echo "   - DNS server cannot resolve the hostname"
else
    echo "✅ Resolved to: $IP"
fi
echo ""

# Test 2: Network Connectivity (TCP)
echo "Test 2: Network Connectivity (TCP Port $DB_PORT)"
echo "----------------------------------------"
echo "Testing connection to $DB_HOST:$DB_PORT..."

# Try with timeout command if available
if command -v timeout >/dev/null 2>&1; then
    if timeout 5 bash -c "cat < /dev/null > /dev/tcp/$DB_HOST/$DB_PORT" 2>/dev/null; then
        echo "✅ Port $DB_PORT is open and accepting connections"
    else
        echo "❌ Cannot connect to port $DB_PORT (timeout or refused)"
        echo "   This usually means:"
        echo "   - Firewall is blocking port $DB_PORT"
        echo "   - Database server is not running"
        echo "   - Wrong hostname or port"
    fi
else
    # Fallback: try with nc (netcat) if available
    if command -v nc >/dev/null 2>&1; then
        if nc -z -w 5 "$DB_HOST" "$DB_PORT" 2>/dev/null; then
            echo "✅ Port $DB_PORT is open and accepting connections"
        else
            echo "❌ Cannot connect to port $DB_PORT (timeout or refused)"
            echo "   Firewall may be blocking connections"
        fi
    else
        echo "⚠️  Cannot test port (no timeout or nc command available)"
    fi
fi
echo ""

# Test 3: MySQL Client Connection
echo "Test 3: MySQL Client Connection"
echo "----------------------------------------"
if command -v mysql >/dev/null 2>&1; then
    echo "Attempting MySQL connection (will prompt for password)..."
    echo "Command: mysql -h $DB_HOST -P $DB_PORT -u $DB_USERNAME -p $DB_DATABASE -e 'SELECT 1 as test;'"
    echo ""
    
    # Note: This will prompt for password
    mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p "$DB_DATABASE" -e "SELECT 1 as test;" 2>&1
    
    if [ $? -eq 0 ]; then
        echo ""
        echo "✅ MySQL connection successful!"
    else
        echo ""
        echo "❌ MySQL connection failed"
        echo "   Check the error message above for details"
    fi
else
    echo "⚠️  MySQL client not installed - skipping"
fi
echo ""

# Test 4: PHP PDO Connection
echo "Test 4: PHP PDO Connection Test"
echo "----------------------------------------"
if command -v php >/dev/null 2>&1; then
    echo "Testing PHP PDO connection..."
    
    php -r "
    \$host = getenv('DB_HOST');
    \$port = getenv('DB_PORT') ?: '3306';
    \$db = getenv('DB_DATABASE');
    \$user = getenv('DB_USERNAME');
    \$pass = getenv('DB_PASSWORD');
    
    echo \"Connecting to: \$host:\$port\n\";
    echo \"Database: \$db\n\";
    echo \"Username: \$user\n\n\";
    
    \$start = microtime(true);
    
    try {
        \$dsn = \"mysql:host=\$host;port=\$port;dbname=\$db\";
        \$pdo = new PDO(\$dsn, \$user, \$pass, [
            PDO::ATTR_TIMEOUT => 5,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        
        \$elapsed = round((microtime(true) - \$start) * 1000, 2);
        echo \"✅ PDO connection successful in \$elapsed ms\n\";
        
        \$result = \$pdo->query('SELECT VERSION() as version')->fetch();
        echo \"✅ MySQL version: \" . \$result['version'] . \"\n\";
        
        \$result = \$pdo->query('SELECT DATABASE() as db')->fetch();
        echo \"✅ Connected to database: \" . \$result['db'] . \"\n\";
        
        exit(0);
    } catch (PDOException \$e) {
        \$elapsed = round((microtime(true) - \$start) * 1000, 2);
        echo \"❌ PDO connection failed after \$elapsed ms\n\";
        echo \"Error: \" . \$e->getMessage() . \"\n\";
        echo \"Code: \" . \$e->getCode() . \"\n\n\";
        
        if (strpos(\$e->getMessage(), 'timed out') !== false) {
            echo \"This is a CONNECTION TIMEOUT error.\n\";
            echo \"Most likely causes:\n\";
            echo \"  1. Firewall blocking port \$port\n\";
            echo \"  2. Database server not reachable from this host\n\";
            echo \"  3. Wrong DB_HOST value\n\";
        } elseif (strpos(\$e->getMessage(), 'Access denied') !== false) {
            echo \"This is an AUTHENTICATION error.\n\";
            echo \"Most likely causes:\n\";
            echo \"  1. Wrong DB_USERNAME\n\";
            echo \"  2. Wrong DB_PASSWORD\n\";
            echo \"  3. User not allowed to connect from this host\n\";
        }
        
        exit(1);
    }
    "
    
    if [ $? -eq 0 ]; then
        echo ""
        echo "✅ PHP can connect to database successfully"
    else
        echo ""
        echo "❌ PHP cannot connect to database"
    fi
else
    echo "⚠️  PHP not available - skipping"
fi
echo ""

echo "========================================="
echo "Diagnostic Test Complete"
echo "========================================="
echo ""
echo "If tests failed, check:"
echo "1. DB_HOST in environment matches your database hostname exactly"
echo "2. Database server firewall allows connections from this server"
echo "3. Database server is running and healthy"
echo "4. Network route exists between app and database"
echo ""
