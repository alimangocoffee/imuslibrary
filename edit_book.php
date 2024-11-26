<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config.php'; 
include 'header.php';

// Check if user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); // Redirect non-admin users
    exit;
}

// Get the book ID from the query parameter
if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>Invalid book ID.</div>";
    exit;
}

$book_id = $_GET['id'];

// Fetch book details
$query = "SELECT title, author, available FROM books WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$stmt->bind_result($title, $author, $available);
$stmt->fetch();
$stmt->close();

// Handle form submission to update book details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_title = $_POST['title'];
    $new_author = $_POST['author'];
    $new_available = $_POST['available'];

    // Validate input
    if (!empty($new_title) && !empty($new_author) && is_numeric($new_available)) {
        // Update book in the database
        $update_query = "UPDATE books SET title = ?, author = ?, available = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssii", $new_title, $new_author, $new_available, $book_id);

        if ($update_stmt->execute()) {
            echo "<div class='alert alert-success'>Book updated successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error updating book. Please try again.</div>";
        }

        $update_stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Please fill in all fields correctly.</div>";
    }
}
?>

<div class="container">
    <h2>Edit Book</h2>

    <form action="edit_book.php?id=<?php echo $book_id; ?>" method="POST">
        <div id="alert-box"><?php if (!empty($alertMessage)) echo $alertMessage; ?></div> <!-- Alert box -->
        <div class="input-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
        </div>
        <div class="input-group">
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($author); ?>" required>
        </div>
        <div class="input-group">
            <label for="available">Available Copies:</label>
            <input type="number" id="available" name="available" value="<?php echo htmlspecialchars($available); ?>" required min="0">
        </div>
        <button type="submit" class="update-button">Update Book</button>
    </form>

    <p><a href="admin_panel.php">Back to Admin Panel</a></p>
</div>

<?php include 'footer.php'; ?>

<!-- CSS for styling the input fields -->
<style>
    body {
        background-color: #f5f5f5; /* Light background color */
    }

    .container {
        backdrop-filter: blur(10px); /* Blur effect for the container */
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Optional shadow for elevation */
        background-color: rgba(255, 255, 255, 0.7); /* Semi-transparent background for form */
    }

    .input-group {
        margin-bottom: 15px;
    }

    label {
        font-weight: bold;
        color: #14213d; /* Change to your preferred color */
    }

    input {
        width: 100%;
        padding: 10px;
        border: 1px solid #d3d3d3;
        border-radius: 6px;
        background-color: rgba(255, 255, 255, 0.3); /* Medium transparency */
        backdrop-filter: blur(5px); /* Blur effect for input */
        transition: border 0.3s;
    }

    input:focus {
        border-color: #4a4e69; /* Highlight on focus */
        outline: none;
    }

    .update-button {
        width: 100%;
        padding: 12px;
        background-color: #f0e68c; /* Cream button color */
        color: #14213d; /* Dark color for text */
        border: none;
        border-radius: 8px;
        font-size: 1em;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    .update-button:hover {
        background-color: #d2c28e; /* Darker cream color on hover */
        transform: scale(1.05); /* Slight grow effect */
    }

    /* Alert Styles */
    .alert {
        text-align: center;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        display: block; /* Make alerts visible */
    }

    .alert-success {
        background-color: #28a745; /* Green background for success */
        color: white;
    }

    .alert-danger {
        background-color: #e63946; /* Red background for danger */
        color: white;
    }
</style>
