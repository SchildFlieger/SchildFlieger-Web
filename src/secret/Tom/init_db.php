<?php
// Script to initialize the database tables for Tom's section
require_once 'db_connect.php';

if ($pdo === null) {
    die("Database connection failed. Cannot initialize database.");
}

try {
    // Create table for storing likes
    $sql = "CREATE TABLE IF NOT EXISTS media_likes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        media_filename VARCHAR(255) NOT NULL UNIQUE,
        like_count INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_media_filename (media_filename)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    echo "Table creation SQL executed.\n";
    
    // Check if table was created successfully
    $stmt = $pdo->query("SHOW TABLES LIKE 'media_likes'");
    $tableExists = $stmt->fetch();
    
    if (!$tableExists) {
        die("Failed to create media_likes table.");
    }
    
    echo "Table 'media_likes' exists.\n";
    
    // Insert initial records for all media files if they don't exist
    $mediaFiles = [
        'Amena Tom.mp4',
        'Bild Theo guffi.jpeg',
        'Bild Theo Random.jpeg',
        'Bild Theo Schlafen.jpeg',
        'Bild Theo.jpeg',
        'Bild Tom klein.jpeg',
        'Bild Tom.jpeg',
        'Erik.jpeg',
        'Franzosen Tom.mp4'
    ];
    
    $insertedCount = 0;
    foreach ($mediaFiles as $filename) {
        // First try INSERT IGNORE
        $stmt = $pdo->prepare("INSERT IGNORE INTO media_likes (media_filename, like_count) VALUES (?, 0)");
        $stmt->execute([$filename]);
        $insertedCount += $stmt->rowCount();
        
        // If that didn't work, try updating existing record to ensure like_count is at least 0
        if ($stmt->rowCount() == 0) {
            $stmt = $pdo->prepare("UPDATE media_likes SET like_count = COALESCE(like_count, 0) WHERE media_filename = ?");
            $stmt->execute([$filename]);
        }
    }
    
    echo "Processed $insertedCount new records.\n";
    
    // Verify data
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM media_likes");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "Total records in table: $count\n";
    
    // Show sample data
    $stmt = $pdo->query("SELECT media_filename, like_count FROM media_likes ORDER BY media_filename LIMIT 5");
    $sampleData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Sample data:\n";
    foreach ($sampleData as $row) {
        echo "  {$row['media_filename']}: {$row['like_count']}\n";
    }
    
    echo "Database initialized successfully!\n";} catch(PDOException $e) {
    echo "Error initializing database: " . $e->getMessage();
}
?>