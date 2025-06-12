<?php
session_start();
include 'db_connection.php';

// Check if emp_id is provided correctly
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_employees.php");
    exit();
}

$emp_id = intval($_GET['id']);

// Fetch Employee Data
$query = "SELECT e.*, p.* FROM employee e
          LEFT JOIN personal_info p ON e.emp_id = p.emp_id
          WHERE e.emp_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $emp_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$stmt->close(); 

// Fetch Family Data
$familyQuery = "SELECT * FROM family WHERE emp_id = ?";
$familyStmt = $conn->prepare($familyQuery);
$familyStmt->bind_param("i", $emp_id);
$familyStmt->execute();
$familyResult = $familyStmt->get_result();
$family = $familyResult->fetch_assoc() ?? [];
$familyStmt->close();

// Fetch Children Data
$childrenQuery = "SELECT * FROM children WHERE emp_id = ?";
$childrenStmt = $conn->prepare($childrenQuery);
$childrenStmt->bind_param("i", $emp_id);
$childrenStmt->execute();
$childrenResult = $childrenStmt->get_result();
$childrenStmt->close();
$conn->close(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Details - <?= htmlspecialchars($employee['last_name'] ?? 'Unknown') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="dash.css">
    <style>
        .section-hidden {
            display: none;
        }

        .nav-item {
            cursor: pointer;
        }
    </style>
</head>
<body>

<?php include 'nav.php'; ?>
<?php include 'sidebar.php'; ?>


    <div class="main-content">
        <section id="personal-info" class="personal-info section-hidden">
            <h2 class="text-center mt-4">
                <button class="btn btn-sm btn-warning float-end" data-bs-toggle="modal" data-bs-target="#editPersonalModal">Edit</button>
            </h2>

            <h2 class="text-center mt-4">Employee Personal Information</h2>
            <table class="table table-bordered">
                <tr><th>Full Name</th><td><?= htmlspecialchars(($employee['last_name'] ?? 'N/A') . ", " . ($employee['first_name'] ?? 'N/A')) ?></td></tr>
                <tr><th>BIO ID</th><td><?= htmlspecialchars($employee['bio_id'] ?? 'N/A') ?></td></tr>
                <tr><th>Status</th><td><?= htmlspecialchars($employee['status'] ?? 'N/A') ?></td></tr>
                <tr><th>Date of Birth</th><td><?= htmlspecialchars($employee['date_of_birth'] ?? 'N/A') ?></td></tr>
                <tr><th>Place of Birth</th><td><?= htmlspecialchars($employee['place_of_birth'] ?? 'N/A') ?></td></tr>
                <tr><th>Address</th><td><?= htmlspecialchars(($employee['house_block_lot'] ?? '') . ", " . ($employee['street'] ?? '') . ", " . ($employee['subdivision_village'] ?? '') . ", " . ($employee['barangay'] ?? '') . ", " . ($employee['city_municipality'] ?? '') . ", " . ($employee['province'] ?? '') . " - " . ($employee['zip_code'] ?? '')) ?></td></tr>
                <tr><th>Permanent Address</th><td><?= htmlspecialchars(($employee['permanent_house_block_lot'] ?? '') . ", " . ($employee['permanent_street'] ?? '') . ", " . ($employee['permanent_subdivision_village'] ?? '') . ", " . ($employee['permanent_barangay'] ?? '') . ", " . ($employee['permanent_city_municipality'] ?? '') . ", " . ($employee['permanent_province'] ?? '') . " - " . ($employee['permanent_zip_code'] ?? '')) ?></td></tr>
            </table>        
            <table class="table table-bordered">
                <h2 class="text-center mt-4">Personal Details</h2>
                <tr><th>Gender</th><td><?= htmlspecialchars($employee['gender'] ?? 'N/A') ?></td></tr>
                <tr><th>Height</th><td><?= htmlspecialchars($employee['height'] ?? 'N/A') ?> m</td></tr>
                <tr><th>Weight</th><td><?= htmlspecialchars($employee['weight'] ?? 'N/A') ?> kg</td></tr>
                <tr><th>Blood Type</th><td><?= htmlspecialchars($employee['blood_type'] ?? 'N/A') ?></td></tr>
                <tr><th>Citizenship</th><td><?= htmlspecialchars($employee['citizenship'] ?? 'N/A') ?></td></tr>
                <tr><th>Civil Status</th><td><?= htmlspecialchars($employee['civil_status'] ?? 'N/A') ?></td></tr>
            </table>   
            <table class="table table-bordered">
                <h2 class="text-center mt-4">Contact Details</h2>
                <tr><th>Email</th><td><?= htmlspecialchars($employee['email'] ?? 'N/A') ?></td></tr>
                <tr><th>Phone</th><td><?= htmlspecialchars($employee['phone'] ?? 'N/A') ?></td></tr>
                <tr><th>Mobile</th><td><?= htmlspecialchars($employee['mobile'] ?? 'N/A') ?></td></tr>
            </table>    
                <table class="table table-bordered">
                <h2 class="text-center mt-4">Government Details</h2>
                <tr><th>GSIS Number</th><td><?= htmlspecialchars($employee['gsis_number'] ?? 'N/A') ?></td></tr>
                <tr><th>SSS Number</th><td><?= htmlspecialchars($employee['sss_number'] ?? 'N/A') ?></td></tr>
                <tr><th>TIN</th><td><?= htmlspecialchars($employee['tin'] ?? 'N/A') ?></td></tr>
            </table>
        
        </section>

        <section id="family-info" class="family-info section-hidden">
            <h2 class="text-center mt-4">
                <button class="btn btn-sm btn-warning float-end" data-bs-toggle="modal" data-bs-target="#editFamilyModal">Edit</button>
            </h2>

            <h2 class="text-center mt-4">Family Information</h2>
            <table class="table table-bordered">
                <tr><th>Father</th><td><?= htmlspecialchars(($family['father_first_name'] ?? 'N/A') . ' ' . ($family['father_middle_name'] ?? '') . ' ' . ($family['father_surname'] ?? 'N/A')) ?></td></tr>
                <tr><th>Mother</th><td><?= htmlspecialchars(($family['mother_first_name'] ?? 'N/A') . ' ' . ($family['mother_middle_name'] ?? '') . ' ' . ($family['mother_surname'] ?? 'N/A')) ?></td></tr>
                <tr><th>Spouse</th><td><?= htmlspecialchars(($family['spouse_first_name'] ?? 'N/A') . ' ' . ($family['spouse_middle_name'] ?? '') . ' ' . ($family['spouse_surname'] ?? 'N/A')) ?></td></tr>
                <tr><th>Child Name</th><th>Date of Birth</th></tr>
                <?php while ($child = $childrenResult->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($child['child_name']) ?></td>
                        <td><?= htmlspecialchars($child['date_of_birth']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </section>
    </div>
</div>
<!-- Include modals -->
<?php include 'personal_info_modal.php'; ?>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('.nav-link').click(function(e) {
            e.preventDefault();

            // Hide all sections
            $('.section-hidden').hide();

            // Show target section
            $($(this).data('target')).fadeIn();
        });

        // Optionally, show the first section by default
        $('#personal-info').show();
    });
</script>

<?php include 'footer.php'; ?>
</body>
</html>
