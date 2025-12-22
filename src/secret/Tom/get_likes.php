<?php
// Script to get like counts for all media files
require_once 'db_connect.php';

header('Content-Type: application/json');

if ($pdo === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

try {
    // Get like counts for all media files
    $stmt = $pdo->query("SELECT media_filename, like_count FROM media_likes ORDER BY media_filename");
    $likes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Convert to associative array for easier access
    $likesArray = [];
    foreach ($likes as $like) {
        $likesArray[$like['media_filename']] = (int)$like['like_count'];
    }
    
    echo json_encode([
        'success' => true,
        'likes' => $likesArray
    ]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>