<?php include 'includes/db_connect.php'; ?>
<?php
$message_sent = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        $error = "Please fill in all required fields.";
    } else {
        $sql = "INSERT INTO inquiries (name, email, subject, message, status) VALUES ('$name', '$email', '$subject', '$message', 'Pending')";

        if (mysqli_query($conn, $sql)) {
            $message_sent = true;
        } else {
            $error = "Database error: " . mysqli_error($conn);
        }
    }
}
?>
<?php include 'includes/header.php'; ?>

<div class="page-header" style="background: var(--primary-color); color: white; padding: 40px 0; text-align: center;">
    <div class="container">
        <h1>Contact Us</h1>
        <p>Get in touch with our team for any inquiries.</p>
    </div>
</div>

<div class="container" style="padding: 60px 15px;">
    <div style="display: flex; flex-wrap: wrap; gap: 50px;">

        <!-- Contact Info -->
        <div style="flex: 1; min-width: 300px;">
            <h2 style="color: var(--primary-color); margin-bottom: 20px;">Contact Information</h2>
            <div style="margin-bottom: 30px;">
                <div style="display: flex; margin-bottom: 20px;">
                    <span style="font-size: 1.5rem; margin-right: 15px;">📍</span>
                    <div>
                        <h4 style="margin-bottom: 5px;">Head Office</h4>
                        <p style="color: #555;">123, Galle Road, Colombo 03, Sri Lanka.</p>
                    </div>
                </div>
                <div style="display: flex; margin-bottom: 20px;">
                    <span style="font-size: 1.5rem; margin-right: 15px;">📞</span>
                    <div>
                        <h4 style="margin-bottom: 5px;">Phone</h4>
                        <p style="color: #555;">+94 11 234 5678</p>
                        <p style="color: #555;">+94 77 123 4567</p>
                    </div>
                </div>
                <div style="display: flex; margin-bottom: 20px;">
                    <span style="font-size: 1.5rem; margin-right: 15px;">✉️</span>
                    <div>
                        <h4 style="margin-bottom: 5px;">Email</h4>
                        <p style="color: #555;">info@unipath.lk</p>
                        <p style="color: #555;">admissions@unipath.lk</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div style="flex: 1; min-width: 300px; background: var(--light-bg); padding: 30px; border-radius: 10px;">
            <h2 style="margin-bottom: 20px;">Send us a Message</h2>

            <?php if ($message_sent): ?>
                <div style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
                    Thank you! Your message has been sent successfully.
                </div>
            <?php elseif ($error): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="contact.php" method="POST" onsubmit="return CheckForm(this)" novalidate>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Name *</label>
                    <input type="text" name="name" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Email *</label>
                    <input type="email" name="email" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Subject</label>
                    <input type="text" name="subject"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Message *</label>
                    <textarea name="message" rows="5" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                </div>
                <button type="submit" class="btn-primary"
                    style="width: 100%; border: none; padding: 12px; cursor: pointer;">Send Message</button>
            </form>
        </div>
    </div>
</div>

<script src="assets/js/validation.js"></script>
<?php include 'includes/footer.php'; ?>