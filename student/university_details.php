<?php
include '../includes/db_connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$is_student = isset($_SESSION['user_id']) && $_SESSION['role'] == 'student';


$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';

$uni_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$uni_sql = "SELECT * FROM universities WHERE uni_id = $uni_id";
$uni_res = mysqli_query($conn, $uni_sql);

if ($uni_row = mysqli_fetch_array($uni_res)) {
    $university = $uni_row;
    $university['country'] = $uni_row['country_name'];
} else {
    echo "<div style='text-align:center; padding: 50px;'><h2>University not found.</h2><a href='universities.php'>Back to list</a></div>";
    exit();
}

$courses = [];
$course_sql = "SELECT * FROM courses WHERE uni_id = $uni_id";
$course_res = mysqli_query($conn, $course_sql);

if ($course_res) {
    while ($row = mysqli_fetch_assoc($course_res)) {
        // Map tuition_fee to fee
        $row['fee'] = $row['tuition_fee'];
        $courses[] = $row;
    }
}
$is_saved = false;
$saved_uni_ids = [];
if ($is_student && isset($university['uni_id'])) {
    $user_id = $_SESSION['user_id'];
    $profile_sql = "SELECT profile_id FROM student_profiles WHERE user_id = '$user_id'";
    $profile_res = mysqli_query($conn, $profile_sql);
    if ($profile_res && mysqli_num_rows($profile_res) > 0) {
        $student_id = mysqli_fetch_assoc($profile_res)['profile_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_save') {
            $uni_id = $university['uni_id'];
            $check_sql = "SELECT save_id FROM saved_universities WHERE student_id = '$student_id' AND uni_id = '$uni_id'";
            $check_res = mysqli_query($conn, $check_sql);

            if (mysqli_num_rows($check_res) > 0) {
                $delete_sql = "DELETE FROM saved_universities WHERE student_id = '$student_id' AND uni_id = '$uni_id'";
                mysqli_query($conn, $delete_sql);
                $is_saved = false;
            } else {
                $insert_sql = "INSERT INTO saved_universities (student_id, uni_id) VALUES ('$student_id', '$uni_id')";
                mysqli_query($conn, $insert_sql);
                $is_saved = true;
            }

            header("Location: university_details.php?id=" . $uni_id);
            exit();
        }

        $status_sql = "SELECT save_id FROM saved_universities WHERE student_id = '$student_id' AND uni_id = '{$university['uni_id']}'";
        if (mysqli_num_rows(mysqli_query($conn, $status_sql)) > 0) {
            $is_saved = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($university['name']); ?> - Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css?v=1.8">

    <style>
        .course-card {
            background: white;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            transition: 0.3s;
        }

        .course-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-color: var(--primary-color);
        }

        .course-info h4 {
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .course-meta {
            font-size: 0.9rem;
            color: #666;
            display: flex;
            gap: 15px;
        }

        .course-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>
</head>

<body>

    <?php if ($is_student): ?>
        <div class="dashboard-container">
            <?php include 'sidebar.php'; ?>
            <div class="main-content">
            <?php else: ?>
                <?php include '../includes/header.php'; ?>
                <div class="container" style="padding: 40px 15px;">
                <?php endif; ?>

                <div style="margin-bottom: 20px;">
                    <a href="universities.php" class="btn-outline" style="border:none; padding-left: 0;">
                        ⬅️ Back to Universities
                    </a>
                </div>

                <div
                    style="background: white; padding: 40px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 40px;">
                    <div style="display: flex; align-items: flex-start; gap: 30px; flex-wrap: wrap;">
                        <div
                            style="width: 100px; height: 100px; background: #eee; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #ccc; overflow: hidden;">
                            <?php if (!empty($university['logo_image'])): ?>
                                <img src="<?php echo htmlspecialchars($university['logo_image']); ?>"
                                    alt="<?php echo htmlspecialchars($university['name']); ?>"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                🏛️
                            <?php endif; ?>
                        </div>
                        <div style="flex: 1;">
                            <span
                                style="background: var(--light-bg); color: var(--primary-color); padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold; display: inline-block; margin-bottom: 10px;">
                                📍
                                <?php echo htmlspecialchars($university['country']); ?>
                            </span>
                            <h1 style="color: var(--primary-color); margin-bottom: 10px;">
                                <?php echo htmlspecialchars($university['name']); ?>
                            </h1>
                            <p style="color: #555; font-size: 1.05rem; line-height: 1.6; margin-bottom: 20px;">
                                <?php echo htmlspecialchars($university['description']); ?>
                            </p>
                            <div
                                style="display: flex; gap: 20px; font-size: 0.95rem; color: #666; margin-bottom: 20px;">
                                <span>🏆 Global Rank:
                                    #<?php echo $university['ranking']; ?></span>
                                <span>✅ Certified Partner</span>
                            </div>

                            <?php
                            $uni_apply_link = "appointment.php?uni=" . urlencode($university['name']) . "&service=University Consultation";
                            if (!$is_student) {
                                $uni_apply_link = "../login.php?redirect=" . urlencode("student/" . $uni_apply_link);
                            }
                            ?>
                            <a href="<?php echo $uni_apply_link; ?>" class="btn btn-primary"
                                style="background: var(--secondary-color); color: var(--primary-color);">
                                📅 Book Consultation
                            </a>

                            <?php if ($is_student): ?>
                                <form action="" method="POST" style="display: inline-block; margin-left: 10px;"
                                    onsubmit="return CheckForm(this)">
                                    <input type="hidden" name="action" value="toggle_save">
                                    <button type="submit" class="btn btn-outline"
                                        style="border-color: #ddd; color: red; font-size: 1.2rem; padding: 10px 15px;">
                                        <?php echo $is_saved ? '❤️ Saved' : '🤍 Save'; ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <h2 style="color: var(--primary-color); margin-bottom: 25px;">Available Courses</h2>

                <?php if (count($courses) > 0): ?>
                    <div class="courses-list">
                        <?php foreach ($courses as $course): ?>
                            <div class="course-card">
                                <div class="course-info">
                                    <h4><?php echo htmlspecialchars($course['name']); ?></h4>
                                    <div class="course-meta">
                                        <span>🎓
                                            <?php echo htmlspecialchars($course['level']); ?></span>
                                        <span>⏰
                                            <?php echo htmlspecialchars($course['duration']); ?></span>
                                        <span>🏷️ <?php echo htmlspecialchars($course['fee']); ?></span>
                                    </div>
                                </div>
                                <div>
                                    <?php
                                    $apply_link = "appointment.php?course=" . urlencode($course['name']) . "&uni=" . urlencode($university['name']) . "&service=Application Assistance";
                                    if (!$is_student) {
                                        $apply_link = "../login.php?redirect=" . urlencode("student/" . $apply_link);
                                    }
                                    ?>
                                    <a href="<?php echo $apply_link; ?>" class="btn-primary btn-sm"
                                        style="text-decoration: none;">Apply with Guidance</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No courses listed for this university yet.</p>
                <?php endif; ?>

                <?php if ($is_student): ?>
                </div>
            </div>
        <?php else: ?>
        </div>
        <?php include '../includes/footer.php'; ?>
    <?php endif; ?>

    <script src="../assets/js/validation.js"></script>
</body>

</html>