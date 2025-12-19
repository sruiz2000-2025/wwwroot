<?php
declare(strict_types=1);

//
/**
 * Database connection (PDO).
 * Fill DB_NAME and DB_PASS, or use environment variables.
 */

$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_PORT = getenv('DB_PORT') ?: '3306';
$DB_NAME = getenv('DB_NAME') ?: '2025';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: 'Redlands@10';

$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $DB_HOST, $DB_PORT, $DB_NAME);

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (Throwable $e) {
    // Allow the site to run without DB (fallback mode)
        

    $pdo = null;
}

