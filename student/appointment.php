<?php
include '../includes/db_connect.php';
session_start();

// Auth Check with Redirect Logic
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    $redirect_url = urlencode($_SERVER['REQUEST_URI']);
    header("Location: ../login.php?redirect=" . $redirect_url);
    exit();
}

$user_id = $_SESSION['user_id'];
$success_msg = '';
$error_msg = '';

$edit_mode = false;
$appt_id = isset($_GET['edit_id']) ? intval($_GET['edit_id']) : 0;
$student_id = 0;

// Fetch Student Profile ID
$prof_res = mysqli_query($conn, "SELECT profile_id FROM student_profiles WHERE user_id='$user_id'");
if (mysqli_num_rows($prof_res) > 0) {
    $prof_row = mysqli_fetch_assoc($prof_res);
    $student_id = $prof_row['profile_id'];
} else {
    $error_msg = "Please update your profile first before booking.";
}

$service = '';
$description = '';
$date = '';
$time = '';
$mode = '';
$status = '';


// Fetch Existing Appointment if Edit Mode
if ($appt_id > 0 && $student_id > 0) {

    $sql = "SELECT * FROM appointments WHERE appt_id = '$appt_id;' AND student_id = '$student_id'";
    $res = mysqli_query($conn, $sql) or die('Error: ' . mysqli_error($conn));

    if ($row = mysqli_fetch_assoc($res)) {
        $edit_mode = true;
        $service = $row['service_type'];
        $description = $row['description'];
        $date = $row['appt_date'];
        $time = $row['appt_time'];
        $mode = $row['mode'];
        $status = $row['status'];
    } else {
        $error_msg = "Appointment not found or access denied.";
    }
} else {
    if (isset($_GET['service'])) {
        $service = urldecode($_GET['service']);
    }

    $context_course = isset($_GET['course']) ? urldecode($_GET['course']) : '';
    $context_uni = isset($_GET['uni']) ? urldecode($_GET['uni']) : '';

    if (!empty($context_course) || !empty($context_uni)) {
        $description = "I am interested in applying";
        if (!empty($context_course)) {
            $description .= " for the " . $context_course . " course";
        }
        if (!empty($context_uni)) {
            $description .= " at " . $context_uni;
        }
        $description .= ". Please guide me through the process.";
    }
}



if ($student_id > 0) {

    if (isset($_POST['cancel_appt']) && $edit_mode) {
        $cancel_sql = "UPDATE appointments SET status = 'Cancelled' WHERE appt_id = '$appt_id'";
        if (mysqli_query($conn, $cancel_sql)) {
            header("Location: dashboard.php?msg=Appointment+Cancelled");
            exit();
        } else {
            die('Error: ' . mysqli_error($conn));
        }
    } elseif (isset($_POST['submit'])) {
        $service = trim($_POST['service']);
        $description = trim($_POST['description']);
        $date = $_POST['date'];
        $time = $_POST['time'];
        $mode = $_POST['mode'];

        $check_sql = "SELECT COUNT(*) as count FROM appointments WHERE appt_date = '$date' AND appt_time = '$time' AND mode = '$mode' AND status != 'Cancelled'";

        if ($edit_mode) {
            $check_sql .= " AND appt_id != '$appt_id'";
        }

        $check_res = mysqli_query($conn, $check_sql) or die('Error: ' . mysqli_error($conn));
        $row_check = mysqli_fetch_assoc($check_res);
        $conflict_count = $row_check['count'];

        if ($conflict_count > 0) {
            $error_msg = "Slot unavailable! An appointment already exists for this Date, Time, and Mode.";
        } else {
            if ($edit_mode) {
                $update_sql = "UPDATE appointments SET 
                               service_type='$service', 
                               description='$description', 
                               appt_date='$date', 
                               appt_time='$time', 
                               mode='$mode', 
                               status='Pending' 
                               WHERE appt_id='$appt_id'";

                if (mysqli_query($conn, $update_sql)) {
                    $success_msg = "Appointment updated successfully!";
                } else {
                    die('Error: ' . mysqli_error($conn));
                }
            } else {
                $insert_sql = "INSERT INTO appointments (student_id, service_type, description, appt_date, appt_time, mode, status) 
                               VALUES ('$student_id', '$service', '$description', '$date', '$time', '$mode', 'Pending')";

                if (mysqli_query($conn, $insert_sql)) {
                    $success_msg = "Appointment booked successfully!";
                    $service = '';
                    $description = '';
                    $date = '';
                    $time = '';
                    $mode = '';
                } else {
                    die('Error: ' . mysqli_error($conn));
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $edit_mode ? 'Manage Appointment' : 'Book Appointment'; ?> - UniPath</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=1.10">

</head>

<body>

    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>

        <div class="main-content">
            <header class="dashboard-header">
                <h1><?php echo $edit_mode ? 'Manage Appointment' : 'Book Appointment'; ?></h1>

            </header>

            <?php if ($success_msg): ?>
                <div class="alert-success">✅ <?php echo $success_msg; ?></div>
            <?php endif; ?>
            <?php if ($error_msg): ?>
                <div class="alert-error">⚠️ <?php echo $error_msg; ?></div>
            <?php endif; ?>

            <div class="content-section">
                <?php if ($edit_mode && $status == 'Cancelled'): ?>
                    <div class="alert-error">This appointment is Cancelled. You cannot edit it.</div>
                <?php else: ?>

                    <form action="" method="POST" style="max-width: 600px;" onsubmit="return CheckForm(this)" novalidate>
                        <div class="form-group">
                            <label class="form-label">Service / Topic</label>
                            <select name="service" class="form-input" required>
                                <option value="">Select Service</option>
                                <?php
                                $svc_sql = "SELECT name FROM services ORDER BY service_id ASC";
                                $svc_res = mysqli_query($conn, $svc_sql);
                                while ($svc = mysqli_fetch_assoc($svc_res)) {
                                    $selected = ($service == $svc['name']) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($svc['name']) . "' $selected>" . htmlspecialchars($svc['name']) . "</option>";
                                }
                                ?>
                            </select>
                            <span class="error-message" id="service-error"></span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Description / Notes</label>
                            <textarea name="description" class="form-input" rows="4"
                                placeholder="Briefly describe what you need help with..."
                                required><?php echo htmlspecialchars($description); ?></textarea>
                            <span class="error-message" id="description-error"></span>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Date</label>
                                <input type="date" name="date" class="form-input" value="<?php echo $date; ?>"
                                    min="<?php echo date('Y-m-d'); ?>" required>
                                <span class="error-message" id="date-error"></span>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Time</label>
                                <input type="time" name="time" class="form-input" value="<?php echo $time; ?>" required>
                                <span class="error-message" id="time-error"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Preferred Mode</label>
                            <select name="mode" class="form-input" required>
                                <option value="">Select Mode</option>
                                <option value="In-person" <?php echo ($mode == 'In-person') ? 'selected' : ''; ?>>In-person
                                </option>
                                <option value="Video" <?php echo ($mode == 'Video') ? 'selected' : ''; ?>>Video Call</option>
                                <option value="Phone" <?php echo ($mode == 'Phone') ? 'selected' : ''; ?>>Phone Call</option>
                            </select>
                            <span class="error-message" id="mode-error"></span>
                        </div>

                        <div style="margin-top: 20px; display: flex; gap: 15px;">
                            <?php if ($edit_mode): ?>
                                <button type="submit" name="submit" class="btn btn-primary">Update Appointment</button>
                                <button type="submit" name="cancel_appt" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to cancel this appointment?');"
                                    formnovalidate>Cancel Appointment</button>
                            <?php else: ?>
                                <button type="submit" name="submit" class="btn btn-success">Book Appointment</button>
                            <?php endif; ?>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="../assets/js/validation.js"></script>

</html>