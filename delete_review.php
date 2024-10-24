<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$review_id = $_GET['id'];
$review = getReviewById($review_id);

if (!$review || $review['user_id'] != $_SESSION['user_id']) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (deleteReview($review_id)) {
        // If there's an image associated with the review, delete it
        if ($review['image_path'] && file_exists($review['image_path'])) {
            unlink($review['image_path']);
        }
        $_SESSION['message'] = "Review deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete the review. Please try again.";
    }
    header("Location: dashboard.php");
    exit();
}

$page_title = 'Delete Review';
include 'includes/header.php';
?>

<h1>Delete Review</h1>

<p>Are you sure you want to delete your review of "<?php echo htmlspecialchars($review['book_title']); ?>"?</p>
<p>This action cannot be undone.</p>

<form method="post" action="">
    <button type="submit" class="btn btn-danger">Yes, Delete Review</button>
    <a href="dashboard.php" class="btn">No, Cancel</a>
</form>

