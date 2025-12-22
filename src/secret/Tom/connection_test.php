<?php
// Simple database connection test
require_once 'db_connect.php';

if ($pdo === null) {
    echo "Database connection failed.";
} else {
    echo "Database connected successfully!";
    
    // Try a simple query
    try {
        $stmt = $pdo->query("SELECT 1 as test");
        $result = $stmt->fetch();
        if ($result) {
            echo "\nQuery executed successfully.";
        }
    } catch (PDOException $e) {
        echo "\nQuery failed: " . $e->getMessage();
    }
}
?>