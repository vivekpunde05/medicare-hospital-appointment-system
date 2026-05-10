<?php
// $activePage should be set by the including page to one of:
// 'home', 'about', 'doctors', 'book-appointment', 'contact', 'login'
if (!isset($activePage)) {
    $activePage = '';
}

$isLoggedIn = isset($_SESSION['patient_id']);

$navLinks = [
    'home'             => ['href' => 'index.php',            'label' => 'Home'],
    'about'            => ['href' => 'about.php',            'label' => 'About'],
    'doctors'          => ['href' => 'doctors.php',          'label' => 'Doctors'],
    'book-appointment' => ['href' => 'book-appointment.php', 'label' => 'Book Appointment'],
    'contact'          => ['href' => 'contact.php',          'label' => 'Contact Us'],
];
?>
<nav>
    <ul>
        <?php foreach ($navLinks as $key => $link): ?>
            <li>
                <a href="<?php echo htmlspecialchars($link['href']); ?>"
                   <?php if ($activePage === $key): ?>class="active"<?php endif; ?>>
                    <?php echo htmlspecialchars($link['label']); ?>
                </a>
            </li>
        <?php endforeach; ?>
        <li>
            <?php if ($isLoggedIn): ?>
                <a href="handlers/login_handler.php?action=logout"
                   <?php if ($activePage === 'login'): ?>class="active"<?php endif; ?>>
                    Logout
                </a>
            <?php else: ?>
                <a href="login.php"
                   <?php if ($activePage === 'login'): ?>class="active"<?php endif; ?>>
                    Login/Register
                </a>
            <?php endif; ?>
        </li>
    </ul>
</nav>
