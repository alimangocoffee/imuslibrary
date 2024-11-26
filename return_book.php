<?php
// Start session only if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include your config and header files
include 'config.php'; // Ensure you have your database connection
include 'header.php';

// Redirect if user is not logged in or not a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit;
}

$alert_message = ""; // Store alert message
$sort_option = isset($_POST['sort_by']) ? $_POST['sort_by'] : 'title'; // Default sorting by title

// Handle book return request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['return_id'])) {
    $borrow_id = $_POST['return_id'];

    // Check if the book is overdue
    $penalty_query = "
        SELECT DATEDIFF(NOW(), DATE_ADD(borrow_date, INTERVAL 14 DAY)) AS days_overdue
        FROM borrowed_books
        WHERE id = ? AND DATE_ADD(borrow_date, INTERVAL 14 DAY) < NOW()
    ";

    $penalty_stmt = $conn->prepare($penalty_query);
    $penalty_stmt->bind_param("i", $borrow_id);
    $penalty_stmt->execute();
    $penalty_stmt->bind_result($days_overdue);
    $penalty_stmt->fetch();
    $penalty_stmt->close();

    $conn->begin_transaction();
    try {
        // Delete borrowed book entry and update availability
        $delete_query = "DELETE FROM borrowed_books WHERE id = ?";
        $update_query = "UPDATE books SET available = available + 1 WHERE id = (SELECT book_id FROM borrowed_books WHERE id = ?)";

        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $borrow_id);
        $delete_stmt->execute();

        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("i", $borrow_id);
        $update_stmt->execute();

        // Insert penalty if overdue
        if ($days_overdue > 0) {
            $penalty_amount = $days_overdue * 1; // Assuming $1 per day
            $insert_penalty_query = "INSERT INTO penalties (user_id, book_id, days_overdue, penalty_amount) VALUES (?, ?, ?, ?)";
            $insert_penalty_stmt = $conn->prepare($insert_penalty_query);
            $insert_penalty_stmt->bind_param("iiid", $_SESSION['user_id'], $book_id, $days_overdue, $penalty_amount);
            $insert_penalty_stmt->execute();
            $insert_penalty_stmt->close();
        }

        $conn->commit();
        $alert_message = "Book returned successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        $alert_message = "Error returning book. Please try again.";
    }
}

// Fetch the list of books borrowed by the user
$user_id = $_SESSION['user_id'];
$order_by = match($sort_option) {
    'id' => 'b.id',
    'author' => 'b.author',
    default => 'b.title',
};

$query = "SELECT b.id, b.title, bb.id AS borrow_id FROM borrowed_books bb JOIN books b ON bb.book_id = b.id WHERE bb.user_id = ? ORDER BY $order_by ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
    <h2>Return Book</h2>

    <!-- Sort Options -->
    <form action="return_book.php" method="POST" style="margin-bottom: 20px;">
        <label for="sort_by">Sort by:</label>
        <select name="sort_by" id="sort_by" onchange="this.form.submit()">
            <option value="title" <?php echo $sort_option === 'title' ? 'selected' : ''; ?>>Title</option>
            <option value="author" <?php echo $sort_option === 'author' ? 'selected' : ''; ?>>Author</option>
            <option value="id" <?php echo $sort_option === 'id' ? 'selected' : ''; ?>>Book ID</option>
        </select>
    </form>

    <!-- Alert Section -->
    <?php if (!empty($alert_message)): ?>
        <div id="alert-box" class="alert">
            <?php echo htmlspecialchars($alert_message); ?>
        </div>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <form action="return_book.php" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Book ID</th>
                        <th>Title</th>
                        <th>Return</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td>
                                <button type="submit" name="return_id" value="<?php echo $row['borrow_id']; ?>" class="return-button">Return</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </form>
    <?php else: ?>
        <p>You have no books to return.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<!-- CSS and JavaScript for Alert Box -->
<style>
    /* Body Styles */
    body {
        background-color: #f0f4f8; /* Example body background color */
        color: #333; /* Text color for body */
        font-family: Arial, sans-serif; /* Font family */
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
        background-color: rgba(5, 55, 116, 0.3);
        color: #333; /* Dark text color */
        border-bottom: 2px solid #d4d4d4; /* Bottom border for headers */
    }

    /* Alert box styling */
    .alert {
        background-color: #4caf50;
        color: white;
        padding: 15px;
        margin-top: 10px;
        border-radius: 5px;
        text-align: center;
        opacity: 1;
        transition: opacity 0.5s ease-in-out;
    }

    /* Button Styling */
    .return-button {
        padding: 10px 15px;
        background-color: #54b24a; /* Cream color */
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    .return-button:hover {
        background-color: #54b24a; /* Slightly darker cream color on hover */
        transform: scale(1.05); /* Slight grow effect */
    }

    /* Hide alert after animation */
    .hide {
        opacity: 0;
    }
</style>

<script>
    // JavaScript to hide the alert after 2 seconds
    window.addEventListener('DOMContentLoaded', (event) => {
        const alertBox = document.getElementById('alert-box');
        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.add('hide');
            }, 2000);
        }
    });
</script>


