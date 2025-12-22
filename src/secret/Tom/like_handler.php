<?php
// Script to handle like operations for Tom's section
require_once 'db_connect.php';

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
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$filename = $input['filename'] ?? '';

error_log("Like handler received filename: " . $filename);

if (empty($filename)) {
    http_response_code(400);
    echo json_encode(['error' => 'Filename is required']);
    exit();
}

try {
    // Increment like count for the specified media file
    $stmt = $pdo->prepare("UPDATE media_likes SET like_count = like_count + 1 WHERE media_filename = ?");
    $result = $stmt->execute([$filename]);
    
    error_log("Update query executed, rows affected: " . $stmt->rowCount());
    
    if ($stmt->rowCount() === 0) {
        // If no rows were affected, insert a new record
        error_log("No existing record found, attempting to insert new record for: " . $filename);
        $stmt = $pdo->prepare("INSERT INTO media_likes (media_filename, like_count) VALUES (?, 1) ON DUPLICATE KEY UPDATE like_count = like_count + 1");
        $insertResult = $stmt->execute([$filename]);
        error_log("Insert query executed, rows affected: " . $stmt->rowCount());
        
        // If that also fails or doesn't insert a new row, try a simple insert ignore
        if ($stmt->rowCount() === 0) {
            error_log("ON DUPLICATE KEY UPDATE didn't insert new row, trying INSERT IGNORE for: " . $filename);
            $stmt = $pdo->prepare("INSERT IGNORE INTO media_likes (media_filename, like_count) VALUES (?, 1)");
            $stmt->execute([$filename]);
            error_log("INSERT IGNORE executed, rows affected: " . $stmt->rowCount());
        }
    }
    
    // Get the updated like count
    $stmt = $pdo->prepare("SELECT like_count FROM media_likes WHERE media_filename = ?");
    $stmt->execute([$filename]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        error_log("Successfully updated like count for " . $filename . ": " . $row['like_count']);
        echo json_encode([
            'success' => true,
            'like_count' => $row['like_count'],
            'filename' => $filename
        ]);
    } else {
        error_log("Failed to retrieve updated like count for " . $filename);
        http_response_code(500);
        echo json_encode(['error' => 'Failed to retrieve updated like count']);
    }
} catch(PDOException $e) {
    error_log("Database error in like_handler.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>