<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$page_title = 'Welcome to Book Review Platform';
include 'includes/header.php';

$recent_reviews = array_slice(getAllReviews(), 0, 6);
?>

<h1>Welcome to Book Review Platform</h1>
<p>Discover new books, share your thoughts, and connect with fellow book lovers!</p>

<h2>Recent Reviews</h2>
<div class="reviews-grid">
    <?php foreach ($recent_reviews as $review): ?>
        <div class="review-card">
            <h3><?php echo htmlspecialchars($review['book_title']); ?></h3>
            <p>Author: <?php echo htmlspecialchars($review['author']); ?></p>
            <p>Genre: <?php echo htmlspecialchars($review['genre']); ?></p>
            <p>Rating: <?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']); ?></p>
            <p>Reviewed by: <?php echo htmlspecialchars($review['username']); ?></p>
            <?php if ($review['image_path']): ?>
                <img src="<?php echo htmlspecialchars($review['image_path']); ?>" alt="Book cover" class="book-cover">
            <?php endif; ?>
            <p><?php echo nl2br(htmlspecialchars(substr($review['review_text'], 0, 150) . '...')); ?></p>
            <a href="view_review.php?id=<?php echo $review['id']; ?>" class="btn btn-small">Read Full Review</a>

            <!-- Show Comments Button -->
            <a href="#" class="btn btn-small show-comments" data-review-id="<?php echo $review['id']; ?>">Show Comments</a>
            
            <!-- Comments Section -->
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

<div class="cta-section">
    <h2>Join Our Community</h2>
    <p>Sign up today to start sharing your book reviews and connecting with other readers!</p>
    <a href="register.php" class="btn">Register Now</a>
</div>
