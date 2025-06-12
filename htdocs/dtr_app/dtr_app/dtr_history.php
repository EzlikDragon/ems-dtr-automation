<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['emp_id'])) {
    header("Location: dtr_login.php");
    exit();
}

$emp_id = $_SESSION['emp_id'];

$stmt = $conn->prepare("SELECT date, time_in, time_out, status FROM dtr WHERE emp_id = ? ORDER BY date DESC");
$stmt->bind_param("i", $emp_id);
$stmt->execute();
$res = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My DTR History</title>
    <style>
        body { font-family: Arial; background: #f8f9fa; padding: 50px; }
        .container { max-width: 700px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: center; }
        th { background-color: #f0f0f0; }
        a.back { display: inline-block; margin-top: 15px; text-decoration: none; color: #2e86de; }
    </style>
</head>
<body>

<div class="container">
    <h2>My DTR History</h2>
    <table>
        <tr>
            <th>Date</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Status</th>
        </tr>
        <?php while($row = $res->fetch_assoc()): ?>
            <tr>
                <td><?= $row['date'] ?></td>
                <td><?= $row['time_in'] ? date("g:i A", strtotime($row['time_in'])) : '-' ?></td>
                <td><?= $row['time_out'] ? date("g:i A", strtotime($row['time_out'])) : '-' ?></td>
                <td><?= $row['status'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a class="back" href="dtr_home.php">&larr; Back to Home</a>
</div>

</body>
</html>
