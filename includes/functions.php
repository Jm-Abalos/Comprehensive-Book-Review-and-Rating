<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function authenticateUser($username, $password) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            return $user;
        }
    }
    return false;
}

function registerUser($username, $email, $password) {
    $conn = getDbConnection();
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    
    return $stmt->execute();
}

function getUserReviews($user_id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getAllReviews() {
    $conn = getDbConnection();
    $result = $conn->query("SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getReviewById($review_id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.id = ?");
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function postReview($user_id, $book_title, $author, $genre, $rating, $review_text, $image_path, $book_link) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, book_title, author, genre, rating, review_text, image_path, book_link) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssisss", $user_id, $book_title, $author, $genre, $rating, $review_text, $image_path, $book_link);
    return $stmt->execute();
}

function updateReview($review_id, $book_title, $author, $genre, $rating, $review_text, $image_path, $book_link) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("UPDATE reviews SET book_title = ?, author = ?, genre = ?, rating = ?, review_text = ?, image_path = ?, book_link = ? WHERE id = ?");
    $stmt->bind_param("sssisssi", $book_title, $author, $genre, $rating, $review_text, $image_path, $book_link, $review_id);
    return $stmt->execute();
}

function deleteReview($review_id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->bind_param("i", $review_id);
    return $stmt->execute();
}

function searchReviews($query, $genre, $rating) {
    $conn = getDbConnection();
    $sql = "SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE 1=1";
    $params = [];
    $types = "";

    if (!empty($query)) {
        $sql .= " AND (r.book_title LIKE ? OR r.author LIKE ?)";
        $params[] = "%$query%";
        $params[] = "%$query%";
        $types .= "ss";
    }

    if (!empty($genre)) {
        $sql .= " AND r.genre = ?";
        $params[] = $genre;
        $types .= "s";
    }

    if ($rating > 0) {
        $sql .= " AND r.rating >= ?";
        $params[] = $rating;
        $types .= "i";
    }

    $sql .= " ORDER BY r.created_at DESC";

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function uploadImage($file) {
    $target_dir = UPLOAD_DIR;
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;

    if (!in_array($file_extension, ALLOWED_EXTENSIONS)) {
        return false;
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        return false;
    }

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return $target_file;
    }

    return false;
}
?>