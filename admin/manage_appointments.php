<?php
include '../includes/db_connect.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$message = "";

// Handle Status Updates
if (isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    $appt_id = intval($_POST['appt_id']);

    $sql_update = "UPDATE appointments SET status='$new_status' WHERE appt_id=$appt_id";
    if (mysqli_query($conn, $sql_update)) {
        $message = "Appointment #$appt_id status updated to '$new_status'.";
    } else {
        $message = "Error updating status: " . mysqli_error($conn);
    }
}

// Handle Deletions
if (isset($_GET['delete'])) {
    $appt_id = intval($_GET['delete']);
    $sql_delete = "DELETE FROM appointments WHERE appt_id=$appt_id";
    if (mysqli_query($conn, $sql_delete)) {
        header("Location: manage_appointments.php?msg=Appointment+deleted+successfully");
        exit();
    } else {
        $message = "Error deleting appointment: " . mysqli_error($conn);
    }
}

// Filter Logic
$status_filter = "";
$where_sql = "";
if (isset($_GET['status']) && !empty($_GET['status'])) {
    $status_filter = $_GET['status'];
    $where_sql = "WHERE appointments.status = '$status_filter'";
}

$sql = "SELECT appointments.*, users.username AS student_name 
        FROM appointments 
        LEFT JOIN student_profiles ON appointments.student_id = student_profiles.profile_id 
        LEFT JOIN users ON student_profiles.user_id = users.user_id 
        $where_sql 
        ORDER BY appt_date DESC, appt_time DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Appointments - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1>All Appointments</h1>
            </header>

            <?php if (isset($_GET['msg'])): ?>
                <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
            <?php endif; ?>
            <?php if ($message): ?>
                <div style="background: #e2e3e5; color: #383d41; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Filter Section -->
            <div class="content-section" style="margin-bottom: 20px; padding: 15px;">
                <form action="" method="GET" style="display: flex; gap: 10px; align-items: center;"
                    onsubmit="return CheckForm(this)" novalidate>
                    <label style="font-weight: 600;">Filter by Status:</label>
                    <select name="status" style="padding: 8px; border-radius: 5px; border: 1px solid #ddd;">
                        <option value="">All Statuses</option>
                        <option value="Pending" <?php echo $status_filter == 'Pending' ? 'selected' : ''; ?>>Pending
                        </option>
                        <option value="Confirmed" <?php echo $status_filter == 'Confirmed' ? 'selected' : ''; ?>>Confirmed
                        </option>
                        <option value="Completed" <?php echo $status_filter == 'Completed' ? 'selected' : ''; ?>>Completed
                        </option>
                        <option value="Cancelled" <?php echo $status_filter == 'Cancelled' ? 'selected' : ''; ?>>Cancelled
                        </option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <?php if ($status_filter): ?>
                        <a href="manage_appointments.php" class="btn btn-primary btn-sm">Clear</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="content-section">
                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Service</th>
                                <th>Date/Time</th>
                                <th>Mode</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $name = !empty($row['student_name']) ? $row['student_name'] : 'Guest/Unknown';

                                    // Use a switch for cleaner color logic
                                    switch ($row['status']) {
                                        case 'Confirmed':
                                            $status_color = '#d4edda';
                                            break;
                                        case 'Pending':
                                            $status_color = '#fff3cd';
                                            break;
                                        case 'Cancelled':
                                            $status_color = '#f8d7da';
                                            break;
                                        case 'Completed':
                                            $status_color = '#d1ecf1';
                                            break;
                                        default:
                                            $status_color = '#e2e3e5';
                                            break;
                                    }

                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($name) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['service_type']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['appt_date'] . ' ' . $row['appt_time']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['mode']) . "</td>";
                                    echo "<td><span style='padding: 5px 10px; border-radius: 15px; background: $status_color; font-size: 0.85em;'>" . htmlspecialchars($row['status']) . "</span></td>";
                                    echo "<td>
                <form action='' method='POST' class='action-buttons' onsubmit='return CheckForm(this)' novalidate>
                    <input type='hidden' name='appt_id' value='" . (int) $row['appt_id'] . "'>
                    <select name='status' style='padding: 5px; border-radius: 4px; border: 1px solid #ddd; font-size: 0.9em; max-width: 100px;'>
                        <option value='Pending' " . ($row['status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                        <option value='Confirmed' " . ($row['status'] == 'Confirmed' ? 'selected' : '') . ">Confirm</option>
                        <option value='Completed' " . ($row['status'] == 'Completed' ? 'selected' : '') . ">Complete</option>
                        <option value='Cancelled' " . ($row['status'] == 'Cancelled' ? 'selected' : '') . ">Cancel</option>
                    </select>
                    <button type='submit' name='update_status' class='btn btn-sm btn-minimal edit' style='color: #0d6efd;'>Update</button>
                    <a href='?delete=" . $row['appt_id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\");'>
                        🗑️ Delete
                    </a>
                </form>
            </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' style='text-align:center;'>No appointments found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/validation.js"></script>
</body>

</html>