<?php
/**
 * Handler: book_appointment.php
 *
 * Processes the appointment booking form submission.
 * - Only accepts POST requests
 * - Validates all fields server-side
 * - Inserts into `appointments` using a PDO prepared statement
 * - Redirects with status/message query params on success or failure
 */

session_start();
require_once __DIR__ . '/../includes/db.php';

// Only logged-in users can book appointments
if (empty($_SESSION['patient_id'])) {
    header('Location: ../login.php?redirect=book-appointment');
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../book-appointment.php');
    exit;
}

/**
 * Redirect back to the booking page with an error message.
 */
function redirectWithError(string $message): void {
    header('Location: ../book-appointment.php?status=error&message=' . urlencode($message));
    exit;
}

// --- Read and sanitize POST fields ---
$name      = trim($_POST['name']      ?? '');
$email     = trim($_POST['email']     ?? '');
$phone     = trim($_POST['phone']     ?? '');
$doctor    = trim($_POST['doctor']    ?? '');
$date      = trim($_POST['date']      ?? '');
$time_slot = trim($_POST['time_slot'] ?? '');

// --- Validation ---

// 1. All fields must be non-empty
if ($name === '' || $email === '' || $phone === '' || $doctor === '' || $date === '' || $time_slot === '') {
    redirectWithError('All fields are required. Please fill in every field before submitting.');
}

// 2. Valid email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectWithError('Please enter a valid email address.');
}

// 3. Phone: extract digits only, must be 10–15 digits
$phoneDigits = preg_replace('/\D/', '', $phone);
$phoneLen    = strlen($phoneDigits);
if ($phoneLen < 10 || $phoneLen > 15) {
    redirectWithError('Phone number must contain between 10 and 15 digits.');
}

// 4. Date must be a valid date AND strictly in the future
$parsedDate = date_create_from_format('Y-m-d', $date);
if (!$parsedDate || date_format($parsedDate, 'Y-m-d') !== $date) {
    redirectWithError('Please enter a valid date in YYYY-MM-DD format.');
}
if ($date <= date('Y-m-d')) {
    redirectWithError('The appointment date must be a future date.');
}

// 5. time_slot must be one of the allowed values
$validSlots = ['morning_9am', 'morning_10am', 'morning_11am', 'evening_5pm', 'evening_6pm', 'evening_7pm'];
if (!in_array($time_slot, $validSlots, true)) {
    redirectWithError('Please select a valid time slot.');
}

// --- Database insert ---
try {
    $pdo = getDB();

    $stmt = $pdo->prepare(
        'INSERT INTO appointments (name, email, phone, doctor, date, time_slot)
         VALUES (:name, :email, :phone, :doctor, :date, :time_slot)'
    );

    $stmt->execute([
        ':name'      => $name,
        ':email'     => $email,
        ':phone'     => $phoneDigits,   // store normalised digits
        ':doctor'    => $doctor,
        ':date'      => $date,
        ':time_slot' => $time_slot,
    ]);

    // Success — redirect with confirmation
    header('Location: ../book-appointment.php?status=success');
    exit;

} catch (PDOException $e) {
    // Log the real error server-side; never expose it to the client
    error_log('book_appointment.php DB error: ' . $e->getMessage());
    redirectWithError('Something went wrong while saving your appointment. Please try again later.');
}
