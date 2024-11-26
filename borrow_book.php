<?php
// Start session only if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config.php';
include 'header.php';

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$alert_message = ""; // Store alert message
$sort_option = isset($_POST['sort_by']) ? $_POST['sort_by'] : 'title'; // Default sorting by title

// Handle the borrow request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];

    // Check if the book is available
    $query = "SELECT available, title FROM books WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $stmt->bind_result($available, $book_title);
    $stmt->fetch();
    $stmt->close();

    if ($available > 0) {
        // Insert into borrowed_books and reduce availability
        $borrow_query = "INSERT INTO borrowed_books (user_id, book_id, borrow_time) VALUES (?, ?, NOW())";
        $update_query = "UPDATE books SET available = available - 1 WHERE id = ?";

        $borrow_stmt = $conn->prepare($borrow_query);
        $borrow_stmt->bind_param("ii", $user_id, $book_id);
        if ($borrow_stmt->execute()) {
            // Update the availability of the book
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("i", $book_id);
            $update_stmt->execute();
            $update_stmt->close();

            $_SESSION['borrow_time'] = time();
            $alert_message = "You have borrowed \"$book_title\". Please return it within 3 hours to avoid penalties.";

            echo "<script>sessionStorage.clear();</script>"; // Clear flags on new borrow
        } else {
            $alert_message = "Error borrowing the book.";
        }
        $borrow_stmt->close();
    } else {
        $alert_message = "Sorry, this book is not available.";
    }
}

// Fetch books based on sort option
$order_by = ($sort_option === 'id') ? 'id' : (($sort_option === 'author') ? 'author' : 'title');
$query = "SELECT id, title, author, available FROM books WHERE available > 0 ORDER BY $order_by ASC";
$result = $conn->query($query);
?>

<div class="container">
    <h2>Borrow Book</h2>

    <form action="borrow_book.php" method="POST" style="margin-bottom: 20px;">
        <label for="sort_by">Sort by:</label>
        <select name="sort_by" id="sort_by" onchange="this.form.submit()">
            <option value="title" <?php echo $sort_option === 'title' ? 'selected' : ''; ?>>Title</option>
            <option value="author" <?php echo $sort_option === 'author' ? 'selected' : ''; ?>>Author</option>
            <option value="id" <?php echo $sort_option === 'id' ? 'selected' : ''; ?>>Book ID</option>
        </select>
    </form>

    <?php if (!empty($alert_message)): ?>
        <div id="alert-box" class="alert">
            <?php echo htmlspecialchars($alert_message); ?>
        </div>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <form action="borrow_book.php" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Book ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Available Copies</th>
                        <th>Borrow</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                            <td><?php echo $row['available']; ?></td>
                            <td>
                                <button type="submit" name="book_id" value="<?php echo $row['id']; ?>" class="borrow-button">Borrow</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </form>
    <?php else: ?>
        <p>No books available to borrow at the moment.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<style>
     @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
    body {
        background-color: #f0f4f8;
        font-family: "Poppins", sans-serif;
    }

    .container {
        background-color: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 12px;
        max-width: 800px;
        margin: 20px auto;
    }

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

    .hide {
        opacity: 0;
    }

    /* Updated styles for the table header and borrow button */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: linear-gradient(to right, #54b24a, #7cde71);
        color: #000; /* Text color for header */
        padding: 10px;
    }

    td {
        padding: 10px;
        border: 1px solid #ddd;
    }

    .borrow-button {
        background: linear-gradient(to right, #54b24a, #7cde71);
        border: none;
        border-radius: 5px;
        padding: 10px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
        color: #000; /* Text color for button */
    }

    .borrow-button:hover {
        background: linear-gradient(to right, #54b24a, #7cde71);
        transform: scale(1.05);
    }
</style>

<script>
    // Hide alert box after 2 seconds
    document.addEventListener('DOMContentLoaded', function () {
        const alertBox = document.getElementById('alert-box');
        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.add('hide');
            }, 2000); // 2-second delay
        }
    });

    function checkTimeLeft() {
        const borrowTime = <?php echo $_SESSION['borrow_time'] ?? 'null'; ?>;
        if (!borrowTime) return;

        const now = Math.floor(Date.now() / 1000);
        const timeElapsed = now - borrowTime;
        const timeLeft = (3 * 60 * 60) - timeElapsed; // 3 hours in seconds

        if (timeLeft <= 300 && timeLeft > 0 && !sessionStorage.getItem('fiveMinutesAlertShown')) {
            alert('You have 5 minutes left to return the book!');
            sessionStorage.setItem('fiveMinutesAlertShown', 'true');
        } else if (timeLeft <= 0 && !sessionStorage.getItem('penaltyAlertShown')) {
            alert('You need to return the book now! You will incur a penalty of 20 pesos per hour.');
            sessionStorage.setItem('penaltyAlertShown', 'true');
        }
    }

    setInterval(checkTimeLeft, 1000); // Check every second
</script>
