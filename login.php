<?php
include 'includes/db_connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: student/dashboard.php");
    }
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];


    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_array($result);
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                if (isset($_GET['redirect'])) {
                    header("Location: " . urldecode($_GET['redirect']));
                } else {
                    header("Location: student/dashboard.php");
                }
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No account found with that email.";
    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - UniPath</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css?v=1.1">

</head>

<body class="auth-body">

    <div class="auth-container auth-card">
        <h2 class="auth-header">
            <a href="<?php echo base_url('index.php'); ?>" class="auth-logo">🎓
                UniPath</a> Login
        </h2>

        <?php if ($error): ?>
            <div class="alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" onsubmit="return CheckForm(this)" novalidate autocomplete="off">
            <?php if (isset($_GET['redirect'])): ?>
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_GET['redirect']); ?>">
            <?php endif; ?>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" required class="form-input" autocomplete="off">
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" required class="form-input" autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-primary btn-full">Sign In</button>
        </form>
        <div class="auth-footer">
            Don't have an account? <a href="<?php echo base_url('student/register.php'); ?>"
                class="link-primary">Register</a>
            <br>
            <a href="<?php echo base_url('index.php'); ?>" class="link-home">Back to Home</a>
        </div>
    </div>

    <script src="assets/js/validation.js"></script>
    <script>
        window.addEventListener('load', function () {
            var forms = document.getElementsByTagName('form');
            for (var i = 0; i < forms.length; i++) {
                forms[i].reset();
            }
        });
    </script>
</body>

</html>