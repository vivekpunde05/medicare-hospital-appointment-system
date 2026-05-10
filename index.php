<?php session_start(); $activePage = 'home'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare Hospital - Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<div class="layout-wrapper">
    <?php include 'includes/sidebar_left.php'; ?>

    <main class="main-content">
        <h2>Welcome to MediCare Hospital</h2>
        <p>Your health is our priority. Book appointments with our experienced doctors quickly and easily.</p>

        <div class="quick-links">
            <a href="doctors.php" class="btn">View Doctors</a>
            <a href="book-appointment.php" class="btn btn-primary">Book Appointment</a>
        </div>
    </main>

    <?php include 'includes/sidebar_right.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
