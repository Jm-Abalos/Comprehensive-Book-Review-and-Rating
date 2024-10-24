<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';
$genre_filter = isset($_GET['genre']) ? $_GET['genre'] : '';
$rating_filter = isset($_GET['rating']) ? (int)$_GET['rating'] : 0;

$reviews = searchReviews($search_query, $genre_filter, $rating_filter);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Reviews - Book Review Platform</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <h1>Search Reviews</h1>
        <form method="get" action="" class="search-form">
            <input type="text" name="query" placeholder="Search by book title or author" value="<?php echo htmlspecialchars($search_query); ?>">
            <select name="genre">
                <option value="">All Genres</option>
                <option value="Fiction" <?php echo $genre_filter === 'Fiction' ? 'selected' : ''; ?>>Fiction</option>
                <option value="Non-Fiction" <?php echo $genre_filter === 'Non-Fiction' ? 'selected' : ''; ?>>Non-Fiction</option>
                <option value="Mystery" <?php echo $genre_filter === 'Mystery' ? 'selected' : ''; ?>>Mystery</option>
                <option value="Sci-Fi" <?php echo $genre_filter === 'Sci-Fi' ? 'selected' : ''; ?>>Sci-Fi</option>
                <option value="Fantasy" <?php echo $genre_filter === 'Fantasy' ? 'selected' : ''; ?>>Fantasy</option>
                <option value="Romance" <?php echo $genre_filter === 'Romance' ? 'selected' : ''; ?>>Romance</option>
                <option value="Thriller" <?php echo $genre_filter === 'Thriller' ? 'selected' : ''; ?>>Thriller</option>
                <option value="Biography" <?php echo $genre_filter === 'Biography' ? 'selected' : ''; ?>>Biography</option>
            </select>
            <select name="rating">
                <option value="0">All Ratings</option>
                <option value="5" <?php echo $rating_filter === 5 ? 'selected' : ''; ?>>5 Stars</option>
                <option value="4" <?php echo $rating_filter === 4 ? 'selected' : ''; ?>>4+ Stars</option>
                <option value="3" <?php echo $rating_filter === 3 ? 'selected' : ''; ?>>3+ Stars</option>
                <option value="2" <?php echo $rating_filter === 2 ? 'selected' : ''; ?>>2+ Stars</option>
                <option value="1" <?php echo $rating_filter === 1 ? 'selected' : ''; ?>>1+ Star</option>
            </select>
            <button type="submit" class="btn">Search</button>
        </form>
        
        <div class="reviews-grid">
            <?php if (empty($reviews)): ?>
                <p>No reviews found matching your search criteria.</p>
            <?php else: ?>
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
                        <p><?php echo nl2br(htmlspecialchars(substr($review['review_text'], 0, 200) . '...')); ?></p>
                        <a href="view_reviews.php?id=<?php echo $review['id']; ?>" class="btn btn-small">Read Full Review</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <script src="js/main.js"></script>
</body>
</html>