<?php
/**
 * Session Test Script for Laravel Cloud
 * Access at: https://your-app.laravel.cloud/test-session.php
 * 
 * This script tests if sessions are working properly
 */

// Start the session
session_start();

// Increment counter
if (!isset($_SESSION['counter'])) {
    $_SESSION['counter'] = 1;
} else {
    $_SESSION['counter']++;
}

// Get session info
$sessionId = session_id();
$sessionSavePath = session_save_path();
$sessionDriver = ini_get('session.save_handler');
$cookiePath = ini_get('session.cookie_path');
$cookieDomain = ini_get('session.cookie_domain');
$cookieSecure = ini_get('session.cookie_secure') ? 'Yes' : 'No';
$cookieHttpOnly = ini_get('session.cookie_httponly') ? 'Yes' : 'No';
$cookieSameSite = ini_get('session.cookie_samesite');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Session Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .info { background: #e3f2fd; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .success { background: #c8e6c9; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .error { background: #ffcdd2; padding: 15px; margin: 10px 0; border-radius: 5px; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 3px; overflow-x: auto; }
        h1 { color: #1976d2; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        td:first-child { font-weight: bold; width: 200px; }
    </style>
</head>
<body>
    <h1>Session Test Results</h1>
    
    <div class="<?php echo $_SESSION['counter'] > 1 ? 'success' : 'info'; ?>">
        <h2>Visit Counter: <?php echo $_SESSION['counter']; ?></h2>
        <?php if ($_SESSION['counter'] > 1): ?>
            <p>✓ Sessions are persisting correctly!</p>
        <?php else: ?>
            <p>First visit - refresh the page to test session persistence.</p>
        <?php endif; ?>
    </div>
    
    <h2>Session Configuration</h2>
    <table>
        <tr>
            <td>Session ID</td>
            <td><?php echo $sessionId; ?></td>
        </tr>
        <tr>
            <td>Session Driver</td>
            <td><?php echo $sessionDriver; ?></td>
        </tr>
        <tr>
            <td>Session Save Path</td>
            <td><?php echo $sessionSavePath ?: '(cookies)'; ?></td>
        </tr>
        <tr>
            <td>Cookie Path</td>
            <td><?php echo $cookiePath; ?></td>
        </tr>
        <tr>
            <td>Cookie Domain</td>
            <td><?php echo $cookieDomain ?: '(current domain)'; ?></td>
        </tr>
        <tr>
            <td>Cookie Secure</td>
            <td><?php echo $cookieSecure; ?></td>
        </tr>
        <tr>
            <td>Cookie HttpOnly</td>
            <td><?php echo $cookieHttpOnly; ?></td>
        </tr>
        <tr>
            <td>Cookie SameSite</td>
            <td><?php echo $cookieSameSite ?: 'lax'; ?></td>
        </tr>
        <tr>
            <td>Current URL</td>
            <td><?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?></td>
        </tr>
    </table>
    
    <h2>All Session Data</h2>
    <pre><?php print_r($_SESSION); ?></pre>
    
    <h2>All Cookies</h2>
    <pre><?php print_r($_COOKIE); ?></pre>
    
    <p><a href="?clear=1" style="color: #d32f2f;">Clear Session</a> | <a href="">Refresh Page</a></p>
    
    <?php
    if (isset($_GET['clear'])) {
        session_destroy();
        echo '<div class="info"><p>Session cleared! <a href="">Click here</a> to start fresh.</p></div>';
    }
    ?>
</body>
</html>
