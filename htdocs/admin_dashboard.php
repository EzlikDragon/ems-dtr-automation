<?php
session_start();

// Redirect to login if session is not active
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];

include 'db_connection.php'; // Ensure database connection is included

// Fetch total employees count
$countQuery = "SELECT COUNT(*) AS total FROM employee"; // Table name should be lowercase as per SQL dump
$result = $conn->query($countQuery);
$totalEmployees = ($result) ? $result->fetch_assoc()['total'] : 0;

// Get auto logout value from system_preferences
$auto_logout = 15; // fallback value
$timeoutQuery = $conn->query("SELECT auto_logout FROM system_preferences WHERE id = 1");
if ($timeoutQuery && $row = $timeoutQuery->fetch_assoc()) {
    $auto_logout = (int) $row['auto_logout'];
}

// Set or update last activity
if (!isset($_SESSION['last_activity'])) {
    $_SESSION['last_activity'] = time();
} elseif (time() - $_SESSION['last_activity'] > $auto_logout * 60) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit();
}
$_SESSION['last_activity'] = time();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel= "shortcut icon" type="x-icon" href="images\MaasinSeal.png">
  <title>HR Dashboard</title>
  <link rel="stylesheet" href="dash.css">
</head>
<style>
  /* 🔷 Center the main content */
.main-content {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  padding: 20px;
  margin: 0 auto;
  padding-top: 100px; /* Space to avoid overlap with navbar */
  text-align: center;
}

/* 🔷 Navbar Styles */
.navbar {
  background: rgba(255, 182, 212, 0.8);
  color: #fff;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 25px;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 999;
  border-radius: 15px;
  backdrop-filter: blur(20px);
  box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);

  
}

/* 🔷 Adjust the spacing of the nav-links */
.nav-links {
  list-style: none;
  display: flex;
  gap: 20px;
  margin: 0;
}

.nav-links li {
  display: inline-flex;
  align-items: center;
}

.nav-links a {
  color: #f83687;
  text-decoration: none;
  font-size: 16px;
  font-weight: 500;
  padding: 8px 15px;
  border-radius: 10px;
  transition: all 0.3s ease-in-out;
}

.nav-links a:hover {
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  text-decoration: none;
}

/* 🔷 Navigation Icons */
.nav-links a img {
  width: 35px;
  height: 35px;
  transition: transform 0.3s ease-in-out, filter 0.3s ease-in-out;
  filter: drop-shadow(0px 2px 5px rgba(255, 255, 255, 0.4)); /* Glow effect */
}

.nav-links a img:hover {
  transform: scale(1.2);
  filter: drop-shadow(0px 4px 8px rgba(248, 54, 135, 0.6)); /* Brighter glow */
}
</style>

<body>
  <div class="dashboard">
    <!-- Include Navbar -->
    <?php include 'nav.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
      <header>
        <div class="city-seal">
          <img src="images/MaasinSeal.png" alt="City of Maasin Official Seal">
        </div>

        <h1>Welcome, <?= htmlspecialchars($admin_name); ?>!</h1>
        <br>
      </header>

      <section class="cards">
        <div class="card">
          <h3>Total Employees</h3>
          <p><?= htmlspecialchars($totalEmployees) ?></p> <!-- Displays total employee count -->
        </div>

        <div class="card">
  <h3>Recent Logins</h3>
  <ul class="list-unstyled">
    <?php
    $recent = $conn->query("
      SELECT firstname, lastname, last_login 
      FROM admins 
      WHERE last_login IS NOT NULL 
      ORDER BY last_login DESC 
      LIMIT 5
    ");

    if ($recent->num_rows > 0):
      while ($row = $recent->fetch_assoc()):
    ?>
      <li>
        <strong><?= $row['firstname'] . ' ' . $row['lastname'] ?></strong><br>
        <small class="text-muted"><?= date('M d, Y h:i A', strtotime($row['last_login'])) ?></small>
      </li>
    <?php
      endwhile;
    else:
    ?>
      <p class="text-muted">No recent admin logins.</p>
    <?php endif; ?>
  </ul>
</div>



        <div class="card">
  <h3>System Alerts</h3>
  <?php include 'alert.php';?>
</div>

      </section>

      <section class="graph">
        <h2>System Usage</h2>
        <div id="chart"></div>
      </section>
    </main>
  </div>
  <?php include 'footer.php'; ?>
</body>
</html>
