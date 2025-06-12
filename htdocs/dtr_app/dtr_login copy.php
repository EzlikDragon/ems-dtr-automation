<?php
session_start();
include 'db_connection.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM employee_accounts WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['emp_id'] = $user['emp_id'];
        header("Location: dtr_home.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>DTR Login</title>
    <style>
        body { font-family: Arial; background:rgb(255, 255, 255); display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input { display: block; width: 100%; margin-bottom: 10px; padding: 10px; }
        button { padding: 10px; width: 100%; background:rgb(255, 143, 212); color: white; border: none; border-radius: 5px; }
        .error { color: red; }
    </style>


</head>
<body>

<div class="login-box">
    <h2>Employee DTR Login</h2>
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
