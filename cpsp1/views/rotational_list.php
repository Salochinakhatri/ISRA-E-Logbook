<?php

declare(strict_types=1);

/**
 * Rotational Training – List all entries
 *
 * Variables injected by dashboard.php:
 * @var list<array<string,mixed>> $rotEntries
 * @var array<string,string>      $filters
 * @var string|null               $rotLastLabel
 * @var string|null               $flashOk
 */

$flashOk      = $flashOk      ?? null;
$rotLastLabel = $rotLastLabel ?? null;
$rotEntries   = $rotEntries   ?? [];

?>
<section class="elog-panel">
    <div class="elog-panel__head">
        <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-list"></i></span>
        <span class="elog-panel__head-title">Rotational Training</span>
    </div>
    <div class="elog-panel__body">

        <form class="training-filters" method="get" action="dashboard.php">
            <input type="hidden" name="tab" value="rotational">
            <input type="hidden" name="sub" value="list">

            <!-- Row 1: Status · Post Date From · Post Date To -->
            <div class="training-filters__grid rot-filters__row1">
                <div class="field">
                    <select class="field__control" name="f_status" id="rf_status">
                        <?php foreach (training_entry_status_options() as $val => $lab): ?>
                            <option value="<?= htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8') ?>"<?= ($filters['status'] === (string) $val) ? ' selected' : '' ?>><?= htmlspecialchars($lab, ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <input class="field__control dateBritish" type="text" name="f_post_from" id="rf_post_from"
                           placeholder="Post Date From"
                           value="<?= htmlspecialchars($filters['post_from'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="field">
                    <input class="field__control dateBritish" type="text" name="f_post_to" id="rf_post_to"
                           placeholder="Post Date To"
                           value="<?= htmlspecialchars($filters['post_to'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </div>

            <!-- Row 2: Level · Adm From · Adm To · Reg No -->
            <div class="training-filters__grid rot-filters__row2">
                <div class="field">
                    <select class="field__control" name="f_level" id="rf_level">
                        <option value="">Level</option>
                        <?php foreach (training_level_options() as $val => $lab): ?>
                            <?php $optVal = (string) $val; ?>
                            <option value="<?= htmlspecialchars($optVal, ENT_QUOTES, 'UTF-8') ?>"<?= ($filters['level'] === $optVal) ? ' selected' : '' ?>><?= htmlspecialchars((string) $lab, ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <input class="field__control dateBritish" type="text" name="f_adm_from" id="rf_adm_from"
                           placeholder="Admission Date From"
                           value="<?= htmlspecialchars($filters['adm_from'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="field">
                    <input class="field__control dateBritish" type="text" name="f_adm_to" id="rf_adm_to"
                           placeholder="Admission Date To"
                           value="<?= htmlspecialchars($filters['adm_to'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="field">
                    <input class="field__control" type="text" name="f_reg" id="rf_reg"
                           placeholder="Hospt Reg No"
                           value="<?= htmlspecialchars($filters['reg'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </div>

            <!-- Actions + Notice -->
            <div class="training-filters__actions">
                <button type="submit" class="btn btn--grey"><i class="fa-solid fa-magnifying-glass"></i> Filter</button>
                <a class="btn btn--grey" href="dashboard.php?tab=rotational&amp;sub=list">
                    <i class="fa-solid fa-list"></i> Show all
                </a>

                <div class="training-filters__notice">
                    <?php if ($rotLastLabel): ?>
                        <p class="training-filters__notice-line rot-notice--date">
                            Last entry add date (Training or rotation training):
                            <strong><?= htmlspecialchars($rotLastLabel, ENT_QUOTES, 'UTF-8') ?></strong>
                        </p>
                    <?php else: ?>
                        <p class="training-filters__notice-line rot-notice--date">
                            Last entry add date (Training or rotation training): <em>no entries yet</em>
                        </p>
                    <?php endif; ?>
                    <p class="training-filters__notice-warn rot-notice--warn">
                        If no new entry (Training or rotation training) is added within 90 days,
                        the e-logbook will be locked for further entries.
                        This lock is not affected by the date the entry is sent to the supervisor.
                    </p>
                </div>
            </div>
        </form>

    </div><!-- .elog-panel__body -->
</section><!-- .elog-panel -->

<?php if ($flashOk): ?>
    <div class="alert alert-success elog-flash" role="status"><?= htmlspecialchars($flashOk, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<div class="elog-table-wrap">
    <table class="elog-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Admit Date /<br>Hpt. Reg No</th>
                <th>Level</th>
                <th>Outcome</th>
                <th>Post date</th>
                <th>Entry Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($rotEntries === []): ?>
                <tr>
                    <td colspan="7" class="elog-table__empty">No rotational training entries found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($rotEntries as $i => $row): ?>
                    <tr>
                        <td><?= (int) $i + 1 ?></td>

                        <td>
                            <div class="cell-admit">
                                <?= htmlspecialchars(
                                    format_admit_ordinal(isset($row['date_of_admission']) ? (string) $row['date_of_admission'] : null),
                                    ENT_QUOTES, 'UTF-8'
                                ) ?>
                            </div>
                            <div class="cell-reg"><?= htmlspecialchars((string) ($row['hospt_reg_no'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                        </td>

                        <td><?= htmlspecialchars(training_level_label((string) ($row['level_id'] ?? '')), ENT_QUOTES, 'UTF-8') ?></td>

                        <td>
                            <?php
                            $outcomeOpts = training_outcome_options();
                            $outcomeKey  = (string) ($row['outcome_id'] ?? '');
                            echo htmlspecialchars($outcomeOpts[$outcomeKey] ?? ($outcomeKey !== '' ? $outcomeKey : '—'), ENT_QUOTES, 'UTF-8');
                            ?>
                        </td>

                        <td><?= htmlspecialchars(format_datetime_display(isset($row['created_at']) ? (string) $row['created_at'] : null), ENT_QUOTES, 'UTF-8') ?></td>

                        <td>
                            <div class="cell-status">
                                <div>Add Date: <?= htmlspecialchars(format_datetime_display(isset($row['created_at']) ? (string) $row['created_at'] : null), ENT_QUOTES, 'UTF-8') ?></div>
                                <?php $st = (string) ($row['entry_status'] ?? ''); ?>
                                <span class="badge badge--<?= $st === 'Approved' ? 'ok' : ($st === 'Draft' ? 'muted' : 'warn') ?>">
                                    <?= htmlspecialchars($st !== '' ? $st : 'Draft', ENT_QUOTES, 'UTF-8') ?>
                                </span>
                                <?php if (!empty($row['approved_at'])): ?>
                                    <div>Approved: <?= htmlspecialchars(format_datetime_display((string) $row['approved_at']), ENT_QUOTES, 'UTF-8') ?></div>
                                <?php endif; ?>
                            </div>
                        </td>

                        <td>
                            <a class="btn btn--view"
                               href="dashboard.php?tab=rotational&amp;sub=view&amp;id=<?= (int) $row['id'] ?>"
                               title="View entry">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>