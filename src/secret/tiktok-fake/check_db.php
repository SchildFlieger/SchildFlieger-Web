<?php
// Simple database connection test
require_once 'db_connect.php';

if ($pdo === null) {
    echo "Database connection failed.";
} else {
    echo "Database connection successful!";
    
    // Try to create the table
    try {
        $sql = "CREATE TABLE IF NOT EXISTS media_likes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            media_filename VARCHAR(255) NOT NULL UNIQUE,
            like_count INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_media_filename (media_filename)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
        echo "\nTable created or already exists.";
        
        // Insert test data
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
        
        echo "\nTest data inserted.";
        
        // Try to select data
        $stmt = $pdo->query("SELECT * FROM media_likes LIMIT 3");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "\nSample data from database:";
        foreach ($results as $row) {
            echo "\n- " . $row['media_filename'] . ": " . $row['like_count'];
        }
        
    } catch(PDOException $e) {
        echo "\nDatabase error: " . $e->getMessage();
    }
}
?>