<?php
include 'includes/db_connect.php';
include 'includes/header.php';
?>

<style>
    .white-text {
        color: white;
    }

    .text-left {
        text-align: left;
    }

    .mb-2 {
        margin-bottom: 2rem;
    }

    .mb-1 {
        margin-bottom: 1rem;
    }

    .about-story-col {
        flex: 2;
    }

    .story-text {
        color: var(--text-muted);
        margin-bottom: 1.5rem;
        font-size: 1.1rem;
    }

    .mission-box {
        background: var(--bg-body);
        padding: 3rem;
        border-radius: 20px;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        font-weight: bold;
    }

    .bg-dark-blue {
        background: #1e293b;
    }

    .bg-darker-blue {
        background: #0f172a;
    }

    .team-desc {
        margin-top: 1rem;
        color: var(--text-muted);
        font-size: 0.9rem;
    }
</style>

<!-- Hero Section -->
<section class="page-hero">
    <div class="container">
        <h1>Empowering Global Dreams</h1>
        <p>UniPath is your trusted partner in international education, dedicated to connecting ambitious students with
            world-class universities.</p>
    </div>
</section>

<!-- Mission & Vision -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="grid-3">
            <div class="about-story-col">
                <h2 class="section-title text-left mb-2">Our Story</h2>
                <p class="story-text">
                    UniPath was founded with a singular vision: to democratize access to global education for Sri Lankan
                    students. We understand that the journey to studying abroad can be complex and overwhelming. That's
                    why we've built a team of experts to guide you every step of the way.
                </p>
                <p class="story-text">
                    From choosing the right course to securing your visa, we provide personalized support that ensures
                    your success. Our partnerships with over 500 top-ranked universities worldwide give you unparalleled
                    access to opportunities.
                </p>
            </div>
            <div class="mission-box">
                <div class="mission-icon">🎯</div>
                <h3 class="mb-1">Our Mission</h3>
                <p class="text-muted">To provide honest, expert, and comprehensive guidance to students, ensuring they
                    achieve their academic potential globally.</p>

                <div class="mission-icon" style="margin-top: 2rem;">👁️</div>
                <h3 class="mb-1">Our Vision</h3>
                <p class="text-muted">To be the most trusted education consultancy in South Asia, known for integrity
                    and student success.</p>
            </div>
        </div>
    </div>
</section>

<!-- Leadership Team -->
<section class="section-padding bg-light">
    <div class="container">
        <h2 class="section-title">Meet Our Leadership</h2>
        <div class="team-grid">

            <!-- CEO -->
            <div class="team-card">
                <div class="team-avatar">
                    <!-- Placeholder/Initials -->
                    <div class="avatar-placeholder bg-dark-blue">A</div>
                </div>
                <h3 class="team-name">Mr. Ahamed</h3>
                <div class="team-role">Chief Executive Officer</div>
                <p class="team-desc">
                    With over 15 years in the education sector, Mr. Ahamed leads UniPath with a focus on strategic
                    partnerships and student welfare.
                </p>
            </div>

            <!-- Senior Consultant -->
            <div class="team-card">
                <div class="team-avatar">
                    <div class="avatar-placeholder bg-darker-blue">F</div>
                </div>
                <h3 class="team-name">Ms. Fathima</h3>
                <div class="team-role">Senior Consultant</div>
                <p class="team-desc">
                    A certified career counselor, Ms. Fathima specializes in visa documentation and university
                    placements across the UK and Canada.
                </p>
            </div>

        </div>
    </div>
</section>

<!-- Values / Why Choose Us -->
<section class="section-padding bg-white">
    <div class="container">
        <h2 class="section-title">Why Trust Us?</h2>
        <div class="grid-3">
            <div class="value-card">
                <div class="value-card-icon">🤝</div>
                <h3>Personalized Guidance</h3>
                <p>We don't believe in one-size-fits-all. Your career path is designed specifically for you.</p>
            </div>
            <div class="value-card">
                <div class="value-card-icon">✅</div>
                <h3>Transparent Process</h3>
                <p>No hidden fees. No false promises. We maintain complete transparency throughout your application.</p>
            </div>
            <div class="value-card">
                <div class="value-card-icon">🌍</div>
                <h3>Global Alumni Network</h3>
                <p>Join our community of thousands of successful students studying in 10+ countries.</p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>