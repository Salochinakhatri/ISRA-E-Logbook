<?php

declare(strict_types=1);

/** @var list<string> $formErrors */
/** @var array<string,mixed> $formOld */

if (!defined('Isra_FROM_DASHBOARD')) {
    header('Location: ../dashboard.php?tab=training&sub=add');
    exit;
}

require_once __DIR__ . '/../includes/competency_tree.php';
require_once __DIR__ . '/../data/competencies.php';

$formErrors = $formErrors ?? [];
$old = $formOld ?? [];
$program = $program ?? '';

?>
<?php if ($formErrors !== []): ?>
    <div class="alert alert-error elog-flash" role="alert">
        <?php foreach ($formErrors as $e): ?>
            <div><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="vd_content-section elog-content-section clearfix">
    <form class="form-horizontal elog-form" action="training_save.php" method="post" id="trainingAddForm" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="fcps_program" value="<?= htmlspecialchars($program, ENT_QUOTES, 'UTF-8') ?>">

        <?php if ($program !== ''): ?>
        <div class="elog-program-badge">
            <?php if ($program === 'urogyn'): ?>
                <span class="badge badge--urogyn"><i class="fa-solid fa-stethoscope"></i> UROGYNAECOLOGY (FCPS)</span>
            <?php elseif ($program === 'obgyn'): ?>
                <span class="badge badge--obgyn"><i class="fa-solid fa-heart-pulse"></i> OBSTETRICS AND GYNAECOLOGY (FCPS)</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="panel widget elog-panel elog-panel--form">
                    <div class="panel-heading vd_bg-grey elog-panel__head">
                        <h3 class="panel-title"><span class="menu-icon"><i class="fa-solid fa-chart-bar"></i></span> Entry Details</h3>
                    </div>
                    <div class="panel-body">
                        
                        <!-- Row 1: Form Type & Hospital Reg No. -->
                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label" for="form_type">Form Type <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="form_type" id="form_type" class="field__control required">
                                        <option value="">- Select -</option>
                                        <?php foreach (training_form_type_options($program) as $val => $lab): ?>
                                            <option value="<?= htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8') ?>" <?= (string)($old['form_type'] ?? '') === (string)$val ? 'selected' : '' ?>><?= htmlspecialchars($lab, ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="control-label" for="hospt_reg_no">Hospt. Reg. No / Autopsy / MLC No <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="hospt_reg_no" name="hospt_reg_no" class="field__control required" value="<?= htmlspecialchars((string)($old['hospt_reg_no'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
                                </div>
                            </div>
                        </div>

                        <!-- Row 2: Date of Admission, Gender, and Age -->
                        <div class="row elog-form__row elog-form__row--3">
                            <div class="col-md-6">
                                <label class="control-label" for="date_of_admission">Date of Admission <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="date_of_admission" name="date_of_admission" value="<?= htmlspecialchars((string)($old['date_of_admission'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" readonly class="field__control required dateBritish" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label" for="pt_gender">Gender <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="pt_gender" id="pt_gender" class="field__control required">
                                        <option value="">- Select -</option>
                                        <option value="Male" <?= ($old['pt_gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= ($old['pt_gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Age <span class="vd_red">*</span></label>
                                <div class="elog-age-row">
                                    <div class="elog-age-num">
                                        <input type="text" id="pt_age" name="pt_age" value="<?= htmlspecialchars((string)($old['pt_age'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="field__control required">
                                    </div>
                                    <div class="elog-age-unit">
                                        <select name="pt_age_type" id="pt_age_type" class="field__control">
                                            <option value="Year[s]" <?= ($old['pt_age_type'] ?? '') === 'Year[s]' ? 'selected' : '' ?>>Year[s]</option>
                                            <option value="Month[s]" <?= ($old['pt_age_type'] ?? '') === 'Month[s]' ? 'selected' : '' ?>>Month[s]</option>
                                            <option value="Week[s]" <?= ($old['pt_age_type'] ?? '') === 'Week[s]' ? 'selected' : '' ?>>Week[s]</option>
                                            <option value="Day[s]" <?= ($old['pt_age_type'] ?? '') === 'Day[s]' ? 'selected' : '' ?>>Day[s]</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 3: Diagnosis & Under Supervision -->
                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label" for="pt_diagnosis">Diagnosis / Suspected Diagnosis / Cause <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="pt_diagnosis" name="pt_diagnosis" class="field__control required" value="<?= htmlspecialchars((string)($old['pt_diagnosis'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="under_sup_name">Under Supervision (name)</label>
                                <div class="controls">
                                    <input type="text" id="under_sup_name" name="under_sup_name" class="field__control" value="<?= htmlspecialchars((string)($old['under_sup_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
                                </div>
                            </div>
                        </div>

                        <!-- Row 4: Level & Outcome -->
                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label" for="level_id">Level</label>
                                <div class="controls">
                                    <select id="level_id" name="level_id" class="field__control">
                                        <option value="">- Select -</option>
                                        <?php foreach (training_level_options() as $val => $lab): ?>
                                            <option value="<?= htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8') ?>" <?= (string)($old['level_id'] ?? '') === (string)$val ? 'selected' : '' ?>><?= htmlspecialchars($lab, ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="outcome_id">Outcome</label>
                                <div class="controls">
                                    <select id="outcome_id" name="outcome_id" class="field__control">
                                        <option value="">- Select -</option>
                                        <?php foreach (training_outcome_options() as $val => $lab): ?>
                                            <option value="<?= htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8') ?>" <?= (string)($old['outcome_id'] ?? '') === (string)$val ? 'selected' : '' ?>><?= htmlspecialchars($lab, ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Row 5: Brief Description -->
                        <div class="row elog-form__row elog-form__row--1">
                            <div class="col-md-12">
                                <label class="control-label" for="brief_desc">Brief Description <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <textarea id="brief_desc" name="brief_desc" class="field__control required" rows="8"><?= htmlspecialchars((string)($old['brief_desc'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Row 6: Program only (Send to Supervisor moved below) -->
                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label" for="entry_for_prog_id">Program <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="entry_for_prog_id" id="entry_for_prog_id" class="field__control required">
                                        <option value="">- Select Program -</option>
                                        <?php foreach (training_program_options() as $val => $lab): ?>
                                            <option value="<?= htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8') ?>" <?= (string)($old['entry_for_prog_id'] ?? '') === (string)$val ? 'selected' : '' ?>><?= htmlspecialchars($lab, ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Competency Tree -->
                        <div class="row elog-form__row elog-form__row--1" style="margin-top: 1.5rem;">
                            <div class="col-md-12">
                                <?php if ($program === 'urogyn'): ?>
                                    <label class="control-label">Competancy Group and Details <span class="vd_red">*</span></label>
                                <?php else: ?>
                                    <h4 style="color: #2b7d4d; font-weight: 700; border-bottom: 1px solid #ddd; padding-bottom: 0.5rem; margin-bottom: 1rem;">Competency Group and Details <span class="vd_red">*</span></h4>
                                    <div class="competency-hint">Please check the applicable competency group(s) and detail(s) below. Use <span class="competency-hint__plus">+</span> to expand groups.</div>
                                    <div class="competency-toolbar">
                                        <button type="button" class="btn--toolbar" id="compExpandAll">Expand All</button>
                                        <button type="button" class="btn--toolbar" id="compCollapseAll">Collapse All</button>
                                        <button type="button" class="btn--toolbar" id="compDefault">Clear Choices</button>
                                    </div>
                                    <div class="controls">
                                        <?php render_competency_tree(competency_tree_data($program), 'com'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Alternate Competency -->
                        <div class="row elog-form__row elog-form__row--1" style="margin-top: 1rem;">
                            <div class="col-md-12">
                                <label class="control-label" for="alt_procedure">
                                    <?php if ($program === 'urogyn'): ?>
                                        Alternate Competancy Group(s) and Details ( if not found in above tree )
                                    <?php else: ?>
                                        Alternate Competency Group(s) and Details (if not found in above tree)
                                    <?php endif; ?>
                                </label>
                                <div class="controls">
                                    <input type="text" id="alt_procedure" name="alt_procedure" class="field__control" value="<?= htmlspecialchars((string)($old['alt_procedure'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
                                </div>
                            </div>
                        </div>

                        <!-- Send to Supervisor (below Alternate Competency as requested) -->
                        <div class="row elog-form__row elog-form__row--2" style="margin-top: 1rem;">
                            <div class="col-md-6">
                                <label class="control-label" for="std_post">Send to Supervisor <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="std_post" id="std_post" class="field__control required">
                                        <option value="No" <?= ($old['std_post'] ?? '') === 'No' ? 'selected' : '' ?>>No</option>
                                        <option value="Yes" <?= ($old['std_post'] ?? '') === 'Yes' ? 'selected' : '' ?>>Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" style="display: flex; align-items: flex-end;">
                                <div style="background-color: #fcf8e3; border: 1px solid #faebcc; color: #8a6d3b; padding: 12px 18px; border-radius: 4px; display: flex; align-items: center; gap: 12px; width: 100%; margin-bottom: 0.35rem; box-sizing: border-box;">
                                    <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.35rem; color: #f0ad4e;"></i>
                                    <span style="font-size: 0.92rem; line-height: 1.4;">
                                        <strong style="color: #c09853; font-weight: 700;">Warning!</strong> Once you select 'Yes' then you can not edit this entry again.
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions-condensed row elog-form__row elog-form__row--1 mgbt-xs-0" style="margin-top: 1.5rem;">
                            <div class="col-sm-12">
                                <button class="btn btn--submit" type="submit" name="submit2" id="mform_submit">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
window. Isra_FORM_OLD = <?= json_encode([
    'com_id' => array_map('intval', $old['com_id'] ?? []),
    'com_detail_id' => array_map('intval', $old['com_detail_id'] ?? [])
]) ?>;
</script>
