<?php
include '../includes/db_connect.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$message = '';
$error = '';
$mode = 'add';
$course_id = 0;
$course_data = [
    'name' => '',
    'uni_id' => '',
    'level' => '',
    'field_of_study' => '',
    'duration' => '',
    'tuition_fee' => ''
];

// Check for Edit Mode
if (isset($_GET['id'])) {
    $course_id = (int) ($_GET['id']);
    $mode = 'edit';
    $sql = "SELECT * FROM courses WHERE course_id=$course_id";
    $res = mysqli_query($conn, $sql);
    if (mysqli_num_rows($res) > 0) {
        $course_data = mysqli_fetch_assoc($res);
    } else {
        $error = "Course not found.";
    }
}

// Fetch Universities for Dropdown
$universities = [];
$uni_query = "SELECT uni_id, name FROM universities ORDER BY name ASC";
$uni_result = mysqli_query($conn, $uni_query);
if (mysqli_num_rows($uni_result) > 0) {
    while ($row = mysqli_fetch_assoc($uni_result)) {
        $universities[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = $_POST['course_name'];
    $university_id = intval($_POST['university_id']);
    $level = $_POST['level'];
    $field_of_study = $_POST['field_of_study'];
    $duration = $_POST['duration'];
    $fee = $_POST['fee'];
    $created_by = $_SESSION['user_id'];

    if ($mode == 'edit') {
        // Update
        $sql = "UPDATE courses SET 
                name = '$course_name', 
                uni_id = '$university_id', 
                level = '$level',
                field_of_study = '$field_of_study',
                duration = '$duration', 
                tuition_fee = '$fee' 
                WHERE course_id = $course_id";

        if (mysqli_query($conn, $sql)) {
            $message = "Course updated successfully!";
            // Refresh data
            $course_data['name'] = $course_name;
            $course_data['uni_id'] = $university_id;
            $course_data['level'] = $level;
            $course_data['field_of_study'] = $field_of_study;
            $course_data['duration'] = $duration;
            $course_data['tuition_fee'] = $fee;
        } else {
            $error = "Error updating course: " . mysqli_error($conn);
        }
    } else {
        // Insert
        $sql = "INSERT INTO courses (name, uni_id, level, field_of_study, duration, tuition_fee, created_by) 
                VALUES ('$course_name', '$university_id', '$level', '$field_of_study', '$duration', '$fee', '$created_by')";

        if (mysqli_query($conn, $sql)) {
            $message = "Course created successfully!";

        } else {
            $error = "Error creating course: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($mode); ?> Course - UniPath Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <div class="main-content">
            <div class="header-actions">
                <h1><?php echo ($mode == 'edit') ? 'Edit Course' : 'Create New Course'; ?></h1>
                <a href="manage_courses.php" class="btn btn-outline">Back to List</a>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="content-section" style="max-width: 800px;">
                <form method="POST" action="" onsubmit="return CheckForm(this)" novalidate>
                    <div class="form-group">
                        <label>Course Name *</label>
                        <input type="text" name="course_name"
                            value="<?php echo htmlspecialchars($course_data['name']); ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label>University *</label>
                        <select name="university_id" required class="form-control">
                            <option value="">Select University</option>
                            <?php foreach ($universities as $uni): ?>
                                <option value="<?php echo $uni['uni_id']; ?>" <?php echo ($course_data['uni_id'] == $uni['uni_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($uni['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Level *</label>
                            <select name="level" required class="form-control">
                                <option value="">Select Level</option>
                                <option value="Undergraduate" <?php echo (isset($course_data['level']) && $course_data['level'] == 'Undergraduate') ? 'selected' : ''; ?>>Undergraduate</option>
                                <option value="Postgraduate" <?php echo (isset($course_data['level']) && $course_data['level'] == 'Postgraduate') ? 'selected' : ''; ?>>Postgraduate</option>
                                <option value="Diploma" <?php echo (isset($course_data['level']) && $course_data['level'] == 'Diploma') ? 'selected' : ''; ?>>Diploma</option>
                                <option value="PhD" <?php echo (isset($course_data['level']) && $course_data['level'] == 'PhD') ? 'selected' : ''; ?>>PhD</option>
                                <option value="Certificate" <?php echo (isset($course_data['level']) && $course_data['level'] == 'Certificate') ? 'selected' : ''; ?>>Certificate</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Field of Study *</label>
                            <input type="text" name="field_of_study"
                                value="<?php echo htmlspecialchars($course_data['field_of_study']); ?>" required
                                placeholder="e.g. Computer Science, Business" class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Duration (e.g., 3 Years)</label>
                            <input type="text" name="duration"
                                value="<?php echo htmlspecialchars($course_data['duration']); ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Tuition Fee</label>
                            <input type="text" name="fee"
                                value="<?php echo htmlspecialchars($course_data['tuition_fee']); ?>"
                                placeholder="$20,000 / year" class="form-control">
                        </div>
                    </div>

                    <button type="submit"
                        class="btn btn-primary"><?php echo ($mode == 'edit') ? 'Update Course' : 'Create Course'; ?></button>
                </form>
            </div>
        </div>
    </div>
    <script src="../assets/js/validation.js"></script>
</body>

</html>