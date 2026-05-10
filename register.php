<?php session_start(); $activePage = 'login'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare Hospital - Register</title>
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
        .btn-register {
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
        .btn-register:hover {
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
        <h2>Create an Account</h2>

        <?php if (isset($_GET['status'])): ?>
            <?php
                $status  = htmlspecialchars($_GET['status']);
                $message = htmlspecialchars($_GET['message'] ?? '');
            ?>
            <?php if ($status === 'error'): ?>
                <div class="status-message error">
                    <?php echo $message !== '' ? $message : 'Registration failed. Please try again.'; ?>
                </div>
            <?php elseif ($status === 'success'): ?>
                <div class="status-message success">
                    <?php echo $message !== '' ? $message : 'Registration successful!'; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="form-container">
            <form action="handlers/register_handler.php" method="POST">

                <div class="form-group">
                    <label for="reg-username">Username</label>
                    <input type="text" id="reg-username" name="username"
                           placeholder="Choose a username" required>
                </div>

                <div class="form-group">
                    <label for="reg-email">Email Address</label>
                    <input type="email" id="reg-email" name="email"
                           placeholder="your@email.com" required>
                </div>

                <div class="form-group">
                    <label for="reg-password">Password</label>
                    <input type="password" id="reg-password" name="password"
                           placeholder="Minimum 8 characters" required>
                </div>

                <div class="form-group">
                    <label for="reg-confirm-password">Confirm Password</label>
                    <input type="password" id="reg-confirm-password" name="confirm_password"
                           placeholder="Repeat your password" required>
                </div>

                <button type="submit" class="btn-register">Register</button>
            </form>

            <p class="form-footer">
                Already have an account? <a href="login.php">Login here</a>
            </p>
        </div>
    </main>

    <?php include 'includes/sidebar_right.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
