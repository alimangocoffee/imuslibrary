<?php
// Database configuration
$host = "localhost";
$user = "root";
$password = "";  // Leave empty if no password is set for MySQL
$database = "library";

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Check connected
/*if ($conn) {
    echo "Database connected successfully!";
}*/

?>


