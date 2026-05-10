<?php
/**
 * Handler: register_handler.php
 *
 * Processes the patient registration form submission.
 * - Only accepts POST requests
 * - Validates all fields server-side
 * - Checks for duplicate email/username
 * - Hashes password with PASSWORD_BCRYPT
 * - Inserts into `patients` using a PDO prepared statement
 * - Redirects with status query params on success or failure
 */

require_once __DIR__ . '/../includes/db.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../register.php');
    exit;
}

/**
 * Redirect back to the registration page with an error message.
 */
function redirectWithError(string $message): void {
    header('Location: ../register.php?status=error&message=' . urlencode($message));
    exit;
}

// --- Read and sanitize POST fields ---
$username         = trim($_POST['username']         ?? '');
$email            = trim($_POST['email']            ?? '');
$password         = $_POST['password']              ?? '';
$confirm_password = $_POST['confirm_password']      ?? '';

// --- Validation ---

// 1. All fields must be non-empty
if ($username === '' || $email === '' || $password === '' || $confirm_password === '') {
    redirectWithError('All fields are required. Please fill in every field before submitting.');
}

// 2. Valid email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectWithError('Please enter a valid email address.');
}

// 3. Password must be at least 8 characters
if (strlen($password) < 8) {
    redirectWithError('Password must be at least 8 characters long.');
}

// 4. Passwords must match
if ($password !== $confirm_password) {
    redirectWithError('Passwords do not match. Please try again.');
}

// --- Database operations ---
try {
    $pdo = getDB();

    // 5. Check for duplicate email or username
    $checkStmt = $pdo->prepare(
        'SELECT COUNT(*) FROM patients WHERE email = :email OR username = :username'
    );
    $checkStmt->execute([
        ':email'    => $email,
        ':username' => $username,
    ]);

    if ((int) $checkStmt->fetchColumn() > 0) {
        redirectWithError('Email or username already registered. Please use a different one or login.');
    }

    // 6. Hash the password
    $hash = password_hash($password, PASSWORD_BCRYPT);

    // 7. Insert the new patient record
    $insertStmt = $pdo->prepare(
        'INSERT INTO patients (username, email, password_hash) VALUES (:username, :email, :password_hash)'
    );
    $insertStmt->execute([
        ':username'      => $username,
        ':email'         => $email,
        ':password_hash' => $hash,
    ]);

    // Success — redirect to login with confirmation
    header('Location: ../login.php?status=registered');
    exit;

} catch (PDOException $e) {
    // Log the real error server-side; never expose it to the client
    error_log('register_handler.php DB error: ' . $e->getMessage());
    redirectWithError('Something went wrong during registration. Please try again later.');
}
