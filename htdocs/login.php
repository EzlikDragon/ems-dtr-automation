<?php
session_start(); // Start session to store admin details on login

include 'db_connection.php'; // Ensure this file does not close $conn prematurely

// Check if the login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Verify the database connection is active
    if (isset($conn) && $conn->ping()) {
        $username = $conn->real_escape_string($_POST['username']);
        $password = $_POST['password'];

        // Fetch admin details
        $sql = "SELECT * FROM admins WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows == 1) {
            $admin = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $admin['password'])) {

               // Update last_login BEFORE session redirect
    $conn->query("UPDATE admins SET last_login = NOW() WHERE id = " . $admin['id']);
    $conn->query("
  UPDATE admins 
  SET 
    last_login = NOW(), 
    login_count_month = login_count_month + 1,
    last_login_date = CURDATE()
  WHERE id = {$admin['id']}
");

$conn->query("UPDATE admins SET last_login = NOW(), is_logged_in = 1 WHERE id = " . $admin['id']);

                // Store admin details in session
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['firstname'] . " " . $admin['lastname'];
                $_SESSION['admin_role'] = $admin['role'];
                $_SESSION['admin_email'] = $admin['email'];

                // Redirect to admin dashboard
                header("Location: admin_dashboard.php");
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Invalid username.";
        }
    } else {
        die("Database connection is not active.");
    }
}



// Signup form logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    // Verify the database connection is active
    if (isset($conn) && $conn->ping()) {
        $username = $conn->real_escape_string($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $firstname = $conn->real_escape_string($_POST['firstname']);
        $lastname = $conn->real_escape_string($_POST['lastname']);
        $email = $conn->real_escape_string($_POST['email']);

        // Insert admin into the database
        $sql = "INSERT INTO admins (username, password, firstname, lastname, email) 
                VALUES ('$username', '$password', '$firstname', '$lastname', '$email')";
        
        if ($conn->query($sql) === TRUE) {
            $success = "Signup successful. You can now log in.";
        } else {
            $error = "Error: " . $conn->error;
        }
    } else {
        die("Database connection is not active.");
    }
}

// Close the connection only after all logic is processed
if (isset($conn) && $conn->ping()) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login & Signup</title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
<div class="wrapper">
<div class="city-seal">
    <img src="images/MaasinSeal.png" alt="City of Maasin Official Seal">
</div>

  <div class="title-text">
    <div class="title login">Admin Login</div>
    <div class="title signup">Admin Signup</div>
  </div>
  <div class="form-container">
    <div class="slide-controls">
      <input type="radio" name="slide" id="login" checked>
      <input type="radio" name="slide" id="signup">
      <label for="login" class="slide login">Login</label>
      <label for="signup" class="slide signup">Signup</label>
      <div class="slider-tab"></div>
    </div>
    <div class="form-inner">
      <!-- Login Form -->
      <form method="POST" action="login.php" class="login">

  <div class="field">
    <input type="text" name="username" placeholder="Username" required>
  </div>
  <div class="field">
    <input type="password" name="password" placeholder="Password" required>
  </div>
  <?php if (isset($_SESSION['error'])) { 
      echo "<p style='color:red;'>".$_SESSION['error']."</p>"; 
      unset($_SESSION['error']); 
  } ?>
  <div class="field btn">
    <div class="btn-layer"></div>
    <input type="submit" name="login" value="Login">
  </div>
</form>

      <!-- Signup Form -->
      <form method="POST" action="#" class="signup">
        <div class="field">
          <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="field">
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="field">
          <input type="text" name="firstname" placeholder="First Name" required>
        </div>
        <div class="field">
          <input type="text" name="lastname" placeholder="Last Name" required>
        </div>
        <div class="field">
          <input type="email" name="email" placeholder="Email" required>
        </div>
        <?php if (isset($success)) { echo "<p style='color:green;'>$success</p>"; } ?>
        <div class="field btn">
          <div class="btn-layer"></div>
          <input type="submit" name="signup" value="Signup">
        </div>
      </form>
    </div>
  </div>
</div>
<script src="./script.js"></script>
</body>
</html>
