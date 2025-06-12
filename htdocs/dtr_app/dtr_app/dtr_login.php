<?php
session_start();
include 'db_connection.php';

$error = '';
$emp_id = isset($_POST['emp_id']) ? trim($_POST['emp_id']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $emp_id !== '') {
    $stmt = $conn->prepare("SELECT * FROM employee_accounts WHERE emp_id = ?");
    $stmt->bind_param("s", $emp_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['emp_id'] = $emp_id;
        header("Location: dtr_home.php");
        exit();
    } else {
        $error = "Invalid Employee ID.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>DTR Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;

        }

        .login-box {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 360px;
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 20px;
        }

        .login-box input[type="text"] {
            width: 90%;
            padding: 10px;
            font-size: 16px;
            margin-top: 10px;
        }

        .login-box button {
            margin-top: 15px;
            padding: 10px 25px;
            background: var(--primary, #007bff);
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .error {
            margin-top: 15px;
            color: red;
        }
    </style>
</head>
<body onload="document.getElementById('emp_id').focus();">

<div class="login-box">
    <h2>Scan Employee ID</h2>
    <form method="POST">
        <input type="text" name="emp_id" id="emp_id" placeholder="Scan barcode here..." autocomplete="off" required>
        <br>
        <button type="submit">Login</button>
    </form>
    <?php if (!empty($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
</div>

</body>
</html>
