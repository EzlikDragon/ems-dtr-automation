<?php
session_start();
date_default_timezone_set('Asia/Manila');
include 'db_connection.php';

$message = '';
$success = false;
$emp_photo = '';
$emp_name = '';
$remarks = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_id = trim($_POST['emp_id']);
    $today = date('Y-m-d');
    $now = date('H:i:s');
    $nowUnix = strtotime($now);

    $stmt = $conn->prepare("SELECT first_name, last_name, photo FROM employee WHERE emp_id = ?");
    $stmt->bind_param("s", $emp_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $emp = $result->fetch_assoc();
        $emp_name = $emp['first_name'] . ' ' . $emp['last_name'];
        $photo_filename = trim($emp['photo']);

        $stmt = $conn->prepare("SELECT * FROM dtr WHERE emp_id = ? AND date = ?");
        $stmt->bind_param("ss", $emp_id, $today);
        $stmt->execute();
        $dtr = $stmt->get_result()->fetch_assoc();

        if (!$dtr) {
            $stmt = $conn->prepare("INSERT INTO dtr (emp_id, photo, date, status) VALUES (?, ?, ?, 'Present')");
            $stmt->bind_param("sss", $emp_id, $photo_filename, $today);
            $stmt->execute();

            $stmt = $conn->prepare("SELECT * FROM dtr WHERE emp_id = ? AND date = ?");
            $stmt->bind_param("ss", $emp_id, $today);
            $stmt->execute();
            $dtr = $stmt->get_result()->fetch_assoc();
        }

        // Use photo from dtr table for display
        $emp_photo = (!empty($dtr['photo']) && file_exists(__DIR__ . "/images/" . $dtr['photo']))
            ? "images/" . $dtr['photo']
            : "images/default.png";

        $slot = '';
        if (empty($dtr['time_in_am']) && $nowUnix >= strtotime('06:00:00') && $nowUnix <= strtotime('12:00:00')) {
            $slot = 'time_in_am';
            if ($nowUnix > strtotime('08:30:00')) {
                $remarks = 'LATE';
            }
        } elseif (empty($dtr['time_out_am']) && $nowUnix >= strtotime('11:30:00') && $nowUnix <= strtotime('12:30:00')) {
            $slot = 'time_out_am';
        } elseif (empty($dtr['time_in_pm']) && $nowUnix >= strtotime('12:30:00') && $nowUnix <= strtotime('14:30:00')) {
            $slot = 'time_in_pm';
            if ($nowUnix > strtotime('13:00:00')) {
                $remarks = 'LATE';
            }
        } elseif (empty($dtr['time_out_pm']) && $nowUnix >= strtotime('16:30:00') && $nowUnix <= strtotime('19:00:00')) {
            $slot = 'time_out_pm';
            if ($nowUnix < strtotime('17:00:00')) {
                $remarks = 'EARLY OUT';
            }
        }

        if ($slot !== '') {
            $query = "UPDATE dtr SET $slot = ?" . ($remarks ? ", remarks = ?" : "") . " WHERE id = ?";
            $stmt = $conn->prepare($query);
            if ($remarks) {
                $stmt->bind_param("ssi", $now, $remarks, $dtr['id']);
            } else {
                $stmt->bind_param("si", $now, $dtr['id']);
            }
            $stmt->execute();

            $message = strtoupper(str_replace('_', ' ', $slot)) . " logged at " . date("h:i A", strtotime($now));
            if ($remarks) $message .= " [Remark: $remarks]";
            $success = true;
        } else {
            if (!empty($dtr['time_in_am']) && empty($dtr['time_out_am']) && $nowUnix < strtotime('11:30:00')) {
                $message = "⏳ You already timed in. Please wait until 11:30 AM for AM Time Out.";
            } elseif (!empty($dtr['time_in_pm']) && empty($dtr['time_out_pm']) && $nowUnix < strtotime('16:30:00')) {
                $message = "⏳ You already timed in this afternoon. Please wait until 4:30 PM for PM Time Out.";
            } else {
                $message = "⛔ Not allowed to log at this time. Please try again within your schedule.";
            }
        }

    } else {
        $message = "❌ Invalid Employee ID!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Scan to Log</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f7;
            text-align: center;
            padding-top: 100px;
        }
        .box {
            background: white;
            padding: 40px;
            width: 360px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        input[type="text"] {
            font-size: 20px;
            padding: 12px;
            width: 300px;
            text-align: center;
            margin-top: 10px;
        }
        .message {
            margin-top: 20px;
            font-size: 16px;
            padding: 10px;
            border-radius: 8px;
            font-weight: bold;
        }
        .success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }
        .error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #007bff;
            margin-bottom: 10px;
        }
        .name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .datetime {
            margin-bottom: 20px;
            font-size: 14px;
            color: #444;
        }
    </style>
    <script>
        setInterval(() => {
            const now = new Date();
            document.getElementById('datetime').textContent = now.toLocaleString('en-PH', { timeZone: 'Asia/Manila' });
        }, 1000);
    </script>
</head>
<body onload="document.getElementById('emp_id').focus();">

<div class="box">
    <h2>📷 Scan Employee ID</h2>
    <div class="datetime" id="datetime"></div>
    <form method="POST">
        <input type="text" name="emp_id" id="emp_id" placeholder="Scan barcode..." autofocus autocomplete="off">
    </form>

    <?php if ($message): ?>
        <div class="message <?= $success ? 'success' : 'error' ?>">
            <?php if ($success): ?>
                <img src="<?= htmlspecialchars($emp_photo) ?>" class="profile-pic"><br>
                <div class="name"><?= htmlspecialchars($emp_name) ?></div>
            <?php endif; ?>
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
</div>

<?php if ($success): ?>
    <audio autoplay>
        <source src="sounds/beep.mp3" type="audio/mpeg">
    </audio>
<?php endif; ?>

<?php if ($message): ?>
<script>
    setTimeout(() => {
        window.location.href = "dtr_scan.php";
    }, 3000);
</script>
<?php endif; ?>

</body>
</html>
