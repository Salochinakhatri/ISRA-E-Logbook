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
    header('Location: dashboard.php?tab=presented&sub=add');
    exit;
}

/* CSRF */
$token = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : '';
if (!csrf_verify($token)) {
    $_SESSION['form_errors'] = ['Invalid session. Please try again.'];
    header('Location: dashboard.php?tab=presented&sub=add');
    exit;
}

/* ---------- helpers ---------- */
function pres_parse_dmy(?string $s): ?string
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

/* ---------- collect fields ---------- */
$dateRaw   = trim((string) ($_POST['rec_date']  ?? ''));
$title     = trim((string) ($_POST['rec_title'] ?? ''));
$venue     = trim((string) ($_POST['rec_venue'] ?? ''));
$confName  = trim((string) ($_POST['conf_name'] ?? ''));
$stdPost   = trim((string) ($_POST['std_post']  ?? 'No'));

/* ---------- validate ---------- */
$errors  = [];
$dateSql = pres_parse_dmy($dateRaw);

if ($dateSql === null) { $errors[] = 'Presented Date must be valid (dd-mm-yyyy).'; }
if ($title === '')     { $errors[] = 'Title is required.'; }
if ($venue === '')     { $errors[] = 'Venue is required.'; }
if ($confName === '')  { $errors[] = 'Name Of Conf. / Seminar / Symposium is required.'; }

if ($errors !== []) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_old']    = $_POST;
    header('Location: dashboard.php?tab=presented&sub=add');
    exit;
}

/* ---------- save ---------- */
$entryStatus = ($stdPost === 'Yes') ? 'Awaiting Approval' : 'Draft';
$userId      = (int) $_SESSION['user_id'];

$stmt = $pdo->prepare(
    'INSERT INTO presented_entries (user_id, rec_date, rec_title, rec_venue, conf_name, std_post, entry_status)
     VALUES (:uid, :rd, :rt, :rv, :cn, :sp, :st)'
);

try {
    $stmt->execute([
        ':uid' => $userId,
        ':rd'  => $dateSql,
        ':rt'  => $title,
        ':rv'  => $venue,
        ':cn'  => $confName,
        ':sp'  => $stdPost,
        ':st'  => $entryStatus,
    ]);
} catch (PDOException $e) {
    $_SESSION['form_errors'] = [
        'Could not save entry. Make sure the presented_entries table exists (run migrate_presented_entries.sql).'
    ];
    $_SESSION['form_old'] = $_POST;
    header('Location: dashboard.php?tab=presented&sub=add');
    exit;
}

$_SESSION['flash_ok'] = 'Paper/Poster Presented entry saved successfully.';
header('Location: dashboard.php?tab=presented&sub=list');
exit;
