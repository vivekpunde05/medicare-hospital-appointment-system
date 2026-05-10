<?php session_start(); $activePage = 'login'; require_once 'includes/db.php'; ?>
<?php
// Require active session — redirect to login if not authenticated
if (!isset($_SESSION['patient_id'])) {
    header('Location: login.php?status=error');
    exit;
}

$patientId       = $_SESSION['patient_id'];
$patientUsername = htmlspecialchars($_SESSION['patient_username'] ?? 'Patient');

// Fetch patient email
$email = '';
try {
    $pdo   = getDB();
    $stmt  = $pdo->prepare('SELECT email FROM patients WHERE id = ?');
    $stmt->execute([$patientId]);
    $row   = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $email = $row['email'];
    }
} catch (PDOException $e) {
    error_log('Dashboard DB error (email fetch): ' . $e->getMessage());
}

// Fetch upcoming appointments for this patient
$appointments = [];
if ($email !== '') {
    try {
        $stmt = $pdo->prepare(
            'SELECT * FROM appointments
              WHERE email = ?
                AND date >= CURDATE()
           ORDER BY date ASC, time_slot ASC'
        );
        $stmt->execute([$email]);
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Dashboard DB error (appointments fetch): ' . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare Hospital - My Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .dashboard-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .dashboard-table th,
        .dashboard-table td {
            padding: 0.65rem 0.85rem;
            border: 1px solid #dee2e6;
            text-align: left;
        }
        .dashboard-table th {
            background-color: var(--color-blue, #007bff);
            color: #fff;
        }
        .dashboard-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .no-appointments {
            margin-top: 1rem;
            color: #555;
        }
        .logout-link {
            display: inline-block;
            margin-top: 1.25rem;
            color: var(--color-green, #28a745);
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<div class="layout-wrapper">
    <?php include 'includes/sidebar_left.php'; ?>

    <main class="main-content">
        <h2>Welcome, <?php echo $patientUsername; ?>!</h2>

        <h3>Your Upcoming Appointments</h3>

        <?php if (empty($appointments)): ?>
            <p class="no-appointments">You have no upcoming appointments.</p>
        <?php else: ?>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Doctor</th>
                        <th>Time Slot</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appt): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($appt['date']); ?></td>
                            <td><?php echo htmlspecialchars($appt['doctor']); ?></td>
                            <td><?php echo htmlspecialchars($appt['time_slot']); ?></td>
                            <td><?php echo htmlspecialchars($appt['name']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a class="logout-link" href="handlers/login_handler.php?action=logout">Logout</a>
    </main>

    <?php include 'includes/sidebar_right.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
