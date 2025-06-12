<?php
// Database connection
include 'db_connection.php'; 
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['videoFile'])) {
    $targetDir = "video/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Create directory if not exists
    }

    $fileName = basename($_FILES['videoFile']['name']);
    $fileName = preg_replace("/[^a-zA-Z0-9\._-]/", "_", $fileName);
    $videoFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Allowed file types
    $allowedTypes = ["mp4", "avi", "mov", "wmv"];
    if (!in_array($videoFileType, $allowedTypes)) {
        header("Location: index.php?message=Invalid file type. Allowed types: MP4, AVI, MOV, WMV.");
        exit;
    } elseif ($_FILES['videoFile']['size'] > 50 * 1024 * 1024) {
        header("Location: index.php?message=File too large (max: 50MB).");
        exit;
    } else {
        // Secure filename and target path
        $newFileName = time() . "_" . $fileName;
        $targetFile = $targetDir . $newFileName;

        // MIME type verification
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $_FILES['videoFile']['tmp_name']);
        finfo_close($finfo);

        if (!preg_match("/video\//", $mimeType)) {
            header("Location: index.php?message=Invalid file type detected.");
            exit;
        } else {
            // Move uploaded file
            if (move_uploaded_file($_FILES['videoFile']['tmp_name'], $targetFile)) {
                $stmt = $conn->prepare("INSERT INTO videos (filename, uploaded_at) VALUES (?, NOW())");
                $stmt->bind_param("s", $newFileName);

                if ($stmt->execute()) {
                    header("Location: index.php?message=File uploaded successfully!");
                } else {
                    header("Location: index.php?message=Database error: " . $conn->error);
                }
                $stmt->close();
            } else {
                header("Location: index.php?message=Upload failed.");
            }
        }
    }
}

$conn->close();
exit;
?>
