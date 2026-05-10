<?php session_start(); $activePage = 'doctors'; require_once 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare Hospital - Our Doctors</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .doctor-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .doctor-card {
            border: 1px solid #cce0f0;
            padding: 16px;
            border-radius: 8px;
            text-align: center;
            background: #fff;
        }
        .doctor-card img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .doctor-card h4 {
            margin: 8px 0 4px;
            color: #1a5276;
        }
        .doctor-card .specialization {
            color: #2e86c1;
            font-weight: bold;
            margin: 4px 0;
        }
        .doctor-card .availability {
            color: #555;
            font-size: 0.9em;
            margin: 4px 0;
        }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<div class="layout-wrapper">
    <?php include 'includes/sidebar_left.php'; ?>

    <main class="main-content">
        <h2>Our Doctors
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#4a9fd4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-left:8px" aria-hidden="true">
                <path d="M11 2a2 2 0 0 0-2 2v5H4a2 2 0 0 0-2 2v2c0 1.1.9 2 2 2h5v5c0 1.1.9 2 2 2h2a2 2 0 0 0 2-2v-5h5a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2h-5V4a2 2 0 0 0-2-2h-2z"/>
            </svg>
        </h2>

        <?php
        // Query all doctors ordered by specialization then name
        $grouped = [];
        try {
            $pdo  = getDB();
            $stmt = $pdo->query('SELECT * FROM doctors ORDER BY specialization, name');
            $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Group by specialization
            foreach ($doctors as $doctor) {
                $grouped[$doctor['specialization']][] = $doctor;
            }
        } catch (PDOException $e) {
            error_log('doctors.php DB error: ' . $e->getMessage());
        }
        ?>

        <?php if (empty($grouped)): ?>
            <p>No doctors are currently listed. Please check back soon.</p>
        <?php else: ?>
            <?php foreach ($grouped as $specialization => $cards): ?>
                <h3><?= htmlspecialchars($specialization, ENT_QUOTES, 'UTF-8') ?></h3>
                <div class="doctor-cards">
                    <?php foreach ($cards as $doctor): ?>
                        <?php
                        $imgSrc = !empty($doctor['image_path'])
                            ? htmlspecialchars($doctor['image_path'], ENT_QUOTES, 'UTF-8')
                            : 'images/doctors/placeholder.png';
                        ?>
                        <div class="doctor-card">
                            <img src="<?= $imgSrc ?>"
                                 alt="<?= htmlspecialchars($doctor['name'], ENT_QUOTES, 'UTF-8') ?>">
                            <h4><?= htmlspecialchars($doctor['name'], ENT_QUOTES, 'UTF-8') ?></h4>
                            <p class="specialization"><?= htmlspecialchars($doctor['specialization'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="availability"><?= htmlspecialchars($doctor['availability'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <?php include 'includes/sidebar_right.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
