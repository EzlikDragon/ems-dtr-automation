<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['emp_id'])) {
    header("Location: dtr_login.php");
    exit();
}

$emp_id = $_SESSION['emp_id'];
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current = $_POST['current'];
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];

    $stmt = $conn->prepare("SELECT password FROM employee_accounts WHERE emp_id = ?");
    $stmt->bind_param("i", $emp_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if (!password_verify($current, $result['password'])) {
        $error = "Incorrect current password.";
    } elseif ($new !== $confirm) {
        $error = "New passwords do not match.";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE employee_accounts SET password = ? WHERE emp_id = ?");
        $update->bind_param("si", $hashed, $emp_id);
        $update->execute();
        $success = "Password changed successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; padding: 50px; text-align: center; }
        .box { background: white; padding: 30px; max-width: 400px; margin: auto; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
        input, button { padding: 10px; width: 100%; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #2e86de; color: white; border: none; cursor: pointer; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>

<div class="box">
    <h2>Change Password</h2>

    <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>

    <form method="post">
        <input type="password" name="current" placeholder="Current Password" required>
        <input type="password" name="new" placeholder="New Password" required>
        <input type="password" name="confirm" placeholder="Confirm New Password" required>
        <button type="submit">Update Password</button>
    </form>

    <br>
    <a href="dtr_home.php">← Back to Home</a>
</div>

</body>

<?php include 'footer.php';   ?> 
</html>
