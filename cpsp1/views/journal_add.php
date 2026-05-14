<?php

declare(strict_types=1);

if (!defined('CPSP_FROM_DASHBOARD')) {
    header('Location: ../dashboard.php?tab=journal&sub=add');
    exit;
}

require_once __DIR__ . '/../includes/csrf.php';

/* Repopulate on validation error */
$old         = $formOld ?? [];
$formErrors  = $formErrors ?? [];
$oldDate     = htmlspecialchars((string) ($old['date_of_diss']    ?? ''), ENT_QUOTES, 'UTF-8');
$oldFacBy    = htmlspecialchars((string) ($old['fac_by']          ?? ''), ENT_QUOTES, 'UTF-8');
$oldRef      = htmlspecialchars((string) ($old['ref_of_art_disc'] ?? ''), ENT_QUOTES, 'UTF-8');
$oldStdPost  = (string) ($old['std_post'] ?? 'No');

?>
<div class="vd_content-section elog-content-section clearfix">
    <form class="form-horizontal elog-form" action="journal_save.php" method="post" name="mform" id="mform_journal" novalidate>
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

                        <!-- Row 1: Date of Discussion + Facilitated by -->
                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label" for="date_of_diss">
                                    Date of Discussion <span class="vd_red">*</span>
                                </label>
                                <div class="controls">
                                    <input type="text"
                                           id="date_of_diss"
                                           name="date_of_diss"
                                           value="<?= $oldDate ?>"
                                           class="field__control dateBritish"
                                           placeholder="dd-mm-yyyy"
                                           autocomplete="off"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="fac_by">
                                    Facilitated by <span class="vd_red">*</span>
                                </label>
                                <div class="controls">
                                    <input type="text"
                                           id="fac_by"
                                           name="fac_by"
                                           value="<?= $oldFacBy ?>"
                                           class="field__control">
                                </div>
                            </div>
                        </div>

                        <!-- Row 2: Article Reference + Example -->
                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label" for="ref_of_art_disc">
                                    Full Reference Of The Article Discussed <span class="vd_red">*</span>
                                </label>
                                <div class="controls">
                                    <textarea id="ref_of_art_disc"
                                              name="ref_of_art_disc"
                                              class="field__control journal-textarea"
                                              rows="9"
                                              placeholder="View example from right column"><?= $oldRef ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Example</label>
                                <div class="controls">
                                    <textarea class="field__control journal-textarea journal-example"
                                              rows="9"
                                              disabled>Title, Journal Name in which published, Author, PMID

Effect of stitch length on wound complications after closure of midline incisions: a randomized controlled trial.

Millbourn D, Cengiz Y, Israelsson LA.
Arch Surg. 2013 Nov;144(11):1056-9.

PMID: 19917943 [PubMed - indexed for MEDLINE]</textarea>
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
                                        id="mform_journal_submit">Submit</button>
                            </div>
                        </div>

                    </div><!-- .panel-body -->
                </div><!-- .panel.widget -->
            </div><!-- .col-md-12 -->
        </div><!-- .row -->

    </form>
</div><!-- .vd_content-section -->