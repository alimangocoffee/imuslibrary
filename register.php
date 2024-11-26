<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session if not already started
}

ob_start(); // Start output buffering

include('config.php'); // Include database connection config
include('header.php'); // Include header

$message = ""; // Store error or success messages
$successMessage = ""; // Store success message for registration

// Handle registration logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']); // Trim whitespace
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    $role = 'student'; // Set default role

    // Check if username already exists
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "<div class='alert alert-danger'>Username already exists!</div>";
    } else {
        // Insert new user into the database
        $insert_query = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("sss", $username, $hashed_password, $role);
        $insert_stmt->execute();

        // Set success message for JavaScript to show
        $successMessage = "<div class='alert alert-success' id='success-alert'>Registration successful! Redirecting to login...</div>";

        // Redirect to login page after displaying the success message
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('success-alert').style.display = 'block';
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 1500); // Redirect after 1.5 seconds
            });
        </script>";
    }
}

ob_end_flush(); // Send output buffer
?>

<div class="auth-container">
    <h3>Register</h3>
    <?php 
    // Show success message if exists
    if ($successMessage) {
        echo $successMessage; 
    }
    // Show error message if exists
    echo $message; 
    ?>
    <form action="register.php" method="POST">
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required minlength="8">
        </div>
        <button type="submit">Register</button>
        <a href="login.php" class="pt-3">Already have an account? Login</a>
    </form>
</div>

<?php include('footer.php');  ?>

<!-- CSS for styling the register form -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&family=Poppins:wght@200;300;400;500;600;700&display=swap');
    .auth-container {
        width: 100%;
        max-width: 400px; 
        background: rgba(255, 255, 255, 0.8); 
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin: 50px auto; 
        display: flex;
        flex-direction: column;
        align-items: center; 
    }

    h3 {
        text-align: center;
        font-weight: bold;
        color: #14213d;
        margin-bottom: 20px;
    }

    /* Input Fields */
    .input-group {
        position: relative;
        width: 100%;
        margin: 10px 0;
    }

    /* Icons with background color */
    .input-group i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #d3d3d3;
        opacity: 0; /* Start hidden */
        transition: opacity 0.3s ease, background-color 0.3s ease; /* Smooth transition */
        background-color: rgba(255, 255, 255, 0.5); /* Lightened background */
        border-radius: 50%; /* Make it circular */
        padding: 5px; /* Add some padding */
    }

    /* Show icon on input focus */
    .input-group:hover i,
    .input-group input:focus + i {
        opacity: 1; /* Show the icon */
    }

    input {
        width: 100%;
        padding: 12px 12px 12px 40px; /* Added padding to the left for the icon */
        border: 1px solid #d3d3d3;
        border-radius: 6px;
        background-color: rgba(255, 255, 255, 0.8); /* Medium transparency */
        transition: border 0.3s;
    }

    input:focus {
        border-color: #4a4e69; /* Highlight on focus */
        outline: none;
    }

    /* Button Styling */
    button {
        width: 100%;
        padding: 12px;
        background-color: #007bff; /* Button color */
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1em;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    button:hover {
        background-color: #0056b3; /* Darker button color on hover */
        transform: scale(1.05); /* Slight grow effect */
    }

    /* Links Styling */
    a {
        display: block;
        text-align: center;
        color: #007bff; /* Link color */
        text-decoration: none;
        transition: color 0.3s ease;
    }

    a:hover {
        color: #0056b3; /* Darker link color on hover */
    }

    /* Alert Styles */
    .alert {
        text-align: center;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        display: block; /* Initially block to show when added */
    }

    .alert-success {
        background-color: #28a745; /* Green background for success */
        color: white;
    }

    .alert-danger {
        background-color: #e63946;
        color: white;
    }
</style>


