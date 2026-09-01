<?php

declare(strict_types=1);

/** @var string $displayName */
/** @var string $displayType */
/** @var list<array<string,mixed>> $recentActivities */
/** @var string $program */
/** @var PDO $pdo */
/** @var int $userId */

$programTitle = '';
if ($program === 'urogyn') {
    $programTitle = 'UROGYNAECOLOGY (FCPS)';
} elseif ($program === 'obgyn') {
    $programTitle = 'OBSTETRICS AND GYNAECOLOGY (FCPS)';
}

$counts = [
    'training' => 0,
    'rotational' => 0,
    'journal' => 0,
    'presented' => 0,
    'published' => 0,
    'suggestions' => 0
];

if ($program !== '') {
    try {
        $trainingTable = ($program === 'obgyn') ? 'tainingobs_entries' : 'traninguro_entries';
        $st = $pdo->prepare("SELECT COUNT(*) FROM {$trainingTable} WHERE user_id = :uid AND fcps_program = :prog");
        $st->execute([':uid' => $userId, ':prog' => $program]);
        $counts['training'] = (int) $st->fetchColumn();

        $st = $pdo->prepare('SELECT COUNT(*) FROM rotational_entries WHERE user_id = :uid AND fcps_program = :prog');
        $st->execute([':uid' => $userId, ':prog' => $program]);
        $counts['rotational'] = (int) $st->fetchColumn();

        $st = $pdo->prepare('SELECT COUNT(*) FROM journal_entries WHERE user_id = :uid AND fcps_program = :prog');
        $st->execute([':uid' => $userId, ':prog' => $program]);
        $counts['journal'] = (int) $st->fetchColumn();

        $st = $pdo->prepare('SELECT COUNT(*) FROM presented_entries WHERE user_id = :uid AND fcps_program = :prog');
        $st->execute([':uid' => $userId, ':prog' => $program]);
        $counts['presented'] = (int) $st->fetchColumn();

        $st = $pdo->prepare('SELECT COUNT(*) FROM published_entries WHERE user_id = :uid AND fcps_program = :prog');
        $st->execute([':uid' => $userId, ':prog' => $program]);
        $counts['published'] = (int) $st->fetchColumn();

        $st = $pdo->prepare('SELECT COUNT(*) FROM suggestions WHERE user_id = :uid AND program = :prog');
        $st->execute([':uid' => $userId, ':prog' => $program]);
        $counts['suggestions'] = (int) $st->fetchColumn();
    } catch (PDOException $e) {
        // Silently fail if schema does not match yet
    }
} else {
    try {
        $c1 = 0;
        $c2 = 0;
        try {
            $st = $pdo->prepare('SELECT COUNT(*) FROM tainingobs_entries WHERE user_id = :uid');
            $st->execute([':uid' => $userId]);
            $c1 = (int) $st->fetchColumn();
        } catch (\Throwable) {}
        try {
            $st = $pdo->prepare('SELECT COUNT(*) FROM traninguro_entries WHERE user_id = :uid');
            $st->execute([':uid' => $userId]);
            $c2 = (int) $st->fetchColumn();
        } catch (\Throwable) {}
        $counts['training'] = $c1 + $c2;

        $st = $pdo->prepare('SELECT COUNT(*) FROM rotational_entries WHERE user_id = :uid');
        $st->execute([':uid' => $userId]);
        $counts['rotational'] = (int) $st->fetchColumn();

        $st = $pdo->prepare('SELECT COUNT(*) FROM journal_entries WHERE user_id = :uid');
        $st->execute([':uid' => $userId]);
        $counts['journal'] = (int) $st->fetchColumn();

        $st = $pdo->prepare('SELECT COUNT(*) FROM presented_entries WHERE user_id = :uid');
        $st->execute([':uid' => $userId]);
        $counts['presented'] = (int) $st->fetchColumn();

        $st = $pdo->prepare('SELECT COUNT(*) FROM published_entries WHERE user_id = :uid');
        $st->execute([':uid' => $userId]);
        $counts['published'] = (int) $st->fetchColumn();

        $st = $pdo->prepare('SELECT COUNT(*) FROM suggestions WHERE user_id = :uid');
        $st->execute([':uid' => $userId]);
        $counts['suggestions'] = (int) $st->fetchColumn();
    } catch (PDOException $e) {
        // Silently fail if schema does not match yet
    }
}

?>

<section class="elog-panel">
    <div class="elog-panel__head">
        <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-house"></i></span>
        <span class="elog-panel__head-title">Home</span>
    </div>
    <div class="elog-panel__body">
        <p class="home-lead">Welcome, <strong><?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?></strong>.</p>
        <p class="home-text">Use <strong>Training</strong> in the sidebar to list or add logbook entries.</p>
        <dl class="home-meta">
            <div><dt>User type</dt><dd><?= htmlspecialchars($displayType, ENT_QUOTES, 'UTF-8') ?></dd></div>
        </dl>
    </div>
</section>

<!-- Summary Section -->
<section class="elog-panel">
    <div class="elog-panel__head">
        <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-regular fa-circle-dot"></i></span>
        <span class="elog-panel__head-title">Summary</span>
    </div>
    <div class="elog-panel__body">
        <div class="elog-table-wrap">
            <table class="elog-table">
                <thead>
                    <tr>
                        <th>Logbook Section</th>
                        <th style="text-align: center; width: 140px;">Entries Count</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Training</strong></td>
                        <td style="text-align: center; font-weight: bold;"><?= $counts['training'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Rotational Training</strong></td>
                        <td style="text-align: center; font-weight: bold;"><?= $counts['rotational'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Journal Club</strong></td>
                        <td style="text-align: center; font-weight: bold;"><?= $counts['journal'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Paper/Poster Presented</strong></td>
                        <td style="text-align: center; font-weight: bold;"><?= $counts['presented'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Paper Published</strong></td>
                        <td style="text-align: center; font-weight: bold;"><?= $counts['published'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Suggestions</strong></td>
                        <td style="text-align: center; font-weight: bold;"><?= $counts['suggestions'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Recent Activities Section -->
<section class="elog-panel">
    <div class="elog-panel__head">
        <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-clock-rotate-left"></i></span>
        <span class="elog-panel__head-title">Recent Activities</span>
    </div>
    <div class="elog-panel__body">
        <?php if ($recentActivities === []): ?>
            <p class="home-text">No recent activity yet. Start by creating your first entry.</p>
        <?php else: ?>
            <ul class="activity-list">
                <?php foreach ($recentActivities as $act): ?>
                    <?php
                    $isProfile = isset($act['kind']) && (string) $act['kind'] === 'profile';
                    $created = isset($act['created_at']) ? format_datetime_display((string) $act['created_at']) : '—';
                    ?>
                    <li class="activity-item">
                        <div class="activity-item__icon">
                            <i class="fa-solid <?= $isProfile ? 'fa-user-pen' : 'fa-file-medical' ?>"></i>
                        </div>
                        <div class="activity-item__content">
                            <?php if ($isProfile): ?>
                                <p class="activity-item__title"><?= htmlspecialchars((string) ($act['title'] ?? 'Profile activity'), ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="activity-item__meta"><?= htmlspecialchars((string) ($act['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                            <?php else: ?>
                                <p class="activity-item__title">Training entry #<?= (int) ($act['id'] ?? 0) ?> - <?= htmlspecialchars((string) ($act['pt_diagnosis'] ?? 'Clinical case'), ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="activity-item__meta">
                                    Status: <strong><?= htmlspecialchars((string) ($act['entry_status'] ?? 'Draft'), ENT_QUOTES, 'UTF-8') ?></strong>
                                    <?php if (!empty($act['hospt_reg_no'])): ?>
                                        | Reg No: <?= htmlspecialchars((string) $act['hospt_reg_no'], ENT_QUOTES, 'UTF-8') ?>
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="activity-item__date"><?= htmlspecialchars($created, ENT_QUOTES, 'UTF-8') ?></div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>
