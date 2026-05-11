<?php

/**
 * Returns a PDO connection to the MySQL database.
 * Credentials are read from the $config array defined below.
 * PDO error mode is set to ERRMODE_EXCEPTION.
 */

$config = [
    'host'     => getenv('DB_HOST') ?: 'localhost',
    'dbname'   => getenv('DB_NAME') ?: 'medicare_db',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: '',
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
