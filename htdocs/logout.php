<?php
session_start();
include 'db_connection.php'; // ✅ This is required to define $conn

if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];

    // Mark admin as logged out
    $conn->query("UPDATE admins SET is_logged_in = 0 WHERE id = $admin_id");
}

// Destroy session
session_unset();
session_destroy();

// Redirect to login page
header("Location: login.php");
exit;
