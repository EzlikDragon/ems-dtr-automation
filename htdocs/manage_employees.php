<?php
// Database connection
include 'db_connection.php';

// Pagination Variables
$limit = 10; // Employees per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Fetch total employees count
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchQuery = $search ? "WHERE bio_id LIKE ? OR last_name LIKE ? OR first_name LIKE ? OR middle_name LIKE ? OR status LIKE ?" : "";

// Get Total Employees Count
$countQuery = "SELECT COUNT(*) AS total FROM employee $searchQuery";
$stmt = $conn->prepare($countQuery);

if ($search) {
    $searchParam = "%$search%";
    $stmt->bind_param("sssss", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
}

$stmt->execute();
$countResult = $stmt->get_result();
$totalEmployees = ($countResult) ? $countResult->fetch_assoc()['total'] : 0;
$totalPages = ceil($totalEmployees / $limit);
$stmt->close();

// Fetch Employees
$orderBy = isset($_GET['order_by']) ? $_GET['order_by'] : 'last_name';
$sortOrder = isset($_GET['sort_order']) && $_GET['sort_order'] == 'desc' ? 'DESC' : 'ASC';
$query = "SELECT * FROM employee $searchQuery ORDER BY $orderBy $sortOrder LIMIT ? OFFSET ?";

$stmt = $conn->prepare($query);

if ($search) {
    $stmt->bind_param("sssssii", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $limit, $offset);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="dash.css"> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="shortcut icon" type="x-icon" href="images/MaasinSeal.png">
</head>
<style>
  /* 🔷 Center the main content */


/* 🔷 Navbar */
.navbar {
  background: rgba(255, 182, 212, 0.8);
  color: #fff;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 25px;
  position: fixed;
  z-index: 999;
  border-radius: 15px;
  backdrop-filter: blur(20px);
  box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
  margin-right: 100px;
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

.table th, .table td {
    white-space: nowrap;          /* Prevent stacking */
    vertical-align: middle;       /* Vertically center */
    text-align: center;           /* Center align text */
    padding: 12px 10px;
}

.table th[data-column="bio_id"],
.table th[data-column="emp_id"] {
    min-width: 80px;              /* Ensure enough width */
}

.table thead th {
    background-color: #f83687;    /* Your theme pink */
    color: white;
    font-weight: bold;
}

.table tbody tr:nth-child(even) {
    background-color: #fff0f5;    /* Light pink alternate rows */
}

</style>

<body>

<!-- 🔷 Navbar -->
<?php include 'nav.php';?>

<br>
<br>
<br>

<!-- 🔷 City Seal -->
<div class="city-seal">
    <img src="images/MaasinSeal.png" alt="City Seal">
</div>

<!-- 🔷 Main Content -->
<div class="container main-content">
    <h2>Employee List</h2>

    <!-- Employee Count -->
    <div class="alert alert-info">
        <strong>Total Employees: <?= htmlspecialchars($totalEmployees) ?></strong>
    </div>

    <!-- Search and Add Employee -->
    <div class="d-flex justify-content-between mb-3">
        <form method="GET" action="manage_employees.php" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Search employees..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
            Add Employee
        </button>
    </div>

    <!-- Employee Table -->
    <div class="table-responsive">
        <table class="table table-bordered employee-details-table">
        <thead class="table-dark">
    <tr>
        <th class="filterable" data-column="last_name">Full Name <span class="dropdown-icon">▼</span></th>
        <th class="filterable" data-column="status">Status <span class="dropdown-icon">▼</span></th>
        <th class="filterable" data-column="bio_id">BIO ID <span class="dropdown-icon">▼</span></th>
        <th class="filterable" data-column="emp_id">EMP ID <span class="dropdown-icon">▼</span></th>
        <th class="filterable" data-column="unit">Unit <span class="dropdown-icon">▼</span></th>
        <th class="filterable" data-column="dtr_group">DTR Group <span class="dropdown-icon">▼</span></th>
        <th class="filterable" data-column="charged_office">Charged Office <span class="dropdown-icon">▼</span></th>
        <th>Actions</th>
    </tr>
</thead>



            <tbody>
                <?php if ($result->num_rows == 0): ?>
                    <tr><td colspan="8" class="text-center">No employees found.</td></tr>
                <?php else: ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr id="row-<?= $row['emp_id'] ?>">
                            <td><?= htmlspecialchars($row['last_name'] . ", " . $row['first_name'] . " " . $row['middle_name']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td><?= htmlspecialchars($row['bio_id']) ?></td>
                            <td><?= htmlspecialchars($row['emp_id']) ?></td>
                            <td><?= htmlspecialchars($row['unit']) ?></td>
                            <td><?= htmlspecialchars($row['dtr_group']) ?></td>
                            <td><?= htmlspecialchars($row['charged_office']) ?></td>
                            <td>
                                <a href="edit_employee.php?id=<?= $row['emp_id'] ?>" class="btn btn-warning btn-sm">View</a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $row['emp_id'] ?>">Delete</button>
                                

                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= ($page - 1) ?>&search=<?= urlencode($search) ?>&order_by=<?= $orderBy ?>&sort_order=<?= $sortOrder ?>">Previous</a></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&order_by=<?= $orderBy ?>&sort_order=<?= $sortOrder ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= ($page + 1) ?>&search=<?= urlencode($search) ?>&order_by=<?= $orderBy ?>&sort_order=<?= $sortOrder ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    </nav>
<br>
<br>
<br>
<br>
</div>
<?php include 'add_employee_modal.php'; ?>
<!-- 🔷 Footer -->
<?php include 'footer.php'; ?>


<script>
$(document).ready(function () {
    let currentSortOrder = "asc"; // Default sorting order
    let dropdownActive = null; // Track which dropdown is open

    // 🔹 DELETE EMPLOYEE FUNCTION
    $(".delete-btn").click(function () {
        var empId = $(this).data("id");
        if (confirm("Are you sure you want to delete this employee?")) {
            $.ajax({
                url: "delete_employee.php",
                type: "POST",
                data: { emp_id: empId },
                success: function () {
                    $("#row-" + empId).fadeOut("slow");
                },
                error: function () {
                    alert("Error deleting employee.");
                }
            });
        }
    });

    // 🔹 OPEN DROPDOWN MENU FOR FILTERING
    $(".filterable").click(function (event) {
        event.stopPropagation(); // Prevents dropdown from closing immediately

        let column = $(this).data("column");

        // Close any existing dropdowns before opening a new one
        $(".filter-container").remove(); // ✅ Ensures only one dropdown is visible at a time

        // If clicking the same column, close it
        if (dropdownActive === column) {
            dropdownActive = null;
            return;
        }
        dropdownActive = column;

        // Fetch existing values for the clicked column
        $.ajax({
            url: "fetch_column_values.php",
            type: "POST",
            data: { column: column },
            success: function (response) {
                showDropdown(column, response);
            },
            error: function () {
                alert("Error fetching column values.");
            }
        });
    });

    // 🔹 FUNCTION TO DISPLAY DROPDOWN MENU
    function showDropdown(column, dropdownOptionsHTML) {
        $(".filter-container").remove(); // ✅ Remove any existing dropdown first

        let dropdownHTML = `
            <div class="filter-container" style="position: absolute; background: white; z-index: 1000; padding: 5px; border: 1px solid #ccc;">
                <select class="form-select filter-dropdown" data-column="${column}" style="width: 200px;">
                    ${dropdownOptionsHTML}
                </select>
            </div>`;

        // Append dropdown to the clicked column
        let header = $(`th[data-column='${column}']`);
        header.append(dropdownHTML);

        // 🔹 Prevent dropdown from closing when interacting with it
        $(".filter-container").click(function (event) {
            event.stopPropagation();
        });

        // Handle dropdown change event
        $(".filter-dropdown").change(function () {
            let selectedValue = $(this).val();
            let column = $(this).data("column");

            // Ensure correct filtering value is sent (empty selection resets filter)
            let filterValue = selectedValue ? selectedValue : '';

            // Fetch filtered results
            $.ajax({
                url: "fetch_sorted_employees.php",
                type: "POST",
                data: { order_by: column, sort_order: currentSortOrder, filter_value: filterValue },
                success: function (response) {
                    $("tbody").html(response);
                    $(".filter-container").remove(); // ✅ Removes dropdown after selection
                },
                error: function () {
                    alert("Error filtering results.");
                }
            });
        });
    }

    // 🔹 CLOSE DROPDOWN WHEN CLICKING OUTSIDE, BUT NOT WHEN INTERACTING WITH IT
    $(document).click(function (e) {
        if (!$(e.target).closest(".filterable, .filter-container").length) {
            $(".filter-container").remove();
            dropdownActive = null;
        }
    });
});
</script>



</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
