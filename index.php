<?php
include 'includes/db_connect.php';

//  Featured Universities (Limit 3)
$featured_unis = [];
$sql_uni = "SELECT * FROM universities ORDER BY ranking ASC LIMIT 3";
$res_uni = mysqli_query($conn, $sql_uni);
if ($res_uni) {
    while ($row = mysqli_fetch_assoc($res_uni)) {
        $featured_unis[] = $row;
    }
}


//  Success Stories (Limit 3)
$success_stories = [];
$sql_story = "SELECT * FROM success_stories ORDER BY story_id DESC LIMIT 3";
$res_story = mysqli_query($conn, $sql_story);
if ($res_story) {
    while ($row = mysqli_fetch_assoc($res_story)) {
        $success_stories[] = $row;
    }
}

//  Latest Blogs (Limit 3)
$blogs = [];
$sql_blog = "SELECT * FROM blog_posts ORDER BY published_date DESC LIMIT 3";
$res_blog = mysqli_query($conn, $sql_blog);
if ($res_blog) {
    while ($row = mysqli_fetch_assoc($res_blog)) {
        $blogs[] = $row;
    }
}
?>


<!-- navbar -->
<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero hero-main">
    <div class="container">
        <h1>Your Gateway to Global Education</h1>
        <p>
            We help Sri Lankan students find the best universities, secure visas, and build a successful future abroad.
        </p>
        <div class="hero-buttons">
            <a href="student/universities.php" class="btn btn-primary">Browse Universities 🎓</a>
            <a href="contact.php" class="btn btn-minimal">Contact Us 📞</a>
        </div>

    </div>
</section>

<!-- Key Features Section -->
<section class="features-section section-padding" style="background: white;">
    <div class="container">
        <h2 class="section-title">Why Choose UniPath?</h2>
        <div class="values-grid">

            <!-- Feature 1 -->
            <div class="value-card">
                <div class="value-card-icon">
                    🎓
                </div>
                <h3>Expert Guidance</h3>
                <p>Our certified counselors provide personalized career advice to help you make the right choice.</p>
            </div>

            <!-- Feature 2 -->
            <div class="value-card">
                <div class="value-card-icon">
                    📔
                </div>
                <h3>98% Visa Success</h3>
                <p>We have a proven track record of successful visa applications with meticulous documentation.</p>
            </div>

            <!-- Feature 3 -->
            <div class="value-card">
                <div class="value-card-icon">
                    🌎
                </div>
                <h3>Global Network</h3>
                <p>Partnered with 500+ top-ranked universities across UK, USA, Canada, Australia, and more.</p>
            </div>

        </div>
    </div>
</section>

<!-- Featured Universities -->
<section class="featured-unis section-padding">
    <div class="container">
        <h2 class="section-title">Featured Universities</h2>
        <div class="uni-grid">
            <?php foreach ($featured_unis as $uni): ?>  
                <div class="uni-card">
                    <div class="uni-card-image-placeholder">
                        <?php if (!empty($uni['logo_image'])): ?>
                            <img src="<?php echo htmlspecialchars($uni['logo_image']); ?>" alt="<?php echo htmlspecialchars($uni['name']); ?>" class="uni-card-logo">
                        <?php else: ?>
                            <span><?php echo htmlspecialchars($uni['name']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="uni-card-body">
                        <span class="uni-card-country-tag"><?php echo htmlspecialchars($uni['country_name']); ?></span>
                        <h3 class="uni-card-title"><?php echo htmlspecialchars($uni['name']); ?></h3>
                        <p class="uni-card-description">
                            <?php echo htmlspecialchars(substr($uni['description'], 0, 100)) . '...'; ?>
                        </p>
                        <a href="<?php echo base_url('student/university_details.php?id=' . $uni['uni_id']); ?>"
                            class="btn btn-outline uni-card-link">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center section-padding" style="padding-top: 40px;">
            <a href="<?php echo base_url('/student/universities.php'); ?>" class="btn btn-primary">View All
                Universities</a>
        </div>
    </div>
</section>

<!-- Success Stories Section -->
<section class="success-stories section-padding">
    <div class="container">
        <h2 class="section-title">Success Stories</h2>
        <div class="story-grid">
            <?php foreach ($success_stories as $story): ?>
                <div class="story-card">
                    <div class="story-card-quote-icon">
                        ❝
                    </div>
                    <h3 class="story-card-title">
                        <?php echo htmlspecialchars($story['title']); ?>
                    </h3>
                    <p class="story-card-content">
                        "<?php echo htmlspecialchars($story['content']); ?>"
                    </p>
                    <div class="story-card-student-info">
                        <div class="story-card-student-avatar">
                            <?php echo substr($story['student_name'], 0, 1); ?>
                        </div>
                        <span class="story-card-student-name">
                            <?php echo htmlspecialchars($story['student_name']); ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center section-padding" style="padding-top: 40px;">
            <a href="<?php echo base_url('success_stories.php'); ?>" class="btn btn-outline">Read More Stories</a>
        </div>
    </div>
</section>

</div>
</section>

</section>

<!-- Blog Section -->
<section class="blog-section section-padding">
    <div class="container">
        <h2 class="section-title">Latest Updates & News</h2>
        <div class="blog-grid">
            <?php if (count($blogs) > 0): ?>
                <?php foreach ($blogs as $blog): ?>
                    <div class="blog-card" style="height: auto; min-height: 280px;">
                        <div class="blog-card-body">
                            <div class="blog-card-date">
                                <span class="blog-date"><?php echo date('M d, Y', strtotime($blog['published_date'])); ?></span>
                                <?php if (!empty($blog['category'])): ?>
                                    <span class="category-badge"
                                        style="background: var(--secondary-color); color: var(--primary-color); padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">
                                        <?php echo htmlspecialchars($blog['category']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <h3 class="blog-card-title"><?php echo htmlspecialchars($blog['title']); ?></h3>
                            <p class="blog-card-content">
                                <?php echo htmlspecialchars(substr($blog['content'], 0, 120)) . '...'; ?>
                            </p>
                            <a href="blog_view.php?id=<?php echo $blog['post_id']; ?>" class="blog-card-link" style="margin-top: auto;">Read More ➡️</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No recent updates available.</p>
            <?php endif; ?>
        </div>

        <!-- View Our Blogs CTA -->
        <div class="text-center section-padding" style="padding-top: 50px;">
            <a href="<?php echo base_url('blog.php'); ?>" class="btn btn-outline">View Our Blogs</a>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq section-padding">
    <div class="container">
        <h2 class="section-title">Frequently Asked Questions
        </h2>
        <div class="faq-container">

            <div class="faq-item">
                <div class="faq-question">
                    How do I apply to a university?
                    <span>▼</span>
                </div>
                <div class="faq-answer">
                    <p>The application process varies by university, but generally involves submitting your academic
                        transcripts, English proficiency test scores (IELTS/TOEFL), a personal statement, and letters of
                        recommendation. Our consultants will guide you through each step.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    What is the consultation process?
                    <span>▼</span>
                </div>
                <div class="faq-answer">
                    <p>Our consultation starts with an initial assessment of your academic background and career goals.
                        We then recommend suitable universities and courses. Once you shortlist your options, we assist
                        with applications, visa documentation, and pre-departure briefings.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    Do you provide visa support?
                    <span>▼</span>
                </div>
                <div class="faq-answer">
                    <p>Yes, we have a dedicated visa support team with a 98% success rate. We help you prepare your
                        financial documents, fill out visa forms, and prepare for the embassy interview.</p>
                </div>
            </div>

        </div>
    </div>
    <script>
        // Simple FAQ Toggle Script
        document.querySelectorAll('.faq-question').forEach(item => {
            item.addEventListener('click', event => {
                const answer = item.nextElementSibling;
                const icon = item.querySelector('span');
                if (answer.style.display === 'block') {
                    answer.style.display = 'none';
                    icon.textContent = '▼';
                } else {
                    answer.style.display = 'block';
                    icon.textContent = '▲';
                }
            });
        });
    </script>
</section>

<!-- Call to Action -->
<section class="cta">
    <div class="container">
        <h2>Ready to Start Your Journey?</h2>
        <p>Book a free consultation with our experts today.</p>
        <a href="<?php echo base_url('student/appointment.php'); ?>" class="btn btn-primary"
            style="background: var(--secondary-color); color: var(--primary-color);">Book Appointment</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>