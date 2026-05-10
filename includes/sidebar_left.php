<?php

/**
 * Left sidebar: renders a compact list of doctors from the `doctors` table.
 */

require_once __DIR__ . '/db.php';

$doctors = [];

try {
    $pdo  = getDB();
    $stmt = $pdo->query('SELECT id, name, specialization FROM doctors ORDER BY specialization, name');
    $doctors = $stmt->fetchAll();
} catch (PDOException $e) {
    // Log the error but show an empty list — never expose DB details to the client
    error_log('sidebar_left: failed to fetch doctors — ' . $e->getMessage());
}

?>
<aside class="sidebar-left">
    <h3>Our Doctors</h3>
    <ul>
        <?php if (empty($doctors)): ?>
            <li class="no-doctors">No doctors available.</li>
        <?php else: ?>
            <?php foreach ($doctors as $doctor): ?>
                <li>
                    <span class="doctor-name"><?= htmlspecialchars($doctor['name'], ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="doctor-specialization"><?= htmlspecialchars($doctor['specialization'], ENT_QUOTES, 'UTF-8') ?></span>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</aside>
