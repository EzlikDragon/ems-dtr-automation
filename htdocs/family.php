<?php

include 'db_connection.php';

// Check if emp_id is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_employees.php");
    exit();
}

$emp_id = intval($_GET['id']);

// Fetch Employee Data
$query = "SELECT * FROM employee WHERE emp_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $emp_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if (!$employee) {
    die("Employee not found.");
}

// Fetch Family Data
$familyQuery = "SELECT * FROM family WHERE emp_id = ?";
$familyStmt = $conn->prepare($familyQuery);
$familyStmt->bind_param("i", $emp_id);
$familyStmt->execute();
$familyResult = $familyStmt->get_result();
$family = $familyResult->fetch_assoc();

// Fetch Children Data
$childrenQuery = "SELECT * FROM children WHERE emp_id = ?";
$childrenStmt = $conn->prepare($childrenQuery);
$childrenStmt->bind_param("i", $emp_id);
$childrenStmt->execute();
$childrenResult = $childrenStmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Details - <?= htmlspecialchars($employee['last_name']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="dash.css">
</head>

<body class="container mt-4" style="background-color: #ffc0cb;"> <!-- Soft pink background -->
<!-- 🔷 Navbar -->
<?php include 'nav.php';?>

    <h2 class="text-center">Employee Details</h2>


    <!-- 🔷 Move Navbar to Full Width Above the Employee Details -->
    <?php include 'hor_bar.php';?>


    <h4>Family Information</h4>
    <div class="row">
        <div class="col-md-6">
            <p><strong>Spouse:</strong> <?= htmlspecialchars($family['spouse_first_name'] ?? 'N/A') . ' ' . htmlspecialchars($family['spouse_middle_name'] ?? '') . ' ' . htmlspecialchars($family['spouse_surname'] ?? 'N/A') ?></p>
            <p><strong>Occupation:</strong> <?= htmlspecialchars($family['occupation'] ?? 'N/A') ?></p>
        </div>
        <div class="col-md-6">
            <p><strong>Father:</strong> <?= htmlspecialchars($family['father_first_name'] ?? 'N/A') . ' ' . htmlspecialchars($family['father_middle_name'] ?? '') . ' ' . htmlspecialchars($family['father_surname'] ?? 'N/A') ?></p>
            <p><strong>Mother:</strong> <?= htmlspecialchars($family['mother_first_name'] ?? 'N/A') . ' ' . htmlspecialchars($family['mother_middle_name'] ?? '') . ' ' . htmlspecialchars($family['mother_surname'] ?? 'N/A') ?></p>
        </div>
    </div>

    <h4>Children</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Date of Birth</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($child = $childrenResult->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($child['child_name']) ?></td>
                    <td><?= htmlspecialchars($child['date_of_birth']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="manage_employees.php" class="btn btn-secondary">Back</a>
</body>
</html>
<?php
$stmt->close();
$familyStmt->close();
$childrenStmt->close();
$conn->close();
?>
