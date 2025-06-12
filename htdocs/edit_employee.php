<?php
include 'db_connection.php';
$emp_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Auto-insert family row if missing
$check_family = $conn->query("SELECT emp_id FROM family WHERE emp_id = $emp_id");
if ($check_family->num_rows === 0) {
    $conn->query("INSERT INTO family (emp_id) VALUES ($emp_id)");
}

// Auto-insert personal_info row if missing
$check_personal = $conn->query("SELECT emp_id FROM personal_info WHERE emp_id = $emp_id");
if ($check_personal->num_rows === 0) {
    $conn->query("INSERT INTO personal_info (emp_id) VALUES ($emp_id)");
}

// Fetch employee data
$employee = $conn->query("SELECT * FROM employee WHERE emp_id = $emp_id")->fetch_assoc();
$family = $conn->query("SELECT * FROM family WHERE emp_id = $emp_id")->fetch_assoc();
$personal = $conn->query("SELECT * FROM personal_info WHERE emp_id = $emp_id")->fetch_assoc();
$children = $conn->query("SELECT * FROM children WHERE emp_id = $emp_id")->fetch_all(MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Employee</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="dash.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    footer {
      margin-top: 100px;
    }
  </style>
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container-fluid mt-5 mb-5 pb-5">
  <h2 class="mb-4 text-center">Edit Employee Record</h2>
  <form method="POST" action="update_employee.php" enctype="multipart/form-data">
    <input type="hidden" name="emp_id" value="<?= $emp_id ?>">
    <div class="row">
      <!-- EMPLOYEE SIDEBAR -->
      <div class="col-md-4">
        <div class="card mb-3">
          <div class="card-header">Employment Details</div>
          <div class="card-body row g-3">
            <div class="col-12">
              <label class="form-label">Employee ID</label>
              <input type="text" class="form-control" name="employee[emp_id]" value="<?= $employee['emp_id'] ?>" readonly>
            </div>
            <?php foreach (["bio_id", "last_name", "first_name", "middle_name", "ext_name", "status", "unit", "dtr_group", "charged_office"] as $field): ?>
              <div class="col-12">
                <label class="form-label text-capitalize"><?= str_replace('_', ' ', $field) ?></label>
                <input type="text" class="form-control" name="employee[<?= $field ?>]" value="<?= htmlspecialchars($employee[$field]) ?>">
              </div>
            <?php endforeach; ?>
            <div class="col-12">
              <label class="form-label">Employee Photo</label>
              <input type="file" class="form-control" name="employee_photo">
              <?php
$photoFile = !empty($employee['photo']) ? $employee['photo'] : 'default.png';
?>
<img src="images/<?= htmlspecialchars($photoFile) ?>" alt="Employee Photo" class="img-thumbnail mt-2" width="120">

            </div>
          </div>
        </div>
      </div>

      <!-- MAIN FORM CONTENT -->
      <div class="col-md-8">

        <!-- PERSONAL INFO SECTION -->
        <div class="card mb-3">
          <div class="card-header">Personal Information</div>
          <div class="card-body row g-3">
            <?php
            $skip = ["personal_id", "emp_id", "photo"];
            foreach ($personal as $field => $value):
              if (!in_array($field, $skip)):
            ?>
              <div class="col-md-6">
                <label class="form-label text-capitalize"><?= str_replace('_', ' ', $field) ?></label>
                <input type="text" class="form-control" name="personal_info[<?= $field ?>]" value="<?= htmlspecialchars($value) ?>">
              </div>
            <?php endif; endforeach; ?>
          </div>
        </div>


        <!-- FAMILY SECTION -->
        <div class="card mb-3">
          <div class="card-header">Family Background</div>
          <div class="card-body row g-3">
            <?php
            $family_fields = ["spouse_surname", "spouse_first_name", "spouse_middle_name", "spouse_ext_name", "occupation", "employer_name", "business_address", "telephone_number",
                            "father_surname", "father_first_name", "father_middle_name", "father_ext_name",
                            "mother_surname", "mother_first_name", "mother_middle_name", "mother_ext_name"];
            foreach ($family_fields as $field): ?>
              <div class="col-md-6">
                <label class="form-label text-capitalize"><?= str_replace('_', ' ', $field) ?></label>
                <input type="text" class="form-control" name="family[<?= $field ?>]" value="<?= htmlspecialchars($family[$field]) ?>">
              </div>
            <?php endforeach; ?>
          </div>
        </div>


   <!-- CHILDREN SECTION -->
   <div class="card mb-3">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Children</span>
            <button type="button" class="btn btn-sm btn-success" id="add-child">Add Child</button>
          </div>
          <div class="card-body" id="children-list">
            <?php foreach ($children as $i => $child): ?>
              <div class="row g-3 align-items-end mb-2 child-entry">
                <div class="col-md-6">
                  <label class="form-label">Child Name</label>
                  <input type="text" name="children[<?= $i ?>][child_name]" value="<?= htmlspecialchars($child['child_name']) ?>" class="form-control">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Date of Birth</label>
                  <input type="date" name="children[<?= $i ?>][date_of_birth]" value="<?= $child['date_of_birth'] ?>" class="form-control">
                </div>
                <input type="hidden" name="children[<?= $i ?>][child_id]" value="<?= $child['child_id'] ?>">
                <div class="col-md-2">
                  <button type="button" class="btn btn-danger remove-child w-100">Remove</button>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="text-end mb-5 pb-5">
          <button type="submit" class="btn btn-primary">Update Employee</button>
        </div>
      </div>
    </div>
  </form>
</div>
<script>
let childIndex = <?= count($children) ?>;

$('#add-child').on('click', function () {
  const newRow = `
    <div class="row g-3 align-items-end mb-2 child-entry">
      <div class="col-md-6">
        <label class="form-label">Child Name</label>
        <input type="text" name="children[${childIndex}][child_name]" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Date of Birth</label>
        <input type="date" name="children[${childIndex}][date_of_birth]" class="form-control">
      </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-danger remove-child w-100">Remove</button>
      </div>
    </div>`;
  $('#children-list').append(newRow);
  childIndex++;
});

$(document).on('click', '.remove-child', function () {
  const entry = $(this).closest('.child-entry');
  const input = entry.find('input[type="hidden"]');

  if (input.length) {
    // Mark for deletion by appending a hidden input with a delete flag
    const deleteInput = `<input type="hidden" name="delete_children[]" value="${input.val()}">`;
    $("form").append(deleteInput);
  }

  entry.remove();
});

</script>
<?php include 'footer.php'; ?>
</body>
</html>
