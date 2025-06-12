<?php
include 'db_connection.php';

// Check if emp_id is provided and valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Employee ID.");
}

$emp_id = intval($_GET['id']);

// Fetch employee details
$query = "SELECT * FROM employee WHERE emp_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Database error: " . $conn->error);
}
$stmt->bind_param("i", $emp_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

// Close statement
$stmt->close();

if (!$employee) {
    die("Employee not found.");
}

// Fix profile picture path
$photoPath = (!empty($employee['photo']) && file_exists(__DIR__ . "/images/" . $employee['photo']))
    ? "images/" . $employee['photo']
    : "images/default.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Details - <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #fff0f5;
            font-family: 'Arial, sans-serif';
        }

        .sidebar {
            width: 300px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background: linear-gradient(145deg, #ff9dc2, #ff66a1);
            padding: 20px;
            color: white;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .employee-photo {
            max-width: 120px;
            height: auto;
            border-radius: 50%;
            margin-bottom: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border: 4px solid white;
        }

        .employee-details ul {
            list-style-type: none;
            padding: 0;
        }

        .employee-details li {
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .employee-details strong {
            color: #f1f1f1;
        }

        .sidebar .nav-link {
            color: white;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }



        .main-content {
            margin-left: 320px;
            padding: 40px;
        }

        .city-seal img {
            max-width: 100px;
            margin-bottom: 20px;
        }

        .card {
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        h2 {
            color: #ff007f;
            font-weight: bold;
        }

        .nav {
            margin-bottom: 20px;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-link {
            font-weight: bold;
            text-decoration: none;
        }

        .nav-link:hover {
            background-color: #ff66a1;
            color: white;
        }
    </style>

    </style>
</head>
<body>

<!-- Sidebar with Employee Details --><div class="sidebar">
    <div class="text-center">
        <img src="<?= htmlspecialchars($photoPath) ?>" alt="Employee Photo" class="employee-photo">
    </div>
    <hr>
    <div class="employee-details">
        <h5><?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></h5>
        <ul>
            <li><strong>Employee ID:</strong> <?= htmlspecialchars($employee['emp_id']) ?></li>
            <li><strong>Bio ID:</strong> <?= htmlspecialchars($employee['bio_id'] ?? 'N/A') ?></li>
            <li><strong>Status:</strong> <?= htmlspecialchars($employee['status']) ?></li>
            <li><strong>Unit:</strong> <?= htmlspecialchars($employee['unit'] ?? 'N/A') ?></li>
            <li><strong>DTR Group:</strong> <?= htmlspecialchars($employee['dtr_group'] ?? 'N/A') ?></li>
            <li><strong>Charged Office:</strong> <?= htmlspecialchars($employee['charged_office'] ?? 'N/A') ?></li>
        </ul>
    </div>

    <hr>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" data-target="#personal-info">Personal Information</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-target="#family-info">Family Information</a>
        </li>
    </ul>
</div>


<!-- Main Content Section -->
<div class="main-content">
    <div class="city-seal">
        <img src="images/MaasinSeal.png" alt="City of Maasin Official Seal">
    </div>



    </div>
</div>

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

</body>
</html>
