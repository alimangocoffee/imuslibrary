<?php
// Start output buffering
ob_start();

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imus City Public Library</title>
    <script src="js/bootstrap.js"></script>
    <script src="js/animations.js" defer></script>
    <link rel="stylesheet" href="library.css">
    <script src="library.js" defer></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    

    <style>
        
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

        /* Reset Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #f3e9d2, #c9d6df);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: url('img/imuslibrary.jpg'); /* Add your image path here */
            background-size: cover; /* Cover the entire body */
            background-position: center; /* Center the background image */
            background-repeat: no-repeat; /* Do not repeat the background image */
        }

        /* Navigation Bar */
        nav {
            background-color: #053774;
            height: 60px;
            padding: 30px;
            box-shadow: none; /* Remove shadow to enhance transparency */
            display: flex;
            justify-content: space-between; /* Space between menu items and social icons */
            align-items: center; /* Align items vertically in the center */
            
        }

        nav ul {
            display: flex;
            list-style: none;
            margin-top: 10px;
        }

        nav ul li {
            display: inline; /* Ensure list items are inline */
            margin: 0 10px; /* Space between items */
            color: white;   
            text-decoration: none;
            font-size: 1em;
            transition: color 0.3s ease;
            margin-right: 15px;
            margin-top: 15px;
            
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1em;
            transition: color 0.3s ease;
            margin-right: 10px;
            line-height: 1.5;
        }

        nav ul li:last-child {
            margin-left: 20px; /* Additional space before Reset Password and Logout */
        }

        nav ul li a:hover {
            color: #54b24a;
            
        }
        nav .logo {
            margin-right: 10px;
            margin-top: 5px;
        }
        nav img {
            height: 50px; 
            width: 50px;
            position: relative;
        }

        /* Social Icons */
        .social-icons {
            display: flex;
        }

        .social-icons a {
            color: white;
            margin: 0 10px;
            font-size: 1.5em; /* Size of the icons */
            transition: color 0.3s ease;
        }

        .social-icons a:hover {
            color: #54b24a; 
        }

        /* Main Container */
    
        /* Header Styles */
        h1, h2 {
            text-align: center;
            color: #14213d;
        }

        /* Footer */
        footer {
            background-color:#053774; /* Transparent background */
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: auto;
            box-shadow: none; /* Remove shadow for a cleaner look */
            backdrop-filter: blur(10px); /* Add a blur effect behind the footer */
        }

        footer p {
            margin: 0;
        }

        /* Animations */
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
    </style>
    
</head>
<body>
<nav>
    <ul>
    <div class="logo ">
            <img src="img/logo.png" alt="Logo">
        </div>
        <li>Imus City Public Library</li>

        <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
        <?php if (!isset($_SESSION['role'])): ?>
            <li><a href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['role'])): ?>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <li><a href="add_book.php"><i class="fas fa-plus-circle"></i> Add Book</a></li>
                <li><a href="report.php"><i class="fas fa-file-alt"></i> Reports</a></li>
                <li><a href="penalties.php"><i class="fas fa-exclamation-triangle"></i> View Penalties</a></li>
            <?php elseif ($_SESSION['role'] === 'student'): ?>
                <li><a href="borrow_book.php"><i class="fas fa-book"></i> Borrow Book</a></li>
                <li><a href="book_list.php"><i class="fas fa-list"></i> List of Books</a></li>
                <li><a href="protocol.php"><i class="fas fa-book-open"></i> Rules</a></li>
            <?php endif; ?>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <?php endif; ?>
    </ul>
    <div class="social-icons">
        <a href="https://facebook.com" target="_blank" aria-label="Facebook">
            <i class="fab fa-facebook"></i>
        </a>
        <a href="https://instagram.com" target="_blank" aria-label="Instagram">
            <i class="fab fa-instagram"></i>
        </a>
    </div>
</nav>











    
    










