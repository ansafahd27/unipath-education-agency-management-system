<?php
include '../includes/db_connect.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM services WHERE service_id=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: manage_services.php?msg=Service+deleted+successfully");
    } else {
        header("Location: manage_services.php?msg=Error+deleting+record");
    }
    exit();
}

// Fetch Services
$services = [];
$sql = "SELECT * FROM services ORDER BY name ASC";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $services[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services - UniPath Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">


</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <header class="header-actions">
                <h1>Manage Services (<?php 
                    $count_query = "SELECT COUNT(*) as total FROM services";
                    $count_res = mysqli_query($conn, $count_query);
                    $count_row = mysqli_fetch_assoc($count_res);
                    echo $count_row['total']; 
                ?>)</h1>
                <a href="add_service.php" class="btn btn-primary">➕ Add New Service</a>
            </header>

            <?php if (isset($_GET['msg'])): ?>
                <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
            <?php endif; ?>

            <div class="content-section">
                <?php if (count($services) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Service Name</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $service): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($service['name']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($service['description'], 0, 30)) . '...'; ?></td>
                                    <td class="action-buttons-cell">
                                        <div class="action-buttons">
                                            <a href="add_service.php?id=<?php echo $service['service_id']; ?>" class="btn btn-sm btn-minimal edit">
                                                ✏️ Edit
                                            </a>
                                            <a href="?delete=<?php echo $service['service_id']; ?>" class="btn btn-sm btn-minimal delete"
                                                onclick="return confirm('Are you sure?');">
                                                🗑️ Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No services found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>