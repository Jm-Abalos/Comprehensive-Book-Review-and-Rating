<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Check if the user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to post a comment.']);
    exit;
}

// Get the review ID and comment text from the POST request
$review_id = isset($_POST['review_id']) ? intval($_POST['review_id']) : 0;
$comment_text = isset($_POST['comment_text']) ? trim($_POST['comment_text']) : '';

if ($review_id > 0 && !empty($comment_text)) {
    // Prepare and insert the comment into the database
    $conn = getDbConnection();
    $stmt = $conn->prepare("INSERT INTO comments (review_id, user_id, comment_text) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $review_id, $_SESSION['user_id'], $comment_text);

    if ($stmt->execute()) {
        // Successfully inserted the comment
        echo json_encode(['success' => true, 'message' => 'Comment posted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to post comment.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid review ID or empty comment.']);
}
?>
