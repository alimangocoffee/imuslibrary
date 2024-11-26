<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config.php'; 
include 'header.php';

// Initialize alert variable
$alertMessage = '';

// Handle the book addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $available = $_POST['available'];

    // Validate input
    if (!empty($title) && !empty($author) && is_numeric($available)) {
        // Insert new book into database
        $query = "INSERT INTO books (title, author, available) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $title, $author, $available);
        if ($stmt->execute()) {
            $alertMessage = "<div class='alert alert-success'>Book added successfully!</div>";
        } else {
            $alertMessage = "<div class='alert alert-danger'>Error adding book. Please try again.</div>";
        }
        $stmt->close();
    } else {
        $alertMessage = "<div class='alert alert-danger'>Please fill in all fields correctly.</div>";
    }
}

// Handle the book deletion
if (isset($_GET['delete'])) {
    $book_id = $_GET['delete'];

    // Delete related borrowed_books entries
    $delete_borrowed_query = "DELETE FROM borrowed_books WHERE book_id = ?";
    $delete_borrowed_stmt = $conn->prepare($delete_borrowed_query);
    $delete_borrowed_stmt->bind_param("i", $book_id);
    $delete_borrowed_stmt->execute();
    $delete_borrowed_stmt->close();

    // Now delete the book
    $delete_query = "DELETE FROM books WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $book_id);
    if ($delete_stmt->execute()) {
        $alertMessage = "<div class='alert alert-success'>Book deleted successfully!</div>";
        // Use JavaScript to remove the alert after 1.5 seconds
        echo "<script>setTimeout(() => { document.getElementById('alert-box').style.display = 'none'; }, 1500);</script>";
    } else {
        $alertMessage = "<div class='alert alert-danger'>Error deleting book. Please try again.</div>";
    }
    $delete_stmt->close();
}

// Fetch list of books
$query = "SELECT * FROM books";
$result = $conn->query($query);
?>

<div class="container">
    <h2>Add Books</h2>

    <h3>Add a New Book</h3>
    <form action="admin_panel.php" method="POST">
        <div id="alert-box"><?php echo $alertMessage; ?></div> <!-- Alert box inside the form -->
        <div class="input-group">
            <i class="fas fa-book"></i>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="input-group">
            <i class="fas fa-user"></i>
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" required>
        </div>
        <div class="input-group">
            <i class="fas fa-copy"></i>
            <label for="available">Available Copies:</label>
            <input type="number" id="available" name="available" required min="0">
        </div>
        <button type="submit" name="add_book" class="add-button">Add Book</button>
    </form>

    <h3 class="text-center m-3 pt-5">List of Books</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Available</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['author']); ?></td>
            <td><?php echo htmlspecialchars($row['available']); ?></td>
            <td>
                <a href="edit_book.php?id=<?php echo $row['id']; ?>" class="edit-button">Edit</a> | 
                <a href="admin_panel.php?delete=<?php echo $row['id']; ?>" class="delete-button"
                   onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h3><a href="report.php">View Borrowed Books Report</a></h3>
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
        margin: 50px auto;
    }

    #alert-box {
        margin-bottom: 15px; /* Space above alert */
        display: block; /* Show alert by default */
    }

    .input-group {
        position: relative;
        margin-bottom: 15px;
    }

    .input-group i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #d3d3d3;
        z-index: 1; /* Make sure the icon is above the input field */
    }

    .input-group label {
        margin-left: 40px; /* Adjusted for icon */
        font-weight: bold;
        color: #14213d; /* Change to your preferred color */
    }

    input {
        width: calc(100% - 40px); /* Adjusted to account for icon */
        padding: 10px 12px 10px 40px; /* Left padding for the icon */
        border: 1px solid #d3d3d3;
        border-radius: 6px;
        background-color: rgba(255, 255, 255, 0.3); /* Medium transparency */
        backdrop-filter: blur(5px); /* Blur effect for input */
        transition: border 0.3s;
        position: relative; /* Ensure proper stacking context */
        z-index: 0; /* Input field should be below the icon */
    }

    input:focus {
        border-color: #4a4e69; /* Highlight on focus */
        outline: none;
    }

    .add-button {
        width: calc(100% - 40px); /* Same width as input fields */
        padding: 12px;
        background: linear-gradient(to right, #0d6efd, #0d6efd);
        text-decoration: none;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1em;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    .add-button:hover {
        background: linear-gradient(to right, #54b24a, #7cde71);
        transform: scale(1.05); /* Slight grow effect */
    }

    /* Edit and Delete Button Styles */
    .edit-button {
        padding: 6px 10px; /* Added padding for consistency */
        background: linear-gradient(to right, #54b24a, #7cde71);
        color: white;
        font-weight: bold;
        border-radius: 5px;
        transition: 0.3s, transform 0.2s;
    }

    .delete-button {
        padding: 6px 10px; /* Added padding for consistency */
        background: linear-gradient(90deg, #e63946, #c92a2a); /* Gradient background */
        color: white;
        font-weight: bold;
        border-radius: 5px;
        transition: 0.3s, transform 0.2s;
    }

    .edit-button:hover {
        background: linear-gradient(to right, #54b24a, #7cde71);
        transform: scale(1.05); /* Slight grow effect */
        text-decoration: none;
    }

    .delete-button:hover {
        background: linear-gradient(90deg, #c92a2a, #9b1c1c);
        text-decoration: none;
        transform: scale(1.05); /* Slight grow effect */
    }

    /* Table Header Styles */
    table th {
        background: linear-gradient(to right, #54b24a, #7cde71);
        color: white; /* Text color */
        padding: 12px; /* Padding for table header */
        text-align: left; /* Align text to the left */
        font-weight: bold; /* Make header text bold */
    }

    table {
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 20px; 
    }

    table, th, td {
        border: 1px solid #d3d3d3; /* Border for table and cells */
    }

    table td {
        padding: 12px; /* Padding for table cells */
        background-color: rgba(255, 255, 255, 0.3); /* Semi-transparent background for table cells */
        transition: background-color 0.3s; /* Smooth transition for background */
    }

    table td:hover {
        background-color: rgba(255, 255, 255, 0.5); /* Change background on hover */
    }

    /* Optional: Responsive design adjustments */
    @media (max-width: 768px) {
        .container {
            padding: 10px; /* Adjust padding for smaller screens */
        }

        .input-group, table {
            font-size: 0.9em; /* Reduce font size */
        }
    }
</style>



