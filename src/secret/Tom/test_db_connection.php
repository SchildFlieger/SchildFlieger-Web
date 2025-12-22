<?php
// Test database connection and table
require_once 'db_connect.php';

if ($pdo === null) {
    echo "Database connection failed.";
    exit();
}

echo "Database connection successful!\n";

try {
    // Test if table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'media_likes'");
    if ($stmt->rowCount() > 0) {
        echo "Table 'media_likes' exists.\n";
        
        // Count records
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM media_likes");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Number of records: " . $row['count'] . "\n";
        
        // Show first few records
        $stmt = $pdo->query("SELECT * FROM media_likes LIMIT 5");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            print_r($row);
        }
        
        // Test inserting a record
        echo "\nTesting insert...\n";
        $stmt = $pdo->prepare("INSERT IGNORE INTO media_likes (media_filename, like_count) VALUES (?, 0)");
        $result = $stmt->execute(['test_file.mp4']);
        echo "Insert result: " . ($result ? "Success" : "Failed") . "\n";
        
        // Test updating a record
        echo "\nTesting update...\n";
        $stmt = $pdo->prepare("UPDATE media_likes SET like_count = like_count + 1 WHERE media_filename = ?");
        $result = $stmt->execute(['test_file.mp4']);
        echo "Update result: " . ($result ? "Success" : "Failed") . "\n";
        echo "Rows affected: " . $stmt->rowCount() . "\n";
        
        // Test selecting the updated record
        echo "\nTesting select...\n";
        $stmt = $pdo->prepare("SELECT like_count FROM media_likes WHERE media_filename = ?");
        $stmt->execute(['test_file.mp4']);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            echo "Like count for test_file.mp4: " . $row['like_count'] . "\n";
        } else {
            echo "Record not found\n";
        }
    } else {
        echo "Table 'media_likes' does not exist.\n";
    }
} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>