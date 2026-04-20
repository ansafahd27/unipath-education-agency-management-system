<?php
include '../includes/db_connect.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int) ($_GET['delete']);
    $sql = "DELETE FROM courses WHERE course_id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: manage_courses.php?msg=Course+deleted+successfully");
    } else {

        header("Location: manage_courses.php?msg=Error+deleting+record");
    }
    exit();
}

// Fetch distinct universities for dropdown
$uni_query = "SELECT uni_id, name FROM universities ORDER BY name ASC";
$uni_result = mysqli_query($conn, $uni_query);

// Filter Logic
$where_clause = "";
$selected_uni = "";
if (isset($_GET['university']) && !empty($_GET['university'])) {
    $selected_uni = intval($_GET['university']);
    $where_clause = "WHERE courses.uni_id = $selected_uni";
}

// Fetch Courses with University Name
$sql = "SELECT courses.*, universities.name as uni_name 
        FROM courses 
        LEFT JOIN universities ON courses.uni_id = universities.uni_id 
        $where_clause 
        ORDER BY courses.name ASC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Courses - UniPath Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <script>
        function filterCourses() {
            var uniId = document.getElementById("universityFilter").value;
            window.location.href = "manage_courses.php?university=" + uniId;
        }
    </script>
</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <header class="header-actions">
                <h1>Manage Courses (<?php
                $count_query = "SELECT COUNT(*) as total FROM courses";
                $count_res = mysqli_query($conn, $count_query);
                $count_row = mysqli_fetch_assoc($count_res);
                echo $count_row['total'];
                ?>)</h1>
                <a href="add_course.php" class="btn btn-primary">➕ Add New Course</a>
            </header>

            <?php if (isset($_GET['msg'])): ?>
                <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
            <?php endif; ?>

            <!-- Filter Section -->
            <div class="content-section" style="margin-bottom: 20px;">
                <label for="universityFilter" style="font-weight: bold;">Filter by University:</label>
                <select id="universityFilter" onchange="filterCourses()"
                    style="padding: 8px; border-radius: 5px; border: 1px solid #ccc; min-width: 200px;">
                    <option value="">All Universities</option>
                    <?php
                    if (mysqli_num_rows($uni_result) > 0) {
                        mysqli_data_seek($uni_result, 0);
                        while ($u = mysqli_fetch_assoc($uni_result)) {
                            $sel = ($selected_uni == $u['uni_id']) ? 'selected' : '';
                            echo "<option value='" . $u['uni_id'] . "' $sel>" . htmlspecialchars($u['name']) . "</option>";
                        }
                    }
                    ?>
                </select>
                <?php if ($selected_uni): ?>
                    <a href="manage_courses.php" class="btn btn-outline btn-sm" style="margin-left: 10px;">Clear</a>
                <?php endif; ?>
            </div>

            <div class="content-section">
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <div style="overflow-x: auto;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Course Name</th>
                                    <th>University</th>
                                    <th>Level</th>
                                    <th>Field</th>
                                    <th>Duration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['uni_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['level']); ?></td>
                                        <td><?php echo htmlspecialchars($row['field_of_study']); ?></td>
                                        <td><?php echo htmlspecialchars($row['duration']); ?></td>
                                        <td>
                                            <div class='action-buttons'>
                                                <a href='add_course.php?id=<?php echo $row['course_id']; ?>'
                                                    class='btn btn-sm btn-minimal edit'>
                                                    ✏️ Edit
                                                </a>
                                                <a href='?delete=<?php echo $row['course_id']; ?>'
                                                    class='btn btn-sm btn-minimal delete'
                                                    onclick='return confirm("Are you sure?");'>
                                                    🗑️ Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>No courses found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="../assets/js/validation.js"></script>
</body>

</html>