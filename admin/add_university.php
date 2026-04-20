<?php
include '../includes/db_connect.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';

$mode = 'add';
$uni_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$uni_data = [
    'name' => '',
    'country_name' => '',
    'ranking' => '',
    'description' => ''
];

if ($uni_id > 0) {
    $mode = 'edit';
    $sql = "SELECT * FROM universities WHERE uni_id = $uni_id";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $uni_data = mysqli_fetch_assoc($result);
    } else {
        $error = "University not found.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $ranking = $_POST['ranking'];
    $description = $_POST['description'];
    $logo_path = $_POST['logo'];

    if (!$error) {
        if ($mode == 'edit') {

            $sql = "UPDATE universities SET 
                    name = '$name', 
                    country_name = '$location', 
                    ranking = '$ranking', 
                    description = '$description',
                    logo_image = '$logo_path'
                    WHERE uni_id = $uni_id";

            if (mysqli_query($conn, $sql)) {
                $success = "University updated successfully!";

                $uni_data['name'] = $name;
                $uni_data['country_name'] = $location;
                $uni_data['ranking'] = $ranking;
                $uni_data['description'] = $description;
                $uni_data['logo_image'] = $logo_path;
            } else {
                $error = "Error updating record: " . mysqli_error($conn);
            }

        } else {

            $sql = "INSERT INTO universities (name, country_name, ranking, description, logo_image) 
                    VALUES ('$name', '$location', '$ranking', '$description', '$logo_path')";

            if (mysqli_query($conn, $sql)) {
                $success = "University added successfully!";
            } else {
                $error = "Error adding record: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo ucfirst($mode); ?> University - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <div class="header-actions">
                <h1><?php echo ucfirst($mode); ?> University</h1>
                <a href="manage_universities.php" class="btn btn-outline">Back to List</a>
            </div>

            <?php if ($error): ?>
                <div class="alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <div class="content-section" style="max-width: 800px;">
                <form action="" method="POST" onsubmit="return CheckForm(this)" novalidate>
                    <div class="form-group">
                        <label>University Name *</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($uni_data['name'] ?? ''); ?>"
                            required class="form-control">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Location (Country) *</label>
                            <select name="location" required class="form-control">
                                <option value="">Select Country</option>
                                <?php
                                $countries = [
                                    "United Kingdom",
                                    "United States",
                                    "Canada",
                                    "Australia",
                                    "New Zealand",
                                    "Germany",
                                    "France",
                                    "Malaysia",
                                    "Singapore",
                                    "United Arab Emirates",
                                    "Ireland",
                                    "Netherlands",
                                    "Sweden",
                                    "Japan",
                                    "China",
                                    "Other"
                                ];
                                foreach ($countries as $country) {
                                    $selected = ($uni_data['country_name'] == $country) ? 'selected' : '';
                                    echo "<option value=\"$country\" $selected>$country</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Global Ranking</label>
                            <input type="number" name="ranking" class="form-control"
                                value="<?php echo htmlspecialchars($uni_data['ranking'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="4"
                            class="form-control"><?php echo htmlspecialchars($uni_data['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>University Logo URL (External Link)</label>
                        <?php if ($mode == 'edit' && !empty($uni_data['logo_image'])): ?>
                            <div style="margin-bottom: 10px;">
                                <img src="<?php echo htmlspecialchars($uni_data['logo_image']); ?>" alt="Current Logo"
                                    style="max-width: 100px; height: auto; border-radius: 5px;">
                            </div>
                        <?php endif; ?>
                        <input type="url" name="logo" class="form-control"
                            value="<?php echo htmlspecialchars(isset($uni_data['logo_image']) ? $uni_data['logo_image'] : ''); ?>"
                            placeholder="https://example.com/logo.png">
                        <small style="color: #666;">Paste the direct link to the university logo image.</small>
                    </div>

                    <button type="submit" class="btn btn-primary"><?php echo $mode == 'edit' ? 'Update' : 'Save'; ?>
                        University</button>
                </form>
            </div>
        </div>
    </div>
    <script src="../assets/js/validation.js"></script>
</body>

</html>