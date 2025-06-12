<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emp_id'])) {
    $emp_id = intval($_POST['emp_id']);

    // Delete from all related tables in correct order
    $tables = ['family', 'children', 'personal_info', 'employee_accounts', 'dtr'];

    foreach ($tables as $table) {
        $stmt = $conn->prepare("DELETE FROM $table WHERE emp_id = ?");
        $stmt->bind_param("i", $emp_id);
        if (!$stmt->execute()) {
            echo "❌ Failed to delete from $table: " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit;
        }
        $stmt->close();
    }

    // Now delete from employee
    $stmt = $conn->prepare("DELETE FROM employee WHERE emp_id = ?");
    $stmt->bind_param("i", $emp_id);
    if ($stmt->execute()) {
        echo "✅ Employee and all related data deleted.";
    } else {
        echo "❌ Failed to delete employee: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
