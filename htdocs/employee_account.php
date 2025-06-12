<?php
include 'db_connection.php';

$default_password = password_hash("password123", PASSWORD_DEFAULT);

// Get all employees
$sql = "SELECT emp_id, first_name FROM employee";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
    $emp_id = $row['emp_id'];
    $username = strtolower(str_replace(' ', '', $row['first_name'])) . $emp_id;

    // Check if already exists
    $check = $conn->prepare("SELECT * FROM employee_accounts WHERE emp_id = ?");
    $check->bind_param("i", $emp_id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO employee_accounts (emp_id, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $emp_id, $username, $default_password);
        $stmt->execute();
        echo "Created account for $username<br>";
    } else {
        echo "Account already exists for $emp_id<br>";
    }
}
?>
