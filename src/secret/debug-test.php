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

echo "<h1>Debug Test</h1>";

// Test the passwords
$testPassword = 'Mat634Live';
$livePassword = $env['LIVE_PASSWORD'] ?? '';

echo "<p>Testing password: '$testPassword'</p>";
echo "<p>LIVE_PASSWORD from .env: '$livePassword'</p>";
echo "<p>Strict comparison (===): " . ($testPassword === $livePassword ? "MATCH" : "NO MATCH") . "</p>";
echo "<p>Loose comparison (==): " . ($testPassword == $livePassword ? "MATCH" : "NO MATCH") . "</p>";

// Also test the other password
$testPassword2 = 'Mat634Web';
$webPassword = $env['ACCESS_PASSWORD'] ?? '';

echo "<p>Testing password: '$testPassword2'</p>";
echo "<p>ACCESS_PASSWORD from .env: '$webPassword'</p>";
echo "<p>Strict comparison (===): " . ($testPassword2 === $webPassword ? "MATCH" : "NO MATCH") . "</p>";
echo "<p>Loose comparison (==): " . ($testPassword2 == $webPassword ? "MATCH" : "NO MATCH") . "</p>";

// Show session info
echo "<h2>Session Info</h2>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>Session data: ";
print_r($_SESSION);
echo "</p>";
?>
<a href="/secret/index.php">Back to login</a>