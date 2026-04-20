<?php
include '../includes/db_connect.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';

// Edit Mode Logic
$mode = 'add';
$service_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$name = '';
$description = '';

if ($service_id > 0) {
    $mode = 'edit';
    $sql = "SELECT * FROM services WHERE service_id=$service_id";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        if ($row = mysqli_fetch_assoc($result)) {
            $name = $row['name'];
            $description = $row['description'];
        }
    } else {
        $error = "Service not found.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];

    if ($mode == 'edit') {
        $sql = "UPDATE services SET name='$name', description='$description' WHERE service_id=$service_id";
        if (mysqli_query($conn, $sql)) {

            $success = "Service updated successfully!";
        } else {
            $error = "Error updating service: " . mysqli_error($conn);
        }
    } else {
        $sql = "INSERT INTO services (name, description) VALUES ('$name', '$description')";
        if (mysqli_query($conn, $sql)) {
            $success = "Service added successfully!";
        } else {
            $error = "Error adding service: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $mode; ?> Service - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <div class="header-actions">
                <h1><?php echo ucfirst($mode); ?> Service</h1>
                <a href="manage_services.php" class="btn btn-outline">Back to List</a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <div class="content-section" style="max-width: 800px;">
                <form action="" method="POST" onsubmit="return CheckForm(this)" novalidate>
                    <div class="form-group">
                        <label>Service Name *</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required
                            class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="4"
                            class="form-control"><?php echo htmlspecialchars($description); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary"><?php echo $mode == 'edit' ? 'Update' : 'Save'; ?>
                        Service</button>
                </form>
            </div>
        </div>
    </div>
    <script src="../assets/js/validation.js"></script>
</body>

</html>