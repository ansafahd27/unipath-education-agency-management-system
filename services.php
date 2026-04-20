<?php
include 'includes/db_connect.php';
include 'includes/header.php';

// Fetch Services
$services_sql = "SELECT * FROM services ORDER BY service_id ASC";
$services_result = mysqli_query($conn, $services_sql);
?>

<section class="page-hero">
    <div class="container">
        <h1>Our Services</h1>
        <p>Comprehensive support for your study abroad journey.</p>
    </div>
</section>

<div class="container" style="padding: 60px 15px;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">

        <?php if ($services_result && mysqli_num_rows($services_result) > 0): ?>
            <?php while ($service = mysqli_fetch_assoc($services_result)): ?>
                <div class="service-card">
                    <div class="service-card-icon">
                        ✅
                    </div>
                    <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                    <p><?php echo htmlspecialchars($service['description']); ?></p>
                    <a href="student/appointment.php?service=<?php echo $service['name']; ?>" class="btn btn-outline">Book
                        Consultation</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center; width: 100%;">No services found in the database.</p>
        <?php endif; ?>

    </div>
</div>

<?php include 'includes/footer.php'; ?>