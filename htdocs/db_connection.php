<?php

// Load environment variables (you may use a library like `vlucas/phpdotenv` or set them manually)
$servername = getenv('DB_SERVER') ?: 'sql313.infinityfree.com'; // Default to 'localhost' if no environment variable is set
$username = getenv('DB_USERNAME') ?: 'if0_37881933';      // Default to 'root'
$password = getenv('DB_PASSWORD') ?: 'bobbybob0901';          // Default to an empty password
$dbname = getenv('DB_NAME') ?: 'if0_37881933_mch_ems_ojt';         // Default to 'mch_emp'

//$servername = "localhost";
//$username = "root"; // Default for XAMPP
//$password = ""; // No password for XAMPP
//$dbname = "demovideo"; // Database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Set character encoding to UTF-8
$conn->set_charset("utf8mb4");

// Check connection
if ($conn->connect_error) {
    // Log the error instead of displaying it in production
    error_log("Database connection error: " . $conn->connect_error, 3, "/var/log/app_errors.log");
    die("Database connection failed. Please try again later.");
}


// Do NOT close the connection here!
// $conn->close();
?>

