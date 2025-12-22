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
    
    foreach ($mediaFiles as $filename) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO media_likes (media_filename, like_count) VALUES (?, 0)");
        $stmt->execute([$filename]);
    }
    
    echo "Database initialized successfully!";
} catch(PDOException $e) {
    echo "Error initializing database: " . $e->getMessage();
}
?>