<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <h2><a href="../index.php" style="color: inherit; text-decoration: none;">🎓
            UniPath</a></h2>
    <ul>
        <li><a href="dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">📊 Dashboard</a></li>
        <li><a href="profile.php" class="<?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">👤 My Profile</a></li>
        <li><a href="appointment.php" class="<?php echo $current_page == 'appointment.php' ? 'active' : ''; ?>">📅 Book Appointment</a></li>
        <li><a href="universities.php" class="<?php echo $current_page == 'universities.php' ? 'active' : ''; ?>">🔍 Browse Unis</a></li>
    </ul>
</div>