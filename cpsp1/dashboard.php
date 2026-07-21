<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/training_constants.php';

require_login();
ensure_session_user_type($pdo);

$userId = (int) $_SESSION['user_id'];
$stmt = $pdo->prepare(
    'SELECT u.username, u.email, ut.name AS type_name,
            \'\' AS full_name,
            \'\' AS phone,
            \'\' AS bio,
            \'\' AS profile_image,
            NULL AS profile_updated_at
     FROM users u
     INNER JOIN user_types ut ON ut.id = u.user_type_id
     WHERE u.id = :id
     LIMIT 1'
);
$stmt->execute([':id' => $userId]);
$me = $stmt->fetch();

if (!$me) {
    session_destroy();
    header('Location: index.php');
    exit;
}

$displayName = htmlspecialchars($me['username'], ENT_QUOTES, 'UTF-8');
$displayType = htmlspecialchars($me['type_name'], ENT_QUOTES, 'UTF-8');
$displayEmail = htmlspecialchars($me['email'], ENT_QUOTES, 'UTF-8');
$displayProfileImage = (string) ($me['profile_image'] ?? '');

$isTrainee = is_trainee();

if (!$isTrainee) {
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Isra ePortal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="style.css">
</head>
<body class="page-dashboard">
    <header class="dash-topbar">
        <div class="dash-brand">
            <img src="assets/images/logo.png" alt="" class="dash-brand__logo" width="40" height="40">
            <span class="dash-brand__text">Isra ePortal <small>e-Log Book</small></span>
        </div>
        <nav class="dash-nav">
            <a href="dashboard.php" class="dash-nav__link active"><i class="fa-solid fa-house"></i> Home</a>
            <a href="logout.php" class="dash-nav__link dash-nav__logout js-logout-confirm"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </nav>
    </header>

    <main class="dash-main">
        <div class="dash-card">
            <h1 class="dash-title">Welcome</h1>
            <p class="dash-lead">You are signed in to the e-Log Book portal.</p>
            <dl class="dash-meta">
                <div>
                    <dt><i class="fa-solid fa-user"></i> Username</dt>
                    <dd><?= $displayName ?></dd>
                </div>
                <div>
                    <dt><i class="fa-solid fa-id-badge"></i> User type</dt>
                    <dd><?= $displayType ?></dd>
                </div>
                <div>
                    <dt><i class="fa-solid fa-envelope"></i> Email</dt>
                    <dd><?= $displayEmail ?></dd>
                </div>
            </dl>
        </div>
    </main>

    <button type="button" class="scroll-top" id="scrollTop" aria-label="Scroll to top">
        <i class="fa-solid fa-chevron-up"></i>
    </button>

    <script src="script.js"></script>
</body>
</html>
    <?php
    exit;
}

$validTabs = ['home', 'training', 'rotational', 'journal', 'presented', 'published', 'reports'];
$tab = isset($_GET['tab']) ? (string) $_GET['tab'] : 'training';
if (!in_array($tab, $validTabs, true)) {
    $tab = 'training';
}

$sub = isset($_GET['sub']) ? (string) $_GET['sub'] : 'list';
$validSubs = ['list', 'add'];
if ($tab === 'training') {
    $validSubs[] = 'view';
}
if (!in_array($sub, $validSubs, true)) {
    $sub = 'list';
}

$flashOk = isset($_SESSION['flash_ok']) ? (string) $_SESSION['flash_ok'] : null;
unset($_SESSION['flash_ok']);

$formErrors = isset($_SESSION['form_errors']) && is_array($_SESSION['form_errors']) ? $_SESSION['form_errors'] : [];
unset($_SESSION['form_errors']);
$formOld = isset($_SESSION['form_old']) && is_array($_SESSION['form_old']) ? $_SESSION['form_old'] : [];
unset($_SESSION['form_old']);
$profileErrors = isset($_SESSION['profile_errors']) && is_array($_SESSION['profile_errors']) ? $_SESSION['profile_errors'] : [];
unset($_SESSION['profile_errors']);
$profileOld = isset($_SESSION['profile_old']) && is_array($_SESSION['profile_old']) ? $_SESSION['profile_old'] : [];
unset($_SESSION['profile_old']);

$trainingListError = null;

$pageTitle = 'Isra e-Logbook';
$mainTitle = 'Training';
if ($tab === 'home') {
    $pageTitle = 'Home | Isra e-Logbook';
    $mainTitle = 'Home';
} elseif ($tab === 'training' && $sub === 'add') {
    $pageTitle = 'Add Training Entry | Isra e-Logbook';
    $mainTitle = 'Add Training Entry';  
} elseif ($tab === 'training' && $sub === 'view') {
    $pageTitle = 'View Training Entry | Isra e-Logbook';
    $mainTitle = 'View Training Entry';
} elseif ($tab === 'rotational' && $sub === 'list') {
    $pageTitle = 'Rotational Training | Isra e-Logbook';
    $mainTitle = 'Rotational Training';
} elseif ($tab === 'rotational' && $sub === 'add') {
    $pageTitle = 'Add Rotational Training | Isra e-Logbook';
    $mainTitle = 'Add Rotational Training';
} elseif ($tab === 'journal' && $sub === 'list') {
    $pageTitle = 'Journal Club | Isra e-Logbook';
    $mainTitle = 'Journal Club';
} elseif ($tab === 'journal' && $sub === 'add') {
    $pageTitle = 'Add Journal Club Entry | Isra e-Logbook';
    $mainTitle = 'Add Journal Club Entry';
} elseif ($tab === 'presented' && $sub === 'list') {
    $pageTitle = 'Paper/Poster Presented | Isra e-Logbook';
    $mainTitle = 'Paper/Poster Presented';
} elseif ($tab === 'presented' && $sub === 'add') {
    $pageTitle = 'Add Paper/Poster Presented | Isra e-Logbook';
    $mainTitle = 'Add Paper/Poster Presented';
} elseif ($tab === 'published' && $sub === 'list') {
    $pageTitle = 'Paper Published | Isra e-Logbook';
    $mainTitle = 'Paper Published';
} elseif ($tab === 'published' && $sub === 'add') {
    $pageTitle = 'Add Paper Published | Isra e-Logbook';
    $mainTitle = 'Add Paper Published';
} elseif ($tab === 'reports' && $sub === 'list') {
    $pageTitle = 'Reports | Isra e-Logbook';
    $mainTitle = 'Reports';
} elseif ($tab === 'reports' && $sub === 'add') {
    $pageTitle = 'Add Report | Isra e-Logbook';
    $mainTitle = 'Add Report';
}

$entries = [];
$filters = [
    'status' => isset($_GET['f_status']) ? (string) $_GET['f_status'] : '',
    'level' => isset($_GET['f_level']) ? (string) $_GET['f_level'] : '',
    'post_from' => isset($_GET['f_post_from']) ? (string) $_GET['f_post_from'] : '',
    'post_to' => isset($_GET['f_post_to']) ? (string) $_GET['f_post_to'] : '',
    'adm_from' => isset($_GET['f_adm_from']) ? (string) $_GET['f_adm_from'] : '',
    'adm_to' => isset($_GET['f_adm_to']) ? (string) $_GET['f_adm_to'] : '',
    'reg' => isset($_GET['f_reg']) ? trim((string) $_GET['f_reg']) : '',
];

$lastEntryLabel = null;
$compMap = training_competency_label_map();
$viewEntry = null;

if ($tab === 'training' && $sub === 'list') {
    $sql = 'SELECT * FROM training_entries WHERE user_id = :uid';
    $params = [':uid' => $userId];

    if ($filters['status'] !== '') {
        $sql .= ' AND entry_status = :st';
        $params[':st'] = $filters['status'];
    }
    if ($filters['level'] !== '') {
        $sql .= ' AND level_id = :lv';
        $params[':lv'] = $filters['level'];
    }
    $pf = parse_dmy_to_sql_date($filters['post_from']);
    if ($pf !== null) {
        $sql .= ' AND DATE(created_at) >= :pf';
        $params[':pf'] = $pf;
    }
    $pt = parse_dmy_to_sql_date($filters['post_to']);
    if ($pt !== null) {
        $sql .= ' AND DATE(created_at) <= :pt';
        $params[':pt'] = $pt;
    }
    $af = parse_dmy_to_sql_date($filters['adm_from']);
    if ($af !== null) {
        $sql .= ' AND date_of_admission >= :af';
        $params[':af'] = $af;
    }
    $at = parse_dmy_to_sql_date($filters['adm_to']);
    if ($at !== null) {
        $sql .= ' AND date_of_admission <= :at';
        $params[':at'] = $at;
    }
    if ($filters['reg'] !== '') {
        $sql .= ' AND hospt_reg_no LIKE :rg';
        $params[':rg'] = '%' . $filters['reg'] . '%';
    }

    $sql .= ' ORDER BY created_at DESC, id DESC';

    try {
        $q = $pdo->prepare($sql);
        $q->execute($params);
        $entries = $q->fetchAll() ?: [];
    } catch (PDOException $e) {
        $entries = [];
        $trainingListError = 'Could not load training entries. Ensure the database is imported (table training_entries).';
    }

    $mx = $pdo->prepare('SELECT MAX(created_at) AS m FROM training_entries WHERE user_id = :uid');
    try {
        $mx->execute([':uid' => $userId]);
        $rowMx = $mx->fetch();
        if ($rowMx && !empty($rowMx['m'])) {
            $lastEntryLabel = format_last_entry_notice((string) $rowMx['m']);
        }
    } catch (PDOException $e) {
        $lastEntryLabel = null;
    }
}

if ($tab === 'training' && $sub === 'view') {
    $vid = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($vid > 0) {
        $vq = $pdo->prepare('SELECT * FROM training_entries WHERE id = :id AND user_id = :uid LIMIT 1');
        try {
            $vq->execute([':id' => $vid, ':uid' => $userId]);
            $viewEntry = $vq->fetch() ?: null;
        } catch (PDOException $e) {
            $viewEntry = null;
        }
    }
}

/* ---- Rotational Training list ---- */
$rotEntries      = [];
$rotLastLabel    = null;
$rotListError    = null;

if ($tab === 'rotational' && $sub === 'list') {
    $rsql    = 'SELECT * FROM rotational_entries WHERE user_id = :uid';
    $rparams = [':uid' => $userId];

    if ($filters['status'] !== '') {
        $rsql .= ' AND entry_status = :st';
        $rparams[':st'] = $filters['status'];
    }
    if ($filters['level'] !== '') {
        $rsql .= ' AND level_id = :lv';
        $rparams[':lv'] = $filters['level'];
    }
    $rpf = parse_dmy_to_sql_date($filters['post_from']);
    if ($rpf !== null) {
        $rsql .= ' AND DATE(created_at) >= :pf';
        $rparams[':pf'] = $rpf;
    }
    $rpt = parse_dmy_to_sql_date($filters['post_to']);
    if ($rpt !== null) {
        $rsql .= ' AND DATE(created_at) <= :pt';
        $rparams[':pt'] = $rpt;
    }
    $raf = parse_dmy_to_sql_date($filters['adm_from']);
    if ($raf !== null) {
        $rsql .= ' AND date_of_admission >= :af';
        $rparams[':af'] = $raf;
    }
    $rat = parse_dmy_to_sql_date($filters['adm_to']);
    if ($rat !== null) {
        $rsql .= ' AND date_of_admission <= :at';
        $rparams[':at'] = $rat;
    }
    if ($filters['reg'] !== '') {
        $rsql .= ' AND hospt_reg_no LIKE :rg';
        $rparams[':rg'] = '%' . $filters['reg'] . '%';
    }
    $rsql .= ' ORDER BY created_at DESC, id DESC';

    try {
        $rq = $pdo->prepare($rsql);
        $rq->execute($rparams);
        $rotEntries = $rq->fetchAll() ?: [];
    } catch (PDOException $e) {
        $rotEntries   = [];
        $rotListError = 'Could not load rotational entries. Run migrate_rotational_entries.sql to create the table.';
    }

    /* last entry date across BOTH tables */
    try {
        $rmx = $pdo->prepare(
            'SELECT MAX(t) AS m FROM (
                SELECT MAX(created_at) AS t FROM training_entries   WHERE user_id = :u1
                UNION ALL
                SELECT MAX(created_at) AS t FROM rotational_entries WHERE user_id = :u2
            ) combined'
        );
        $rmx->execute([':u1' => $userId, ':u2' => $userId]);
        $rmxRow = $rmx->fetch();
        if ($rmxRow && !empty($rmxRow['m'])) {
            $rotLastLabel = format_last_entry_notice((string) $rmxRow['m']);
        }
    } catch (PDOException $e) {
        $rotLastLabel = null;
    }
}

/* ---- Journal Club list ---- */
$journalEntries   = [];
$journalLastLabel = null;
$journalListError = null;

if ($tab === 'journal' && $sub === 'list') {
    $jsql    = 'SELECT * FROM journal_entries WHERE user_id = :uid';
    $jparams = [':uid' => $userId];

    if ($filters['status'] !== '') {
        $jsql .= ' AND entry_status = :st';
        $jparams[':st'] = $filters['status'];
    }
    $jpf = parse_dmy_to_sql_date($filters['post_from']);
    if ($jpf !== null) {
        $jsql .= ' AND DATE(created_at) >= :pf';
        $jparams[':pf'] = $jpf;
    }
    $jpt = parse_dmy_to_sql_date($filters['post_to']);
    if ($jpt !== null) {
        $jsql .= ' AND DATE(created_at) <= :pt';
        $jparams[':pt'] = $jpt;
    }
    
    // Journal Club specific filters (discussion dates, facilitated by) - assume mapped from existing form if we had them.
    // Assuming filters map: f_disc_from, f_disc_to, f_fac
    $df = isset($_GET['f_disc_from']) ? parse_dmy_to_sql_date($_GET['f_disc_from']) : null;
    if ($df !== null) {
        $jsql .= ' AND date_of_diss >= :df';
        $jparams[':df'] = $df;
    }
    $dt = isset($_GET['f_disc_to']) ? parse_dmy_to_sql_date($_GET['f_disc_to']) : null;
    if ($dt !== null) {
        $jsql .= ' AND date_of_diss <= :dt';
        $jparams[':dt'] = $dt;
    }
    $fac = isset($_GET['f_fac']) ? trim((string) $_GET['f_fac']) : '';
    if ($fac !== '') {
        $jsql .= ' AND fac_by LIKE :fc';
        $jparams[':fc'] = '%' . $fac . '%';
    }

    $jsql .= ' ORDER BY created_at DESC, id DESC';

    try {
        $jq = $pdo->prepare($jsql);
        $jq->execute($jparams);
        $journalEntries = $jq->fetchAll() ?: [];
    } catch (PDOException $e) {
        $journalEntries   = [];
        $journalListError = 'Could not load journal entries. Run migrate_journal_entries.sql to create the table.';
    }

    try {
        $jmx = $pdo->prepare('SELECT MAX(created_at) AS m FROM journal_entries WHERE user_id = :uid');
        $jmx->execute([':uid' => $userId]);
        $jmxRow = $jmx->fetch();
        if ($jmxRow && !empty($jmxRow['m'])) {
            $journalLastLabel = format_last_entry_notice((string) $jmxRow['m']);
        }
    } catch (PDOException $e) {
        $journalLastLabel = null;
    }
}

/* ---- Paper/Poster Presented list ---- */
$presentedEntries   = [];
$presentedLastLabel = null;
$presentedListError = null;

if ($tab === 'presented' && $sub === 'list') {
    $psql    = 'SELECT * FROM presented_entries WHERE user_id = :uid';
    $pparams = [':uid' => $userId];

    if ($filters['status'] !== '') {
        $psql .= ' AND entry_status = :st';
        $pparams[':st'] = $filters['status'];
    }
    $ppf = parse_dmy_to_sql_date($filters['post_from']);
    if ($ppf !== null) {
        $psql .= ' AND DATE(created_at) >= :pf';
        $pparams[':pf'] = $ppf;
    }
    $ppt = parse_dmy_to_sql_date($filters['post_to']);
    if ($ppt !== null) {
        $psql .= ' AND DATE(created_at) <= :pt';
        $pparams[':pt'] = $ppt;
    }
    
    // Presented specific filters: f_pres_from, f_pres_to, f_title, f_venue
    $prf = isset($_GET['f_pres_from']) ? parse_dmy_to_sql_date($_GET['f_pres_from']) : null;
    if ($prf !== null) {
        $psql .= ' AND rec_date >= :prf';
        $pparams[':prf'] = $prf;
    }
    $prt = isset($_GET['f_pres_to']) ? parse_dmy_to_sql_date($_GET['f_pres_to']) : null;
    if ($prt !== null) {
        $psql .= ' AND rec_date <= :prt';
        $pparams[':prt'] = $prt;
    }
    $tit = isset($_GET['f_title']) ? trim((string) $_GET['f_title']) : '';
    if ($tit !== '') {
        $psql .= ' AND rec_title LIKE :tt';
        $pparams[':tt'] = '%' . $tit . '%';
    }
    $ven = isset($_GET['f_venue']) ? trim((string) $_GET['f_venue']) : '';
    if ($ven !== '') {
        $psql .= ' AND rec_venue LIKE :vv';
        $pparams[':vv'] = '%' . $ven . '%';
    }

    $psql .= ' ORDER BY created_at DESC, id DESC';

    try {
        $pq = $pdo->prepare($psql);
        $pq->execute($pparams);
        $presentedEntries = $pq->fetchAll() ?: [];
    } catch (PDOException $e) {
        $presentedEntries   = [];
        $presentedListError = 'Could not load presented entries. Run migrate_presented_entries.sql to create the table.';
    }

    try {
        $pmx = $pdo->prepare('SELECT MAX(created_at) AS m FROM presented_entries WHERE user_id = :uid');
        $pmx->execute([':uid' => $userId]);
        $pmxRow = $pmx->fetch();
        if ($pmxRow && !empty($pmxRow['m'])) {
            $presentedLastLabel = format_last_entry_notice((string) $pmxRow['m']);
        }
    } catch (PDOException $e) {
        $presentedLastLabel = null;
    }
}

/* ---- Paper Published list ---- */
$publishedEntries   = [];
$publishedLastLabel = null;
$publishedListError = null;

if ($tab === 'published' && $sub === 'list') {
    $pbsql    = 'SELECT * FROM published_entries WHERE user_id = :uid';
    $pbparams = [':uid' => $userId];

    if ($filters['status'] !== '') {
        $pbsql .= ' AND entry_status = :st';
        $pbparams[':st'] = $filters['status'];
    }
    $pbpf = parse_dmy_to_sql_date($filters['post_from']);
    if ($pbpf !== null) {
        $pbsql .= ' AND DATE(created_at) >= :pf';
        $pbparams[':pf'] = $pbpf;
    }
    $pbpt = parse_dmy_to_sql_date($filters['post_to']);
    if ($pbpt !== null) {
        $pbsql .= ' AND DATE(created_at) <= :pt';
        $pbparams[':pt'] = $pbpt;
    }
    
    // Published specific filters: f_pub_from, f_pub_to, f_title
    $pbf = isset($_GET['f_pub_from']) ? parse_dmy_to_sql_date($_GET['f_pub_from']) : null;
    if ($pbf !== null) {
        $pbsql .= ' AND pub_date >= :pbf';
        $pbparams[':pbf'] = $pbf;
    }
    $pbt = isset($_GET['f_pub_to']) ? parse_dmy_to_sql_date($_GET['f_pub_to']) : null;
    if ($pbt !== null) {
        $pbsql .= ' AND pub_date <= :pbt';
        $pbparams[':pbt'] = $pbt;
    }
    $tit = isset($_GET['f_title']) ? trim((string) $_GET['f_title']) : '';
    if ($tit !== '') {
        $pbsql .= ' AND pub_title LIKE :tt';
        $pbparams[':tt'] = '%' . $tit . '%';
    }

    $pbsql .= ' ORDER BY created_at DESC, id DESC';

    try {
        $pbq = $pdo->prepare($pbsql);
        $pbq->execute($pbparams);
        $publishedEntries = $pbq->fetchAll() ?: [];
    } catch (PDOException $e) {
        $publishedEntries   = [];
        $publishedListError = 'Could not load published entries. Run migrate_published_entries.sql to create the table.';
    }

    try {
        $pbmx = $pdo->prepare('SELECT MAX(created_at) AS m FROM published_entries WHERE user_id = :uid');
        $pbmx->execute([':uid' => $userId]);
        $pbmxRow = $pbmx->fetch();
        if ($pbmxRow && !empty($pbmxRow['m'])) {
            $publishedLastLabel = format_last_entry_notice((string) $pbmxRow['m']);
        }
    } catch (PDOException $e) {
        $publishedLastLabel = null;
    }
}

$recentActivities = [];
if ($tab === 'home') {
    try {
        $actStmt = $pdo->prepare(
            'SELECT id, pt_diagnosis, hospt_reg_no, entry_status, created_at
             FROM training_entries
             WHERE user_id = :uid
             ORDER BY created_at DESC, id DESC
             LIMIT 5'
        );
        $actStmt->execute([':uid' => $userId]);
        $recentActivities = $actStmt->fetchAll() ?: [];
    } catch (PDOException $e) {
        $recentActivities = [];
    }

    if (!empty($me['profile_updated_at'])) {
        $recentActivities[] = [
            'kind' => 'profile',
            'created_at' => (string) $me['profile_updated_at'],
            'title' => 'Profile information available',
            'subtitle' => 'Your profile details are up to date for supervisors and records.',
        ];
    }
}

$profileViewData = null;

$useRichEditor = ($tab === 'training' && $sub === 'add');
$bodyClass = 'page-elogbook';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="style.css">
    <?php if ($useRichEditor): ?>
        <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js"></script>
    <?php endif; ?>
</head>
<body class="<?= htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8') ?>">
    <div class="elog-layout">
        <aside class="elog-sidebar" id="elogSidebar">
            <div class="elog-sidebar__brand">
                <img src="assets/images/logo.png" alt="logo" class="elog-sidebar__logo" width="36" height="36">
                <span class="elog-sidebar__brand-text">Isra e-Logbook</span>
            </div>
            <nav class="elog-sidebar__nav" aria-label="Main">
                <a class="elog-sidebar__link<?= $tab === 'home' ? ' is-active' : '' ?>" href="dashboard.php?tab=home">
                    <i class="fa-solid fa-house"></i><span>Home</span>
                </a>
                <!-- <a class="elog-sidebar__link<?= $tab === 'profile' ? ' is-active' : '' ?>" href="dashboard.php?tab=profile">
                    <i class="fa-solid fa-user-pen"></i><span>Profile</span>
                </a> -->
                <div class="elog-sidebar__group<?= $tab === 'training' ? ' is-active is-open' : '' ?>">
                    <button type="button" class="elog-sidebar__parent" id="trainingToggle" aria-expanded="<?= $tab === 'training' ? 'true' : 'false' ?>">
                        <span class="elog-sidebar__parent-left"><i class="fa-solid fa-table-cells"></i><span>Training</span></span>
                        <span class="elog-sidebar__chev" aria-hidden="true"><i class="fa-solid fa-chevron-down"></i></span>
                    </button>
                    <div class="elog-sidebar__sub" id="trainingSubnav">
                        <a class="elog-sidebar__sublink<?= $tab === 'training' && $sub === 'list' ? ' is-active' : '' ?>" href="dashboard.php?tab=training&amp;sub=list">List all entries</a>
                        <a class="elog-sidebar__sublink<?= $tab === 'training' && $sub === 'add' ? ' is-active' : '' ?>" href="dashboard.php?tab=training&amp;sub=add">Add new</a>
                    </div>
                </div>
                <div class="elog-sidebar__group<?= $tab === 'rotational' ? ' is-active is-open' : '' ?>">
                    <button type="button" class="elog-sidebar__parent" id="rotationalToggle" aria-expanded="<?= $tab === 'rotational' ? 'true' : 'false' ?>">
                        <span class="elog-sidebar__parent-left"><i class="fa-solid fa-table-cells"></i><span>Rotational Training</span></span>
                        <span class="elog-sidebar__chev" aria-hidden="true"><i class="fa-solid fa-chevron-down"></i></span>
                    </button>
                    <div class="elog-sidebar__sub" id="rotationalSubnav">
                        <a class="elog-sidebar__sublink<?= $tab === 'rotational' && $sub === 'list' ? ' is-active' : '' ?>" href="dashboard.php?tab=rotational&amp;sub=list">List all entries</a>
                        <a class="elog-sidebar__sublink<?= $tab === 'rotational' && $sub === 'add' ? ' is-active' : '' ?>" href="dashboard.php?tab=rotational&amp;sub=add">Add new</a>
                    </div>
                </div>
                <div class="elog-sidebar__group<?= $tab === 'journal' ? ' is-active is-open' : '' ?>">
                    <button type="button" class="elog-sidebar__parent" id="journalToggle" aria-expanded="<?= $tab === 'journal' ? 'true' : 'false' ?>">
                        <span class="elog-sidebar__parent-left"><i class="fa-solid fa-table-cells"></i><span>Journal Club</span></span>
                        <span class="elog-sidebar__chev" aria-hidden="true"><i class="fa-solid fa-chevron-down"></i></span>
                    </button>
                    <div class="elog-sidebar__sub" id="journalSubnav">
                        <a class="elog-sidebar__sublink<?= $tab === 'journal' && $sub === 'list' ? ' is-active' : '' ?>" href="dashboard.php?tab=journal&amp;sub=list">List all entries</a>
                        <a class="elog-sidebar__sublink<?= $tab === 'journal' && $sub === 'add' ? ' is-active' : '' ?>" href="dashboard.php?tab=journal&amp;sub=add">Add new</a>
                    </div>
                </div>
                <div class="elog-sidebar__group<?= $tab === 'presented' ? ' is-active is-open' : '' ?>">
                    <button type="button" class="elog-sidebar__parent" id="presentedToggle" aria-expanded="<?= $tab === 'presented' ? 'true' : 'false' ?>">
                        <span class="elog-sidebar__parent-left"><i class="fa-solid fa-table-cells"></i><span>Paper/Poster Presented</span></span>
                        <span class="elog-sidebar__chev" aria-hidden="true"><i class="fa-solid fa-chevron-down"></i></span>
                    </button>
                    <div class="elog-sidebar__sub" id="presentedSubnav">
                        <a class="elog-sidebar__sublink<?= $tab === 'presented' && $sub === 'list' ? ' is-active' : '' ?>" href="dashboard.php?tab=presented&amp;sub=list">List all entries</a>
                        <a class="elog-sidebar__sublink<?= $tab === 'presented' && $sub === 'add' ? ' is-active' : '' ?>" href="dashboard.php?tab=presented&amp;sub=add">Add new</a>
                    </div>
                </div>
                <div class="elog-sidebar__group<?= $tab === 'published' ? ' is-active is-open' : '' ?>">
                    <button type="button" class="elog-sidebar__parent" id="publishedToggle" aria-expanded="<?= $tab === 'published' ? 'true' : 'false' ?>">
                        <span class="elog-sidebar__parent-left"><i class="fa-solid fa-table-cells"></i><span>Paper Published</span></span>
                        <span class="elog-sidebar__chev" aria-hidden="true"><i class="fa-solid fa-chevron-down"></i></span>
                    </button>
                    <div class="elog-sidebar__sub" id="publishedSubnav">
                        <a class="elog-sidebar__sublink<?= $tab === 'published' && $sub === 'list' ? ' is-active' : '' ?>" href="dashboard.php?tab=published&amp;sub=list">List all entries</a>
                        <a class="elog-sidebar__sublink<?= $tab === 'published' && $sub === 'add' ? ' is-active' : '' ?>" href="dashboard.php?tab=published&amp;sub=add">Add new</a>
                    </div>
                </div>
                <div class="elog-sidebar__group<?= $tab === 'reports' ? ' is-active is-open' : '' ?>">
                    <button type="button" class="elog-sidebar__parent" id="reportsToggle" aria-expanded="<?= $tab === 'reports' ? 'true' : 'false' ?>">
                        <span class="elog-sidebar__parent-left"><i class="fa-solid fa-file-lines"></i><span>Reports</span></span>
                        <span class="elog-sidebar__chev" aria-hidden="true"><i class="fa-solid fa-chevron-down"></i></span>
                    </button>
                    <div class="elog-sidebar__sub" id="reportsSubnav">
                        <a class="elog-sidebar__sublink<?= $tab === 'reports' && $sub === 'list' ? ' is-active' : '' ?>" href="dashboard.php?tab=reports&amp;sub=list">List all entries</a>
                        <a class="elog-sidebar__sublink<?= $tab === 'reports' && $sub === 'add' ? ' is-active' : '' ?>" href="dashboard.php?tab=reports&amp;sub=add">Add new</a>
                    </div>
                </div>
            </nav>
        </aside>

        <div class="elog-shell">
            <header class="elog-topbar">
                <button type="button" class="elog-menu-btn" id="elogMenuOpen" aria-controls="elogSidebar" aria-expanded="false" aria-label="Toggle sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="elog-topbar__brand">
                    <span class="elog-topbar__title">INTERNAL MEDICINE (FCPS)</span>
                </div>
                <button type="button" class="elog-menu-btn" id="elogTopbarToggle" aria-controls="elogTopbarNav" aria-expanded="false" aria-label="Open account menu">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <nav class="elog-topbar__nav" aria-label="Account">
                    <span class="elog-topbar__user"><i class="fa-solid fa-user"></i> <?= $displayName ?></span>
                    <a class="elog-topbar__link" href="dashboard.php?tab=home"><i class="fa-solid fa-house"></i> Home</a>
                    <a class="elog-topbar__link elog-topbar__link--out js-logout-confirm" href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                </nav>
            </header>

            <?php 
            $isList = in_array($tab, ['training', 'rotational', 'journal', 'presented', 'published'], true) && $sub === 'list';
            $isFluid = ($tab === 'training' && $sub === 'add') || $isList; 
            ?>
            <main class="elog-main<?= $isFluid ? ' elog-main--fluid' : '' ?>">
    <?php if ($tab === 'training' && $sub === 'list'): ?>
                    <div class="elog-page-head">
                        <h1 class="elog-page-title">Training</h1>
                        <a class="btn btn--add" href="dashboard.php?tab=training&amp;sub=add">Add New</a>
                    </div>
                <?php elseif ($tab === 'home'): ?>
                    <div class="elog-page-head">
                        <h1 class="elog-page-title">Home</h1>
                    </div>

                <?php elseif ($tab === 'training' && $sub === 'add'): ?>
                    <div class="elog-page-head">
                        <h1 class="elog-page-title">Add Training Entry</h1>
                        <a class="btn btn--blue" href="dashboard.php?tab=training&amp;sub=list">List All</a>
                    </div>
                <?php elseif ($tab === 'training' && $sub === 'view'): ?>
                    <div class="elog-page-head">
                        <h1 class="elog-page-title">View Training Entry</h1>
                        <a class="btn btn--add" href="dashboard.php?tab=training&amp;sub=list">List All</a>
                    </div>

                <?php elseif ($tab === 'rotational' && $sub === 'list'): ?>
                    <div class="elog-page-head">
                        <h1 class="elog-page-title">Rotational Training</h1>
                        <a class="btn btn--add" href="dashboard.php?tab=rotational&amp;sub=add">Add New</a>
                    </div>
                <?php elseif ($tab === 'rotational' && $sub === 'add'): ?>
                    <div class="elog-page-head">
                        <h1 class="elog-page-title">Add Rotational Training</h1>
                        <a class="btn btn--blue" href="dashboard.php?tab=rotational&amp;sub=list">List All</a>
                    </div>

                <?php elseif ($tab === 'journal' && $sub === 'list'): ?>
                    <div class="elog-page-head">
                        <h1 class="elog-page-title">Journal Club</h1>
                        <a class="btn btn--add" href="dashboard.php?tab=journal&amp;sub=add">Add New</a>
                    </div>
                <?php elseif ($tab === 'journal' && $sub === 'add'): ?>
                    <div class="elog-page-head">
                        <h1 class="elog-page-title">Add Journal Club Entry</h1>
                        <a class="btn btn--blue" href="dashboard.php?tab=journal&amp;sub=list">List All</a>
                    </div>

                <?php elseif ($tab === 'presented' && $sub === 'list'): ?>
                    <div class="elog-page-head">
                        <h1 class="elog-page-title">Paper/Poster Presented</h1>
                        <a class="btn btn--add" href="dashboard.php?tab=presented&amp;sub=add">Add New</a>
                    </div>
                <?php elseif ($tab === 'presented' && $sub === 'add'): ?>
                    <div class="elog-page-head">
                        <h1 class="elog-page-title">Add Paper/Poster Presented</h1>
                        <a class="btn btn--blue" href="dashboard.php?tab=presented&amp;sub=list">List All</a>
                    </div>

                <?php elseif ($tab === 'published' && $sub === 'list'): ?>
                    <div class="elog-page-head">
                        <h1 class="elog-page-title">Paper Published</h1>
                        <a class="btn btn--add" href="dashboard.php?tab=published&amp;sub=add">Add New</a>
                    </div>
                <?php elseif ($tab === 'published' && $sub === 'add'): ?>
                    <div class="elog-page-head">
                        <h1 class="elog-page-title">Add Paper Published</h1>
                        <a class="btn btn--blue" href="dashboard.php?tab=published&amp;sub=list">List All</a>
                    </div>

                <?php elseif ($tab === 'reports' && $sub === 'list'): ?>
                    <div class="elog-page-head">
                        <h1 class="elog-page-title">Reports</h1>
                        <a class="btn btn--add" href="dashboard.php?tab=reports&amp;sub=add">Add New</a>
                    </div>
                <?php elseif ($tab === 'reports' && $sub === 'add'): ?>
                    <div class="elog-page-head">
                        <h1 class="elog-page-title">Add Report</h1>
                        <a class="btn btn--blue" href="dashboard.php?tab=reports&amp;sub=list">List All</a>
                    </div>
                <?php endif; ?>

                <?php if ($trainingListError !== null && $tab === 'training' && $sub === 'list'): ?>
                    <div class="alert alert-error elog-flash" role="alert"><?= htmlspecialchars($trainingListError, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <?php if ($rotListError !== null && $tab === 'rotational' && $sub === 'list'): ?>
                    <div class="alert alert-error elog-flash" role="alert"><?= htmlspecialchars($rotListError, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <?php if ($journalListError !== null && $tab === 'journal' && $sub === 'list'): ?>
                    <div class="alert alert-error elog-flash" role="alert"><?= htmlspecialchars($journalListError, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <?php if ($presentedListError !== null && $tab === 'presented' && $sub === 'list'): ?>
                    <div class="alert alert-error elog-flash" role="alert"><?= htmlspecialchars($presentedListError, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <?php if ($publishedListError !== null && $tab === 'published' && $sub === 'list'): ?>
                    <div class="alert alert-error elog-flash" role="alert"><?= htmlspecialchars($publishedListError, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <?php if ($tab === 'home'): ?>
                    <?php require __DIR__ . '/views/trainee_home.php'; ?>
                <?php elseif ($tab === 'training' && $sub === 'list'): ?>
                    <?php require __DIR__ . '/views/training_list.php'; ?>
                <?php elseif ($tab === 'training' && $sub === 'add'): ?>
                    <?php
                    if (!defined('Isra_FROM_DASHBOARD')) {
                        define('Isra_FROM_DASHBOARD', true);
                    }
                    require __DIR__ . '/views/training_add.php';
                    ?>
                <?php elseif ($tab === 'training' && $sub === 'view'): ?>
                    <?php
                    $entry = $viewEntry;
                    require __DIR__ . '/views/training_view.php';
                    ?>
                <?php elseif ($tab === 'rotational' && $sub === 'list'): ?>
                    <?php require __DIR__ . '/views/rotational_list.php'; ?>
                <?php elseif ($tab === 'rotational' && $sub === 'add'): ?>
                    <?php
                    if (!defined('Isra_FROM_DASHBOARD')) {
                        define('Isra_FROM_DASHBOARD', true);
                    }
                    require __DIR__ . '/views/rotational_add.php';
                    ?>
                <?php elseif ($tab === 'journal' && $sub === 'list'): ?>
                    <?php require __DIR__ . '/views/journal_list.php'; ?>
                <?php elseif ($tab === 'journal' && $sub === 'add'): ?>
                    <?php
                    if (!defined('Isra_FROM_DASHBOARD')) {
                        define('Isra_FROM_DASHBOARD', true);
                    }
                    require __DIR__ . '/views/journal_add.php';
                    ?>
                <?php elseif ($tab === 'presented' && $sub === 'list'): ?>
                    <?php require __DIR__ . '/views/presented_list.php'; ?>
                <?php elseif ($tab === 'presented' && $sub === 'add'): ?>
                    <?php
                    if (!defined('Isra_FROM_DASHBOARD')) {
                        define('Isra_FROM_DASHBOARD', true);
                    }
                    require __DIR__ . '/views/presented_add.php';
                    ?>
                <?php elseif ($tab === 'published' && $sub === 'list'): ?>
                    <?php require __DIR__ . '/views/published_list.php'; ?>
                <?php elseif ($tab === 'published' && $sub === 'add'): ?>
                    <?php
                    if (!defined('Isra_FROM_DASHBOARD')) {
                        define('Isra_FROM_DASHBOARD', true);
                    }
                    require __DIR__ . '/views/published_add.php';
                    ?>
                <?php elseif ($tab === 'reports' && $sub === 'list'): ?>
                    <?php require __DIR__ . '/views/reports_list.php'; ?>
                <?php elseif ($tab === 'reports' && $sub === 'add'): ?>
                    <?php require __DIR__ . '/views/reports_add.php'; ?>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <div class="elog-backdrop" id="elogBackdrop" hidden></div>

    <div class="modal" id="logoutModal" role="dialog" aria-modal="true" aria-labelledby="logoutModalTitle" hidden>
        <div class="modal__backdrop" data-close-modal></div>
        <div class="modal__panel">
            <h2 class="modal__title" id="logoutModalTitle" style="color: #0b6040; margin-bottom: 10px;">Confirm Logout</h2>
            <p class="modal__text" style="font-size: 16px; margin-bottom: 25px;">Are you sure you want to logout from Isra e-Logbook?</p>
            <div style="display: flex; gap: 15px;">
                <a href="logout.php" class="btn btn-login" style="flex: 1; text-align: center; text-decoration: none;">OK</a>
                <button type="button" class="btn btn-forgot" data-close-modal style="flex: 1; margin: 0;">Cancel</button>
            </div>
        </div>
    </div>

    <button type="button" class="scroll-top" id="scrollTop" aria-label="Scroll to top">
        <i class="fa-solid fa-chevron-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="script.js"></script>
    <script src="elogbook.js"></script>
    <?php if ($useRichEditor): ?>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof tinymce === 'undefined') {
                return;
            }
            tinymce.init({
                    selector: '#brief_desc',
                    menubar: false,
                    plugins: 'lists link table code',
                    toolbar: 'bold italic underline strikethrough | bullist numlist | table | removeformat',
                    branding: false,
                    height: 280
                });
        });
        </script>
    <?php endif; ?>
</body>
</html>
