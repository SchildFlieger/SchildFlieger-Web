<?php
session_start();

// Simple function to parse .env file
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
$env = loadEnv(__DIR__ . '/.env');

echo "<h1>Simple Password Test</h1>";

// Test the passwords
$testPassword = 'Mat634Live';
$livePassword = $env['LIVE_PASSWORD'] ?? '';

echo "<p>Testing password: '$testPassword'</p>";
echo "<p>LIVE_PASSWORD from .env: '$livePassword'</p>";
echo "<p>Comparison result: " . ($testPassword === $livePassword ? "MATCH - Should redirect to /secret/live/index.php" : "NO MATCH") . "</p>";

$testPassword2 = 'Mat634Web';
$webPassword = $env['ACCESS_PASSWORD'] ?? '';

echo "<p>Testing password: '$testPassword2'</p>";
echo "<p>ACCESS_PASSWORD from .env: '$webPassword'</p>";
echo "<p>Comparison result: " . ($testPassword2 === $webPassword ? "MATCH - Should redirect to /secret/go/index.php" : "NO MATCH") . "</p>";
?>