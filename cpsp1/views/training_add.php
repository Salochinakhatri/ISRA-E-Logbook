<?php

declare(strict_types=1);

/** @var list<string> $formErrors */
/** @var array<string,mixed> $old */

if (!defined('Isra_FROM_DASHBOARD')) {
    header('Location: ../dashboard.php?tab=training&sub=add');
    exit;
}

require_once __DIR__ . '/../includes/training_constants.php';

$formErrors = $formErrors ?? [];
$old = $formOld ?? [];

if (!function_exists('training_add_oldv')) {
    /**
     * @param array<string,mixed> $old
     */
    function training_add_oldv(array $old, string $key, string $default = ''): string
    {
        $v = $old[$key] ?? $default;

        return htmlspecialchars(is_scalar($v) ? (string) $v : $default, ENT_QUOTES, 'UTF-8');
    }
}

require_once __DIR__ . '/../data/competencies.php';
require_once __DIR__ . '/../includes/competency_tree.php';

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

        <div class="row">
            <div class="col-md-12">
                <div class="panel widget elog-panel elog-panel--form">
                    <div class="panel-heading vd_bg-grey elog-panel__head">
                        <h3 class="panel-title"><span class="menu-icon"><i class="fa-solid fa-chart-bar"></i></span> Entry Details</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label">Form Type <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="form_type" id="form_type" class="required field__control" required>
                                        <option value="">- Select -</option>
                                        <?php foreach (training_form_type_options() as $val => $lab): ?>
                                            <?php $optVal = (string) $val; ?>
                                            <option value="<?= htmlspecialchars($optVal, ENT_QUOTES, 'UTF-8') ?>"<?= training_add_oldv($old, 'form_type') === $optVal ? ' selected' : '' ?>><?= htmlspecialchars((string) $lab, ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Hospt. Reg. No / Autopsy / MLC No <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="hospt_reg_no" name="hospt_reg_no" class="required field__control" required value="<?= training_add_oldv($old, 'hospt_reg_no') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--3">
                            <div class="col-md-6">
                                <label class="control-label"><span id="date_caption">Date of Admission</span> <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="date_of_admission" name="date_of_admission" value="<?= training_add_oldv($old, 'date_of_admission') ?>" readonly class="required dateBritish field__control" data-date-format="dd-mm-yyyy" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">Gender <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="pt_gender" id="pt_gender" class="required field__control" required>
                                        <option value="">- Select -</option>
                                        <option value="Male"<?= training_add_oldv($old, 'pt_gender') === 'Male' ? ' selected' : '' ?>>Male</option>
                                        <option value="Female"<?= training_add_oldv($old, 'pt_gender') === 'Female' ? ' selected' : '' ?>>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Age <span class="vd_red">*</span></label>
                                <div class="input-group elog-age-row">
                                    <input type="text" id="pt_age" name="pt_age" value="<?= training_add_oldv($old, 'pt_age') ?>" class="field__control elog-age-num" required>
                                    <select name="pt_age_type" id="pt_age_type" class="field__control elog-age-unit">
                                        <?php
                                        $ageTypes = ['Year[s]', 'Month[s]', 'Week[s]', 'Day[s]'];
                                        $curAgeType = (string) ($old['pt_age_type'] ?? 'Year[s]');
                                        foreach ($ageTypes as $at):
                                        ?>
                                            <option value="<?= htmlspecialchars($at, ENT_QUOTES, 'UTF-8') ?>"<?= $curAgeType === $at ? ' selected' : '' ?>><?= htmlspecialchars($at, ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label">Diagnosis / Suspected Diagnosis / Cause <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="pt_diagnosis" name="pt_diagnosis" value="<?= training_add_oldv($old, 'pt_diagnosis') ?>" class="required field__control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Under Supervision (name)</label>
                                <div class="controls">
                                    <input type="text" id="under_sup_name" name="under_sup_name" value="<?= training_add_oldv($old, 'under_sup_name') ?>" class="field__control">
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label">Level</label>
                                <div class="controls">
                                    <select id="level_id" name="level_id" class="field__control">
                                        <option value="">- Select -</option>
                                        <?php foreach (training_level_options() as $val => $lab): ?>
                                            <?php $optVal = (string) $val; ?>
                                            <option value="<?= htmlspecialchars($optVal, ENT_QUOTES, 'UTF-8') ?>"<?= training_add_oldv($old, 'level_id') === $optVal ? ' selected' : '' ?>><?= htmlspecialchars((string) $lab, ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Outcome</label>
                                <div class="controls">
                                    <select id="outcome_id" name="outcome_id" class="field__control">
                                        <option value="">- Select -</option>
                                        <?php foreach (training_outcome_options() as $val => $lab): ?>
                                            <?php $optVal = (string) $val; ?>
                                            <option value="<?= htmlspecialchars($optVal, ENT_QUOTES, 'UTF-8') ?>"<?= training_add_oldv($old, 'outcome_id') === $optVal ? ' selected' : '' ?>><?= htmlspecialchars((string) $lab, ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--1">
                            <div class="col-md-12">
                                <label class="control-label">Brief Description <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <textarea id="brief_desc" name="brief_desc" class="required" rows="8" required><?= training_add_oldv($old, 'brief_desc') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--1">
                            <div class="col-md-3">
                                <label class="control-label">Program <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="entry_for_prog_id" id="entry_for_prog_id" class="required field__control" required>
                                        <option value="">- Select Program -</option>
                                        <?php foreach (training_program_options() as $val => $lab): ?>
                                            <?php $optVal = (string) $val; ?>
                                            <option value="<?= htmlspecialchars($optVal, ENT_QUOTES, 'UTF-8') ?>"<?= training_add_oldv($old, 'entry_for_prog_id') === $optVal ? ' selected' : '' ?>><?= htmlspecialchars((string) $lab, ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="competancy_div">
                            <div class="row elog-form__row elog-form__row--1">
                                <div class="col-md-12">
                                    <label class="control-label">Competancy Group and Details <span class="vd_red">*</span></label>
                                    <div class="controls competency-wrap">
                                        <p class="competency-hint">( Click on &quot;<span class="competency-hint__plus">+</span>&quot; sign to view competancy details. )</p>
                                        <div class="competency-toolbar">
                                            <button type="button" class="btn btn--toolbar" id="compExpandAll">Expand All</button>
                                            <button type="button" class="btn btn--toolbar" id="compCollapseAll">Collapse All</button>
                                            <button type="button" class="btn btn--toolbar" id="compDefault">Default</button>
                                        </div>
                                        <?php render_competency_tree(competency_tree_data()); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row elog-form__row elog-form__row--1">
                                <div class="col-md-12">
                                    <label class="control-label">Alternate Competancy Group(s) and Details ( if not found in above tree )</label>
                                    <div class="controls">
                                        <input type="text" name="alt_procedure" value="<?= training_add_oldv($old, 'alt_procedure') ?>" class="field__control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label">Send to Supervisor <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <?php $sp = (string) ($old['std_post'] ?? 'No'); ?>
                                    <select name="std_post" id="std_post" class="field__control">
                                        <option value="No"<?= $sp === 'No' ? ' selected' : '' ?>>No</option>
                                        <option value="Yes"<?= $sp === 'Yes' ? ' selected' : '' ?>>Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-warning elog-warn-inline">
                                    <span class="vd_alert-icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
                                    <strong>Warning!</strong> Once you select &apos;Yes&apos; then you can not edit this entry again.
                                </div>
                            </div>
                        </div>

                        <div class="form-actions-condensed row elog-form__row elog-form__row--1 mgbt-xs-0">
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

<?php
$oldCom = [];
if (!empty($old['com_id']) && is_array($old['com_id'])) {
    foreach ($old['com_id'] as $v) {
        $oldCom[] = (int) $v;
    }
}
$oldDet = [];
if (!empty($old['com_detail_id']) && is_array($old['com_detail_id'])) {
    foreach ($old['com_detail_id'] as $v) {
        $oldDet[] = (int) $v;
    }
}
?>
<script>
window.Isra_FORM_OLD = <?= json_encode(['com_id' => $oldCom, 'com_detail_id' => $oldDet], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?: '{}' ?>;
</script>
