<?php
include '../includes/db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

// Fetch User ID
$user_id = $_SESSION['user_id'];
$user = [
    'name' => $_SESSION['username'], // Default from session
    'email' => ''
];

$profile_id = 0;
$sql_profile = "SELECT profile_id, full_name, user_id FROM student_profiles WHERE user_id = '$user_id'";
$result_profile = mysqli_query($conn, $sql_profile);
if ($result_profile && mysqli_num_rows($result_profile) > 0) {
    $profile_data = mysqli_fetch_assoc($result_profile);
    $profile_id = $profile_data['profile_id'];
    if (!empty($profile_data['full_name'])) {
        $user['name'] = $profile_data['full_name'];
    }
}


// Handle Appointment Deletion
if (isset($_GET['delete_appt'])) {
    $del_id = intval($_GET['delete_appt']);
    $check_sql = "SELECT student_id FROM appointments WHERE appt_id = $del_id";
    $check_res = mysqli_query($conn, $check_sql);
    if ($check_res && mysqli_num_rows($check_res) > 0) {
        $appt_check = mysqli_fetch_assoc($check_res);
        if ($appt_check['student_id'] == $profile_id) {
            $del_sql = "DELETE FROM appointments WHERE appt_id = $del_id";
            if (mysqli_query($conn, $del_sql)) {
                header("Location: dashboard.php?msg=" . urlencode("Appointment cancelled successfully."));
                exit();
            }
        }
    }
}

$student_id = 0;
$u_id = $_SESSION['user_id'];
$s_sql = "SELECT profile_id FROM student_profiles WHERE user_id = '$u_id'";
$s_res = mysqli_query($conn, $s_sql);
if ($s_res && $row = mysqli_fetch_assoc($s_res)) {
    $student_id = $row['profile_id'];
}

$saved_count = 0;
if ($student_id > 0) {
    $saved_sql = "SELECT COUNT(*) as count FROM saved_universities WHERE student_id = '$student_id'";
    $saved_res = mysqli_query($conn, $saved_sql);
    if ($saved_res) {
        $saved_count = mysqli_fetch_assoc($saved_res)['count'];
    }
}
$appointments = [];
if ($profile_id > 0) {
    $sql_appts = "SELECT * FROM appointments WHERE student_id = '$profile_id' ORDER BY appt_date DESC, appt_time DESC";
    $result_appts = mysqli_query($conn, $sql_appts);
    if ($result_appts) {
        while ($row = mysqli_fetch_assoc($result_appts)) {
            // Map keys to match view or update view
            $row['date'] = $row['appt_date'];
            $row['time'] = $row['appt_time'];
            $appointments[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - UniPath</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=1.8">

    <style>
        .status-Pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-Confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status-Completed {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-Cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: 600;
        }

        .student-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header-title {
            margin: 0;
        }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .section-title {
            margin-bottom: 15px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .empty-state-container {
            text-align: center;
            padding: 2rem;
        }

        .empty-state-text {
            color: #666;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <header class="student-header">
                <h1 class="header-title">Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
                <div class="profile-section"><a class="btn btn-danger" href="../logout.php">Logout</a>
                </div>
            </header>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📅</div>
                    <div>
                        <h3><?php echo count($appointments); ?></h3>
                        <p class="stat-label">Total Appointments</p>
                    </div>
                </div>
                <a href="universities.php?view=saved" class="stat-card" style="text-decoration: none; color: inherit;">
                    <div class="stat-icon">❤️</div>
                    <div>
                        <h3><?php echo $saved_count; ?></h3>
                        <p class="stat-label">Saved Universities</p>
                    </div>
                </a>
            </div>

            <div class="content-section">
                <h3 class="section-title">My Appointments</h3>
                <?php if (count($appointments) > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Mode</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointments as $appt): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($appt['service_type']); ?></td>
                                        <td><?php echo htmlspecialchars($appt['date']); ?></td>
                                        <td><?php echo htmlspecialchars($appt['time']); ?></td>
                                        <td><?php echo htmlspecialchars($appt['mode']); ?></td>
                                        <td><span
                                                class="status-badge status-<?php echo $appt['status']; ?>"><?php echo $appt['status']; ?></span>
                                        </td>
                                        <td>
                                            <?php if ($appt['status'] == 'Cancelled'): ?>
                                                <form action="dashboard.php" method="GET"
                                                    onsubmit="return confirm('Are you sure you want to delete this appointment?') && CheckForm(this);">
                                                    <input type="hidden" name="delete_appt" value="<?php echo $appt['appt_id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        style="padding: 5px 10px; font-size: 0.8rem;">🗑️ Delete</button>
                                                </form>
                                            <?php else: ?>
                                                <a href="appointment.php?edit_id=<?php echo $appt['appt_id']; ?>"
                                                    class="btn btn-sm btn-outline">Manage</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state-container">
                        <p class="empty-state-text">No appointments booked yet.</p>
                        <a href="appointment.php" class="btn btn-primary">Book Your First Appointment</a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
    <script src="../assets/js/validation.js"></script>
</body>

</html>