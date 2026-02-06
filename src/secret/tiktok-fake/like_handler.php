<?php
// Script to handle like operations for Tom's section
require_once 'db_connect.php';

// Log the request
error_log("Like handler called with method: " . $_SERVER['REQUEST_METHOD']);

header('Content-Type: application/json');

error_log("Like handler called with method: " . $_SERVER['REQUEST_METHOD']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

if ($pdo === null) {
    error_log("Database connection failed in like_handler.php");
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed', 'details' => 'Could not connect to database']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$filename = $input['filename'] ?? '';

error_log("Like handler received filename: " . $filename);

if (empty($filename)) {
    error_log("Empty filename provided");
    http_response_code(400);
    echo json_encode(['error' => 'Filename is required']);
    exit();
}

// Generate a user identifier based on IP and user agent to distinguish between different users/devices
$userIdentifier = hash('sha256', $_SERVER['REMOTE_ADDR'] . '|' . ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'));

try {
    // Check if this user has already liked this media
    $stmt = $pdo->prepare("SELECT id FROM user_media_likes WHERE media_filename = ? AND user_identifier = ?");
    $stmt->execute([$filename, $userIdentifier]);
    $existingLike = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingLike) {
        // User already liked this media, so unlike it
        $stmt = $pdo->prepare("DELETE FROM user_media_likes WHERE media_filename = ? AND user_identifier = ?");
        $stmt->execute([$filename, $userIdentifier]);
        
        // Decrease the total like count
        $stmt = $pdo->prepare("UPDATE media_likes SET like_count = GREATEST(0, like_count - 1) WHERE media_filename = ?");
        $stmt->execute([$filename]);
        
        // Get the new like count
        $stmt = $pdo->prepare("SELECT like_count FROM media_likes WHERE media_filename = ?");
        $stmt->execute([$filename]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $newCount = $result ? (int)$result['like_count'] : 0;
        
        error_log("User $userIdentifier unliked media $filename, new count: $newCount");
        
        echo json_encode([
            'success' => true,
            'like_count' => $newCount,
            'filename' => $filename,
            'action' => 'unliked'
        ]);
    } else {
        // User hasn't liked this media yet, so like it
        $stmt = $pdo->prepare("INSERT IGNORE INTO user_media_likes (media_filename, user_identifier) VALUES (?, ?)");
        $stmt->execute([$filename, $userIdentifier]);
        
        // Only increment if a new record was actually inserted
        if ($stmt->rowCount() > 0) {
            // Increase the total like count
            $stmt = $pdo->prepare("INSERT INTO media_likes (media_filename, like_count) VALUES (?, 1) ON DUPLICATE KEY UPDATE like_count = like_count + 1");
            $stmt->execute([$filename]);
        }
        
        // Get the new like count
        $stmt = $pdo->prepare("SELECT like_count FROM media_likes WHERE media_filename = ?");
        $stmt->execute([$filename]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $newCount = $result ? (int)$result['like_count'] : 1;
        
        error_log("User $userIdentifier liked media $filename, new count: $newCount");
        
        echo json_encode([
            'success' => true,
            'like_count' => $newCount,
            'filename' => $filename,
            'action' => 'liked'
        ]);
    }
} catch(PDOException $e) {
    error_log("Database error in like_handler.php: " . $e->getMessage());
    error_log("Database error trace: " . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage(), 'trace' => $e->getTraceAsString()]);
}
?>