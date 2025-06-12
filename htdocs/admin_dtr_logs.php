<?php
// ✅ Enable error display for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db_connection.php';

$search = $_GET['search'] ?? '';
$date = $_GET['date'] ?? '';

$sql = "SELECT d.*, e.first_name, e.last_name, e.unit, e.dtr_group, e.photo 
        FROM dtr d 
        JOIN employee e ON d.emp_id = e.emp_id 
        WHERE 1";

if (!empty($search)) {
    $sql .= " AND (e.first_name LIKE '%$search%' OR e.last_name LIKE '%$search%')";
}

if (!empty($date)) {
    $sql .= " AND d.date = '$date'";
}

$sql .= " ORDER BY d.date DESC, d.time_in DESC";
$result = $conn->query($sql);

// Count status totals
$status_count = [
    'PRESENT' => 0,
    'LATE' => 0,
    'HALF-DAY' => 0
];

$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
    $status = strtoupper($row['status']);
    if (isset($status_count[$status])) {
        $status_count[$status]++;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin DTR Logs</title>
    <link rel="stylesheet" href="dash.css">
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 40px; }
        .container { max-width: 1100px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: center; }
        th { background-color: #f2f2f2; }
        form { margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; gap: 10px; }
        input[type="text"], input[type="date"] { padding: 8px; width: 200px; }
        button { padding: 8px 16px; background: #2e86de; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .reset { background: #ccc; color: black; }
        .avatar { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; }
        .scan-button-container { text-align: right; margin-bottom: 10px; }
        .scan-button { padding: 8px 16px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="container">
    <h2>Daily Time Records — All Employees</h2>

    <!-- Go to Scanner -->
    <div class="scan-button-container">
        <a href="dtr_scan.php">
            <button type="button" class="scan-button">➕ Go to DTR Scanner</button>
        </a>
    </div>

    <!-- Filter Form -->
    <form method="get">
        <input type="text" name="search" placeholder="Search name..." value="<?= htmlspecialchars($search) ?>">
        <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">
        <button type="submit">Filter</button>
        <a href="admin_dtr_logs.php"><button type="button" class="reset">Reset</button></a>
    </form>

    <!-- Data Table -->
    <table>
        <thead>
            <tr>
                <th>Photo</th>
                <th>Employee</th>
                <th>Date</th>
                <th>Time In (AM)</th>
                <th>Time Out (AM)</th>
                <th>Time In (PM)</th>
                <th>Time Out (PM)</th>
                <th>Status</th>
                <th>Unit</th>
                <th>Group</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td>
                        <img src="images/<?= $row['photo'] ?: 'default.png' ?>" class="avatar">
                    </td>
                    <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                    <td><?= $row['date'] ?></td>
                    <td><?= $row['time_in_am'] ?></td>
                    <td><?= $row['time_out_am'] ?></td>
                    <td><?= $row['time_in_pm'] ?></td>
                    <td><?= $row['time_out_pm'] ?></td>
                    <td><?= strtoupper($row['status']) ?></td>
                    <td><?= $row['unit'] ?></td>
                    <td><?= $row['dtr_group'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
