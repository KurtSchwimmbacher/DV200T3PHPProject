<?php 
// Start the session
session_start();

// Database connection details
$server = "localhost";
$username = "root";
$password = "";
$dbname = "user_system";

// Create a connection
$conn = new mysqli($server, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
