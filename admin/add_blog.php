<?php
include '../includes/db_connect.php';

// 1. Security Check
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// 2. Initialize Variables
$mode = 'add';
$blog_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$title = '';
$content = '';
$category = '';
$current_image = '';
$error = '';
$success = '';

// 3. If Edit Mode: Fetch Existing Data
if ($blog_id > 0) {
    $mode = 'edit';
    $result = mysqli_query($conn, "SELECT * FROM blog_posts WHERE post_id=$blog_id");
    if ($row = mysqli_fetch_assoc($result)) {
        $title = $row['title'];
        $content = $row['content'];
        $category = $row['category'];
        $current_image = $row['featured_image'];
    } else {
        $error = "Blog post not found.";
    }
}

// 4. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $publisher_id = $_SESSION['user_id'];

    // Save to Database (Insert or Update)
    if (!$error) {
        if ($mode == 'edit') {
            $sql = "UPDATE blog_posts SET title='$title', content='$content', category='$category' WHERE post_id=$blog_id";
        } else {
            $sql = "INSERT INTO blog_posts (title, publisher_id, content, category) VALUES ('$title', '$publisher_id', '$content', '$category')";
        }

        if (mysqli_query($conn, $sql)) {
            $success = "Blog post saved successfully!";
            // Update variables to show changes immediately
            if ($mode == 'add') {
                $title = '';
                $content = '';
                $category = '';
            }
        } else {
            $error = "Database Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= ucfirst($mode) ?> Blog Post</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>

        <div class="main-content">
            <div class="header-actions">
                <h1><?= ucfirst($mode) ?> Blog Post</h1>
                <a href="manage_blogs.php" class="btn btn-outline">Back to List</a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div> <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div> <?php endif; ?>

            <div class="content-section" style="max-width: 800px;">
                <form method="POST" onsubmit="return CheckForm(this)" novalidate>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($title) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control" required>
                            <option value="">Select Category</option>
                            <?php
                            $cats = ["Visa Advice", "Country Guides", "Test Preparation", "Student Life", "News"];
                            foreach ($cats as $c) {
                                $sel = ($category == $c) ? 'selected' : '';
                                echo "<option value='$c' $sel>$c</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="content" rows="8" class="form-control" required><?= htmlspecialchars($content) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary"><?= $mode == 'edit' ? 'Update' : 'Save' ?> Post</button>
                </form>
            </div>
        </div>
    </div>
    <script src="../assets/js/validation.js"></script>
</body>

</html>