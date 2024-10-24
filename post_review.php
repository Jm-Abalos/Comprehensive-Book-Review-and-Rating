<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $book_title = $_POST['book_title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];
    $book_link = $_POST['book_link'];

    $image_path = null;
    if (isset($_FILES['book_image']) && $_FILES['book_image']['error'] == 0) {
        $image_path = uploadImage($_FILES['book_image']);
    }

    if (postReview($user_id, $book_title, $author, $genre, $rating, $review_text, $image_path, $book_link)) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Failed to post review. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Review - Book Review Platform</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <h1>Post a New Review</h1>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post" action="" enctype="multipart/form-data">
            <input type="text" name="book_title" placeholder="Book Title" required>
            <input type="text" name="author" placeholder="Author" required>
            <select name="genre" required>
                <option value="">Select Genre</option>
                <option value="Fiction">Fiction</option>
                <option value="Non-Fiction">Non-Fiction</option>
                <option value="Mystery">Mystery</option>
                <option value="Sci-Fi">Sci-Fi</option>
                <option value="Fantasy">Fantasy</option>
                <option value="Romance">Romance</option>
                <option value="Thriller">Thriller</option>
                <option value="Biography">Biography</option>
            </select>
            <select name="rating" required>
                <option value="">Select Rating</option>
                <option value="1">1 Star</option>
                <option value="2">2 Stars</option>
                <option value="3">3 Stars</option>
                <option value="4">4 Stars</option>
                <option value="5">5 Stars</option>
            </select>
            <textarea name="review_text" placeholder="Write your review here" required></textarea>
            <input type="file" name="book_image" accept="image/*">
            <input type="url" name="book_link" placeholder="Link to buy/read the book">
            <button type="submit">Post Review</button>
        </form>
    </div>
</body>
</html>