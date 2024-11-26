<?php 
// Start output buffering and session
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include header
include 'header.php'; 
?>

<!-- Start of the content -->
<div class="container">
    <h1 class="p-3">Welcome to Imus City Public Library</h1>
    <p>Select an option from the menu below to get started.</p>

    <h2>Available Options:</h2>
    <button class="toggle-button" onclick="toggleOptions()">Show/Hide Options</button>
    <div class="options-list">
        <ul>
            <li><a href="guide.php">Guides</a></li>
        </ul>
    </div>
</div>

<?php 
// Include footer
include ('footer.php'); 
?>

<!-- CSS Styling -->
<style>
    
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
    
    .container {
        background: rgba(255, 255, 255, 0.8); 
        border-radius: 12px; 
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 40px;
        margin: 50px auto; 
        max-width: 900px; 
        opacity: 0; 
        animation: fadeInUp 0.6s forwards;
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

    /* Toggle Button Styles */
    .toggle-button {
        display: inline-block;
        padding: 10px 20px;
        margin: 20px auto;
        border: none;
        border-radius: 5px;
        background: linear-gradient(to right, #f3e9d2, #c9d6df); /* Gradient background */
        color: #333; /* Darker text color for contrast */
        font-size: 1em;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .toggle-button:hover {
        background: linear-gradient(to right, #e6e6b2, #b2c9d6); /* Slightly darker gradient on hover */
        transform: translateY(-2px);
    }

    /* Options List Styles */
    .options-list {
        display: none; /* Initially hidden */
        opacity: 0; /* For animation */
        transition: opacity 0.5s ease; /* Fade-in effect */
    }

    .options-list.show {
        display: block; /* Change to block when shown */
        opacity: 1; /* Fade-in effect */
    }

    .options-list ul {
        list-style: none;
        padding: 0;
    }

    .options-list ul li {
        padding: 10px 0;
    }

    .options-list ul li a {
        text-decoration: none;
        color: #007bff;
        transition: color 0.3s ease;
    }

    .options-list ul li a:hover {
        color: #0056b3;
    }

    /* General Styles */
    body {
        background: linear-gradient(to right, #f3e9d2, #c9d6df);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background-image: url('img/imuslibrary.jpg'); /* Your background image */
        background-size: cover; /* Cover the entire body */
        background-position: center; /* Center the background image */
    }
</style>

<!-- Add JavaScript for toggle functionality before the closing </body> tag -->
<script>
function toggleOptions() {
    const optionsList = document.querySelector('.options-list');
    optionsList.classList.toggle('show');
}
</script>
