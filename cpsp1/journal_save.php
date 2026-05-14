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

/* ---------- collect fields ---------- */
$dateRaw  = trim((string) ($_POST['date_of_diss']    ?? ''));
$facBy    = trim((string) ($_POST['fac_by']          ?? ''));
$refArt   = (string)       ($_POST['ref_of_art_disc'] ?? '');
$stdPost  = trim((string) ($_POST['std_post']        ?? 'No'));

/* ---------- validate ---------- */
$errors  = [];
$dateSql = jrnl_parse_dmy($dateRaw);

if ($dateSql === null)               { $errors[] = 'Date of Discussion must be valid (dd-mm-yyyy).'; }
if ($facBy === '')                   { $errors[] = 'Facilitated by is required.'; }
if (trim(strip_tags($refArt)) === '') { $errors[] = 'Full Reference of the Article Discussed is required.'; }

if ($errors !== []) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_old']    = $_POST;
    header('Location: dashboard.php?tab=journal&sub=add');
    exit;
}

/* ---------- save ---------- */
$entryStatus = ($stdPost === 'Yes') ? 'Awaiting Approval' : 'Draft';
$userId      = (int) $_SESSION['user_id'];

$stmt = $pdo->prepare(
    'INSERT INTO journal_entries (user_id, date_of_diss, fac_by, ref_of_art_disc, std_post, entry_status)
     VALUES (:uid, :dod, :fac, :ref, :sp, :st)'
);

try {
    $stmt->execute([
        ':uid' => $userId,
        ':dod' => $dateSql,
        ':fac' => $facBy,
        ':ref' => $refArt,
        ':sp'  => $stdPost,
        ':st'  => $entryStatus,
    ]);
} catch (PDOException $e) {
    $_SESSION['form_errors'] = [
        'Could not save entry. Make sure the journal_entries table exists (run migrate_journal_entries.sql).'
    ];
    $_SESSION['form_old'] = $_POST;
    header('Location: dashboard.php?tab=journal&sub=add');
    exit;
}

$_SESSION['flash_ok'] = 'Journal Club entry saved successfully.';
header('Location: dashboard.php?tab=journal&sub=list');
exit;
