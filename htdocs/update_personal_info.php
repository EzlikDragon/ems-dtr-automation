<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_id = intval($_POST["emp_id"]);
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $date_of_birth = $_POST["date_of_birth"] ?: NULL;
    $place_of_birth = trim($_POST["place_of_birth"]) ?: NULL;
    $gender = trim($_POST["gender"]) ?: NULL;
    $address = trim($_POST["address"]) ?: NULL;

    // Split the address into parts (optional logic based on structure)
    list($house_block_lot, $street, $barangay, $city_municipality, $province) = array_pad(explode(", ", $address), 5, NULL);

    $query = "UPDATE personal_info 
              SET first_name = ?, last_name = ?, date_of_birth = ?, place_of_birth = ?, gender = ?, 
                  house_block_lot = ?, street = ?, barangay = ?, city_municipality = ?, province = ?
              WHERE emp_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssssi", $first_name, $last_name, $date_of_birth, $place_of_birth, $gender, 
                                   $house_block_lot, $street, $barangay, $city_municipality, $province, $emp_id);

    if ($stmt->execute()) {
        $_SESSION["success"] = "Personal information updated successfully.";
    } else {
        $_SESSION["error"] = "Error updating personal information.";
    }

    header("Location: view_employee.php?id=$emp_id");
    exit();
}
?>
