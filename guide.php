<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config.php'; 
include 'header.php'; // Include your header file

?>

<div class="container">
    <h2>Guide to the Imus Public Library</h2>

    <p>Welcome to our library! Follow these steps to navigate our library management system efficiently:</p>

    <h3>Step 1: Logging In</h3>
    <ol>
        <li>Visit the <strong>Login</strong> page from the homepage.</li>
        <li>Enter your registered username and password.</li>
        <li>Click the <strong>Login</strong> button to access your account.</li>
    </ol>

    <h3>Step 2: Borrowing a Book</h3>
    <ol>
        <li>Once logged in, navigate to the <strong>Borrow Book</strong> section.</li>
        <li>Browse the list of available books or use the search feature.</li>
        <li>Select the book you wish to borrow and click the <strong>Borrow</strong> button.</li>
        <li>A confirmation message will appear, indicating the successful borrowing of the book.</li>
        <li>Note the return time, as you have a maximum of 1 hour to return the book to avoid penalties.</li>
    </ol>

    <h3>Step 3: Checking Your Penalties</h3>
    <ol>
        <li>Go to the <strong>Penalties</strong> page from the menu.</li>
        <li>If you have any overdue books, the page will display the titles along with the penalties incurred.</li>
        <li>Ensure you return any overdue books promptly to avoid further charges.</li>
    </ol>

    <h3>Step 4: Returning a Book</h3>
    <ol>
        <li>Return your borrowed book to the library before the borrowing period ends.</li>
        <li>If the book is overdue, check your penalties on the <strong>Penalties</strong> page.</li>
    </ol>

    <h3>Step 5: Logging Out</h3>
    <ol>
        <li>After completing your activities, click on the <strong>Logout</strong> option to end your session.</li>
        <li>This helps keep your account secure.</li>
    </ol>

    <h3>Contact Support</h3>
    <p>If you have any questions or encounter issues, please contact the library staff:</p>
    <ul>
        <li>Email: cityofimus.gov.ph</li>
        <li>Phone: (046) 472-2623</li>
    </ul>

    <h3>Helpful Links</h3>
    <ul>
        <li><a href="penalties.php">View Penalties</a></li>
        <li><a href="borrow_book.php">Borrow a Book</a></li>
        <li><a href="return_book.php">Return a Book</a></li>
        
    </ul>
</div>

<?php include 'footer.php'; // Include footer ?>


<!-- Add the following CSS to your styles.css or inline -->
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
    background-image: url('imuslibrary.jpg'); /* Your background image */
    background-size: cover; /* Cover the entire body */
    background-position: center; /* Center the background image */
    background-repeat: no-repeat; /* Do not repeat the background image */
}
</style>
