<?php include 'includes/db_connect.php'; ?>
<?php 
// Fetch Success Stories from DB
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
<?php include 'includes/header.php'; ?>

<div class="page-header" style="background: var(--primary-color); color: white; padding: 40px 0; text-align: center;">
    <div class="container">
        <h1>Success Stories</h1>
        <p>Inspiration from our students who made it.</p>
    </div>
</div>

<div class="container" style="padding: 60px 15px;">
    <?php if(count($stories) > 0): ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
        <?php foreach($stories as $story): ?>
        <div class="story-card" style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 3px 10px rgba(0,0,0,0.1); display: flex;">
            <div style="width: 100px; background: #eee; flex-shrink: 0; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                🎓
            </div>
            <div class="card-body" style="padding: 20px;">
                <h3 style="margin-bottom: 5px; font-size: 1.2rem;"><?php echo htmlspecialchars($story['title']); ?></h3>
                <p style="font-size: 0.9rem; color: #666; margin-bottom: 15px;">"<?php echo htmlspecialchars($story['content']); ?>"</p>
                <div style="font-weight: bold; color: var(--primary-color); font-size: 0.8rem;">- <?php echo htmlspecialchars($story['student_name']); ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <p style="text-align: center;">No success stories to share yet.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>