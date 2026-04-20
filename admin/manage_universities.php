<?php
include '../includes/db_connect.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM universities WHERE uni_id=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: manage_universities.php?msg=University+deleted+successfully");
    } else {
        header("Location: manage_universities.php?msg=Error+deleting+record");
    }
    exit();
}

$countries_query = "SELECT DISTINCT country_name FROM universities ORDER BY country_name ASC";
$countries_result = mysqli_query($conn, $countries_query);

$where_clause = "";
if (isset($_GET['country']) && !empty($_GET['country'])) {
    $selected_country = $_GET['country'];
    $where_clause = "WHERE country_name = '$selected_country'";
}

$sql = "SELECT * FROM universities $where_clause";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Universities - UniPath Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <script>
        function filterUniversities() {
            var country = document.getElementById("countryFilter").value;
            window.location.href = "manage_universities.php?country=" + country;
        }
    </script>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <header class="header-actions">
                <h1>Manage Universities (<?php 
                    $count_query = "SELECT COUNT(*) as total FROM universities";
                    $count_res = mysqli_query($conn, $count_query);
                    $count_row = mysqli_fetch_assoc($count_res);
                    echo $count_row['total']; 
                ?>)</h1>
                <a href="add_university.php" class="btn btn-primary">➕ Add New University</a>
            </header>

            <?php if (isset($_GET['msg'])): ?>
                <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
            <?php endif; ?>

            <div class="content-section">
                <!-- Country Filter -->
                <div style="margin-bottom: 20px;">
                    <label for="countryFilter" style="font-weight: bold;">Filter by Country:</label>
                    <select id="countryFilter" onchange="filterUniversities()"
                        style="padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                        <option value="">All Countries</option>
                        <?php
                        // Country Dropdown Loop
                        if (mysqli_num_rows($countries_result) > 0) {
                            while ($row = mysqli_fetch_assoc($countries_result)) {
                                $selected = ($selected_country == $row['country_name']) ? 'selected' : '';
                                echo '<option value="' . $row['country_name'] . '" ' . $selected . '>' . $row['country_name'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Country</th>
                            <th>Ranking</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>";
                                if (!empty($row['logo_image'])) {
                                    echo "<img src='" . htmlspecialchars($row['logo_image']) . "' alt='Logo' style='width: 50px; height: auto; border-radius: 4px;'>";
                                } else {
                                    echo "<span style='color: #ccc;'>No Logo</span>";
                                }
                                echo "</td>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['country_name'] . "</td>";
                                echo "<td>#" . $row['ranking'] . "</td>";
                                echo "<td>
                                    <div class='action-buttons'>
                                        <a href='add_university.php?id=" . $row['uni_id'] . "' class='btn btn-sm btn-minimal edit'>
                                            ✏️ Edit
                                        </a>
                                        <a href='?delete=" . $row['uni_id'] . "' class='btn btn-sm btn-minimal delete' onclick='return confirm(\"Are you sure?\");'>
                                            🗑️ Delete
                                        </a>
                                    </div>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No universities found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>