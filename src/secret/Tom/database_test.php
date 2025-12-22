<?php
// Test script to verify database connectivity and functionality
require_once 'db_connect.php';

if ($pdo === null) {
    die("Database connection failed.");
}

echo "<h1>Database Test</h1>";

try {
    // Test 1: Check if table exists
    echo "<h2>Test 1: Checking if media_likes table exists</h2>";
    $stmt = $pdo->query("SHOW TABLES LIKE 'media_likes'");
    $tableExists = $stmt->fetch();
    
    if ($tableExists) {
        echo "<p style='color: green;'>✓ Table 'media_likes' exists</p>";
        
        // Test 2: Check table structure
        echo "<h2>Test 2: Checking table structure</h2>";
        $stmt = $pdo->query("DESCRIBE media_likes");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $hasFilename = false;
        $hasLikeCount = false;
        
        foreach ($columns as $column) {
            if ($column['Field'] == 'media_filename') {
                $hasFilename = true;
            }
            if ($column['Field'] == 'like_count') {
                $hasLikeCount = true;
            }
        }
        
        if ($hasFilename && $hasLikeCount) {
            echo "<p style='color: green;'>✓ Table has required columns</p>";
        } else {
            echo "<p style='color: red;'>✗ Table missing required columns</p>";
            echo "<pre>" . print_r($columns, true) . "</pre>";
        }
        
        // Test 3: Insert test data
        echo "<h2>Test 3: Testing data insertion</h2>";
        $testFilename = "database_test_file.mp4";
        
        // Clean up any existing test data
        $stmt = $pdo->prepare("DELETE FROM media_likes WHERE media_filename = ?");
        $stmt->execute([$testFilename]);
        
        // Insert test data
        $stmt = $pdo->prepare("INSERT INTO media_likes (media_filename, like_count) VALUES (?, 0)");
        $result = $stmt->execute([$testFilename]);
        
        if ($result) {
            echo "<p style='color: green;'>✓ Test data inserted successfully</p>";
            
            // Test 4: Update test data
            echo "<h2>Test 4: Testing data update</h2>";
            $stmt = $pdo->prepare("UPDATE media_likes SET like_count = like_count + 1 WHERE media_filename = ?");
            $result = $stmt->execute([$testFilename]);
            
            if ($result) {
                echo "<p style='color: green;'>✓ Test data updated successfully</p>";
                
                // Test 5: Retrieve updated data
                echo "<h2>Test 5: Testing data retrieval</h2>";
                $stmt = $pdo->prepare("SELECT like_count FROM media_likes WHERE media_filename = ?");
                $stmt->execute([$testFilename]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($row && $row['like_count'] == 1) {
                    echo "<p style='color: green;'>✓ Test data retrieved correctly (like_count: " . $row['like_count'] . ")</p>";
                } else {
                    echo "<p style='color: red;'>✗ Test data retrieval failed</p>";
                    echo "<pre>" . print_r($row, true) . "</pre>";
                }
            } else {
                echo "<p style='color: red;'>✗ Test data update failed</p>";
            }
            
            // Clean up test data
            $stmt = $pdo->prepare("DELETE FROM media_likes WHERE media_filename = ?");
            $stmt->execute([$testFilename]);
            echo "<p>Test data cleaned up</p>";
        } else {
            echo "<p style='color: red;'>✗ Test data insertion failed</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Table 'media_likes' does not exist</p>";
    }
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Database error: " . $e->getMessage() . "</p>";
}

echo "<h2>Database Connection Info</h2>";
echo "<p>Host: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "</p>";
?>