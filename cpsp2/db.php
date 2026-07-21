<?php

declare(strict_types=1);

/**
 * PDO connection for CPSP ePortal (XAMPP / WAMP / Laragon).
 * Adjust credentials if your MySQL user differs.
 */
$DB_HOST = '127.0.0.1';
$DB_NAME = 'gynae&obs';
$DB_USER = 'root';
$DB_PASS = '';
$DB_CHARSET = 'utf8mb4';

$dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset={$DB_CHARSET}";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection failed. Check db.php settings and import database.sql.');
}

/**
 * Cookie path for “Remember me” (works when the app lives in a subfolder, e.g. /cpsp1/).
 */
function app_cookie_path(): string
{
    if (PHP_SAPI === 'cli') {
        return '/';
    }
    $dir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    $dir = str_replace('\\', '/', (string) $dir);
    if ($dir === '' || $dir === '/' || $dir === '.') {
        return '/';
    }

    return rtrim($dir, '/') . '/';
}
