<?php
// Database connection script for Tom's section

// Load environment variables
function loadEnv($path) {
    if (!file_exists($path)) {
        return [];
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];
    
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse key=value pairs
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $env[trim($key)] = trim($value);
        }
    }
    
    return $env;
}

// Load .env file
$env = loadEnv(__DIR__ . '/../.env');

// Database configuration
$db_host = $env['DB_HOST'];
$db_name = $env['DB_NAME'];
$db_user = $env['DB_USER'];
$db_pass = $env['DB_PASS'];

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // If connection fails, we'll handle it gracefully in the main script
    $pdo = null;
}
?>