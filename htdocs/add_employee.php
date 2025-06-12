<?php
session_start();
include 'db_connection.php';

// Function to sanitize user input
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form and sanitize
    $bio_id = (int)sanitize_input($_POST['bio_id']);  // Ensure bio_id is treated as integer
    $first_name = sanitize_input($_POST["first_name"] ?? '');
    $middle_name = sanitize_input($_POST["middle_name"] ?? NULL);
    $last_name = sanitize_input($_POST["last_name"] ?? '');
    $ext_name = sanitize_input($_POST["ext_name"] ?? NULL);
    $status = sanitize_input($_POST["status"] ?? '');
    $unit = sanitize_input($_POST["unit"] ?? NULL);
    $dtr_group = sanitize_input($_POST["dtr_group"] ?? NULL);
    $charged_office = sanitize_input($_POST["charged_office"] ?? NULL);
    
    // Validate the input
    $error_message = '';
    if (empty($bio_id) || empty($first_name) || empty($last_name) || empty($status)) {
        $error_message = "Bio ID, First Name, Last Name, and Status are required fields.";
    }

    // If no error, proceed with database insertion
    if (empty($error_message)) {
        // Prepare SQL query to insert employee data into the database
        $query = "INSERT INTO employee (bio_id, first_name, middle_name, last_name, ext_name, status, unit, dtr_group, charged_office)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("issssssss", $bio_id, $first_name, $middle_name, $last_name, $ext_name, $status, $unit, $dtr_group, $charged_office);
            
            // Execute the query
            if ($stmt->execute()) {
                // Redirect with success message
                $_SESSION['status_message'] = "Employee added successfully.";
                header("Location: manage_employees.php");
                exit();
            } else {
                // Error inserting data
                $_SESSION['status_message'] = "Error: " . $stmt->error;
                header("Location: manage_employees.php");
                exit();
            }

            // Close the statement
            $stmt->close();
        } else {
            $_SESSION['status_message'] = "Error: Could not prepare SQL statement.";
            header("Location: manage_employees.php");
            exit();
        }
    } else {
        // Display validation errors and redirect
        $_SESSION['status_message'] = $error_message;
        header("Location: manage_employees.php");
        exit();
    }
} else {
    // Invalid request method
    $_SESSION['status_message'] = "Invalid request method.";
    header("Location: manage_employees.php");
    exit();
}

// Close the database connection
$conn->close();
?>
