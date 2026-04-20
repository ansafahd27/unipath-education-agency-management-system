<?php
include '../includes/db_connect.php';
session_start();

// check is it admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = 'admin';

    // Insert data into the database table
    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$name', '$email', '$password', '$role')";

    try {
        if (mysqli_query($conn, $sql)) {
            $success = "New Administrator '$name' created successfully.";
        }
    } catch (mysqli_sql_exception $e) {
        $error = "Error: " . $e->getMessage();
    }

}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Admin - UniPath</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">


</head>

<body>
    <div class="dashboard-container">

        <?php include 'sidebar.php'; ?>

        <div class="main-content">
            <header style="margin-bottom: 30px;">
                <h1>Create New Administrator</h1>
            </header>

            <?php if (isset($success)): ?>
                <div class="alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="content-section">

                <form action="" method="POST" autocomplete="off" onsubmit="return CheckForm(this)" novalidate>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required
                            autocomplete="new-password">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Create Admin
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script src="../assets/js/validation.js"></script>
</body>

</html>