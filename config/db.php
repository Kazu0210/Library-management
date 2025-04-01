<?php
// Database connection settings
$servername = "localhost";  // Database host (for XAMPP, it's usually localhost)
$username = "root";         // MySQL username (for XAMPP, the default is root)
$password = "";             // MySQL password (for XAMPP, the default is an empty string)
$dbname = "library_db";     // Database name

// Create a connection to the database using mysqli with error handling
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set the character set for the connection (UTF-8)
$conn->set_charset("utf8");

// This connection can now be included in any PHP file where you need to interact with the database.
?>