<?php
include 'db_connection.php';

$orderBy = isset($_POST['order_by']) ? $_POST['order_by'] : 'last_name';
$sortOrder = isset($_POST['sort_order']) && $_POST['sort_order'] == 'desc' ? 'DESC' : 'ASC';
$filterValue = isset($_POST['filter_value']) ? trim($_POST['filter_value']) : '';

// Allowed columns for sorting and filtering
$allowedColumns = ['last_name', 'status', 'bio_id', 'emp_id', 'unit', 'dtr_group', 'charged_office'];
if (!in_array($orderBy, $allowedColumns)) {
    $orderBy = 'last_name'; // Default column if invalid
}

// Handle filtering
if ($filterValue !== '') {
    $filterQuery = "WHERE $orderBy = ?";
    $query = "SELECT * FROM employee $filterQuery ORDER BY $orderBy $sortOrder";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $filterValue);
} else {
    // No filtering applied
    $query = "SELECT * FROM employee ORDER BY $orderBy $sortOrder";
    $stmt = $conn->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<tr><td colspan='8' class='text-center'>No employees found.</td></tr>";
} else {
    while ($row = $result->fetch_assoc()) {
        echo "<tr id='row-{$row['emp_id']}'>";
        echo "<td>" . htmlspecialchars($row['last_name'] . ", " . $row['first_name'] . " " . $row['middle_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "<td>" . htmlspecialchars($row['bio_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['emp_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['unit']) . "</td>";
        echo "<td>" . htmlspecialchars($row['dtr_group']) . "</td>";
        echo "<td>" . htmlspecialchars($row['charged_office']) . "</td>";
        echo "<td>
                <a href='view_employee.php?id={$row['emp_id']}' class='btn btn-info btn-sm'>View</a>
                <button class='btn btn-danger btn-sm delete-btn' data-id='{$row['emp_id']}'>Delete</button>
              </td>";
        echo "</tr>";
    }
}

$stmt->close();
$conn->close();
?>
