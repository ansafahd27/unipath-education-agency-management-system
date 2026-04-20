<?php
include '../includes/db_connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if email already exists
    $check_sql = "SELECT user_id FROM users WHERE email = '$email'";
    $check_res = mysqli_query($conn, $check_sql) or die('Error: ' . mysqli_error($conn));

    if (mysqli_num_rows($check_res) > 0) {
        $error = "Email is already registered.";
    } else {
        $role = 'student';

        // Insert User
        $sql_insert = "INSERT INTO users (username, email, password, role) VALUES ('$name', '$email', '$password', '$role')";

        if (mysqli_query($conn, $sql_insert)) {
            $user_id = mysqli_insert_id($conn);

            $sql_profile = "INSERT INTO student_profiles (user_id, full_name) VALUES ('$user_id', '$name')";
            if (mysqli_query($conn, $sql_profile)) {
                $success = "Registration successful! <a href='../login.php'>Login here</a>";
            } else {
                die('Error creating profile: ' . mysqli_error($conn));
            }
        } else {
            $error = "Registration failed. Please try again. (" . mysqli_error($conn) . ")";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register - UniPath</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css?v=1.1">
    </style>
</head>

<body class="auth-body">

    <div class="auth-container auth-card">
        <h2 class="auth-header">
            <a href="../index.php" class="auth-logo">🎓
                UniPath</a> Register
        </h2>

        <?php if ($error): ?>
            <div class="alert-error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert-success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" onsubmit="return CheckForm(this)" novalidate>
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" required class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" required class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" required class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" required class="form-input">
            </div>
            <button type="submit" name="submit" class="btn btn-primary btn-full">Register</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="../login.php" class="link-primary">Login</a>
            <br>
            <a href="../index.php" class="link-home">Back to Home</a>
        </div>
    </div>

    <script src="../assets/js/validation.js"></script>
</body>

</html>