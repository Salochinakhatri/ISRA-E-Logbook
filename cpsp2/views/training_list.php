<?php

declare(strict_types=1);

/** @var list<array<string,mixed>> $entries */
/** @var array<string,string> $filters */
/** @var string|null $lastEntryLabel */
/** @var string|null $flashOk */
/** @var array<int,string> $compMap */

$flashOk = $flashOk ?? null;
$lastEntryLabel = $lastEntryLabel ?? null;

?>
<section class="elog-panel">
    <div class="elog-panel__head">
        <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-list"></i></span>
        <span class="elog-panel__head-title">Training</span>
    </div>
    <div class="elog-panel__body">
        <form class="training-filters" method="get" action="dashboard.php">
            <input type="hidden" name="tab" value="training">
            <input type="hidden" name="sub" value="list">
            <input type="hidden" name="program" value="<?= htmlspecialchars($program ?? '') ?>">

            <div class="training-filters__grid">
                <div class="field">
                    <label class="field__label" for="f_status">Entry Status</label>
                    <select class="field__control" name="f_status" id="f_status">
                        <?php foreach (training_entry_status_options() as $val => $lab): ?>
                            <option value="<?= htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8') ?>"<?= ($filters['status'] === (string) $val) ? ' selected' : '' ?>><?= htmlspecialchars($lab, ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="f_level">Level</label>
                    <select class="field__control" name="f_level" id="f_level">
                        <option value="">Level</option>
                        <?php foreach (training_level_options() as $val => $lab): ?>
                            <?php $optVal = (string) $val; ?>
                            <option value="<?= htmlspecialchars($optVal, ENT_QUOTES, 'UTF-8') ?>"<?= ($filters['level'] === $optVal) ? ' selected' : '' ?>><?= htmlspecialchars((string) $lab, ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="f_post_from">Post Date From</label>
                    <input class="field__control dateBritish" type="text" name="f_post_from" id="f_post_from" placeholder="dd-mm-yyyy" value="<?= htmlspecialchars($filters['post_from'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="field">
                    <label class="field__label" for="f_post_to">Post Date To</label>
                    <input class="field__control dateBritish" type="text" name="f_post_to" id="f_post_to" placeholder="dd-mm-yyyy" value="<?= htmlspecialchars($filters['post_to'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="field">
                    <label class="field__label" for="f_adm_from">Admission Date From</label>
                    <input class="field__control dateBritish" type="text" name="f_adm_from" id="f_adm_from" placeholder="dd-mm-yyyy" value="<?= htmlspecialchars($filters['adm_from'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="field">
                    <label class="field__label" for="f_adm_to">Admission Date To</label>
                    <input class="field__control dateBritish" type="text" name="f_adm_to" id="f_adm_to" placeholder="dd-mm-yyyy" value="<?= htmlspecialchars($filters['adm_to'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="field">
                    <label class="field__label" for="f_reg">Hospt Reg No</label>
                    <input class="field__control" type="text" name="f_reg" id="f_reg" placeholder="" value="<?= htmlspecialchars($filters['reg'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </div>

            <div class="training-filters__actions">
                <button type="submit" class="btn btn--grey"><i class="fa-solid fa-magnifying-glass"></i> Filter</button>
                <a class="btn btn--grey" href="dashboard.php?tab=training&amp;sub=list&amp;program=<?= htmlspecialchars($program ?? '') ?>"><i class="fa-solid fa-list"></i> Show all</a>
                <div class="training-filters__notice">
                    <?php if ($lastEntryLabel): ?>
                        <p class="training-filters__notice-line">Last entry add date (Training or rotation training): <?= htmlspecialchars($lastEntryLabel, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php else: ?>
                        <p class="training-filters__notice-line">Last entry add date (Training or rotation training): <em>no entries yet</em></p>
                    <?php endif; ?>
                    <p class="training-filters__notice-warn">If no new entry is added within 90 days, the e-logbook may be locked as per  Isra policy.</p>
                </div>
            </div>
        </form>
    </div>
</section>

<?php if ($flashOk): ?>
    <div class="alert alert-success elog-flash" role="status"><?= htmlspecialchars($flashOk, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<div class="elog-table-wrap">
    <table class="elog-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Admit Date / Hpt. Reg No</th>
                <th>Diagnosis</th>
                <th>Brief Description</th>
                <th><?= ($program ?? '') === 'urogyn' ? 'Competency' : 'Competancy Group/Details' ?></th>
                <th>Level</th>
                <th>Entry Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($entries === []): ?>
                <tr>
                    <td colspan="8" class="elog-table__empty">No training entries found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($entries as $i => $row): ?>
                    <?php
                    $gids = is_string($row['com_ids'] ?? null) ? json_decode($row['com_ids'], true) : ($row['com_ids'] ?? []);
                    $dids = is_string($row['com_detail_ids'] ?? null) ? json_decode($row['com_detail_ids'], true) : ($row['com_detail_ids'] ?? []);
                    if (!is_array($gids)) {
                        $gids = [];
                    }
                    if (!is_array($dids)) {
                        $dids = [];
                    }
                    /** @var array<int,int> $gids */
                    /** @var array<int,int> $dids */
                    $gids = array_map('intval', $gids);
                    $dids = array_map('intval', $dids);
                    ?>
                    <tr>
                        <td><?= (int) $i + 1 ?></td>
                        <td>
                            <div class="cell-admit"><?= htmlspecialchars(format_admit_ordinal(isset($row['date_of_admission']) ? (string) $row['date_of_admission'] : null), ENT_QUOTES, 'UTF-8') ?></div>
                            <div class="cell-reg"><?= htmlspecialchars((string) ($row['hospt_reg_no'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                        </td>
                        <td><?= htmlspecialchars((string) ($row['pt_diagnosis'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><div class="cell-brief"><?= nl2br(htmlspecialchars(strip_tags((string) ($row['brief_desc'] ?? '')), ENT_QUOTES, 'UTF-8')) ?></div></td>
                        <td class="cell-comp"><?= training_format_competency_cell($gids, $dids, $compMap) ?></td>
                        <td><?= htmlspecialchars(training_level_label((string) ($row['level_id'] ?? '')), ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <div class="cell-status">
                                <div>Add: <?= htmlspecialchars(format_datetime_display(isset($row['created_at']) ? (string) $row['created_at'] : null), ENT_QUOTES, 'UTF-8') ?></div>
                                <?php $st = (string) ($row['entry_status'] ?? ''); ?>
                                <span class="badge badge--<?= $st === 'Approved' ? 'ok' : 'muted' ?>"><?= htmlspecialchars($st !== '' ? $st : 'Draft', ENT_QUOTES, 'UTF-8') ?></span>
                                <?php if (!empty($row['approved_at'])): ?>
                                    <div>Appr: <?= htmlspecialchars(format_datetime_display((string) $row['approved_at']), ENT_QUOTES, 'UTF-8') ?></div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <a class="btn btn--view" href="dashboard.php?tab=training&amp;sub=view&amp;id=<?= (int) $row['id'] ?>&amp;program=<?= htmlspecialchars($program ?? '') ?>" title="View"><i class="fa-solid fa-eye"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
