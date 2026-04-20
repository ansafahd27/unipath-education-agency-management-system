<?php
include '../includes/db_connect.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle View Filtering
$view = isset($_GET['view']) ? $_GET['view'] : 'all';
$where_clause = "";
if ($view === 'admin') {
    $where_clause = "WHERE role = 'admin'";
} elseif ($view === 'student') {
    $where_clause = "WHERE role = 'student'";
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Prevent deleting self?
    if ($id == $_SESSION['user_id']) {
        $msg = "You cannot delete your own account.";
        header("Location: users.php?view=$view&msg=" . urlencode($msg));
        exit();
    }

    $sql = "DELETE FROM users WHERE user_id=$id";
    if (mysqli_query($conn, $sql)) {
        $msg = "User deleted successfully.";
    } else {
        $msg = "Error deleting user: " . mysqli_error($conn);
    }
    header("Location: users.php?view=$view&msg=" . urlencode($msg));
    exit();
}

// Real User Fetch
$displayed_users = [];
$sql = "SELECT user_id, username, email, role FROM users $where_clause";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $row['id'] = $row['user_id'];
        $displayed_users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - UniPath Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .filter-tabs {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .filter-tabs a {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            background: #f0f0f0;
            color: #555;
            font-weight: 500;
            transition: 0.3s;
        }

        .filter-tabs a.active,
        .filter-tabs a:hover {
            background: var(--primary-color);
            color: white;
        }

        .role-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: bold;
        }

        .role-admin {
            background: #e2e6ea;
            color: #283e59;
        }

        .role-student {
            background: #d1ecf1;
            color: #0c5460;
        }

        .alert-message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 30px;
            height: 30px;
            background: #eee;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
            font-weight: bold;
        }

        .empty-state {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>

        <div class="main-content">
            <header class="header-actions">
                <h1>Manage Users (<?php
                $count_query = "SELECT COUNT(*) as total FROM users";
                $count_res = mysqli_query($conn, $count_query);
                $count_row = mysqli_fetch_assoc($count_res);
                echo $count_row['total'];
                ?>)</h1>
                <a href="add_admin.php" class="btn btn-primary">➕ Add New User</a>
            </header>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert-message alert-success">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
            <?php endif; ?>

            <!-- Filter Tabs -->
            <div class="filter-tabs">
                <a href="?view=all" class="<?php echo $view == 'all' ? 'active' : ''; ?>">All Users</a>
                <a href="?view=admin" class="<?php echo $view == 'admin' ? 'active' : ''; ?>">Admins</a>
                <a href="?view=student" class="<?php echo $view == 'student' ? 'active' : ''; ?>">Students</a>
            </div>

            <!-- Users Table -->
            <div class="content-section">
                <?php if (count($displayed_users) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($displayed_users as $user): ?>
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            <div class="user-avatar">
                                                <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                                            </div>
                                            <?php echo htmlspecialchars($user['username']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <span class="role-badge role-<?php echo $user['role']; ?>">
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </td>
                                    <!-- Date Removed as not in DB -->
                                    <td>
                                        <!-- Edit not fully implemented for all roles yet, simple placeholder or link to profile? -->
                                        <div class="action-buttons">
                                            <a href="?view=<?php echo $view; ?>&delete=<?php echo $user['user_id']; ?>"
                                                class="btn btn-sm btn-minimal delete"
                                                onclick="return confirm('Are you sure you want to delete this user? This cannot be undone.');">
                                                🗑️ Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="empty-state">No users found for this category.</p>
                <?php endif; ?>
            </div>

        </div>
    </div>
</body>

</html>