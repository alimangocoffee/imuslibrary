<?php
// Start session only if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include your config and header files
include 'config.php'; // Ensure you have your database connection
include 'header.php';

// Handle admin registration logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    $role = 'admin'; // Set the role to admin

    // Check if the username already exists
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<div class='alert alert-danger'>Username already exists! Please choose another username.</div>";
    } else {
        // If the username is unique, insert the new admin user into the database
        $insert_query = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("sss", $username, $hashed_password, $role);
        
        if ($insert_stmt->execute()) {
            echo "<div class='alert alert-success'>Admin account created successfully!</div>";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'login.php'; // Redirect to login after 2 seconds
                    }, 2000);
                  </script>";
        } else {
            echo "<div class='alert alert-danger'>Error creating admin account.</div>";
        }

        $insert_stmt->close();
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link your CSS file -->
</head>
<body>
    <div class="container">
        <h2>Admin Registration</h2>
        <form action="admin_register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Register Admin</button>
        </form>
    </div>
</body>
</html>
