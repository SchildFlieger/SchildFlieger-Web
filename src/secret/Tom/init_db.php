<?php
// Script to initialize the database tables for Tom's section
require_once 'db_connect.php';

if ($pdo === null) {
    die("Database connection failed. Cannot initialize database.");
}

try {
    // Check if setup.sql file exists
    $setupSqlFile = __DIR__ . '/setup.sql';
    if (!file_exists($setupSqlFile)) {
        throw new Exception("Setup SQL file not found: $setupSqlFile");
    }
    
    // Read the SQL file
    $sql = file_get_contents($setupSqlFile);
    if ($sql === false) {
        throw new Exception("Failed to read setup SQL file");
    }
    
    // Execute the SQL commands
    $pdo->exec($sql);
    
    echo "Database initialized successfully!";
} catch(Exception $e) {
    echo "Error initializing database: " . $e->getMessage();
}
?>