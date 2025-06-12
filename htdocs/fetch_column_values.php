<?php
include 'db_connection.php';

if (isset($_POST['column'])) {
    $column = $_POST['column'];

    // Validate column name
    $allowedColumns = ['last_name', 'status', 'bio_id', 'emp_id', 'unit', 'dtr_group', 'charged_office'];
    if (!in_array($column, $allowedColumns)) {
        exit; // Stop execution if column is invalid
    }

    $query = "SELECT DISTINCT $column FROM employee WHERE $column IS NOT NULL AND $column != '' ORDER BY $column ASC";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<option value=''>All</option>"; // Default option
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row[$column]) . "'>" . htmlspecialchars($row[$column]) . "</option>";
        }
    }
}

$conn->close();
?>
