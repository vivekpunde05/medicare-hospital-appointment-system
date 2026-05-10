<?php
/**
 * Handler: login_handler.php
 *
 * Processes patient login and logout.
 *
 * GET ?action=logout  — destroys the session and redirects to login.php
 * POST                — validates credentials, starts session on success
 */

session_start();
require_once __DIR__ . '/../includes/db.php';

// --- Logout ---
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'logout') {
    session_destroy();
    header('Location: ../login.php');
    exit;
}

// --- Only allow POST for login ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

// --- Read POST fields ---
$email    = trim($_POST['email']    ?? '');
$password = $_POST['password']      ?? '';

// --- Basic non-empty validation ---
if ($email === '' || $password === '') {
    header('Location: ../login.php?status=error');
    exit;
}

// --- Database lookup and credential check ---
try {
    $pdo = getDB();

    $stmt = $pdo->prepare(
        'SELECT id, username, password_hash FROM patients WHERE email = ?'
    );
    $stmt->execute([$email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // No matching email OR wrong password — same generic message (Req 10.4)
    if (!$row || !password_verify($password, $row['password_hash'])) {
        header('Location: ../login.php?status=error');
        exit;
    }

    // --- Success: start session and redirect to dashboard ---
    $_SESSION['patient_id']       = $row['id'];
    $_SESSION['patient_username'] = $row['username'];

    header('Location: ../dashboard.php');
    exit;

} catch (PDOException $e) {
    error_log('login_handler.php DB error: ' . $e->getMessage());
    header('Location: ../login.php?status=error');
    exit;
}
