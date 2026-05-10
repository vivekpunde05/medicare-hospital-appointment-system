<?php
session_start();
$activePage = 'book-appointment';
require_once 'includes/db.php';

// Only logged-in users can book appointments
if (empty($_SESSION['patient_id'])) {
    header('Location: login.php?redirect=book-appointment');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare Hospital - Book Appointment</title>
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
        .form-group select {
            width: 100%;
            max-width: 400px;
            padding: 8px 10px;
            border: 1px solid #cce0f0;
            border-radius: 4px;
            font-size: 1rem;
            min-height: 44px;
            box-sizing: border-box;
        }
        .field-error {
            color: red;
            display: none;
            font-size: 0.85rem;
            margin-top: 4px;
        }
        .slot-btn {
            padding: 8px 16px;
            margin: 4px;
            border: 1px solid #2e86c1;
            border-radius: 4px;
            cursor: pointer;
            background: #fff;
            min-height: 44px;
            min-width: 44px;
            font-size: 0.95rem;
        }
        .slot-btn.selected {
            background: var(--color-secondary, green);
            color: #fff;
            border-color: var(--color-secondary, green);
        }
        .slot-btn.slot-booked {
            opacity: 0.5;
            cursor: not-allowed;
        }
        #slot-container {
            margin-top: 8px;
            padding: 10px;
            border: 1px dashed #cce0f0;
            border-radius: 4px;
            color: #555;
            max-width: 400px;
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
        <h2>Book an Appointment</h2>

        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] === 'success'): ?>
                <div class="status-message success">
                    Your appointment has been booked successfully. We will contact you to confirm.
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

        <?php
        // Query doctors for the select dropdown
        $doctors = [];
        try {
            $pdo  = getDB();
            $stmt = $pdo->query('SELECT id, name, specialization FROM doctors ORDER BY specialization, name');
            $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('book-appointment.php DB error: ' . $e->getMessage());
        }
        ?>

        <form action="handlers/book_appointment.php" method="POST" onsubmit="return validateBookingForm()">

            <div class="form-group">
                <label for="booking-name">Full Name <span aria-hidden="true">*</span></label>
                <input type="text" id="booking-name" name="name" placeholder="Enter your full name" required>
                <span class="field-error" id="booking-name-error"></span>
            </div>

            <div class="form-group">
                <label for="booking-email">Email Address <span aria-hidden="true">*</span></label>
                <input type="email" id="booking-email" name="email" placeholder="Enter your email address" required>
                <span class="field-error" id="booking-email-error"></span>
            </div>

            <div class="form-group">
                <label for="booking-phone">Phone Number <span aria-hidden="true">*</span></label>
                <input type="tel" id="booking-phone" name="phone" placeholder="Enter your phone number (10-15 digits)" required>
                <span class="field-error" id="booking-phone-error"></span>
            </div>

            <div class="form-group">
                <label for="booking-doctor">Select Doctor <span aria-hidden="true">*</span></label>
                <select id="booking-doctor" name="doctor" required>
                    <option value="">-- Choose a Doctor --</option>
                    <?php foreach ($doctors as $doctor): ?>
                        <option value="<?= htmlspecialchars($doctor['name'], ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($doctor['name'], ENT_QUOTES, 'UTF-8') ?>
                            (<?= htmlspecialchars($doctor['specialization'], ENT_QUOTES, 'UTF-8') ?>)
                        </option>
                    <?php endforeach; ?>
                    <?php if (empty($doctors)): ?>
                        <option value="" disabled>No doctors available</option>
                    <?php endif; ?>
                </select>
                <span class="field-error" id="booking-doctor-error"></span>
            </div>

            <div class="form-group">
                <label for="booking-date">Preferred Date <span aria-hidden="true">*</span></label>
                <input type="date" id="booking-date" name="date" required>
                <span class="field-error" id="booking-date-error"></span>
            </div>

            <div class="form-group">
                <label>Available Time Slots <span aria-hidden="true">*</span></label>
                <div id="slot-container">Select a doctor and date to see available slots</div>
                <input type="hidden" id="booking-time-slot" name="time_slot">
                <span class="field-error" id="booking-time-slot-error"></span>
            </div>

            <div class="form-group">
                <button type="submit" class="submit-btn">Book Appointment</button>
            </div>

        </form>
    </main>

    <?php include 'includes/sidebar_right.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>

<script src="js/validation.js"></script>
<script src="js/slots.js"></script>
</body>
</html>
