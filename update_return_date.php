<?php
// Include the configuration file
include 'config.php';
session_start();

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit; // Ensure no further code is executed after header redirect
}

// Check if the book ID is provided
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
    
    // Get the current date and time
    $return_date = date('Y-m-d H:i:s');

    // Update the return date in the database
    $query = "UPDATE borrowed_books SET return_date = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $return_date, $book_id);
    
    if ($stmt->execute()) {
        // Successfully updated, redirect to the report page
        header("Location: report.php");
        exit;
    } else {
        die("Error updating return date: " . $stmt->error);
    }
} else {
    die("No book ID provided.");
}
?>