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
    // Schema uses story_id
    $sql = "DELETE FROM success_stories WHERE story_id=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: manage_success_stories.php?msg=Story+deleted+successfully");
    } else {
        header("Location: manage_success_stories.php?msg=Error+deleting+record");
    }
    exit();
}

// Fetch Stories with University Name
$stories = [];
$sql = "SELECT s.*, u.name as uni_name 
        FROM success_stories s 
        LEFT JOIN universities u ON s.university_id = u.uni_id 
        ORDER BY s.story_id DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $stories[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Success Stories - UniPath Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">


</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <header class="header-actions">
                <h1>Manage Success Stories</h1>
                <a href="add_success_story.php" class="btn btn-primary">➕ Add Success Story</a>
            </header>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
            <?php endif; ?>

            <div class="content-section">
                <?php if (count($stories) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>University</th>
                                <th>Title</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stories as $story): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($story['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($story['uni_name']); ?></td> <!-- Joined Name -->
                                    <td><?php echo htmlspecialchars($story['title']); ?></td> <!-- Was course -->
                                    <td>
                                            <div class="action-buttons">
                                                <a href="add_success_story.php?id=<?php echo $story['story_id']; ?>" class="btn btn-sm btn-minimal edit">
                                                    ✏️ Edit
                                                </a>
                                                <a href="?delete=<?php echo $story['story_id']; ?>" class="btn btn-sm btn-minimal delete" onclick="return confirm('Are you sure?');">
                                                    🗑️ Delete
                                                </a>
                                            </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No success stories found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>