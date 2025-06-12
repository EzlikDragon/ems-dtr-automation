<?php
include '../db_connection.php';
date_default_timezone_set('Asia/Manila');
?>

<!DOCTYPE html>
<html>
<head>
    <title>DTR Logs</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #343a40;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .navbar .logo h1 {
            color: #fff;
            margin: 0;
            font-size: 20px;
        }
        .nav-links {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }
        .nav-links li {
            margin-left: 20px;
        }
        .nav-links a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        .nav-links a img {
            width: 18px;
            height: 18px;
            margin-right: 8px;
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color:rgba(255, 182, 212, 0.8);
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        img.avatar {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px rgba(255, 182, 212, 0.8);
        }
    </style>
</head>
<body>

<nav class="navbar">
  <div class="logo">
    <h1>Admin Panel</h1>
  </div>
  <ul class="nav-links">
    <li>
      <a href="../admin_dashboard.php">
        <img src="../images/dashboard.png" alt="Dashboard" />
        <span>Dashboard</span>
      </a>
    </li>
    <li>
      <a href="../manage_employees.php">
        <img src="../images/emp.png" alt="Manage Employees" />
        <span>Employees</span>
      </a>
    </li>
    <li>
      <a href="../admin_logs.php">
        <img src="../images/logs.png" alt="System Logs" />
        <span>Logs</span>
      </a>
    </li>
    <li>
      <a href="../reports.php">
        <img src="../images/reports.png" alt="Reports" />
        <span>Reports</span>
      </a>
    </li>
    <li>
      <a href="../settings.php">
        <img src="../images/settings.png" alt="Settings" />
        <span>Settings</span>
      </a>
    </li>
    <li>
      <a href="admin_dtr_logs.php">🕒 Monitor DTR</a>
    </li>
    <li>
      <a href="../logout.php">
        <img src="../images/logout.png" alt="Logout" />
        <span>Logout</span>
      </a>
    </li>
  </ul>
</nav>

<div class="container">
    <h2>🕓 Daily DTR Logs</h2>
    <table>
        <thead>
            <tr>
                <th>Photo</th>
                <th>Employee ID</th>
                <th>Date</th>
                <th>AM In</th>
                <th>AM Out</th>
                <th>PM In</th>
                <th>PM Out</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "
                SELECT dtr.*, e.first_name, e.last_name 
                FROM dtr 
                LEFT JOIN employee e ON dtr.emp_id = e.emp_id
                ORDER BY dtr.date DESC
            ";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()):
                $name = $row['first_name'] . ' ' . $row['last_name'];
                $photo = !empty($row['photo']) && file_exists("../images/" . $row['photo']) ? "../images/" . $row['photo'] : "../images/default.png";
            ?>
            <tr>
                <td><img class="avatar" src="<?= htmlspecialchars($photo) ?>" alt="photo"></td>
                <td><?= htmlspecialchars($row['emp_id']) ?><br><?= htmlspecialchars($name) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td><?= $row['time_in_am'] ? date("h:i A", strtotime($row['time_in_am'])) : '—' ?></td>
                <td><?= $row['time_out_am'] ? date("h:i A", strtotime($row['time_out_am'])) : '—' ?></td>
                <td><?= $row['time_in_pm'] ? date("h:i A", strtotime($row['time_in_pm'])) : '—' ?></td>
                <td><?= $row['time_out_pm'] ? date("h:i A", strtotime($row['time_out_pm'])) : '—' ?></td>
                <td><?= htmlspecialchars($row['remarks']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../footer.php'; ?>

</body>
</html>
