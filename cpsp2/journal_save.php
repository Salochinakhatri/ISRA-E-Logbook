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
    header('Location: dashboard.php?tab=journal&sub=add');
    exit;
}

/* CSRF */
$token = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : '';
if (!csrf_verify($token)) {
    $_SESSION['form_errors'] = ['Invalid session. Please try again.'];
    header('Location: dashboard.php?tab=journal&sub=add');
    exit;
}

/* ---------- helpers ---------- */
function jrnl_parse_dmy(?string $s): ?string
{
    if ($s === null || trim($s) === '') {
        return null;
    }
    if (!preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', trim($s), $m)) {
        return null;
    }
    if (!checkdate((int) $m[2], (int) $m[1], (int) $m[3])) {
        return null;
    }
    return $m[3] . '-' . $m[2] . '-' . $m[1];
}

$program = $_SESSION['active_program'] ?? 'urogyn';
$userId = (int) $_SESSION['user_id'];
$entryStatus = 'Draft';

$stmt = $pdo->prepare(
    'INSERT INTO journal_entries (
        user_id, ref_of_art_disc, fcps_program, entry_status
    ) VALUES (
        :uid, :ref, :prog, :st
    )'
);

try {
    $stmt->execute([
        ':uid'   => $userId,
        ':ref'   => 'Draft Entry',
        ':prog'  => $program,
        ':st'    => $entryStatus,
    ]);
} catch (PDOException $e) {
    $_SESSION['form_errors'] = ['Could not save entry. Database error: ' . $e->getMessage()];
    header('Location: dashboard.php?tab=journal&sub=add');
    exit;
}

$_SESSION['flash_ok'] = 'Journal Club entry draft created successfully.';
header('Location: dashboard.php?tab=journal&sub=list');
exit;
