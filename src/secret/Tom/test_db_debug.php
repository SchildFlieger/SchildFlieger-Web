<?php
// Debug script to test database connectivity and table structure
require_once 'db_connect.php';

if ($pdo === null) {
    die("Database connection failed.");
}

echo "<h1>Database Debug Information</h1>";

try {
    // Test connection
    echo "<h2>Connection Test</h2>";
    echo "<p>Connected successfully to database: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "</p>";
    
    // Check if table exists
    echo "<h2>Table Check</h2>";
    $stmt = $pdo->query("SHOW TABLES LIKE 'media_likes'");
    $tableExists = $stmt->fetch();
    
    if ($tableExists) {
        echo "<p>Table 'media_likes' exists.</p>";
        
        // Show table structure
        echo "<h3>Table Structure</h3>";
        $stmt = $pdo->query("DESCRIBE media_likes");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($columns);
        echo "</pre>";
        
        // Show current data
        echo "<h3>Current Data</h3>";
        $stmt = $pdo->query("SELECT * FROM media_likes ORDER BY media_filename");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        
        // Test inserting a record
        echo "<h3>Insert Test</h3>";
        $stmt = $pdo->prepare("INSERT INTO media_likes (media_filename, like_count) VALUES (?, ?) ON DUPLICATE KEY UPDATE like_count = like_count + 1");
        $result = $stmt->execute(['test_file.mp4', 1]);
        echo "<p>Insert result: " . ($result ? "Success" : "Failed") . "</p>";
        echo "<p>Rows affected: " . $stmt->rowCount() . "</p>";
        
        // Test updating a record
        echo "<h3>Update Test</h3>";
        $stmt = $pdo->prepare("UPDATE media_likes SET like_count = like_count + 1 WHERE media_filename = ?");
        $result = $stmt->execute(['test_file.mp4']);
        echo "<p>Update result: " . ($result ? "Success" : "Failed") . "</p>";
        echo "<p>Rows affected: " . $stmt->rowCount() . "</p>";
        
        // Show updated data
        echo "<h3>Updated Data</h3>";
        $stmt = $pdo->query("SELECT * FROM media_likes WHERE media_filename = 'test_file.mp4'");
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        
        // Clean up test record
        $stmt = $pdo->prepare("DELETE FROM media_likes WHERE media_filename = ?");
        $stmt->execute(['test_file.mp4']);
        echo "<p>Cleaned up test record.</p>";
    } else {
        echo "<p>Table 'media_likes' does not exist.</p>";
    }
} catch(PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>