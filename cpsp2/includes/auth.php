<?php

declare(strict_types=1);

function require_login(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }
}

function is_trainee(): bool
{
    return (int) ($_SESSION['user_type_id'] ?? 0) === 1;
}

function ensure_session_user_type(PDO $pdo): void
{
    if (!empty($_SESSION['user_type_id']) || empty($_SESSION['user_id'])) {
        return;
    }
    $stmt = $pdo->prepare('SELECT user_type_id FROM users WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => (int) $_SESSION['user_id']]);
    $row = $stmt->fetch();
    if ($row) {
        $_SESSION['user_type_id'] = (int) $row['user_type_id'];
    }
}
