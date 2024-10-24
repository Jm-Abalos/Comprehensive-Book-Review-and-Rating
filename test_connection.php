<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/config.php';
require_once 'includes/db.php'; // Ensure this file establishes the $conn variable

// Test the database connection
if ($conn) {
    echo "Connected successfully to the database!";
} else {
    echo "Connection failed!";
    error_log("Connection failed: " . $conn->connect_error); // Log error
}

$conn->close();
?>
