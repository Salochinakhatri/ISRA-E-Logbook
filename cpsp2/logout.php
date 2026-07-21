<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/db.php';

$userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;

if ($userId > 0) {
    $stmt = $pdo->prepare('UPDATE users SET remember_token = NULL WHERE id = :id');
    $stmt->execute([':id' => $userId]);
}

setcookie('cpsp_remember', '', [
    'expires' => time() - 3600,
    'path' => app_cookie_path(),
    'httponly' => true,
    'samesite' => 'Lax',
]);

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}

session_destroy();

header('Location: index.php');
exit;
