<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/config.php';
require_once 'includes/db.php';  // Make sure this is included to use getDbConnection()
require_once 'includes/functions.php';

$conn = getDbConnection(); // Get the database connection

if (isset($_GET['review_id'])) {
    $review_id = $_GET['review_id'];

    // Prepare SQL statement to fetch comments for the specific review
    $stmt = $conn->prepare("SELECT c.comment_text, c.created_at, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.review_id = ? ORDER BY c.created_at DESC");
    $stmt->bind_param("i", $review_id); // Assuming review_id is an integer
    $stmt->execute();
    $result = $stmt->get_result();

    $comments = [];
    
    // Check if there are comments
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row; // Collect comments
    }

    // Return comments as JSON
    header('Content-Type: application/json');
    echo json_encode($comments);

    $stmt->close();
}

$conn->close(); // Close the database connection
?>
