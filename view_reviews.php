<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$reviews = getAllReviews();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Reviews - Book Review Platform</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <h1>All Book Reviews</h1>
        <div class="reviews-grid">
            <?php foreach ($reviews as $review): ?>
                <div class="review-card">
                    <h2><?php echo htmlspecialchars($review['book_title']); ?></h2>
                    <p>Author: <?php echo htmlspecialchars($review['author']); ?></p>
                    <p>Genre: <?php echo htmlspecialchars($review['genre']); ?></p>
                    <p>Rating: <?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']); ?></p>
                    <p>Reviewed by: <?php echo htmlspecialchars($review['username']); ?></p>
                    <?php if ($review['image_path']): ?>
                        <img src="<?php echo htmlspecialchars($review['image_path']); ?>" alt="Book cover" class="book-cover">
                    <?php endif; ?>
                    <p><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                    <?php if ($review['book_link']): ?>
                        <a href="<?php echo htmlspecialchars($review['book_link']); ?>" target="_blank" class="btn">Read/Buy Book</a>
                    <?php endif; ?>
                    <a href="#" class="btn btn-small show-comments" data-review-id="<?php echo $review['id']; ?>">Show Comments</a>
                    <div class="comments-section" id="comments-<?php echo $review['id']; ?>" style="display: none;">
                        <!-- Comments will be loaded here via AJAX -->
                    </div>
                    <?php if (isLoggedIn()): ?>
                        <form class="comment-form" data-review-id="<?php echo $review['id']; ?>">
                            <textarea name="comment_text" placeholder="Write a comment" required></textarea>
                            <button type="submit" class="btn btn-small">Post Comment</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="js/main.js"></script>
</body>
</html>
