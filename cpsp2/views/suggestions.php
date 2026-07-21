<?php

declare(strict_types=1);

if (!defined('Isra_FROM_DASHBOARD')) {
    header('Location: ../dashboard.php?tab=suggestions');
    exit;
}

require_once __DIR__ . '/../includes/csrf.php';

$old = $formOld ?? [];
$formErrors = $formErrors ?? [];
$flashOk = $flashOk ?? $_SESSION['flash_ok'] ?? null;
unset($_SESSION['flash_ok']);

$userId = (int) $_SESSION['user_id'];

// Retrieve suggestions for the current program
$suggestionsList = [];
try {
    $stmt = $pdo->prepare(
        'SELECT * FROM suggestions 
         WHERE user_id = :uid AND program = :prog 
         ORDER BY created_at DESC'
    );
    $stmt->execute([
        ':uid' => $userId,
        ':prog' => $program
    ]);
    $suggestionsList = $stmt->fetchAll() ?: [];
} catch (PDOException $e) {
    // If not migrated yet
    $suggestionsList = [];
}

?>
<section class="elog-panel">
    <div class="elog-panel__head">
        <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-lightbulb"></i></span>
        <span class="elog-panel__head-title">Submit a Suggestion</span>
    </div>
    <div class="elog-panel__body">
        <?php if ($flashOk): ?>
            <div class="alert alert-success elog-flash" role="status"><?= htmlspecialchars($flashOk, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if ($formErrors !== []): ?>
            <div class="alert alert-danger elog-alert-errors" role="alert">
                <strong>Please fix the following errors:</strong>
                <ul class="elog-error-list">
                    <?php foreach ($formErrors as $err): ?>
                        <li><?= htmlspecialchars((string) $err, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="form-horizontal elog-form" action="suggestions_save.php" method="post" id="suggestionsForm" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="program" value="<?= htmlspecialchars($program, ENT_QUOTES, 'UTF-8') ?>">

            <div class="row elog-form__row elog-form__row--1">
                <div class="col-md-12">
                    <label class="control-label" for="suggestion_text">
                        Your Suggestion / Feedback <span class="vd_red">*</span>
                    </label>
                    <div class="controls">
                        <textarea id="suggestion_text" 
                                  name="suggestion_text" 
                                  class="field__control" 
                                  rows="6" 
                                  placeholder="Type your feedback here..." 
                                  required><?= htmlspecialchars((string)($old['suggestion_text'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="form-actions-condensed row elog-form__row elog-form__row--1 mgbt-xs-0">
                <div class="col-sm-12">
                    <button class="btn btn--submit" type="submit" name="submit" id="suggestions_submit">Submit Suggestion</button>
                </div>
            </div>
        </form>
    </div>
</section>

<section class="elog-panel">
    <div class="elog-panel__head">
        <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-list"></i></span>
        <span class="elog-panel__head-title">Previous Suggestions</span>
    </div>
    <div class="elog-panel__body">
        <div class="elog-table-wrap">
            <table class="elog-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">#</th>
                        <th>Suggestion / Feedback</th>
                        <th style="width: 180px;">Submitted On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($suggestionsList === []): ?>
                        <tr>
                            <td colspan="3" class="elog-table__empty">No suggestions submitted yet for this program.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($suggestionsList as $index => $row): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= nl2br(htmlspecialchars((string)$row['suggestion_text'], ENT_QUOTES, 'UTF-8')) ?></td>
                                <td><?= htmlspecialchars(format_datetime_display((string)$row['created_at']), ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
