<?php
session_start();
include 'config.php';

// Redirect if user is not logged in or not a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

// Fetch and display student's borrowed books or allow borrowing new books
// Example query to fetch borrowed books
$query = "SELECT b.title, b.author FROM borrowed_books bb JOIN books b ON bb.book_id = b.id WHERE bb.user_id = ? AND bb.return_date IS NULL";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
    <h2>Student Panel</h2>
    <h3>Your Borrowed Books</h3>
    <table>
        <tr>
            <th>Title</th>
            <th>Author</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['author']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include 'footer.php'; ?>
