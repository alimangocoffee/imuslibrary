<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session if not already started
}

ob_start(); // Start output buffering

include('config.php'); // Include database connection config
include('header.php'); // Include header

$message = ""; // Store error or success messages
$successMessage = ""; // Store success message for reset password

// Handle reset password logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']); // Trim whitespace
    $old_password = $_POST['old_password']; // Get old password from form
    $new_password = $_POST['new_password'];
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password

    // Prepare SQL query to fetch user data
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Verify the old password
        if (password_verify($old_password, $user['password'])) {
            // Update user password in the database if old password is correct
            $update_query = "UPDATE users SET password = ? WHERE username = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ss", $hashed_password, $username);
            
            if ($update_stmt->execute() && $update_stmt->affected_rows > 0) {
                // Set success message for JavaScript to show
                $successMessage = "<div class='alert alert-success' id='success-alert'>Password reset successful! Redirecting to login...</div>";

                // Redirect to login page after 1.5 seconds
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('success-alert').style.display = 'block';
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 1500); // Redirect after 1.5 seconds
                    });
                </script>";
            } else {
                $message = "<div class='alert alert-danger'>Failed to reset password!</div>";
            }
        } else {
            // Old password is incorrect
            $message = "<div class='alert alert-danger'>Invalid username or password!</div>";
        }
    } else {
        // Username not found
        $message = "<div class='alert alert-danger'>Invalid username or password!</div>";
    }
}

ob_end_flush(); // Send output buffer
?>

<div class="auth-container">
    <h3>Reset Password</h3>
    <?php 
    // Show success message if exists
    if ($successMessage) {
        echo $successMessage; 
    }
    // Show error message if exists
    echo $message; 
    ?>
    <form action="reset_password.php" method="POST">
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="old_password" placeholder="Old Password" required minlength="8">
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="new_password" placeholder="New Password" required minlength="8">
        </div>
        <button type="submit">Reset Password</button>
        <a href="login.php">Remembered your password? Login</a>
    </form>
</div>

<?php include('footer.php'); // Include footer ?>

<!-- CSS for styling the reset password form -->
<style>
    /* Auth Container Styles */
    .auth-container {
        width: 100%;
        max-width: 400px; /* Maintain a max-width for better readability */
        background-color: rgba(255, 255, 255, 0.8); /* Medium transparent background */
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin: 50px auto; /* Center the container */
        display: flex;
        flex-direction: column;
        align-items: center; /* Center items horizontally */
    }

    h3 {
        text-align: center;
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
        margin-top: 10px;
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
        display: block; /* Make alerts visible */
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



