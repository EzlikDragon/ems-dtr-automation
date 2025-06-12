<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$adminId = $_SESSION['admin_id'];
$adminName = $_SESSION['admin_name'];
$success_message = null;

// Fetch system preferences for display
$preferences = $conn->query("SELECT * FROM system_preferences WHERE id = 1")->fetch_assoc();

// Handle Update Profile
if (isset($_POST['update_profile'])) {
  $firstname = $_POST['firstname'];
  $email = $_POST['email'];
  $photo = $_FILES['photo']['name'] ?? null;

  if (!empty($photo)) {
    move_uploaded_file($_FILES['photo']['tmp_name'], "images/" . $photo);
    $conn->query("UPDATE admins SET firstname='$firstname', email='$email', photo='$photo' WHERE id=$adminId");
  } else {
    $conn->query("UPDATE admins SET firstname='$firstname', email='$email' WHERE id=$adminId");
  }

  if (!empty($_POST['password'])) {
    $hashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $conn->query("UPDATE admins SET password='$hashed' WHERE id=$adminId");
  }

  $_SESSION['admin_email'] = $email;
  $_SESSION['admin_name'] = $firstname;

  $success_message = "✅ Profile updated successfully.";
}

// Handle System Preferences
if (isset($_POST['save_preferences'])) {
  $system_name = $_POST['system_name'];
  $timezone = $_POST['timezone'];
  $maintenance = isset($_POST['maintenance']) ? 1 : 0;
  
  $conn->query("INSERT INTO system_preferences (id, system_name, timezone, maintenance_mode) VALUES (1, '$system_name', '$timezone', $maintenance) ON DUPLICATE KEY UPDATE system_name='$system_name', timezone='$timezone', maintenance_mode=$maintenance");

  $preferences = $conn->query("SELECT * FROM system_preferences WHERE id = 1")->fetch_assoc();
  $success_message = "✅ System preferences saved.";
}

// Handle Login & Security
if (isset($_POST['update_security'])) {
  $auto_logout = intval($_POST['auto_logout']);
  $multi_login = isset($_POST['simultaneous_login']) ? 1 : 0;

  $conn->query("INSERT INTO system_preferences (id, auto_logout, multi_login) VALUES (1, $auto_logout, $multi_login) ON DUPLICATE KEY UPDATE auto_logout=$auto_logout, multi_login=$multi_login");

  $preferences = $conn->query("SELECT * FROM system_preferences WHERE id = 1")->fetch_assoc();
  $success_message = "✅ Login & security settings updated.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Settings</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="dash.css">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container mt-5 mb-5">
  <h2 class="mb-4">System Settings</h2>

  <?php if ($success_message): ?>
    <div class="alert alert-success"> <?= $success_message ?> </div>
  <?php endif; ?>

  <div class="accordion" id="settingsAccordion">
    <!-- Admin Profile Settings -->
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          👤 Admin Profile Settings
        </button>
      </h2>
      <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#settingsAccordion">
        <div class="accordion-body">
          <form method="POST" action="" enctype="multipart/form-data">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">First Name</label>
                <input type="text" class="form-control" name="firstname" value="<?= htmlspecialchars($_SESSION['admin_name']) ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($_SESSION['admin_email']) ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">Change Password</label>
                <input type="password" class="form-control" name="password" placeholder="New Password">
              </div>
              <div class="col-md-6">
                <label class="form-label">Profile Photo</label>
                <input type="file" class="form-control" name="photo">
              </div>
            </div>
            <div class="mt-3 text-end">
              <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- System Preferences -->
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingTwo">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          ⚙️ System Preferences
        </button>
      </h2>
      <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#settingsAccordion">
        <div class="accordion-body">
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">System Name</label>
              <input type="text" class="form-control" name="system_name" value="<?= htmlspecialchars($preferences['system_name'] ?? '') ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Timezone</label>
              <select class="form-select" name="timezone">
                <option <?= ($preferences['timezone'] ?? '') === 'Asia/Manila' ? 'selected' : '' ?>>Asia/Manila</option>
                <option <?= ($preferences['timezone'] ?? '') === 'UTC' ? 'selected' : '' ?>>UTC</option>
                <option <?= ($preferences['timezone'] ?? '') === 'America/New_York' ? 'selected' : '' ?>>America/New_York</option>
              </select>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="maintenanceMode" name="maintenance" <?= !empty($preferences['maintenance_mode']) ? 'checked' : '' ?> >
              <label class="form-check-label" for="maintenanceMode">Enable Maintenance Mode</label>
            </div>
            <div class="mt-3 text-end">
              <button type="submit" name="save_preferences" class="btn btn-success">Save Preferences</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Login & Security -->
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingThree">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          🔒 Login & Security
        </button>
      </h2>
      <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#settingsAccordion">
        <div class="accordion-body">
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Auto Logout (minutes)</label>
              <input type="number" class="form-control" name="auto_logout" value="<?= htmlspecialchars($preferences['auto_logout'] ?? 15) ?>">
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="simultaneous_login" id="simultaneous_login" <?= !empty($preferences['multi_login']) ? 'checked' : '' ?> >
              <label class="form-check-label" for="simultaneous_login">Allow Multiple Logins</label>
            </div>
            <div class="mt-3 text-end">
              <button type="submit" name="update_security" class="btn btn-warning">Update Security</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
