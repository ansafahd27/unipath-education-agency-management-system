<?php
include '../includes/db_connect.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';

//drop down 
$universities = [];
$uni_query = "SELECT uni_id, name FROM universities ORDER BY name ASC";
$uni_result = mysqli_query($conn, $uni_query);
if (mysqli_num_rows($uni_result) > 0) {
    while ($row = mysqli_fetch_assoc($uni_result)) {
        $universities[] = $row;
    }
}


$mode = 'add';
$story_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$story_data = [
    'student_name' => '',
    'university_id' => '',
    'title' => '',
    'content' => '',
    'student_image' => ''
];

if ($story_id > 0) {
    $mode = 'edit';
    $sql = "SELECT * FROM success_stories WHERE story_id=$story_id";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $story_data = mysqli_fetch_assoc($result);
    } else {
        $error = "Success story not found.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $_POST['student_name'];
    $university_id = (int) $_POST['university_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (!$error) {
        if ($mode == 'edit') {
            $sql = "UPDATE success_stories SET 
                    student_name='$student_name', 
                    university_id='$university_id', 
                    title='$title', 
                    content='$content'
                    WHERE story_id=$story_id";

            if (mysqli_query($conn, $sql)) {
                $success = "Success Story updated successfully!";
                $story_data['student_name'] = $student_name;
                $story_data['university_id'] = $university_id;
                $story_data['title'] = $title;
                $story_data['content'] = $content;
            } else {
                $error = "Error updating record: " . mysqli_error($conn);
            }
        } else {
            $sql = "INSERT INTO success_stories (student_name, university_id, title, content) 
                    VALUES ('$student_name', '$university_id', '$title', '$content')";

            if (mysqli_query($conn, $sql)) {
                $success = "Success Story added successfully!";
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
    <title><?= ucfirst($mode) ?> Success Story - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <div class="header-actions">
                <h1><?= ucfirst($mode) ?> Success Story</h1>
                <a href="manage_success_stories.php" class="btn btn-outline">Back to List</a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <div class="content-section" style="max-width: 800px;">
                <form method="POST" action="" onsubmit="return CheckForm(this)" novalidate>
                    <div class="form-group">
                        <label>Student Name *</label>
                        <input type="text" name="student_name"
                            value="<?= htmlspecialchars($story_data['student_name']) ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label>University *</label>
                        <select name="university_id" required class="form-control">
                            <option value="">Select University</option>
                            <?php foreach ($universities as $uni): ?>
                                <option value="<?= $uni['uni_id'] ?>" <?= (isset($story_data['university_id']) && $story_data['university_id'] == $uni['uni_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($uni['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Story Title *</label>
                        <input type="text" name="title" value="<?= htmlspecialchars($story_data['title']) ?>" required
                            class="form-control" placeholder="e.g. My Journey to Oxford">
                    </div>

                    <div class="form-group">
                        <label>Testimonial/Content *</label>
                        <textarea name="content" rows="4" required
                            class="form-control"><?= htmlspecialchars($story_data['content']) ?></textarea>
                    </div>

                    <button type="submit"
                        class="btn btn-primary"><?= ($mode == 'edit') ? 'Update Story' : 'Add Story' ?></button>
                </form>
            </div>
        </div>
    </div>
    <script src="../assets/js/validation.js"></script>
</body>

</html>