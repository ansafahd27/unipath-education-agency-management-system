<?php include 'includes/db_connect.php'; ?>
<?php
$categories = [];
$cat_sql = "SELECT DISTINCT category FROM blog_posts WHERE category IS NOT NULL AND category != ''";
$cat_result = mysqli_query($conn, $cat_sql);
if ($cat_result) {
    while ($row = mysqli_fetch_assoc($cat_result)) {
        $categories[] = $row['category'];
    }
}

$posts = [];
$where_clause = "";
$selected_category = "";

if (isset($_GET['category']) && !empty($_GET['category'])) {
    $selected_category = $_GET['category'];
    $where_clause = "WHERE category = '$selected_category'";
}

$sql = "SELECT * FROM blog_posts $where_clause ORDER BY published_date DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }
}
?>
<?php include 'includes/header.php'; ?>

<section class="page-hero">
    <div class="container">
        <h1>Blog & News</h1>
        <p>Stay updated with the latest in international education.</p>
    </div>
</section>

<div class="container" style="padding: 60px 15px;">

    <!-- Filter Section -->
    <div class="filter-container"
        style="margin-bottom: 30px; display: flex; justify-content: flex-end; align-items: center; gap: 15px;">
        <label for="category-filter" style="font-weight: bold; color: var(--primary-color);">Filter by Category:</label>
        <form action="" method="GET" style="margin: 0;" onsubmit="return CheckForm(this)">
            <select name="category" id="category-filter" onchange="this.form.submit()"
                style="padding: 8px 15px; border-radius: 5px; border: 1px solid #ddd; outline: none;">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $selected_category == $cat ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if (count($posts) > 0): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
            <?php foreach ($posts as $post): ?>
                <div class="blog-card"
                    style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 3px 10px rgba(0,0,0,0.1); display: flex; flex-direction: column; height: auto; min-height: 250px;">
                    <div class="card-body" style="padding: 20px; flex-grow: 1; display: flex; flex-direction: column;">
                        <div
                            style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                            <span style="font-size: 0.8rem; color: #999; display: block;">
                                <?php echo date('F j, Y', strtotime($post['published_date'])); ?>
                            </span>
                            <?php if (!empty($post['category'])): ?>
                                <span
                                    style="background: var(--secondary-color); color: var(--primary-color); padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: bold;">
                                    <?php echo htmlspecialchars($post['category']); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <h3 style="margin: 0 0 10px 0; font-size: 1.3rem;"><a
                                href="blog_view.php?id=<?php echo $post['post_id']; ?>"
                                style="color: var(--primary-color); text-decoration: none;"><?php echo htmlspecialchars($post['title']); ?></a>
                        </h3>
                        <p style="font-size: 0.9rem; color: #666; margin-bottom: 15px; flex-grow: 1;">
                            <?php echo htmlspecialchars(substr($post['content'], 0, 150)) . '...'; ?>
                        </p>
                        <a href="blog_view.php?id=<?php echo $post['post_id']; ?>" class="btn-outline"
                            style="border: none; padding: 0; color: var(--primary-color); font-weight: bold; align-self: flex-start;">Read
                            More ➝</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 50px 0;">
            <h3 style="color: #666;">No blog posts found.</h3>
            <?php if ($selected_category): ?>
                <p>Try selecting a different category or <a href="blog.php">view all</a>.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script src="assets/js/validation.js"></script>
<?php include 'includes/footer.php'; ?>