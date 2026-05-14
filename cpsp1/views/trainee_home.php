<?php

declare(strict_types=1);

/** @var string $displayName */
/** @var string $displayType */
/** @var list<array<string,mixed>> $recentActivities */

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

<section class="elog-panel">
    <div class="elog-panel__head">
        <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-clock-rotate-left"></i></span>
        <span class="elog-panel__head-title">Recent Activities</span>
    </div>
    <div class="elog-panel__body">
        <?php if ($recentActivities === []): ?>
            <p class="home-text">No recent activity yet. Start by creating your first training entry.</p>
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
