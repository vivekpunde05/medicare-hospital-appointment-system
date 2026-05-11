<?php

/**
 * Returns a PDO connection to the MySQL database.
 * Credentials are read from the $config array defined below.
 * PDO error mode is set to ERRMODE_EXCEPTION.
 */

$config = [
    // Prefer individual env vars (Render-style)
    'host'     => getenv('DB_HOST') ?: null,
    'dbname'   => getenv('DB_NAME') ?: null,
    'username' => getenv('DB_USER') ?: null,
    'password' => getenv('DB_PASS') ?: null,
];

// Optional: support Aiven-style DB URL in DB_URL.
// Example:
// mysql://user:pass@host:12087/defaultdb?ssl-mode=REQUIRED
$dsnUrl = getenv('DB_URL') ?: null;

if ($dsnUrl) {
    $parts = parse_url($dsnUrl);
    if (!empty($parts['host']) && !empty($parts['user']) && !empty($parts['pass'])) {
        $config['host'] = $config['host'] ?: $parts['host'];
        $config['username'] = $config['username'] ?: $parts['user'];
        $config['password'] = $config['password'] ?: $parts['pass'];

        // Path format: /defaultdb
        $path = $parts['path'] ?? '';
        $dbName = ltrim($path, '/');
        $config['dbname'] = $config['dbname'] ?: $dbName;
    }
}

// Final fallbacks (local dev)
$config = [
    'host'     => $config['host'] ?: 'localhost',
    'dbname'   => $config['dbname'] ?: 'medicare_db',
    'username' => $config['username'] ?: 'root',
    'password' => $config['password'] ?: '',
];



function getDB(): PDO {
    global $config;

    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        // Log the real error server-side; never expose it to the client
        error_log('Database connection failed: ' . $e->getMessage());
        http_response_code(503);
        die('Service temporarily unavailable. Please try again later.');
    }
}
