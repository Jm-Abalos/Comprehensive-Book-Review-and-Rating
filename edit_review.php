<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$page_title = 'Edit Review';
include 'includes/header.php';

$error = '';
$success = '';

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
    $book_title = $_POST['book_title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];
    $book_link = $_POST['book_link'];

    $image_path = $review['image_path'];
    if (isset($_FILES['book_image']) && $_FILES['book_image']['error'] == 0) {
        $new_image_path = uploadImage($_FILES['book_image']);
        if ($new_image_path) {
            if ($image_path && file_exists($image_path)) {
                unlink($image_path);
            }
            $image_path = $new_image_path;
        }
    }

    if (updateReview($review_id, $book_title, $author, $genre, $rating, $review_text, $image_path, $book_link)) {
        $success = "Review updated successfully.";
        $review = getReviewById($review_id); // Refresh the review data
    } else {
        $error = "Failed to update the review. Please try again.";
    }
}
?>

<h1>Edit Review</h1>

<?php if ($error): ?>
    <p class="error"><?php echo $error; ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p class="success"><?php echo $success; ?></p>
<?php endif; ?>

<form method="post" action="" enctype="multipart/form-data">
    <label for="book_title">Book Title:</label>
    <input type="text" id="book_title" name="book_title" value="<?php echo htmlspecialchars($review['book_title']); ?>" required>

    <label for="author">Author:</label>
    <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($review['author']); ?>" required>

    <label for="genre">Genre:</label>
    <select id="genre" name="genre" required>
        <option value="">Select Genre</option>
        <?php
        $genres = ['Fiction', 'Non-Fiction', 'Mystery', 'Sci-Fi', 'Fantasy', 'Romance', 'Thriller', 'Biography'];
        foreach ($genres as $genre) {
            $selected = ($review['genre'] == $genre) ? 'selected' : '';
            echo "<option value=\"$genre\" $selected>$genre</option>";
        }
        ?>
    </select>

    <label for="rating">Rating:</label>
    <select id="rating" name="rating" required>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <option value="<?php echo $i; ?>" <?php echo ($review['rating'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?> Star<?php echo ($i > 1) ? 's' : ''; ?></option>
        <?php endfor; ?>
    </select>

    <label for="review_text">Review:</label>
    <textarea id="review_text" name="review_text" required><?php echo htmlspecialchars($review['review_text']); ?></textarea>

    <label for="book_image">Book Cover Image:</label>
    <input type="file" id="book_image" name="book_image" accept="image/*">
    <?php if ($review['image_path']): ?>
        <p>Current image: <img src="<?php echo htmlspecialchars($review['image_path']); ?>" alt="Current book cover" style="max-width: 100px;"></p>
    <?php endif; ?>

    <label for="book_link">Book Link:</label>
    <input type="url" id="book_link" name="book_link" value="<?php echo htmlspecialchars($review['book_link']); ?>">

    <button type="submit" class="btn">Update Review</button>
</form>

<p><a href="dashboard.php" class="btn btn-small">Back to Dashboard</a></p>
