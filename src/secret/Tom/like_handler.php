<?php
// Script to handle like operations for Tom's section
require_once 'db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

if ($pdo === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$filename = $input['filename'] ?? '';

if (empty($filename)) {
    http_response_code(400);
    echo json_encode(['error' => 'Filename is required']);
    exit();
}

try {
    // Increment like count for the specified media file
    $stmt = $pdo->prepare("UPDATE media_likes SET like_count = like_count + 1 WHERE media_filename = ?");
    $result = $stmt->execute([$filename]);
    
    if ($stmt->rowCount() === 0) {
        // If no rows were affected, insert a new record
        $stmt = $pdo->prepare("INSERT INTO media_likes (media_filename, like_count) VALUES (?, 1) ON DUPLICATE KEY UPDATE like_count = like_count + 1");
        $stmt->execute([$filename]);
    }
    
    // Get the updated like count
    $stmt = $pdo->prepare("SELECT like_count FROM media_likes WHERE media_filename = ?");
    $stmt->execute([$filename]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        echo json_encode([
            'success' => true,
            'like_count' => $row['like_count'],
            'filename' => $filename
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to retrieve updated like count']);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>