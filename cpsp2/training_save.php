<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/training_constants.php';

require_login();
ensure_session_user_type($pdo);

if (!is_trainee()) {
    http_response_code(403);
    exit('Access denied.');
}

// Get active program from POST (hidden field) or session fallback
$postProg = isset($_POST['fcps_program']) ? trim((string) $_POST['fcps_program']) : '';
$program = in_array($postProg, ['urogyn', 'obgyn'], true)
    ? $postProg
    : (string) ($_SESSION['active_program'] ?? 'urogyn');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php?tab=training&sub=add&program=' . $program);
    exit;
}

$token = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : '';
if (!csrf_verify($token)) {
    $_SESSION['form_errors'] = ['Invalid session. Please try again.'];
    header('Location: dashboard.php?tab=training&sub=add&program=' . $program);
    exit;
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

$errors = [];

// Form Type
$formType = isset($_POST['form_type']) ? trim((string)$_POST['form_type']) : '';
if (!array_key_exists($formType, training_form_type_options($program))) {
    $errors[] = 'Please select a valid Form Type.';
}

// Hospital Reg No
$hosptRegNo = isset($_POST['hospt_reg_no']) ? trim((string)$_POST['hospt_reg_no']) : '';
if ($hosptRegNo === '') {
    $errors[] = 'Hospital Registration Number is required.';
}

// Date of Admission
$dateOfAdmissionRaw = isset($_POST['date_of_admission']) ? trim((string)$_POST['date_of_admission']) : '';
$dateOfAdmission = parse_dmy_to_sql_date($dateOfAdmissionRaw);
if ($dateOfAdmission === null) {
    $errors[] = 'Please enter a valid Date of Admission (DD-MM-YYYY).';
}

// Gender
$ptGender = isset($_POST['pt_gender']) ? trim((string)$_POST['pt_gender']) : '';
if (!in_array($ptGender, ['Male', 'Female'], true)) {
    $errors[] = 'Please select a valid patient gender.';
}

// Age
$ptAge = isset($_POST['pt_age']) ? trim((string)$_POST['pt_age']) : '';
$ptAgeType = isset($_POST['pt_age_type']) ? trim((string)$_POST['pt_age_type']) : 'Year[s]';
if ($ptAge === '' || !is_numeric($ptAge)) {
    $errors[] = 'Patient Age must be a valid number.';
}
if (!in_array($ptAgeType, ['Year[s]', 'Month[s]', 'Week[s]', 'Day[s]'], true)) {
    $errors[] = 'Please select a valid patient age type.';
}

// Diagnosis
$ptDiagnosis = isset($_POST['pt_diagnosis']) ? trim((string)$_POST['pt_diagnosis']) : '';
if ($ptDiagnosis === '') {
    $errors[] = 'Diagnosis / Suspected Diagnosis is required.';
}

// Under supervision
$underSupName = isset($_POST['under_sup_name']) ? trim((string)$_POST['under_sup_name']) : '';

// Level
$levelId = isset($_POST['level_id']) ? trim((string)$_POST['level_id']) : '';
if ($levelId !== '' && !array_key_exists($levelId, training_level_options())) {
    $errors[] = 'Please select a valid Level.';
}

// Outcome
$outcomeId = isset($_POST['outcome_id']) ? trim((string)$_POST['outcome_id']) : '';
if ($outcomeId !== '' && !array_key_exists($outcomeId, training_outcome_options())) {
    $errors[] = 'Please select a valid Outcome.';
}

// Brief Description
$briefDesc = isset($_POST['brief_desc']) ? trim((string)$_POST['brief_desc']) : '';
if ($briefDesc === '') {
    $errors[] = 'Brief Description is required.';
}

// Program
$entryForProgId = isset($_POST['entry_for_prog_id']) ? trim((string)$_POST['entry_for_prog_id']) : '';
if (!array_key_exists($entryForProgId, training_program_options())) {
    $errors[] = 'Please select a valid Program.';
}

// Alternate Procedure & Competency
$altProcedure = isset($_POST['alt_procedure']) ? trim((string)$_POST['alt_procedure']) : '';
$comIds = int_array_from_post('com_id');
$comDetailIds = int_array_from_post('com_detail_id');

if ($program === 'urogyn') {
    if ($altProcedure === '') {
        $errors[] = 'Alternate Competancy Group(s) and Details is required.';
    }
} else {
    if ($comIds === [] && $altProcedure === '') {
        $errors[] = 'Please select at least one Competency Group or provide an Alternate Competency Group.';
    }
}

// Send to Supervisor / status
$stdPost = isset($_POST['std_post']) ? trim((string)$_POST['std_post']) : 'No';
if (!in_array($stdPost, ['Yes', 'No'], true)) {
    $errors[] = 'Invalid choice for Send to Supervisor.';
}

if ($errors !== []) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_old'] = $_POST;
    header('Location: dashboard.php?tab=training&sub=add&program=' . $program);
    exit;
}

$userId = (int) $_SESSION['user_id'];
$entryStatus = ($stdPost === 'Yes') ? 'Awaiting Approval' : 'Draft';

$trainingTable = ($program === 'obgyn') ? 'tainingobs_entries' : 'traninguro_entries';

$stmt = $pdo->prepare(
    "INSERT INTO {$trainingTable} (
        user_id, form_type, hospt_reg_no, date_of_admission, pt_gender, pt_age, pt_age_type,
        pt_diagnosis, under_sup_name, level_id, outcome_id, brief_desc, entry_for_prog_id,
        com_ids, com_detail_ids, alt_procedure, std_post, entry_status, fcps_program
    ) VALUES (
        :uid, :ft, :hr, :da, :pg, :pa, :pat,
        :pd, :us, :lv, :oc, :bd, :prog_id,
        :cids, :cdids, :alt, :sp, :st, :prog
    )"
);

try {
    $stmt->execute([
        ':uid'     => $userId,
        ':ft'      => $formType,
        ':hr'      => $hosptRegNo,
        ':da'      => $dateOfAdmission,
        ':pg'      => $ptGender,
        ':pa'      => $ptAge,
        ':pat'     => $ptAgeType,
        ':pd'      => $ptDiagnosis,
        ':us'      => $underSupName,
        ':lv'      => $levelId,
        ':oc'      => $outcomeId,
        ':bd'      => $briefDesc,
        ':prog_id' => $entryForProgId,
        ':cids'    => json_encode($comIds),
        ':cdids'   => json_encode($comDetailIds),
        ':alt'     => $altProcedure,
        ':sp'      => $stdPost,
        ':st'      => $entryStatus,
        ':prog'    => $program,
    ]);
} catch (PDOException $e) {
    $_SESSION['form_errors'] = ['Could not save entry. Database error: ' . $e->getMessage()];
    $_SESSION['form_old'] = $_POST;
    header('Location: dashboard.php?tab=training&sub=add&program=' . $program);
    exit;
}

$_SESSION['flash_ok'] = 'Training entry created successfully.';
header('Location: dashboard.php?tab=training&sub=list&program=' . $program);
exit;
