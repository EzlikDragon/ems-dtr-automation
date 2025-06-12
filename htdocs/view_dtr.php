<?php
include 'db_connection.php';

$sql = "SELECT d.*, e.first_name, e.last_name, e.dtr_group FROM dtr d 
        JOIN employee e ON d.emp_id = e.emp_id
        ORDER BY d.date DESC, d.time_in DESC";

$result = $conn->query($sql);
?>

<table border="1">
    <tr>
        <th>Date</th>
        <th>Employee</th>
        <th>DTR Group</th>
        <th>Time In</th>
        <th>Time Out</th>
        <th>Status</th>
        <th>Remarks</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['date'] ?></td>
        <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
        <td><?= $row['dtr_group'] ?></td>
        <td><?= $row['time_in'] ?></td>
        <td><?= $row['time_out'] ?></td>
        <td><?= $row['status'] ?></td>
        <td><?= $row['remarks'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>
