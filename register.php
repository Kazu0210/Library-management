<?php
session_start();
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Retrieve form data
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password']; // Fixed mismatch
    $role = 'member';

    // Validate passwords
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: register.php"); // No echo before header
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username or email already exists
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ? OR email = ?"); // Fixed table name
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Username or email already exists!";
        header("Location: register.php"); // No echo before header
        exit();
    }

    // Insert the user into the database
    $stmt = $conn->prepare("INSERT INTO user (full_name, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $full_name, $username, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful!";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
        header("Location: register.php");
        exit();
    }

    // Close the statement
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home | Library Management System</title>
</head>
<body>
    <form action="" method="POST">
        <h1>Register</h1>

        <div class="input-cont">
            <label for="fullname">Full Name</label>
            <input type="text" name="full_name" id="fullname-input" required>
        </div>

        <div class="input-cont">
            <label for="username">Username</label>
            <input type="text" name="username" id="username-input" required>
        </div>

        <div class="input-cont">
            <label for="email">Email</label>
            <input type="email" name="email" id="email-input" required>
        </div>

        <div class="input-cont">
            <label for="password">Password</label>
            <input type="password" name="password" id="password-input" required>
        </div>

        <div class="input-cont">
            <label for="confirmpass">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirmpass-input" required>
        </div>

        <button type="submit" name="register" id="register-btn">Register</button>
    </form>
</body>
</html>
