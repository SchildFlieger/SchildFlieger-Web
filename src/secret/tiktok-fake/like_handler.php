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

try {
    // First, get the current like count
    error_log("Fetching current like count for: " . $filename);
    $stmt = $pdo->prepare("SELECT like_count FROM media_likes WHERE media_filename = ?");
    $stmt->execute([$filename]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $currentCount = (int)$row['like_count'];
        error_log("Current like count for " . $filename . ": " . $currentCount);
        
        // Toggle like: if current count is > 0, decrease by 1 (unlike), otherwise increase by 1 (like)
        $newCount = ($currentCount > 0) ? ($currentCount - 1) : ($currentCount + 1);
        
        error_log("Updating like count for " . $filename . " from " . $currentCount . " to " . $newCount);
        
        // Update like count for the specified media file
        $stmt = $pdo->prepare("UPDATE media_likes SET like_count = ? WHERE media_filename = ?");
        $result = $stmt->execute([$newCount, $filename]);
        
        error_log("Update query executed, rows affected: " . $stmt->rowCount());
        
        if ($stmt->rowCount() > 0) {
            // Successfully updated
            error_log("Successfully updated like count for " . $filename . ": " . $newCount);
            echo json_encode([
                'success' => true,
                'like_count' => $newCount,
                'filename' => $filename,
                'action' => ($newCount > $currentCount) ? 'liked' : 'unliked'
            ]);
        } else {
            error_log("Failed to update like count for " . $filename);
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update like count']);
        }
    } else {
        // No existing record found, insert a new record with 1 like (first like)
        error_log("No existing record found, inserting new record for: " . $filename . " with 1 like");
        $stmt = $pdo->prepare("INSERT INTO media_likes (media_filename, like_count) VALUES (?, 1)");
        $insertResult = $stmt->execute([$filename]);
        
        if ($insertResult) {
            error_log("Successfully inserted new record for " . $filename . " with 1 like");
            echo json_encode([
                'success' => true,
                'like_count' => 1,
                'filename' => $filename,
                'action' => 'liked'
            ]);
        } else {
            error_log("Failed to insert new record for " . $filename);
            http_response_code(500);
            echo json_encode(['error' => 'Failed to insert new record']);
        }
    }
} catch(PDOException $e) {
    error_log("Database error in like_handler.php: " . $e->getMessage());
    error_log("Database error trace: " . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage(), 'trace' => $e->getTraceAsString()]);
}
?>