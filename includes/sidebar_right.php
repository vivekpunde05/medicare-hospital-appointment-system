<?php

/**
 * Right sidebar: renders a static list of news/notices items.
 */

$notices = [
    [
        'title' => 'New Cardiology Wing Opening',
        'date'  => 'June 15, 2025',
    ],
    [
        'title' => 'COVID-19 Vaccination Drive',
        'date'  => 'June 10, 2025',
    ],
    [
        'title' => 'Extended Evening Hours Now Available',
        'date'  => 'May 28, 2025',
    ],
    [
        'title' => 'Annual Health Camp – Free Checkups',
        'date'  => 'May 20, 2025',
    ],
];

?>
<aside class="sidebar-right">
    <h3>News &amp; Notices</h3>
    <?php foreach ($notices as $notice): ?>
        <div class="notice-item">
            <h4><?= htmlspecialchars($notice['title'], ENT_QUOTES, 'UTF-8') ?></h4>
            <p><?= htmlspecialchars($notice['date'], ENT_QUOTES, 'UTF-8') ?></p>
        </div>
    <?php endforeach; ?>
</aside>
