<?php
$host = "localhost";
$user = "root";
// UPDATE THIS: Change "YOUR_MSI_PASSWORD" to the password you created during MySQL setup
$pass = "YOUR_MSI_PASSWORD"; 
$dbname = "health_v3";

// Standard port for standalone MySQL is 3306
$port = 3306; 

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    // This will help us debug if the password or port is wrong
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set character set to utf8 to handle special characters in addresses
$conn->set_charset("utf8");

?>
