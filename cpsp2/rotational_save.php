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
    header('Location: dashboard.php?tab=rotational&sub=add');
    exit;
}

$token = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : '';
if (!csrf_verify($token)) {
    $_SESSION['form_errors'] = ['Invalid session. Please try again.'];
    header('Location: dashboard.php?tab=rotational&sub=add');
    exit;
}

/* ---------- helpers ---------- */
function rot_parse_dmy(?string $s): ?string
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

function rot_int_array(string $key): array
{
    $raw = $_POST[$key] ?? [];
    if (!is_array($raw)) {
        return [];
    }
    $out = [];
    foreach ($raw as $v) {
        $n = (int) $v;
        if ($n > 0) {
            $out[] = $n;
        }
    }
    return array_values(array_unique($out));
}

$program = $_SESSION['active_program'] ?? 'urogyn';
$userId = (int) $_SESSION['user_id'];
$entryStatus = 'Draft';

$stmt = $pdo->prepare(
    'INSERT INTO rotational_entries (
        user_id, brief_desc, fcps_program, entry_status
    ) VALUES (
        :uid, :bd, :prog, :st
    )'
);

try {
    $stmt->execute([
        ':uid'   => $userId,
        ':bd'    => 'Draft Entry',
        ':prog'  => $program,
        ':st'    => $entryStatus,
    ]);
} catch (PDOException $e) {
    $_SESSION['form_errors'] = ['Could not save entry. Database error: ' . $e->getMessage()];
    header('Location: dashboard.php?tab=rotational&sub=add');
    exit;
}

$_SESSION['flash_ok'] = 'Rotational training entry draft created successfully.';
header('Location: dashboard.php?tab=rotational&sub=list');
exit;
