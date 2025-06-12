<?php
include 'db_connection.php';
session_start();

$currentAdminId = $_SESSION['admin_id'] ?? null;

// Fetch all admins ordered by last login desc
$result = $conn->query("SELECT id, firstname, lastname, email, last_login, login_count_month, last_login_date, updated_at, is_logged_in FROM admins WHERE last_login IS NOT NULL ORDER BY last_login DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login History</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="dash.css">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container mt-5">
  <h2 class="mb-4 text-center">Admin Login History</h2>
  <div class="card">
    <div class="card-body">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Last Login</th>
            <th>Status</th>
            <th>Frequency</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td>
                <?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?>
                <?php if ($row['id'] == $currentAdminId): ?>
                  <span class="badge bg-info">🟣 You</span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= date("M d, Y h:i A", strtotime($row['last_login'])) ?></td>
              <td>
                <?php
                $lastActive = strtotime($row['updated_at']);
                $isActiveNow = $row['is_logged_in'] ?? 0;
                if ($isActiveNow): ?>
                  <span class="badge bg-success">🟢 Currently Logged In</span>
                <?php elseif ($row['last_login_date'] == date('Y-m-d')): ?>
                  <span class="badge bg-secondary">🟣 Active Today</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Inactive</span>
                <?php endif; ?>
              </td>
              <td>
                <?php
                $count = (int)$row['login_count_month'];
                if ($count > 10) {
                  echo '<span class="badge bg-primary">🟢 Frequent</span>';
                } elseif ($count > 0) {
                  echo '<span class="badge bg-warning text-dark">🟡 Occasional</span>';
                } else {
                  echo '<span class="badge bg-danger">🔴 Inactive</span>';
                }
                ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
