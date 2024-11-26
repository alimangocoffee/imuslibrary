<?php
include 'config.php';
include 'header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Reset all borrowed books when reset button is pressed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_report'])) {
    $deleteQuery = "DELETE FROM borrowed_books";
    $conn->query($deleteQuery);
    header("Location: report.php");
    exit;
}

// Fetch borrowed books with penalty details
$query = "
    SELECT bb.id, u.username, b.title, bb.borrow_date AS borrow_date, 
           bb.returned_at, bb.penalty_amount
    FROM borrowed_books bb
    JOIN users u ON bb.user_id = u.id
    JOIN books b ON bb.book_id = b.id
    WHERE u.role = 'student' -- Only show penalties for regular users
    ORDER BY bb.id DESC";

$result = $conn->query($query);
?>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<div class="container">
<h1>Employee Report</h1>
<form method="POST" style="margin-bottom: 20px;">
        <button type="submit" name="reset_report" class="btn btn-reset">Reset Report</button>
    </form>

    <?php if ($result->num_rows > 0): ?>
<!-- DataTable -->
<table id="employeeTable" class="display">
    <thead>
        <tr>
            <th>Username</th>
            <th>Book Title</th>
            <th>Borrow Date</th>
            <th>Return Date</th>
            <th>Penalty Amount (Pesos)</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo date("Y-m-d H:i:s", strtotime($row['borrow_date'])); ?></td> 
                        <td><?php echo $row['returned_at'] ? date("Y-m-d H:i:s", strtotime($row['returned_at'])) : 'Not Returned'; ?></td>
                        <td><?php echo $row['penalty_amount'] ? number_format($row['penalty_amount'], 2) : 'No Penalty'; ?></td>
                    </tr>
                <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
        <p>No borrowed books to display.</p>
<?php endif; ?>
</div>

    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('#employeeTable').DataTable();
        });
    </script>

<?php include 'footer.php'; ?>

<style>
    .btn-reset {
        background: linear-gradient(to right, #54b24a, #7cde71);
        border: none;
        padding: 10px 20px;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s;
        border-radius: 5px;
    }

    .btn-reset:hover {
        background: linear-gradient(to right, #54b24a, #7cde71);
    }

    .container {
        margin-top: 30px;
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 24px;
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th, td {
        padding: 12px 15px;
        border: 1px solid #ddd;
        text-align: center;
    }

    table#employeeTable th {
        background: linear-gradient(to right, #54b24a, #7cde71);
        color: #000000; /* Text color */
        font-weight: bold;
    }

    td {
        background-color: #fff;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2; /* Light gray for even rows */
    }

    p {
        text-align: center;
        font-size: 18px;
        color: #fff;
    }
</style>
