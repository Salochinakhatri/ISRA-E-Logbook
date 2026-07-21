<?php

declare(strict_types=1);

/**
 * Paper Published – List all entries
 *
 * Variables injected by dashboard.php:
 * @var list<array<string,mixed>> $publishedEntries
 * @var array<string,string>      $filters
 * @var string|null               $publishedLastLabel
 * @var string|null               $flashOk
 */

$flashOk            = $flashOk            ?? null;
$publishedLastLabel = $publishedLastLabel ?? null;
$publishedEntries   = $publishedEntries   ?? [];

?>
<section class="elog-panel">
    <div class="elog-panel__head">
        <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-list"></i></span>
        <span class="elog-panel__head-title">Paper Published</span>
    </div>
    <div class="elog-panel__body">

        <form class="training-filters" method="get" action="dashboard.php">
            <input type="hidden" name="tab" value="published">
            <input type="hidden" name="sub" value="list">
            <input type="hidden" name="program" value="<?= htmlspecialchars($program ?? '') ?>">

            <!-- Row 1: Status · Post Date From · Post Date To -->
            <div class="training-filters__grid rot-filters__row1">
                <div class="field">
                    <select class="field__control" name="f_status" id="pubf_status">
                        <?php foreach (training_entry_status_options() as $val => $lab): ?>
                            <option value="<?= htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8') ?>"<?= ($filters['status'] === (string) $val) ? ' selected' : '' ?>><?= htmlspecialchars($lab, ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <input class="field__control dateBritish" type="text" name="f_post_from" id="pubf_post_from"
                           placeholder="Post Date From"
                           value="<?= htmlspecialchars($filters['post_from'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="field">
                    <input class="field__control dateBritish" type="text" name="f_post_to" id="pubf_post_to"
                           placeholder="Post Date To"
                           value="<?= htmlspecialchars($filters['post_to'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </div>

            <!-- Row 2: Pub Date From · Pub Date To · Title -->
            <div class="training-filters__grid rot-filters__row1">
                <div class="field">
                    <input class="field__control dateBritish" type="text" name="f_pub_from" id="pubf_pub_from"
                           placeholder="Published Date From"
                           value="<?= htmlspecialchars($filters['pub_from'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="field">
                    <input class="field__control dateBritish" type="text" name="f_pub_to" id="pubf_pub_to"
                           placeholder="Published Date To"
                           value="<?= htmlspecialchars($filters['pub_to'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="field">
                    <input class="field__control" type="text" name="f_title" id="pubf_title"
                           placeholder="Title"
                           value="<?= htmlspecialchars($filters['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </div>

            <!-- Actions + Notice -->
            <div class="training-filters__actions">
                <button type="submit" class="btn btn--grey"><i class="fa-solid fa-magnifying-glass"></i> Filter</button>
                <a class="btn btn--grey" href="dashboard.php?tab=published&amp;sub=list&amp;program=<?= htmlspecialchars($program ?? '') ?>">
                    <i class="fa-solid fa-list"></i> Show all
                </a>

                <div class="training-filters__notice">
                    <?php if ($publishedLastLabel): ?>
                        <p class="training-filters__notice-line rot-notice--date">
                            Last entry add date (Paper Published):
                            <strong><?= htmlspecialchars($publishedLastLabel, ENT_QUOTES, 'UTF-8') ?></strong>
                        </p>
                    <?php else: ?>
                        <p class="training-filters__notice-line rot-notice--date">
                            Last entry add date (Paper Published): <em>no entries yet</em>
                        </p>
                    <?php endif; ?>
                    <p class="training-filters__notice-warn rot-notice--warn">
                        If no new entry is added within 90 days, the e-logbook will be locked for further entries.
                        This lock is not affected by the date the entry is sent to the supervisor.
                    </p>
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
                <th>Published Date</th>
                <th>Title</th>
                <th>Full Reference</th>
                <th>Post Date</th>
                <th>Entry Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($publishedEntries === []): ?>
                <tr>
                    <td colspan="7" class="elog-table__empty">No Paper Published entries found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($publishedEntries as $i => $row): ?>
                    <tr>
                        <td><?= (int) $i + 1 ?></td>

                        <td>
                            <?= htmlspecialchars(
                                format_admit_ordinal(isset($row['pub_date']) ? (string) $row['pub_date'] : null),
                                ENT_QUOTES, 'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            <div class="cell-brief">
                                <?= nl2br(htmlspecialchars((string) ($row['pub_title'] ?? ''), ENT_QUOTES, 'UTF-8')) ?>
                            </div>
                        </td>

                        <td>
                            <div class="cell-brief">
                                <?= nl2br(htmlspecialchars(
                                    mb_strimwidth((string) ($row['full_ref'] ?? ''), 0, 180, '…'),
                                    ENT_QUOTES, 'UTF-8'
                                )) ?>
                            </div>
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
                               href="dashboard.php?tab=published&amp;sub=view&amp;id=<?= (int) $row['id'] ?>&amp;program=<?= htmlspecialchars($program ?? '') ?>"
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