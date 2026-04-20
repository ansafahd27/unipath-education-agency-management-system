<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
        <h2><a href="<?php echo base_url('index.php'); ?>" style="color: inherit; text-decoration: none;">🎓
                        UniPath</a></h2>
        <ul>
                <li><a href="<?php echo base_url('admin/dashboard.php'); ?>"
                                class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">📊 Overview</a>
                </li>
                <li><a href="<?php echo base_url('admin/manage_universities.php'); ?>"
                                class="<?php echo $current_page == 'manage_universities.php' ? 'active' : ''; ?>">🏛️
                                Universities</a></li>
                <li><a href="<?php echo base_url('admin/manage_courses.php'); ?>"
                                class="<?php echo ($current_page == 'add_course.php') ? 'active' : ''; ?>">📚
                                Courses</a></li>
                <li><a href="<?php echo base_url('admin/manage_services.php'); ?>"
                                class="<?php echo $current_page == 'manage_services.php' ? 'active' : ''; ?>">🛎️
                                Services</a></li>
                <li><a href="<?php echo base_url('admin/manage_blogs.php'); ?>"
                                class="<?php echo $current_page == 'manage_blogs.php' ? 'active' : ''; ?>">📰
                                Blogs/News</a></li>
                <li><a href="<?php echo base_url('admin/manage_success_stories.php'); ?>"
                                class="<?php echo $current_page == 'manage_success_stories.php' ? 'active' : ''; ?>">⭐
                                Success Stories</a></li>
                <li><a href="<?php echo base_url('admin/manage_inquiries.php'); ?>"
                                class="<?php echo $current_page == 'manage_inquiries.php' ? 'active' : ''; ?>">📩
                                Inquiries</a></li>
                <li><a href="<?php echo base_url('admin/users.php'); ?>"
                                class="<?php echo $current_page == 'users.php' ? 'active' : ''; ?>">👥 Users</a></li>
        </ul>
</div>