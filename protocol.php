<?php
// Start output buffering
ob_start();

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config.php'; 
include 'header.php'; // Include your header file

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!-- Start of the content -->
<div class="container">
    <h2>Library Borrowing Protocol</h2>
    
    <p>Welcome to our library! Below are the protocols and rules you should follow when borrowing books:</p>
    
    <h3>Borrowing Rules</h3>
    <ul>
        <li>You may borrow up to 5 books at a time.</li>
        <li>The maximum borrowing period is 3 hours. Please return the books on time.</li>
        <li>If you fail to return the book within the allowed time, penalties will apply.</li>
        <li>The penalty is 5 pesos for every second the book is overdue.</li>
    </ul>
    
    <h3>Penalties</h3>
    <p>If you have any overdue books, you can view your penalties on the <a href="penalties.php">Penalties Page</a>.</p>
    
    <h3>Contact Information</h3>
    <p>If you encounter any issues or have questions regarding the borrowing process, please contact the library staff:</p>
    <ul>
        <li>Email: cityofimus.gov.ph</li>
        <li>Phone: (046) 472-2623</li>
    </ul>
    
    <h3>Helpful Links</h3>
    <ul>
        <li><a href="borrow_book.php">Borrow a Book</a></li>
        <li><a href="penalties.php">View Penalties</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<?php include 'footer.php'; // Include footer ?>

<?php
// End output buffering and flush the output
ob_end_flush();
?>

<style>
    /* Blurred Background Effect for Container */
    .container {
        background: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
        backdrop-filter: blur(10px); /* Apply blur effect */
        border-radius: 12px; /* Maintain your existing border radius */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); /* Add shadow for depth */
        padding: 40px; /* Keep existing padding */
        margin: 20px auto; /* Center the container */
        max-width: 900px; /* Maximum width for the container */
        opacity: 0; /* For animation effect */
        animation: fadeInUp 0.6s forwards; /* Animation for fade-in effect */
    }

    /* Keyframes for fade-in animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* General Styles */
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(to right, #f3e9d2, #c9d6df);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background-image: url('img/imuslibrary.jpg'); /* Your background image */
        background-size: cover; /* Cover the entire body */
        background-position: center; /* Center the background image */
        background-repeat: no-repeat; /* Do not repeat the background image */
    }
</style>
