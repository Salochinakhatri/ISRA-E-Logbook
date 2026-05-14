<?php

declare(strict_types=1);

if (!defined('CPSP_FROM_DASHBOARD')) {
    header('Location: ../dashboard.php?tab=presented&sub=add');
    exit;
}

require_once __DIR__ . '/../includes/csrf.php';

/* Repopulate on validation error */
$old         = $formOld ?? [];
$formErrors  = $formErrors ?? [];
$oldDate     = htmlspecialchars((string) ($old['rec_date']  ?? ''), ENT_QUOTES, 'UTF-8');
$oldTitle    = htmlspecialchars((string) ($old['rec_title'] ?? ''), ENT_QUOTES, 'UTF-8');
$oldVenue    = htmlspecialchars((string) ($old['rec_venue'] ?? ''), ENT_QUOTES, 'UTF-8');
$oldConfName = htmlspecialchars((string) ($old['conf_name'] ?? ''), ENT_QUOTES, 'UTF-8');
$oldStdPost  = (string) ($old['std_post'] ?? 'No');

?>
<div class="vd_content-section elog-content-section clearfix">
    <form class="form-horizontal elog-form" action="presented_save.php" method="post" name="mform" id="mform_presented" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="isSubmitted" value="Y">

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

        <div class="row">
            <div class="col-md-12">
                <div class="panel widget">
                    <div class="panel-heading vd_bg-grey">
                        <h3 class="panel-title">
                            <span class="menu-icon"><i class="fa-solid fa-bar-chart"></i></span>
                            Entry Details
                        </h3>
                    </div>
                    <div class="panel-body">

                        <!-- Row 1: Presented Date + Title -->
                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label" for="rec_date">
                                    Presented Date <span class="vd_red">*</span>
                                </label>
                                <div class="controls">
                                    <input type="text"
                                           id="rec_date"
                                           name="rec_date"
                                           value="<?= $oldDate ?>"
                                           class="field__control dateBritish"
                                           placeholder="dd-mm-yyyy"
                                           autocomplete="off"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="rec_title">
                                    Title <span class="vd_red">*</span>
                                </label>
                                <div class="controls">
                                    <input type="text"
                                           id="rec_title"
                                           name="rec_title"
                                           value="<?= $oldTitle ?>"
                                           class="field__control">
                                </div>
                            </div>
                        </div>

                        <!-- Row 2: Venue + Conf Name -->
                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label" for="rec_venue">
                                    Venue <span class="vd_red">*</span>
                                </label>
                                <div class="controls">
                                    <input type="text"
                                           id="rec_venue"
                                           name="rec_venue"
                                           value="<?= $oldVenue ?>"
                                           class="field__control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="conf_name">
                                    Name Of Conf. / Seminar / Symposium <span class="vd_red">*</span>
                                </label>
                                <div class="controls">
                                    <textarea id="conf_name"
                                              name="conf_name"
                                              class="field__control"
                                              rows="4"><?= $oldConfName ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Row 3: Send to Supervisor + Warning -->
                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label" for="std_post">
                                    Send to Supervisor <span class="vd_red">*</span>
                                </label>
                                <div class="controls">
                                    <select name="std_post" id="std_post" class="field__control">
                                        <option value="No"<?= $oldStdPost !== 'Yes' ? ' selected' : '' ?>>No&nbsp;</option>
                                        <option value="Yes"<?= $oldStdPost === 'Yes' ? ' selected' : '' ?>>Yes&nbsp;</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-warning elog-warn-inline">
                                    <span class="vd_alert-icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
                                    <strong>Warning!</strong> Once you select 'Yes' then you can not edit this entry again.
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="form-actions-condensed row elog-form__row elog-form__row--1 mgbt-xs-0">
                            <div class="col-sm-12">
                                <button class="btn btn--submit"
                                        type="submit"
                                        name="submit"
                                        id="mform_presented_submit">Submit</button>
                            </div>
                        </div>

                    </div><!-- .panel-body -->
                </div><!-- .panel.widget -->
            </div><!-- .col-md-12 -->
        </div><!-- .row -->

    </form>
</div><!-- .vd_content-section -->