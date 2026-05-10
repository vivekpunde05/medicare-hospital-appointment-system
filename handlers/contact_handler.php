<?php
/**
 * Handler: contact_handler.php
 *
 * Processes the contact form submission.
 * - Only accepts POST requests
 * - Validates all fields server-side
 * - Inserts into `contact_messages` using a PDO prepared statement
 * - Redirects with status/message query params on success or failure

 */

require_once __DIR__ . '/../includes/db.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../contact.php');
    exit;
}

/**
 * Redirect back to the contact page with an error message.
 */
function redirectWithError(string $message): void {
    header('Location: ../contact.php?status=error&message=' . urlencode($message));
    exit;
}

// --- Read and sanitize POST fields ---
$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// --- Validation ---

// 1. All fields must be non-empty
if ($name === '' || $email === '' || $subject === '' || $message === '') {
    redirectWithError('All fields are required. Please fill in every field before submitting.');
}

// 2. Valid email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectWithError('Please enter a valid email address.');
}

// --- Database insert ---
try {
    $pdo = getDB();

    $stmt = $pdo->prepare(
        'INSERT INTO contact_messages (name, email, subject, message)
         VALUES (:name, :email, :subject, :message)'
    );

    $stmt->execute([
        ':name'    => $name,
        ':email'   => $email,
        ':subject' => $subject,
        ':message' => $message,
    ]);

    // Success — redirect with confirmation
    header('Location: ../contact.php?status=success');
    exit;

} catch (PDOException $e) {
    // Log the real error server-side; never expose it to the client
    error_log('contact_handler.php DB error: ' . $e->getMessage());
    redirectWithError('Something went wrong while sending your message. Please try again later.');
}
