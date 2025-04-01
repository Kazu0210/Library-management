<?php
session_start();
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Secure query to prevent SQL injection
    $stmt = $conn->prepare("SELECT user_id, password FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        
        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "Login successful!";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password!";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "User not found!";
        header("Location: login.php");
        exit();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($_SESSION['error'])) { echo "<p style='color:red;'>" . $_SESSION['error'] . "</p>"; unset($_SESSION['error']); } ?>
    <form action="" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" name="login" value="Login">
    </form>
</body>
</html>
