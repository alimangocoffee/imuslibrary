<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session if not already started
}

ob_start(); // Start output buffering

include('config.php'); // Include database connection config
include('header.php'); // Include header

$message = ""; // Store error or success messages
$successMessage = ""; // Store success message for login

// Handle login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']); // Trim whitespace
    $password = $_POST['password'];

    // Prepare SQL query to fetch user data
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Store user details in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Set success message for JavaScript to show
        $successMessage = "<div class='alert alert-success' id='success-alert'>Login successful! Redirecting...</div>";

        // Redirect based on user role after 1.5 seconds
        $redirectUrl = ($user['role'] === 'admin') ? 'admin_panel.php' : 'user_panel.php';

        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('success-alert').style.display = 'block';
                setTimeout(function() {
                    window.location.href = '$redirectUrl';
                }, 1500); // Redirect after 1.5 seconds
            });
        </script>";
    } else {
        // Set error message for invalid credentials
        $message = "<div class='alert alert-danger' id='error-alert'>Invalid login credentials!</div>";
    }
}

ob_end_flush(); // Send output buffer
?>

<div class="auth-container">
    <h3>Login</h3>
    <?php 
    // Show success message if exists
    if ($successMessage) {
        echo $successMessage; 
    }
    // Show error message if exists
    if ($message) {
        echo $message; 
    }
    ?>
    <form action="login.php" method="POST">
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required minlength="8">
        </div>
        <button type="submit">Login</button>
        <a href="reset_password.php" class="pt-3">Forgot Password?</a>
        <a href="register.php" class="pt-1">Don't have an account? Register Here!</a>
    </form>
</div>

<?php include('footer.php'); // Include footer ?>

<!-- CSS for styling the login form -->
<style>
    .auth-container {
        width: 100%;
        max-width: 400px;
        background-color: rgba(255, 255, 255, 0.8);
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

    .input-group {
        position: relative;
        width: 100%;
        margin: 10px 0;
    }

    .input-group i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #d3d3d3;
        opacity: 0;
        transition: opacity 0.3s ease, background-color 0.3s ease;
        background-color: rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        padding: 5px;
    }

    .input-group:hover i,
    .input-group input:focus + i {
        opacity: 1;
    }

    input {
        width: 100%;
        padding: 12px 12px 12px 40px;
        border: 1px solid #d3d3d3;
        border-radius: 6px;
        background-color: rgba(255, 255, 255, 0.8);
        transition: border 0.3s;
    }

    input:focus {
        border-color: #4a4e69;
        outline: none;
    }

    button {
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1em;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    button:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }

    a {
        display: block;
        text-align: center;
        color: #007bff;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    a:hover {
        color: #0056b3;
    }

    .alert {
        text-align: center;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        display: block;
    }

    .alert-success {
        background-color: #28a745;
        color: white;
    }

    .alert-danger {
        background-color: #e63946;
        color: white;
    }
</style>




