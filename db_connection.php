<?php
$servername = "localhost";
$username = "root"; // Update as needed
$password = ""; // Update as needed
$dbname = "gaming_platform"; // Update as needed

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
