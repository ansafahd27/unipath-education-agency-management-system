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
    $sql = "DELETE FROM inquiries WHERE inquiry_id=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: manage_inquiries.php?msg=" . urlencode("Inquiry deleted successfully."));
    } else {
        header("Location: manage_inquiries.php?err=" . urlencode("Error deleting inquiry."));
    }
    exit();
}

// Handle Status Update
if (isset($_POST['update_status'])) {
    $inquiry_id = intval($_POST['inquiry_id']);
    $status = $_POST['status'];
    $admin_id = $_SESSION['user_id'];

    $sql = "UPDATE inquiries SET status='$status', handled_by='$admin_id' WHERE inquiry_id=$inquiry_id";

    if (mysqli_query($conn, $sql)) {
        header("Location: manage_inquiries.php?msg=" . urlencode("Status updated successfully."));
    } else {
        header("Location: manage_inquiries.php?err=" . urlencode("Error updating status."));
    }
    exit();
}

// Fetch Inquiries
$sql = "SELECT inquiries.*, users.username as admin_name 
        FROM inquiries 
        LEFT JOIN users ON inquiries.handled_by = users.user_id 
        ORDER BY inquiry_id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inquiries - UniPath Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=1.10">
    <style>
        .message-preview {
            max-width: 200px;
            max-height: 100px;
            overflow-y: auto;
        }

        .status-col {
            display: flex;
            flex-direction: column;
            gap: 5px;
            align-items: flex-start;
        }

        .inline-form {
            display: inline;
        }

        .status-select {
            width: 100%;
            padding: 3px;
            font-size: 0.85rem;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-top: 5px;
        }

        .center-text {
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <header class="dashboard-header">
                <h1>📩 Manage Inquiries</h1>
                <div class="header-actions">
                    <!-- No extra actions needed -->
                </div>
            </header>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['err'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['err']); ?></div>
            <?php endif; ?>

            <div class="content-section">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Handled By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                    <td>
                                        <div class="message-preview">
                                            <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="status-col">
                                            <form action="" method="POST" class="inline-form"
                                                onsubmit="return CheckForm(this)">
                                                <input type="hidden" name="inquiry_id"
                                                    value="<?php echo $row['inquiry_id']; ?>">
                                                <select name="status" onchange="this.form.submit()" class="status-select">
                                                    <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="Responded" <?php echo $row['status'] == 'Responded' ? 'selected' : ''; ?>>Responded</option>
                                                </select>
                                                <input type="hidden" name="update_status" value="1">
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo $row['admin_name'] ? htmlspecialchars($row['admin_name']) : '-'; ?>
                                    </td>
                                    <td>
                                        <a href="manage_inquiries.php?delete=<?php echo $row['inquiry_id']; ?>"
                                            onclick="return confirm('Are you sure you want to delete this inquiry?');"
                                            class="btn btn-sm btn-danger">🗑️</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if (mysqli_num_rows($result) == 0): ?>
                                <tr>
                                    <td colspan="7" class="center-text">No inquiries found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <script src="../assets/js/validation.js"></script>
</body>

</html>