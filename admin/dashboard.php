<?php
include '../includes/db_connect.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Real Stats Logic
$stundet_count = 0;
$appt_count = 0;
$msg_count = 0;
$count_sql = "SELECT 
    (SELECT COUNT(*) FROM users WHERE role='student') as u_count,
    (SELECT COUNT(*) FROM inquiries WHERE status='Pending') as in_count,
    (SELECT COUNT(*) FROM appointments) as a_count";

$count_result = mysqli_query($conn, $count_sql);
if ($row = mysqli_fetch_assoc($count_result)) {
    $stundet_count = $row['u_count'];
    $msg_count = $row['in_count'];
    $appt_count = $row['a_count'];
}


$appts = [];
$sql_recent = "SELECT appointments.*, users.username AS student_name 
               FROM appointments 
               LEFT JOIN student_profiles ON appointments.student_id = student_profiles.profile_id 
               LEFT JOIN users ON student_profiles.user_id = users.user_id 
               ORDER BY appt_date DESC, appt_time DESC LIMIT 5";
$result_recent = mysqli_query($conn, $sql_recent);
if ($result_recent) {
    while ($row = mysqli_fetch_assoc($result_recent)) {

        $appts[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - UniPath</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=1.11">


</head>

<body>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <header class="dashboard-header">
                <h1>Dashboard Overview</h1>
                <div class="header-actions">
                    <a href="add_admin.php" class="btn btn-primary">➕ Create Admin</a>
                    <a href="../logout.php" class="btn btn-danger">🚪 Logout</a>
                </div>
            </header>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">🎓</div>
                    <div>
                        <h3><?php echo $stundet_count; ?></h3>
                        <p>Students</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📅</div>
                    <div>
                        <h3><?php echo $appt_count; ?></h3>
                        <p>Appointments</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">📩</div>
                    <div>
                        <h3><?php echo $msg_count; ?></h3>
                        <p>Pending Inquiries</p>
                    </div>
                </div>

            </div>

            <div style="margin-bottom: 20px; text-align: right;">
                <a href="manage_appointments.php" class="btn btn-primary">📅 View All Appointments</a>
            </div>

            <div class="content-section" id="appointments">
                <h3>Manage Appointments</h3>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Service</th>
                                <th>Date/Time</th>
                                <th>Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appts as $appt): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($appt['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($appt['service_type']); ?></td>
                                    <td><?php echo htmlspecialchars($appt['appt_date'] . ' ' . $appt['appt_time']); ?></td>
                                    <td>
                                        <?php
                                        $s_class = 'status-default';
                                        if ($appt['status'] == 'Confirmed')
                                            $s_class = 'status-confirmed';
                                        elseif ($appt['status'] == 'Pending')
                                            $s_class = 'status-pending';
                                        elseif ($appt['status'] == 'Cancelled')
                                            $s_class = 'status-cancelled';
                                        elseif ($appt['status'] == 'Completed')
                                            $s_class = 'status-completed';
                                        ?>
                                        <span class="status-badge <?php echo $s_class; ?>">
                                            <?php echo htmlspecialchars($appt['status']); ?>
                                        </span>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <script src="../assets/js/validation.js"></script>
</body>

</html>