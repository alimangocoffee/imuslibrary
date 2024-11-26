<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config.php'; 
include 'header.php'; // Ensure this has the <head> section

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch the list of books
$search_query = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search'])) {
    $search_query = $_POST['search'];
}

// Prepare the SQL query to fetch books based on the search query
$query = "SELECT id, title, author, available FROM books WHERE (title LIKE ? OR author LIKE ?) AND available > 0";
$stmt = $conn->prepare($query);
$search_param = '%' . $search_query . '%';
$stmt->bind_param("ss", $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Book List Page Content -->
<div class="container">
    <h2>Available Books</h2>

    <!-- Search Bar -->
    <form action="book_list.php" method="POST" class="mb-4">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by title or author" required>
        <button type="submit" class="search-button">Search</button>
    </form>

    <!-- Book List Table -->
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Book ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Available Copies</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['author']); ?></td>
                        <td><?php echo htmlspecialchars($row['available']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No books found matching your search criteria.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<!-- CSS and JavaScript for Alert Box -->
<style>
     @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
    body {
        background-color: #f0f4f8; /* Example body background color */
        color: #333; /* Text color for body */
        font-family: "Poppins", sans-serif; /* Font family */
    }

    /* Container Styles */
    .container {
        background-color: rgba(255, 255, 255, 0.8); /* Slight transparency for content */
        backdrop-filter: blur(12px); /* Blur effect */
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        max-width: 800px; /* Optional: Set a max-width */
        margin: 20px auto; /* Center the container */
    }

    /* Table Styling */
    table {
        width: 100%; /* Full width for the table */
        border-collapse: collapse; /* Collapse borders */
    }

    th, td {
        padding: 10px;
        text-align: left;
        backdrop-filter: blur(6px); /* Blur effect for table cells */
    }

    thead th {
        background: linear-gradient(to right, #54b24a, #7cde71); /* Gradient for headers */
        color: #333; /* Dark text color */
        border-bottom: 2px solid #d4d4d4; /* Bottom border for headers */
    }

    /* Button Styling */
    button {
        padding: 10px 15px;
        background: linear-gradient(to right, #54b24a, #7cde71); /* Gradient color for button */
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
        color: #000;
    }

    button:hover {
        background: linear-gradient(to right, #053774, #475167); /* Darker gradient on hover */
        transform: scale(1.05); /* Slight grow effect */
    }
</style>

