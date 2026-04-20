<?php
include 'includes/db_connect.php';


$post_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$post = null;

if ($post_id > 0) {
    $sql = "SELECT blog_posts.*, users.username AS publisher_name 
            FROM blog_posts 
            LEFT JOIN users ON blog_posts.publisher_id = users.user_id 
            WHERE post_id = $post_id";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $post = mysqli_fetch_assoc($result);

    }
}
?>
<?php include 'includes/header.php'; ?>

<div class="page-header" style="background: var(--primary-color); color: white; padding: 40px 0; text-align: center;">
    <div class="container">
        <!-- Category Badge -->
        <?php if ($post && !empty($post['category'])): ?>
            <span class="category-badge"
                style="background: var(--secondary-color); color: var(--primary-color); padding: 5px 15px; border-radius: 20px; font-size: 0.9rem; font-weight: bold; display: inline-block; margin-bottom: 15px;">
                <?php echo htmlspecialchars($post['category']); ?>
            </span>
        <?php endif; ?>

        <h1><?php echo $post ? htmlspecialchars($post['title']) : 'Blog Post Not Found'; ?></h1>
        <?php if ($post): ?>
            <p><small>Published on <?php echo date('F j, Y', strtotime($post['published_date'])); ?> by
                    <?php echo htmlspecialchars($post['publisher_name']); ?></small></p>
        <?php endif; ?>
    </div>
</div>

<div class="container" style="padding: 60px 15px; max-width: 800px;">
    <?php if ($post): ?>
        <div class="blog-content">
            <?php if (!empty($post['featured_image'])): ?>
                <div
                    style="margin-bottom: 30px; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <img src="<?php echo htmlspecialchars($post['featured_image']); ?>"
                        alt="<?php echo htmlspecialchars($post['title']); ?>" style="width: 100%; display: block;">
                </div>
            <?php endif; ?>

            <div class="content-body" style="font-size: 1.1rem; line-height: 1.8; color: #444;">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
            </div>

            <div style="margin-top: 50px; text-align: center;">
                <a href="<?php echo base_url('blog.php'); ?>" class="btn btn-outline">⬅️ Back to Blogs</a>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center">
            <h2>Oops!</h2>
            <p>The blog post you are looking for does not exist or has been removed.</p>
            <a href="<?php echo base_url('blog.php'); ?>" class="btn btn-primary" style="margin-top: 20px;">Browse All
                Blogs</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>