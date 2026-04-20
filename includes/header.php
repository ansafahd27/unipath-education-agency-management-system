<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniPath - Educational Agency</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css?v=3.1'); ?>">
    <script>
        const APP_URL = "<?php echo BASE_URL; ?>";
    </script>
</head>

<body>

    <header>
        <div class="container navbar">
            <a href="<?php echo base_url('index.php'); ?>" class="logo">🎓 UniPath</a>



            <ul class="nav-links">
                <li><a href="<?php echo base_url('index.php'); ?>">Home</a></li>
                <li><a href="<?php echo base_url('about.php'); ?>">About Us</a></li>
                <li><a href="<?php echo base_url('services.php'); ?>">Services</a></li>
                <li><a href="<?php echo base_url('blog.php'); ?>">Blog</a></li>
                <li><a href="<?php echo base_url('student/universities.php'); ?>">Universities</a></li>
                <li><a href="<?php echo base_url('contact.php'); ?>">Contact</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <li><a href="<?php echo base_url('admin/dashboard.php'); ?>" class="btn btn-outline">Admin Panel</a>
                        </li>
                    <?php else: ?>
                        <li><a href="<?php echo base_url('student/dashboard.php'); ?>" class="btn btn-outline">My Dashboard</a>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a href="<?php echo base_url('login.php'); ?>" class="btn btn-outline">Login</a></li>

                <?php endif; ?>
            </ul>
        </div>  
    </header>

    