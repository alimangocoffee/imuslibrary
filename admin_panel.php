<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'header.php';
?>

<div class="container">
    <h2 class="welcome-message">Admin Panel</h2>
    <div class="image-gallery m-5">
            <div class="image-card">
                <img src="img/add_book.png" alt="Add Books" class="icon">
                <h3>Add Books</h3>
                <p>Add books/materials in the library.</p>
                <a href="add_book.php" class="card-link">Go to Add Books</a>
            </div>
            <div class="image-card">
                <img src="img/report.png" alt="Report" class="icon">
                <h3>Reports</h3>
                <p>View who accessed or what book was taken.</p>
                <a href="report.php" class="card-link">Go to Report</a>
            </div>
            <div class="image-card">
                <img src="img/penalties.png" alt="Penalties" class="icon">
                <h3>Penalties</h3>
                <p>View the penalties to users who violated terms.</p>
                <a href="penalties.php" class="card-link">Go to Penalties</a>
            </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<!-- CSS for styling the input fields -->
<style>
    body {
        background-color: #f0f4f8;
        font-family: 'Poppins', sans-serif;
        color: #fff;
    }

    .container {
        backdrop-filter: blur(10px); /* Blur effect for the container */
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
        background-color: rgba(255, 255, 255, 0.7); 
    }

    .welcome-message {
        text-align: center;
        color: #000000; /* Light color for text */
        margin: 50px 0;
        font-size: 48px; /* Larger font size */
        animation: slide-in 1s ease-out forwards; /* Animation */
        text-shadow: 0 0 5px rgba(255, 255, 255, 0.8), 0 0 10px rgba(255, 255, 255, 0.6); /* Lighting effect */
    }

    @keyframes slide-in {
        0% {
            transform: translateY(-50px); /* Start above */
            opacity: 0; /* Start invisible */
        }
        100% {
            transform: translateY(0); /* End in place */
            opacity: 1; /* Fully visible */
        }
    }

    .image-gallery {
        display: flex;
        justify-content: space-around;
        gap: 20px;
        margin: 0 auto;
        max-width: 1200px; /* Set a maximum width for the gallery */
        flex-wrap: wrap; /* Allow wrapping on smaller screens */
    }

    .image-card {
        width: 200px; /* Width of the image cards */
        height: 400px; /* Height of the image cards */
        background-color: rgba(5, 55, 116, 0.3); 
        border-radius: 12px;
        display: flex;
        flex-direction: column; /* Stack elements vertically */
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 20px;
        backdrop-filter: blur(8px); /* Blur effect */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Add shadow for depth */
        transition: transform 0.3s; /* Smooth hover effect */
    }

    .image-card:hover {
        transform: scale(1.05); /* Slightly enlarge on hover */
    }

    .icon {
        width: 80px; /* Width of the icon */
        height: 80px; /* Height of the icon */
        margin-bottom: 15px; /* Space between icon and text */
    }

    h3 {
        color: #ffff; /* Title color */
        margin: 10px 0; /* Space above and below title */
    }

    .card-link {
        margin-top: 15px;
        padding: 10px;
        background-color: #fff; /* Cream color for buttons */
        color: #14213d;
        text-decoration: none;
        border-radius: 8px;
        transition: background-color 0.3s;
        border: 1px solid #14213d; /* Border for the button */
    }

    .card-link:hover {
        background: linear-gradient(to right, #54b24a, #7cde71);
        color: #fff;
    }
   
</style>
