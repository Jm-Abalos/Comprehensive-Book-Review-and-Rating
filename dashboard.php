<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$reviews = getUserReviews($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Book Review Platform</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <a href="post_review.php" class="btn">Post a New Review</a>
        <h2>Your Reviews</h2>
        <?php if (empty($reviews)): ?>
            <p>You haven't posted any reviews yet.</p>
        <?php else: ?>
            <div class="reviews-grid">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <h3><?php echo htmlspecialchars($review['book_title']); ?></h3>
                        <p>Author: <?php echo htmlspecialchars($review['author']); ?></p>
                        <p>Rating: <?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']); ?></p>
                        <a href="edit_review.php?id=<?php echo $review['id']; ?>" class="btn btn-small">Edit</a>
                        <a href="delete_review.php?id=<?php echo $review['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Are you sure you want to delete this review?');">Delete</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>