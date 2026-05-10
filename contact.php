<?php session_start(); $activePage = 'contact'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare Hospital - Contact Us</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            margin-bottom: 4px;
            font-weight: bold;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            max-width: 400px;
            padding: 8px 10px;
            border: 1px solid #cce0f0;
            border-radius: 4px;
            font-size: 1rem;
            min-height: 44px;
            box-sizing: border-box;
        }
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        .field-error {
            color: red;
            display: none;
            font-size: 0.85rem;
            margin-top: 4px;
        }
        .status-message {
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 16px;
            max-width: 400px;
        }
        .status-message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .submit-btn {
            padding: 10px 24px;
            background: var(--color-primary, #2e86c1);
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            min-height: 44px;
            min-width: 44px;
        }
        .submit-btn:hover {
            background: var(--color-primary-dark, #1a5276);
        }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<div class="layout-wrapper">
    <?php include 'includes/sidebar_left.php'; ?>

    <main class="main-content">
        <h2>Contact Us</h2>

        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] === 'success'): ?>
                <div class="status-message success">
                    Your message has been sent successfully. We will get back to you shortly.
                </div>
            <?php else: ?>
                <div class="status-message error">
                    <?php
                    $errorMsg = isset($_GET['message'])
                        ? htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8')
                        : 'Something went wrong. Please try again.';
                    echo $errorMsg;
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <form action="handlers/contact_handler.php" method="POST" onsubmit="return validateContactForm()">

            <div class="form-group">
                <label for="contact-name">Full Name <span aria-hidden="true">*</span></label>
                <input type="text" id="contact-name" name="name" placeholder="Enter your full name" required>
                <span class="field-error" id="contact-name-error"></span>
            </div>

            <div class="form-group">
                <label for="contact-email">Email Address <span aria-hidden="true">*</span></label>
                <input type="email" id="contact-email" name="email" placeholder="Enter your email address" required>
                <span class="field-error" id="contact-email-error"></span>
            </div>

            <div class="form-group">
                <label for="contact-subject">Subject <span aria-hidden="true">*</span></label>
                <input type="text" id="contact-subject" name="subject" placeholder="Enter the subject" required>
                <span class="field-error" id="contact-subject-error"></span>
            </div>

            <div class="form-group">
                <label for="contact-message">Message <span aria-hidden="true">*</span></label>
                <textarea id="contact-message" name="message" rows="5" placeholder="Enter your message" required></textarea>
                <span class="field-error" id="contact-message-error"></span>
            </div>

            <div class="form-group">
                <button type="submit" class="submit-btn">Send Message</button>
            </div>

        </form>
    </main>

    <?php include 'includes/sidebar_right.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>

<script src="js/validation.js"></script>
</body>
</html>
