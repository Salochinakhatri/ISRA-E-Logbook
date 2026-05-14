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
    header('Location: dashboard.php?tab=training&sub=add');
    exit;
}

$token = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : '';
if (!csrf_verify($token)) {
    $_SESSION['form_errors'] = ['Invalid session. Please try again.'];
    header('Location: dashboard.php?tab=training&sub=add');
    exit;
}

function parse_dmy_to_sql(?string $s): ?string
{
    if ($s === null || trim($s) === '') {
        return null;
    }
    if (!preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', trim($s), $m)) {
        return null;
    }
    $d = $m[1];
    $mo = $m[2];
    $y = $m[3];
    if (!checkdate((int) $mo, (int) $d, (int) $y)) {
        return null;
    }

    return $y . '-' . $mo . '-' . $d;
}

function int_array_from_post(string $key): array
{
    $raw = $_POST[$key] ?? [];
    if (!is_array($raw)) {
        return [];
    }
    $out = [];
    foreach ($raw as $v) {
        if (is_string($v) || is_int($v)) {
            $n = (int) $v;
            if ($n > 0) {
                $out[] = $n;
            }
        }
    }

    return array_values(array_unique($out));
}

$formType = trim((string) ($_POST['form_type'] ?? ''));
$hospt = trim((string) ($_POST['hospt_reg_no'] ?? ''));
$admRaw = trim((string) ($_POST['date_of_admission'] ?? ''));
$gender = trim((string) ($_POST['pt_gender'] ?? ''));
$age = trim((string) ($_POST['pt_age'] ?? ''));
$ageType = trim((string) ($_POST['pt_age_type'] ?? 'Year[s]'));
$diagnosis = trim((string) ($_POST['pt_diagnosis'] ?? ''));
$underSup = trim((string) ($_POST['under_sup_name'] ?? ''));
$levelId = trim((string) ($_POST['level_id'] ?? ''));
$outcomeId = trim((string) ($_POST['outcome_id'] ?? ''));
$brief = (string) ($_POST['brief_desc'] ?? '');
$progId = trim((string) ($_POST['entry_for_prog_id'] ?? ''));
$alt = trim((string) ($_POST['alt_procedure'] ?? ''));
$stdPost = trim((string) ($_POST['std_post'] ?? 'No'));

$comIds       = int_array_from_post('com_id');
$comDetailIds = int_array_from_post('com_detail_id');

$errors = [];

if ($formType === '') {
    $errors[] = 'Form Type is required.';
}
if ($hospt === '') {
    $errors[] = 'Hospital registration number is required.';
}
$admSql = parse_dmy_to_sql($admRaw);
if ($admSql === null) {
    $errors[] = 'Date of Admission must be valid (dd-mm-yyyy).';
}
if ($gender === '') {
    $errors[] = 'Gender is required.';
}
if ($age === '') {
    $errors[] = 'Age is required.';
}
if ($diagnosis === '') {
    $errors[] = 'Diagnosis is required.';
}
if (trim(strip_tags($brief)) === '') {
    $errors[] = 'Brief Description is required.';
}
if ($progId === '') {
    $errors[] = 'Program is required.';
}
if ($comIds === [] && $comDetailIds === []) {
    $errors[] = 'Please select at least one competency group or detail.';
}

if ($errors !== []) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_old'] = $_POST;
    header('Location: dashboard.php?tab=training&sub=add');
    exit;
}

$entryStatus = 'Draft';
if ($stdPost === 'Yes') {
    $entryStatus = 'Awaiting Approval';
}

$userId = (int) $_SESSION['user_id'];

$stmt = $pdo->prepare(
    'INSERT INTO training_entries (
        user_id, form_type, hospt_reg_no, date_of_admission, pt_gender, pt_age, pt_age_type,
        pt_diagnosis, under_sup_name, level_id, outcome_id, brief_desc, entry_for_prog_id,
        com_ids, com_detail_ids, alt_procedure, std_post, entry_status
    ) VALUES (
        :uid, :ft, :hrn, :doa, :gen, :age, :aget,
        :dx, :usup, :lid, :oid, :bd, :pid,
        :cids, :cdids, :alt, :sp, :st
    )'
);

try {
    $stmt->execute([
    ':uid'   => $userId,
    ':ft'    => $formType,
    ':hrn'   => $hospt,
    ':doa'   => $admSql,
    ':gen'   => $gender,
    ':age'   => $age,
    ':aget'  => $ageType,
    ':dx'    => $diagnosis,
    ':usup'  => $underSup,
    ':lid'   => $levelId,
    ':oid'   => $outcomeId,
    ':bd'    => $brief,
    ':pid'   => $progId,
    ':cids'  => json_encode($comIds,       JSON_THROW_ON_ERROR),
    ':cdids' => json_encode($comDetailIds, JSON_THROW_ON_ERROR),
    ':alt'   => $alt,
    ':sp'    => $stdPost,
    ':st'    => $entryStatus,
]);
} catch (PDOException $e) {
    $_SESSION['form_errors'] = ['Could not save entry. Check that the training_entries table exists (import database.sql or migrate_training_entries.sql).'];
    $_SESSION['form_old'] = $_POST;
    header('Location: dashboard.php?tab=training&sub=add');
    exit;
}

$_SESSION['flash_ok'] = 'Training entry saved successfully.';
header('Location: dashboard.php?tab=training&sub=list');
exit;

