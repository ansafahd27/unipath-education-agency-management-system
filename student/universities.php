<?php
include '../includes/db_connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$is_student = isset($_SESSION['user_id']) && $_SESSION['role'] == 'student';

// Fetch Unique Countries for Filter
$countries = [];
$c_sql = "SELECT DISTINCT country_name FROM universities ORDER BY country_name ASC";
$c_res = mysqli_query($conn, $c_sql);
if ($c_res) {
    while ($row = mysqli_fetch_assoc($c_res)) {
        $countries[] = $row['country_name'];
    }
}


$where_sql = "WHERE TRUE";

if (!empty($_GET['search'])) {
    $search = $_GET['search'];
    $where_sql .= " AND name LIKE '%$search%'";
}

if (!empty($_GET['country'])) {
    $country_filter = $_GET['country'];
    $where_sql .= " AND country_name = '$country_filter'";
}

$student_id = null;
$saved_uni_ids = [];

if ($is_student) {
    $uid = $_SESSION['user_id'];
    $prof_res = mysqli_query($conn, "SELECT profile_id FROM student_profiles WHERE user_id = '$uid'");
    if ($prof_row = mysqli_fetch_assoc($prof_res)) {
        $student_id = $prof_row['profile_id'];
    }
}


// Fetch Universities & Handle 'Saved' View
$view = $_GET['view'] ?? 'all';
$join_sql = "";

if ($view === 'saved' && $student_id) {
    $join_sql = "INNER JOIN saved_universities ON universities.uni_id = saved_universities.uni_id AND saved_universities.student_id = '$student_id'";
}

$sql = "SELECT universities.* FROM universities $join_sql $where_sql ORDER BY name ASC";
$result = mysqli_query($conn, $sql);
$universities = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $row['country'] = $row['country_name']; // Keep for view compatibility
        $universities[] = $row;
    }
}
?>

<?php if ($is_student): ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Universities - Student Dashboard</title>
        <link rel="stylesheet" href="../assets/css/style.css?v=1.9">
    </head>

    <body>
        <div class="dashboard-container">
            <?php include 'sidebar.php'; ?>
            <div class="main-content">
            <?php else: ?>
                <?php include '../includes/header.php'; ?>
                <section class="page-hero">
                    <div class="container">
                        <h1>Partner Universities</h1>
                        <p>Explore top universities around the globe.</p>
                    </div>
                </section>
            <?php endif; ?>

            <div class="<?php echo $is_student ? '' : 'container'; ?>">

                <?php if ($is_student): ?>

                    <header class="uni-page-header">
                        <h1 class="header-title">Universities</h1>

                        <!-- Tabs -->
                        <div class="filter-tabs">
                            <a href="universities.php?view=all"
                                class="filter-tab-link <?php echo $view !== 'saved' ? 'active' : ''; ?>">
                                All Universities
                            </a>
                            <a href="universities.php?view=saved"
                                class="filter-tab-link <?php echo $view === 'saved' ? 'active' : ''; ?>">
                                My Saved Universities
                            </a>
                        </div>
                    </header>
                <?php endif; ?>

                <!-- Filter Bar -->
                <div class="filter-bar">
                    <form action="" method="GET" class="filter-form">
                        <input type="text" name="search" placeholder="Search by name..."
                            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                            class="filter-input">
                        <select name="country" class="filter-select">
                            <option value="">All Countries</option>
                            <?php foreach ($countries as $country): ?>
                                <option value="<?php echo htmlspecialchars($country); ?>" <?php echo (isset($_GET['country']) && $_GET['country'] == $country) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($country); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <?php if (isset($_GET['search']) || isset($_GET['country'])): ?>
                            <a href="universities.php" class="btn btn-outline btn-filter-clear">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- University Grid -->
                <?php if (count($universities) > 0): ?>
                    <div class="uni-grid">
                        <?php foreach ($universities as $uni): ?>
                            <div class="uni-card">
                                <div class="uni-card-image-placeholder">
                                    <?php if (!empty($uni['logo_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($uni['logo_image']); ?>"
                                            alt="<?php echo htmlspecialchars($uni['name']); ?>" class="uni-card-logo">
                                    <?php else: ?>
                                        🏛️
                                    <?php endif; ?>
                                </div>
                                <div class="uni-card-body">
                                    <div class="uni-card-meta">
                                        <span class="uni-card-country-tag">
                                            📍 <?php echo htmlspecialchars($uni['country']); ?>
                                        </span>
                                    </div>
                                    <h3 class="uni-card-title">
                                        <?php echo htmlspecialchars($uni['name']); ?>
                                    </h3>
                                    <p class="uni-card-description">
                                        <?php echo htmlspecialchars(substr($uni['description'], 0, 100)) . '...'; ?>
                                    </p>
                                    <div class="uni-card-actions">
                                        <a href="university_details.php?id=<?php echo $uni['uni_id']; ?>"
                                            class="btn btn-primary">View</a>
                                        <a href="appointment.php?uni=<?php echo urlencode($uni['name']); ?>&service=University Consultation"
                                            class="btn btn-outline">
                                            Apply
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php else: ?>
                    <p class="no-results">No universities found matching your criteria.</p>
                <?php endif; ?>

            </div>

            <?php if ($is_student): ?>
            </div>
        </div>
    </body>

    </html>
<?php else: ?>
    <?php include '../includes/footer.php'; ?>
<?php endif; ?>