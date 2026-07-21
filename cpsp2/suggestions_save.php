<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';

require_login();
ensure_session_user_type($pdo);

if (!is_trainee()) {
    http_response_code(403);
    exit('Access denied.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php?tab=suggestions');
    exit;
}

/* CSRF */
$token = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : '';
if (!csrf_verify($token)) {
    $_SESSION['form_errors'] = ['Invalid session. Please try again.'];
    header('Location: dashboard.php?tab=suggestions');
    exit;
}

$program = isset($_POST['program']) ? trim((string)$_POST['program']) : '';
$suggestionText = isset($_POST['suggestion_text']) ? trim((string)$_POST['suggestion_text']) : '';

$errors = [];
if (!in_array($program, ['urogyn', 'obgyn'], true)) {
    $errors[] = 'A valid program must be active.';
}
if ($suggestionText === '') {
    $errors[] = 'Suggestion or feedback content cannot be empty.';
}

if ($errors !== []) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_old'] = $_POST;
    header('Location: dashboard.php?tab=suggestions');
    exit;
}

$userId = (int) $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare(
        'INSERT INTO suggestions (user_id, program, suggestion_text) 
         VALUES (:uid, :prog, :txt)'
    );
    $stmt->execute([
        ':uid'  => $userId,
        ':prog' => $program,
        ':txt'  => $suggestionText
    ]);
    $_SESSION['flash_ok'] = 'Your suggestion has been submitted successfully.';
} catch (PDOException $e) {
    $_SESSION['form_errors'] = ['Could not save suggestion. Database error: ' . $e->getMessage()];
    $_SESSION['form_old'] = $_POST;
}

header('Location: dashboard.php?tab=suggestions');
exit;
