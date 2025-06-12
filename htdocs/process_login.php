<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    if ($conn && $conn->ping()) {
        $username = $conn->real_escape_string($_POST['username']);
        $password = $_POST['password'];

        $sql = "SELECT * FROM admins WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $admin = $result->fetch_assoc();
            
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['firstname'] . " " . $admin['lastname'];
                $_SESSION['admin_role'] = $admin['role'];
                $_SESSION['admin_email'] = $admin['email'];

                header("Location: admin_dashboard.php"); // Redirect to dashboard after login
                exit();
            } else {
                $_SESSION['error'] = "Invalid password.";
                header("Location: index.php"); // Redirect back to index.php on failure
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid username.";
            header("Location: index.php"); // Redirect back to index.php on failure
            exit();
        }
    } else {
        die("Database connection is not active.");
    }
}
?>
