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
    $sql = "DELETE FROM blogs WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: manage_blogs.php?msg=Blog+post+deleted+successfully");
    } else {
        header("Location: manage_blogs.php?msg=Error+deleting+record");
    }
    exit();
}

// Fetch Blogs
$blogs = [];
// Updated to match schema: joined with users to get publisher name, used published_date
$sql = "SELECT b.post_id, b.title, u.username as publisher, b.published_date as date 
        FROM blog_posts b 
        LEFT JOIN users u ON b.publisher_id = u.user_id 
        ORDER BY b.published_date DESC";
// Fallback if table doesn't exist yet? Not my job to create table unless error.
// But I'll wrap in try/check.
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $blogs[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blogs - UniPath Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">


</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <header class="header-actions">
                <h1>Manage Blogs & News (<?php 
                    $count_query = "SELECT COUNT(*) as total FROM blog_posts";
                    $count_res = mysqli_query($conn, $count_query);
                    $count_row = mysqli_fetch_assoc($count_res);
                    echo $count_row['total']; 
                ?>)</h1>
                <a href="add_blog.php" class="btn btn-primary">➕ Create New Post</a>
            </header>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
            <?php endif; ?>

            <div class="content-section">
                <?php if (count($blogs) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Publisher</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($blogs as $blog): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($blog['title']); ?></td>
                                    <td><?php echo htmlspecialchars($blog['publisher']); ?></td>
                                    <td><?php echo htmlspecialchars($blog['date']); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="add_blog.php?id=<?php echo $blog['post_id']; ?>" class="btn btn-sm btn-minimal edit">
                                                ✏️ Edit
                                            </a>
                                            <a href="?delete=<?php echo $blog['post_id']; ?>" class="btn btn-sm btn-minimal delete"
                                               onclick="return confirm('Are you sure?');">
                                                🗑️ Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No blog posts found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>