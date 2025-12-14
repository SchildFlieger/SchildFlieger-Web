<?php
// Simple test to check if .env file is being loaded correctly

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

echo "<h1>.env File Test</h1>";
echo "<p>Current directory: " . __DIR__ . "</p>";
echo "<p>.env file path: " . __DIR__ . '/.env' . "</p>";
echo "<p>File exists: " . (file_exists(__DIR__ . '/.env') ? "Yes" : "No") . "</p>";

echo "<h2>Environment Variables:</h2>";
echo "<pre>";
print_r($env);
echo "</pre>";

echo "<h2>Password Values:</h2>";
echo "<p>ACCESS_PASSWORD: '" . ($env['ACCESS_PASSWORD'] ?? 'NOT SET') . "'</p>";
echo "<p>LIVE_PASSWORD: '" . ($env['LIVE_PASSWORD'] ?? 'NOT SET') . "'</p>";

echo "<h2>Test Comparisons:</h2>";
echo "<p>'Mat634Web' === ACCESS_PASSWORD: " . (('Mat634Web' === ($env['ACCESS_PASSWORD'] ?? '')) ? "TRUE" : "FALSE") . "</p>";
echo "<p>'Mat634Live' === LIVE_PASSWORD: " . (('Mat634Live' === ($env['LIVE_PASSWORD'] ?? '')) ? "TRUE" : "FALSE") . "</p>";
?>
<br><br>
<a href="/secret/index.php">Back to login</a>