<?php

/**
 * handlers/get_slots.php
 *
 * Accepts GET parameters: doctor, date
 * Returns JSON: { "booked": [...], "available": [...] }
 */

header('Content-Type: application/json');

// Validate required GET parameters
$doctor = isset($_GET['doctor']) ? trim($_GET['doctor']) : '';
$date   = isset($_GET['date'])   ? trim($_GET['date'])   : '';

if ($doctor === '' || $date === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameters: doctor and date']);
    exit;
}

// Full set of available time slots
$allSlots = [
    'morning_9am',
    'morning_10am',
    'morning_11am',
    'evening_5pm',
    'evening_6pm',
    'evening_7pm',
];

require_once __DIR__ . '/../includes/db.php';

try {
    $pdo  = getDB();
    $stmt = $pdo->prepare(
        'SELECT time_slot FROM appointments WHERE doctor = ? AND date = ?'
    );
    $stmt->execute([$doctor, $date]);

    $booked    = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $available = array_values(array_diff($allSlots, $booked));

    echo json_encode([
        'booked'    => $booked,
        'available' => $available,
    ]);
} catch (PDOException $e) {
    error_log('get_slots.php error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Unable to retrieve slots. Please try again later.']);
}
