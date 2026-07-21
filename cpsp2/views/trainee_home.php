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
}

?>

<?php if ($program === ''): ?>
    <section class="elog-panel">
        <div class="elog-panel__head">
            <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-house"></i></span>
            <span class="elog-panel__head-title">ePortal Home</span>
        </div>
        <div class="elog-panel__body" style="text-align: center; padding: 2.5rem 1.5rem;">
            <p class="home-lead" style="font-size: 1.35rem; margin-bottom: 2rem;">
                Welcome, <strong><?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?></strong>.
            </p>
            <p class="home-text" style="color: #666; margin-bottom: 2.5rem; font-size: 1.15rem;">
                Please select a program under <strong> Isra e-Logbook</strong> in the sidebar to open your logbook, or choose one below:
            </p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; max-width: 800px; margin: 0 auto;">
                <a href="dashboard.php?tab=home&amp;program=obgyn" class="dash-card" style="text-decoration: none; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 2rem 1.5rem; border: 2px solid #2f8f56; background: #fff; border-radius: 10px; transition: transform 0.2s ease, box-shadow 0.2s ease; cursor: pointer;">
                    <i class="fa-solid fa-graduation-cap" style="font-size: 3rem; color: #2f8f56; margin-bottom: 1rem;"></i>
                    <h3 style="margin: 0; color: #333; font-size: 1.1rem; font-weight: 700; text-transform: uppercase;">Obstetrics &amp; Gynaecology</h3>
                    <span style="color: #888; font-size: 0.85rem; margin-top: 5px;">(FCPS Logbook)</span>
                </a>

                <a href="dashboard.php?tab=home&amp;program=urogyn" class="dash-card" style="text-decoration: none; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 2rem 1.5rem; border: 2px solid #2f8f56; background: #fff; border-radius: 10px; transition: transform 0.2s ease, box-shadow 0.2s ease; cursor: pointer;">
                    <i class="fa-solid fa-graduation-cap" style="font-size: 3rem; color: #2f8f56; margin-bottom: 1rem;"></i>
                    <h3 style="margin: 0; color: #333; font-size: 1.1rem; font-weight: 700; text-transform: uppercase;">Urogynaecology</h3>
                    <span style="color: #888; font-size: 0.85rem; margin-top: 5px;">(FCPS Logbook)</span>
                </a>
            </div>
        </div>
    </section>
<?php else: ?>
    <!-- Premium Trainee Feedback box -->
    <div class="home-feedback-card">
        <div class="home-feedback-card__icon">
            <i class="fa-solid fa-thumbs-up"></i>
        </div>
        <div>
            <h3 class="home-feedback-card__title">Trainee Feedback</h3>
            <p class="home-feedback-card__text">Provide valuable feedback about your training experience and supervisor support.</p>
        </div>
    </div>

    <!-- Active Program Title Header block -->
    <div class="home-program-header">
        <h2><?= htmlspecialchars($programTitle, ENT_QUOTES, 'UTF-8') ?></h2>
    </div>

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
                            <th style="text-align: center; width: 120px;">Entries Count</th>
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
                <p class="home-text">No recent activity yet for this program. Start by creating your first entry.</p>
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
<?php endif; ?>
