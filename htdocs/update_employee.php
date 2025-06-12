<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_id = intval($_POST['emp_id']);

    // Update employee
    $employee = $_POST['employee'];
    $photoName = $_FILES['employee_photo']['name'] ?? '';
    if (!empty($photoName)) {
        $targetDir = "images/";
        $targetFile = $targetDir . basename($photoName);
        move_uploaded_file($_FILES['employee_photo']['tmp_name'], $targetFile);
        $employee['photo'] = $photoName;
    }

    $setEmployee = [];
    foreach ($employee as $key => $value) {
        $setEmployee[] = "$key = '" . $conn->real_escape_string($value) . "'";
    }
    $conn->query("UPDATE employee SET " . implode(", ", $setEmployee) . " WHERE emp_id = $emp_id");

    // Update family
    $family = $_POST['family'];
    $setFamily = [];
    foreach ($family as $key => $value) {
        $setFamily[] = "$key = '" . $conn->real_escape_string($value) . "'";
    }
    $conn->query("UPDATE family SET " . implode(", ", $setFamily) . " WHERE emp_id = $emp_id");

    // Update personal_info
    $personal_info = $_POST['personal_info'];
    $setPersonal = [];
    foreach ($personal_info as $key => $value) {
        $setPersonal[] = "$key = '" . $conn->real_escape_string($value) . "'";
    }
    $conn->query("UPDATE personal_info SET " . implode(", ", $setPersonal) . " WHERE emp_id = $emp_id");

    // Handle children
    $existingIds = [];
    if (isset($_POST['children'])) {
        foreach ($_POST['children'] as $child) {
            $child_name = $conn->real_escape_string($child['child_name']);
            $dob = $conn->real_escape_string($child['date_of_birth']);

            if (isset($child['child_id'])) {
                $child_id = intval($child['child_id']);
                $existingIds[] = $child_id;
                $conn->query("UPDATE children SET child_name = '$child_name', date_of_birth = '$dob' WHERE child_id = $child_id");
            } else {
                $conn->query("INSERT INTO children (emp_id, child_name, date_of_birth) VALUES ($emp_id, '$child_name', '$dob')");
            }
        }
    }

    // Delete marked children
    if (isset($_POST['delete_children'])) {
        $deleteIds = array_map('intval', $_POST['delete_children']);
        $deleteList = implode(',', $deleteIds);
        if (!empty($deleteList)) {
            $conn->query("DELETE FROM children WHERE emp_id = $emp_id AND child_id IN ($deleteList)");
        }
    }

    header("Location: manage_employees.php?success=1");
    exit();
}
?>