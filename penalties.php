<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config.php';
include 'header.php';

// Update query to calculate the penalty based on 20 pesos per hour
$query = "
    SELECT bb.id AS borrowed_book_id, bb.user_id, u.username, 
           bb.book_id, b.title, 
           TIMESTAMPDIFF(SECOND, bb.borrow_time, NOW()) AS overdue_duration,
           (FLOOR(TIMESTAMPDIFF(SECOND, bb.borrow_time, NOW()) / 3600) * 20) AS penalty_amount
    FROM borrowed_books bb
    JOIN users u ON bb.user_id = u.id
    JOIN books b ON bb.book_id = b.id
    WHERE TIMESTAMPDIFF(SECOND, bb.borrow_time, NOW()) > 10800 -- 3 hours in seconds
      AND bb.returned_at IS NULL
    ORDER BY bb.id DESC";

$result = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_done'])) {
    $borrowedBookId = $_POST['borrowed_book_id'];
    $penaltyAmount = $_POST['penalty_amount'];

    // Update the borrowed book as returned with penalty amount
    $updateQuery = "UPDATE borrowed_books SET returned_at = NOW(), penalty_amount = ? WHERE id = ?";
    if ($stmt = $conn->prepare($updateQuery)) {
        $stmt->bind_param("di", $penaltyAmount, $borrowedBookId);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: penalties.php");
    exit;
}
?>

<div class="container">
    <h2>Penalty Report</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Book ID</th>
                    <th>Book Title</th>
                    <th>Overdue Duration (seconds)</th>
                    <th>Penalty Amount (Pesos)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['user_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo $row['book_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo $row['overdue_duration']; ?></td>
                        <td><?php echo number_format($row['penalty_amount'], 2); ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="borrowed_book_id" value="<?php echo $row['borrowed_book_id']; ?>">
                                <input type="hidden" name="penalty_amount" value="<?php echo $row['penalty_amount']; ?>">
                                <button type="submit" name="mark_done" class="btn btn-success">Mark as Done</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No penalties recorded.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<!-- CSS Styling -->
<style>
    body {
        background-color: #f0f4f8;
        color: #333;
        font-family: Arial, sans-serif;
    }

    .container {
        background-color: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        margin: 20px auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th {
        background: linear-gradient(45deg, #d2c28e, #f0e68c); /* Gradient background for table header */
        color: white; /* Text color */
    }

    td {
        background-color: #fff;
    }

    .btn-success {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2; /* Light gray for even rows */
    }

    p {
        text-align: center;
        font-size: 18px;
        color: #333;
    }
</style>
