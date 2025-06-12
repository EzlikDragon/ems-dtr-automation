<?php
include 'db_connection.php';

// SYSTEM ALERT CHECKS
$alerts = [];

// Check missing family
$res = $conn->query("SELECT emp_id FROM employee WHERE emp_id NOT IN (SELECT emp_id FROM family)");
if ($res->num_rows > 0) {
    $alerts[] = "<strong>" . $res->num_rows . "</strong> employee(s) missing <strong>family background</strong> info.";
}

// Check missing personal_info
$res = $conn->query("SELECT emp_id FROM employee WHERE emp_id NOT IN (SELECT emp_id FROM personal_info)");
if ($res->num_rows > 0) {
    $alerts[] = "<strong>" . $res->num_rows . "</strong> employee(s) missing <strong>personal information</strong>.";
}

// Check default photo usage
$res = $conn->query("SELECT emp_id FROM employee WHERE photo = 'default.png' OR photo = 'images/default.png'");
if ($res->num_rows > 0) {
    $alerts[] = "<strong>" . $res->num_rows . "</strong> employee(s) are using the <strong>default profile photo</strong>.";
}

// Check employees without children listed
$res = $conn->query("SELECT emp_id FROM employee WHERE emp_id NOT IN (SELECT DISTINCT emp_id FROM children)");
if ($res->num_rows > 0) {
    $alerts[] = "<strong>" . $res->num_rows . "</strong> employee(s) have <strong>no child records</strong>.";
}

// Check employees missing unit or charged office
$res = $conn->query("SELECT emp_id FROM employee WHERE unit = '' OR charged_office = ''");
if ($res->num_rows > 0) {
    $alerts[] = "<strong>" . $res->num_rows . "</strong> employee(s) have missing <strong>unit or charged office</strong>.";
}

// Check employees with missing date of birth
$res = $conn->query("SELECT emp_id FROM personal_info WHERE date_of_birth IS NULL OR date_of_birth = ''");
if ($res->num_rows > 0) {
    $alerts[] = "<strong>" . $res->num_rows . "</strong> employee(s) have missing <strong>date of birth</strong>.";
}

// Check for duplicate BIO IDs
$res = $conn->query("SELECT bio_id, COUNT(*) as total FROM employee GROUP BY bio_id HAVING total > 1");
if ($res->num_rows > 0) {
    $alerts[] = "⚠️ <strong>Duplicate BIO IDs detected</strong>. Please review employee records.";
}
?>

<?php if (!empty($alerts)): ?>
  <ul class="list-unstyled mb-0">
    <?php foreach ($alerts as $alert): ?>
      <li>⚠️ <?= $alert ?></li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p class="text-success mb-0">✅ No critical system alerts.</p>
<?php endif; ?>