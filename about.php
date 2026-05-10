<?php session_start(); $activePage = 'about'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare Hospital - About Us</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<div class="layout-wrapper">
    <?php include 'includes/sidebar_left.php'; ?>

    <main class="main-content">
        <h2>About MediCare Hospital</h2>
        <p>MediCare Hospital has been serving the community for over 30 years, providing compassionate and high-quality medical care to patients of all ages. Our team of experienced doctors, nurses, and support staff are dedicated to ensuring the best possible health outcomes for every patient who walks through our doors.</p>

        <h3>Our Mission</h3>
        <p>Our mission is to deliver exceptional healthcare services with integrity, compassion, and excellence. We are committed to improving the health and well-being of our community by offering accessible, patient-centered care supported by the latest medical technology and evidence-based practices.</p>

        <h3>Our Services</h3>
        <ul>
            <li>General Medicine</li>
            <li>Dentistry</li>
            <li>Cardiology</li>
            <li>Emergency Care</li>
            <li>Diagnostic Services</li>
        </ul>
    </main>

    <?php include 'includes/sidebar_right.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
