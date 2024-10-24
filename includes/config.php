<?php
session_start();
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'rootroot');
define('DB_NAME', 'book_review_platform');

define('UPLOAD_DIR', 'uploads/');
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>