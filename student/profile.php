<?php
include '../includes/db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

$success_msg = '';
$error_msg = '';

$user_id = $_SESSION['user_id'];


if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $dob = $_POST['dob'];
    $destination = trim($_POST['destination']);
    $field = trim($_POST['field']);

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if profile exists
    $check_sql = "SELECT profile_id FROM student_profiles WHERE user_id='$user_id'";
    $check_res = mysqli_query($conn, $check_sql) or die('Error: ' . mysqli_error($conn));

    if (mysqli_num_rows($check_res) > 0) {
        $sql = "UPDATE student_profiles SET 
                full_name='$name', 
                phone='$phone', 
                address='$address', 
                date_of_birth='$dob', 
                target_destination='$destination', 
                target_field='$field' 
                WHERE user_id='$user_id'";
        mysqli_query($conn, $sql) or die('Error: ' . mysqli_error($conn));
    } else {
        $sql = "INSERT INTO student_profiles (user_id, full_name, phone, address, date_of_birth, target_destination, target_field) 
                VALUES ('$user_id', '$name', '$phone', '$address', '$dob', '$destination', '$field')";
        mysqli_query($conn, $sql) or die('Error: ' . mysqli_error($conn));
    }

    if (!empty($password)) {
        $sql_pw = "UPDATE users SET password='$password' WHERE user_id='$user_id'";
        mysqli_query($conn, $sql_pw) or die('Error: ' . mysqli_error($conn));
    }

    $success_msg = "Profile updated successfully!";
}

$fetch_sql = "SELECT u.email, sp.full_name, sp.phone, sp.address, sp.date_of_birth, sp.target_destination, sp.target_field 
              FROM users u 
              LEFT JOIN student_profiles sp ON u.user_id = sp.user_id 
              WHERE u.user_id = '$user_id'";
$result = mysqli_query($conn, $fetch_sql) or die('Error: ' . mysqli_error($conn));
$data = mysqli_fetch_array($result);

$user = [
    'email' => $data['email'],
    'name' => $data['full_name'] ?? $_SESSION['username'],
    'phone' => $data['phone'],
    'address' => $data['address'],
    'dob' => $data['date_of_birth'],
    'destination' => $data['target_destination'],
    'field' => $data['target_field']
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - UniPath Student</title>
    <link rel="stylesheet" href="../assets/css/style.css">


</head>

<body>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <header class="dashboard-title">
                <h1>My Profile</h1>
            </header>

            <?php if ($success_msg): ?>
                <div class="alert-success">
                    ✅ <?php echo $success_msg; ?>
                </div>
            <?php endif; ?>

            <?php if ($error_msg): ?>
                <div class="alert-error">
                    ⚠️ <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <div class="content-section">
                <form action="" method="POST" onsubmit="return CheckForm(this)" novalidate>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>"
                                required class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" readonly
                                class="form-input form-input-readonly">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                required class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date of Birth</label>
                            <input class="form-input" type="date" name="dob"
                                value="<?php echo htmlspecialchars($user['dob'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>"
                            class="form-input">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Target Destination (Preferred Country)</label>
                            <input type="text" name="destination"
                                value="<?php echo htmlspecialchars($user['destination'] ?? ''); ?>"
                                placeholder="e.g. UK, USA, Canada" class="form-input">

                        </div>
                        <div class="form-group">
                            <label class="form-label">Target Field of Study</label>
                            <input type="text" name="field" value="<?php echo htmlspecialchars($user['field'] ?? ''); ?>"
                                placeholder="e.g. Computer Science, Business" class="form-input">
                        </div>
                    </div>

                    <hr class="section-divider">
                    <h3 class="section-subtitle">Change Password <span class="subtitle-note">(Leave blank to keep
                            current)</span></h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" placeholder="Min 6 characters" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" placeholder="Confirm new password"
                                class="form-input">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="submit" class="btn-update">💾 Save Changes</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script src="../assets/js/validation.js"></script>
</body>

</html>