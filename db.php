<?php
// db.php - Database connection

$servername = "localhost";  // Database server (usually localhost)
$username = "root";         // MySQL username (for XAMPP it's usually 'root')
$password = "";             // MySQL password (empty for XAMPP by default)
$dbname = "blog_db";        // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
