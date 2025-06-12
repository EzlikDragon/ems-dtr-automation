<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['emp_id']) || !isset($_POST['action'])) {
    header("Location: dtr_home.php");
    exit();
}

$emp_id = $_SESSION['emp_id'];
$today = date('Y-m-d');

// Check existing DTR
$check = $conn->prepare("SELECT * FROM dtr WHERE emp_id = ? AND date = ?");
$check->bind_param("is", $emp_id, $today);
$check->execute();
$result = $check->get_result();
$dtr = $result->fetch_assoc();

if ($_POST['action'] == "time_in" && !$dtr) {
    $now_time = date('H:i:s');

    // Determine DTR status
    if ($now_time < '08:15:00') {
        $status = 'PRESENT';
    } elseif ($now_time >= '08:15:00' && $now_time < '12:00:00') {
        $status = 'LATE';
    } else {
        $status = 'HALF-DAY';
    }

    $insert = $conn->prepare("INSERT INTO dtr (emp_id, date, time_in, status) VALUES (?, ?, NOW(), ?)");
    $insert->bind_param("iss", $emp_id, $today, $status);
    $insert->execute();
}

if ($_POST['action'] == "time_out" && $dtr && !$dtr['time_out']) {
    $update = $conn->prepare("UPDATE dtr SET time_out = NOW() WHERE id = ?");
    $update->bind_param("i", $dtr['id']);
    $update->execute();
}

$scan_id = $_POST['scan_id'] ?? '';
$emp = $conn->query("SELECT emp_id FROM employee WHERE emp_id = '$scan_id' OR bio_id = '$scan_id'")->fetch_assoc();

if ($emp) {
    $emp_id = $emp['emp_id'];
    // Proceed with regular DTR logic...
} else {
    echo "<script>alert('Unrecognized ID. Please try again.');window.location.href='dtr_home.php';</script>";
}


header("Location: dtr_home.php");
exit();
?>
