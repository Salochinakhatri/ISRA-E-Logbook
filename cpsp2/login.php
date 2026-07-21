<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$csrf = $_POST['csrf_token'] ?? '';
if (!is_string($csrf) || !hash_equals($_SESSION['csrf_token'] ?? '', $csrf)) {
    $_SESSION['login_error'] = 'Invalid session. Please refresh the page and try again.';
    header('Location: index.php');
    exit;
}

$userTypeId = isset($_POST['user_type_id']) ? (int) $_POST['user_type_id'] : 0;
$username = isset($_POST['username']) ? trim((string) $_POST['username']) : '';
$password = isset($_POST['password']) ? (string) $_POST['password'] : '';
$remember = !empty($_POST['remember_me']);

if ($userTypeId <= 0) {
    $_SESSION['login_error'] = 'Please select a user type.';
    header('Location: index.php');
    exit;
}

if ($username === '') {
    $_SESSION['login_error'] = 'Please enter your username.';
    header('Location: index.php');
    exit;
}

if ($password === '') {
    $_SESSION['login_error'] = 'Please enter your password.';
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare(
    'SELECT u.id, u.user_type_id, u.username, u.email, u.password, ut.name AS type_name
     FROM users u
     INNER JOIN user_types ut ON ut.id = u.user_type_id
     WHERE u.username = :username AND u.user_type_id = :tid
     LIMIT 1'
);
$stmt->execute([':username' => $username, ':tid' => $userTypeId]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['login_error'] = 'Invalid username, password, or user type.';
    header('Location: index.php');
    exit;
}

session_regenerate_id(true);

$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['user_type_id'] = (int) $user['user_type_id'];
$_SESSION['username'] = $user['username'];
$_SESSION['email'] = $user['email'];
$_SESSION['user_type'] = $user['type_name'];

if ($remember) {
    $token = bin2hex(random_bytes(32));
    $upd = $pdo->prepare('UPDATE users SET remember_token = :t WHERE id = :id');
    $upd->execute([':t' => $token, ':id' => $user['id']]);
    setcookie('cpsp_remember', $token, [
        'expires' => time() + 60 * 60 * 24 * 30,
        'path' => app_cookie_path(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
} else {
    $pdo->prepare('UPDATE users SET remember_token = NULL WHERE id = :id')->execute([':id' => $user['id']]);
    setcookie('cpsp_remember', '', [
        'expires' => time() - 3600,
        'path' => app_cookie_path(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

header('Location: dashboard.php');
exit;
