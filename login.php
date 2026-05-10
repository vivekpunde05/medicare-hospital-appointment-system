<?php session_start(); $activePage = 'login'; ?>
<?php
// If already logged in, redirect to dashboard
if (isset($_SESSION['patient_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare Hospital - Login</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .form-container {
            max-width: 480px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.35rem;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 0.55rem 0.75rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
            min-height: 44px;
        }
        .btn-login {
            width: 100%;
            padding: 0.65rem;
            background-color: var(--color-green, #28a745);
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            min-height: 44px;
        }
        .btn-login:hover {
            opacity: 0.9;
        }
        .form-footer {
            margin-top: 1rem;
            text-align: center;
        }
        .status-message {
            padding: 0.75rem 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .status-message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .status-message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<div class="layout-wrapper">
    <?php include 'includes/sidebar_left.php'; ?>

    <main class="main-content">
        <h2>Patient Login</h2>

        <?php if (isset($_GET['status'])): ?>
            <?php $status = $_GET['status']; ?>
            <?php if ($status === 'registered'): ?>
                <div class="status-message success">
                    Registration successful! Please log in.
                </div>
            <?php elseif ($status === 'error'): ?>
                <div class="status-message error">
                    Invalid credentials. Please try again.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="form-container">
            <form action="handlers/login_handler.php" method="POST">

                <div class="form-group">
                    <label for="login-email">Email Address</label>
                    <input type="email" id="login-email" name="email"
                           placeholder="your@email.com" required>
                </div>

                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password"
                           placeholder="Your password" required>
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>

            <p class="form-footer">
                Don't have an account? <a href="register.php">Register here</a>
            </p>
        </div>
    </main>

    <?php include 'includes/sidebar_right.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
