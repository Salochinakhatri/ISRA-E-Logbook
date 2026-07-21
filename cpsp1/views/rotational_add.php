<?php
declare(strict_types=1);

if (!defined('CPSP_FROM_DASHBOARD')) {
    header('Location: ../dashboard.php?tab=rotational&sub=add');
    exit;
}

require_once __DIR__ . '/../includes/training_constants.php';
require_once __DIR__ . '/../includes/csrf.php';

$old = $formOld ?? [];
$formErrors = $formErrors ?? [];

$oldRotIds = [];
if (!empty($old['rot_id']) && is_array($old['rot_id'])) {
    foreach ($old['rot_id'] as $v) {
        $oldRotIds[] = (int) $v;
    }
}
$oldRotDetailIds = [];
if (!empty($old['rot_detail_id']) && is_array($old['rot_detail_id'])) {
    foreach ($old['rot_detail_id'] as $v) {
        $oldRotDetailIds[] = (int) $v;
    }
}

function rot_is_checked(array $oldList, int $id): string
{
    return in_array($id, $oldList, true) ? ' checked' : '';
}

function rot_parent_state(array $oldRotIds, array $oldRotDetailIds, int $parentId, array $childIds): array
{
    $isOpen = false;
    if (in_array($parentId, $oldRotIds, true)) {
        $isOpen = true;
    } else {
        foreach ($childIds as $cid) {
            if (in_array($cid, $oldRotDetailIds, true)) {
                $isOpen = true;
                break;
            }
        }
    }
    return [
        'li_class' => $isOpen ? 'rot-tree__node rot-open' : 'rot-tree__node',
        'toggle_text' => $isOpen ? '−' : '+',
        'ul_attr' => $isOpen ? '' : ' hidden'
    ];
}

function render_rot_node(string $label, int $parentId, array $children, array $oldRotIds, array $oldRotDetailIds): void
{
    $childIds = array_keys($children);
    $state = rot_parent_state($oldRotIds, $oldRotDetailIds, $parentId, $childIds);
    
    $parentChecked = in_array($parentId, $oldRotIds, true) ? ' checked' : '';
    
    echo '<li class="' . $state['li_class'] . '">';
    echo '<span class="rot-tree__toggle" role="button" tabindex="0">' . $state['toggle_text'] . '</span>';
    echo '<input type="checkbox" name="rot_id[]" value="' . $parentId . '"' . $parentChecked . '>&nbsp;<span class="rot-tree__label">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>';
    echo '<ul class="rot-tree__branch"' . $state['ul_attr'] . '>';
    foreach ($children as $cid => $clabel) {
        $childChecked = in_array($cid, $oldRotDetailIds, true) ? ' checked' : '';
        echo '<li><input type="checkbox" name="rot_detail_id[]" value="' . $cid . '"' . $childChecked . '>&nbsp;' . htmlspecialchars($clabel, ENT_QUOTES, 'UTF-8') . '</li>';
    }
    echo '</ul>';
    echo '</li>';
}
?>
<div class="vd_content-section elog-content-section clearfix">
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
    <form class="form-horizontal elog-form" action="rotational_save.php" method="post" name="mform2" id="mform2" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="isSubmitted" value="Y">

        <div class="row">
            <div class="col-md-12">
                <div class="panel widget elog-panel elog-panel--form">
                    <div class="panel-heading vd_bg-grey elog-panel__head">
                        <h3 class="panel-title"> <span class="menu-icon"> <i class="fa fa-bar-chart-o"></i> </span> Entry Details </h3>
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
                                            <option value="<?= htmlspecialchars($optVal, ENT_QUOTES, 'UTF-8') ?>"<?= ($old['form_type'] ?? '') === $optVal ? ' selected' : '' ?>><?= htmlspecialchars((string) $lab, ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Program <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="entry_for_prog_id" id="entry_for_prog_id" class="required field__control" required>
                                        <option value="">- Select Program -</option>
                                        <?php foreach (training_program_options() as $val => $lab): ?>
                                            <?php $optVal = (string) $val; ?>
                                            <option value="<?= htmlspecialchars($optVal, ENT_QUOTES, 'UTF-8') ?>"<?= ($old['entry_for_prog_id'] ?? '') === $optVal ? ' selected' : '' ?>><?= htmlspecialchars((string) $lab, ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Hospt. Reg. No / Autopsy / MLC No <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="hospt_reg_no" name="hospt_reg_no" class="required field__control" value="<?= htmlspecialchars((string)($old['hospt_reg_no'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--3">
                            <div class="col-md-6">
                                <label class="control-label">Date of Admission <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="date_of_admission" name="date_of_admission" value="<?= htmlspecialchars((string)($old['date_of_admission'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" readonly class="required dateBritish field__control" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">Gender <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="pt_gender" id="pt_gender" class="required field__control" required>
                                        <option value="">- Select -</option>
                                        <option value="Male"<?= ($old['pt_gender'] ?? '') === 'Male' ? ' selected' : '' ?>>Male</option>
                                        <option value="Female"<?= ($old['pt_gender'] ?? '') === 'Female' ? ' selected' : '' ?>>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Age <span class="vd_red">*</span></label>
                                <div class="input-group elog-age-row">
                                    <input type="text" id="pt_age" name="pt_age" value="<?= htmlspecialchars((string)($old['pt_age'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="field__control elog-age-num required">
                                    <select name="pt_age_type" id="pt_age_type" class="field__control elog-age-unit">
                                        <?php $curAgeType = $old['pt_age_type'] ?? 'Year[s]'; ?>
                                        <option value="Year[s]"<?= $curAgeType === 'Year[s]' ? ' selected' : '' ?>>Year[s]</option>
                                        <option value="Month[s]"<?= $curAgeType === 'Month[s]' ? ' selected' : '' ?>>Month[s]</option>
                                        <option value="Week[s]"<?= $curAgeType === 'Week[s]' ? ' selected' : '' ?>>Week[s]</option>
                                        <option value="Day[s]"<?= $curAgeType === 'Day[s]' ? ' selected' : '' ?>>Day[s]</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label">Diagnosis / Suspected Diagnosis / Cause <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="pt_diagnosis" name="pt_diagnosis" value="<?= htmlspecialchars((string)($old['pt_diagnosis'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="required field__control" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Under Supervision (name)</label>
                                <div class="controls">
                                    <input type="text" id="under_sup_name" name="under_sup_name" value="<?= htmlspecialchars((string)($old['under_sup_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="field__control" />
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
                                            <option value="<?= htmlspecialchars($optVal, ENT_QUOTES, 'UTF-8') ?>"<?= ($old['level_id'] ?? '') === $optVal ? ' selected' : '' ?>><?= htmlspecialchars((string) $lab, ENT_QUOTES, 'UTF-8') ?></option>
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
                                            <option value="<?= htmlspecialchars($optVal, ENT_QUOTES, 'UTF-8') ?>"<?= ($old['outcome_id'] ?? '') === $optVal ? ' selected' : '' ?>><?= htmlspecialchars((string) $lab, ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__Row elog-form__row--1">
                            <div class="col-md-12">
                                <label class="control-label">Brief Description <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <textarea id="brief_desc" name="brief_desc" class="required" rows="8" required><?= htmlspecialchars((string)($old['brief_desc'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--1">
                            <div class="col-md-12">
                                <label class="control-label">Competancy Group and Details <span class="vd_red">*</span></label>
                                <div class="controls competency-wrap">
                                    <p class="competency-hint">( Click on " <span class="competency-hint__plus">+</span> " sign to view competancy details. )</p>

                                    <div class="rot-tree-toolbar">
                                        <button type="button" class="btn btn--toolbar" id="rotExpandAll">Expand All</button>
                                        <button type="button" class="btn btn--toolbar" id="rotCollapseAll">Collapse All</button>
                                        <button type="button" class="btn btn--toolbar" id="rotDefault">Default</button>
                                    </div>

                                    <ul id="rotTree" class="rot-tree">
                                        <?php
                                        $rotationalGroups = [
                                            10 => [
                                                'label' => 'Cardiology(IMM)',
                                                'children' => [
                                                    38 => 'Thromvolysis in acute MI',
                                                    39 => 'Management of Arrythmias-Drug / Defibrillation',
                                                    40 => 'ECG recording and reporting',
                                                    41 => 'ETT',
                                                    42 => 'ECHO',
                                                    43 => 'CPR',
                                                ]
                                            ],
                                            360 => [
                                                'label' => 'Clinical Heamatology(IMM)',
                                                'children' => [
                                                    2540 => 'Routine Haematology',
                                                    2541 => 'Haemoglobinopathies',
                                                    2542 => 'Coagulation disorders',
                                                    2543 => 'Stem cell transplantation',
                                                    2544 => 'Malignant haematology',
                                                    2545 => 'Blood transfusion',
                                                ]
                                            ],
                                            361 => [
                                                'label' => 'Dermatology(IMM)',
                                                'children' => [
                                                    2559 => 'Psoriasis',
                                                    2560 => 'Scabies',
                                                    2561 => 'HS Purpura',
                                                    2562 => 'Erythema Nodosum',
                                                    2563 => 'Fixed Drug Eruption',
                                                ]
                                            ],
                                            16 => [
                                                'label' => 'Gastroenterology(IMM)',
                                                'children' => [
                                                    44 => 'Peritonal Aspiration',
                                                    45 => 'Liver Biopsy',
                                                    46 => 'Upper GI Endoscopy',
                                                    47 => 'colonoscopy / sigmoidoscopy',
                                                    48 => 'variceal banding / Sclerothrepy',
                                                ]
                                            ],
                                            12 => [
                                                'label' => 'Nephrology(IMM)',
                                                'children' => [
                                                    59 => 'Haemodialysis',
                                                    60 => 'Renal Biopsy',
                                                    61 => 'Insertion of double lumen catheter',
                                                ]
                                            ],
                                            11 => [
                                                'label' => 'Neurology(IMM)',
                                                'children' => [
                                                    62 => 'CT Scan interpretation',
                                                    63 => 'MRI interpretation',
                                                    64 => 'EEG interpretation',
                                                    65 => 'EMG interpretation',
                                                ]
                                            ],
                                            15 => [
                                                'label' => 'Oncology(IMM)',
                                                'children' => [
                                                    55 => 'Chemeotherapy',
                                                    56 => 'Radiotherapy',
                                                ]
                                            ],
                                            13 => [
                                                'label' => 'Pulmonology(IMM)',
                                                'children' => [
                                                    49 => 'Pleural Aspiration',
                                                    50 => 'Pleural Biopsy',
                                                    51 => 'Chest Intubation',
                                                    52 => 'Broncoscopy',
                                                    53 => 'Pulmonolary function test',
                                                    54 => 'Blood gasses interpertation',
                                                ]
                                            ],
                                            36 => [
                                                'label' => 'Cardiology(FCPS - II)',
                                                'children' => [
                                                    148 => 'Thrombolysis in acute MI',
                                                    149 => 'Management of arrythmias- Drug / Defibrillation',
                                                    150 => 'ECG recordings and reporting',
                                                    151 => 'Excercise tolerence test (ETT)',
                                                    152 => 'Echocardiography',
                                                    153 => 'Cardio pulmonary resucitation (CPR)',
                                                ]
                                            ],
                                            320 => [
                                                'label' => 'Dermatology(FCPS - II)',
                                                'children' => [
                                                    2395 => 'Cellulitis',
                                                    2396 => 'Cutaneous drug reactions',
                                                    2397 => 'Herpes zoster',
                                                    2398 => 'Disseminated herpes simplex',
                                                    2399 => 'Pruritis',
                                                    2400 => 'Cutaneous manifestations of systemic disease',
                                                    2401 => 'Drugs used for the management of these disorders',
                                                ]
                                            ],
                                            41 => [
                                                'label' => 'Endocrinology(FCPS - II)',
                                                'children' => [
                                                    2547 => 'Interpretation of thyroid function tests / thyroid isotope scan / thyroid ultrasound / thyroid FNA-C',
                                                    2548 => 'Interpretation of pituitary function tests / stimulation/suppression testing of pituitary',
                                                    2549 => 'Interpretation of adrenal function tests / stimulation / suppression testing of adrenals',
                                                    2550 => 'Evaluation of disorders of Gonadal dysfunction',
                                                    2551 => 'Disorders of growth and sexual differentiation / development',
                                                    2552 => '(Interpretation of calcium metabolism (calcium and phosphorus lab tests',
                                                    2553 => 'Interpretation of DEXA scan / MRI pituitary / MRI or CT Adrenals',
                                                    2554 => 'Interpretation of glucose lab tests / HbA1c / OGTT for diagnosis of diabetes and its complications',
                                                    2555 => 'Clinical and laboratory evaluation of patients with diabetes to evaluate glycemic, lipemic, hypertension and obesity control and its complications',
                                                    2556 => 'Formulate a comprehensive management plan for patients with diabetes',
                                                    2557 => 'Clinical and laboratory evaluation and management of patients with gestational diabetes',
                                                    2558 => 'Prescribing and adjusting insulin for management with diabetes',
                                                ]
                                            ],
                                            39 => [
                                                'label' => 'Gaetroenterology(FCPS - II)',
                                                'children' => [
                                                    162 => 'Peritonial Aspiration',
                                                    163 => 'Liver Biopsy',
                                                    164 => 'Upper GI Endoscopy',
                                                    165 => 'Colonoscopy / sigmoidoscopy',
                                                    166 => 'Variceal banding / Sclerothrepy',
                                                    2546 => 'Endotracheal Intubation',
                                                ]
                                            ],
                                            35 => [
                                                'label' => 'Intensive Care(FCPS - II)',
                                                'children' => [
                                                    142 => 'Endotracheal Intubtion',
                                                    143 => 'Insertion of CVP line',
                                                    144 => 'Arterial puncture',
                                                    145 => 'Mechanical Ventilation',
                                                    146 => 'Cardio pulmonary resuscitation (CPR)',
                                                    147 => 'Blood gases interpertation',
                                                ]
                                            ],
                                            42 => [
                                                'label' => 'Nephrology(FCPS - II)',
                                                'children' => [
                                                    170 => 'Haemodialysis',
                                                    171 => 'Renal Biopsy',
                                                    172 => 'Insertion of double lumen catheter',
                                                    173 => 'Peritonial Dialysis',
                                                ]
                                            ],
                                            38 => [
                                                'label' => 'Neurology(FCPS - II)',
                                                'children' => [
                                                    159 => 'CAT scan head',
                                                    160 => 'Magnetic resonance imaging (MRI) brain/spine',
                                                    161 => 'Electroencephalography (EGG)',
                                                    176 => 'Electromyography Nerve conduction studies(EMG/NCS)',
                                                ]
                                            ],
                                            40 => [
                                                'label' => 'Oncology(FCPS - II)',
                                                'children' => [
                                                    167 => 'Chemeotherapy',
                                                    168 => 'Radiotherapy',
                                                ]
                                            ],
                                            43 => [
                                                'label' => 'Psychiatry(FCPS - II)',
                                                'children' => [
                                                    174 => 'Psychotherapy Sessions',
                                                    175 => 'Electro convulsive therapy (ECT)',
                                                ]
                                            ],
                                            37 => [
                                                'label' => 'Pulmonolgy(FCPS - II)',
                                                'children' => [
                                                    154 => 'Pleural Aspiration',
                                                    155 => 'Pleural biopsy',
                                                    156 => 'Chest intubation',
                                                    157 => 'Broncoscopy',
                                                    158 => 'Lung function test',
                                                ]
                                            ]
                                        ];

                                        foreach ($rotationalGroups as $parentId => $groupInfo) {
                                            render_rot_node($groupInfo['label'], $parentId, $groupInfo['children'], $oldRotIds, $oldRotDetailIds);
                                        }
                                        ?>
                                    </ul><!-- #rotTree -->
                                </div>
                            </div>
                        </div>

<script>
(function () {
    'use strict';

    /* ---- helpers ---- */
    function open(node) {
        var branch = node.querySelector('.rot-tree__branch');
        var toggle = node.querySelector('.rot-tree__toggle');
        if (!branch) return;
        branch.hidden = false;
        node.classList.add('rot-open');
        toggle.textContent = '\u2212'; /* minus */
    }

    function close(node) {
        var branch = node.querySelector('.rot-tree__branch');
        var toggle = node.querySelector('.rot-tree__toggle');
        if (!branch) return;
        branch.hidden = true;
        node.classList.remove('rot-open');
        toggle.textContent = '+';
    }

    function allNodes() {
        return document.querySelectorAll('#rotTree > .rot-tree__node');
    }

    /* ---- toggle on click / Enter / Space ---- */
    document.querySelectorAll('#rotTree .rot-tree__toggle').forEach(function (btn) {
        var node = btn.closest('.rot-tree__node');
        function toggle() {
            node.classList.contains('rot-open') ? close(node) : open(node);
        }
        btn.addEventListener('click', toggle);
        btn.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); }
        });
    });

    /* ---- toolbar buttons ---- */
    document.getElementById('rotExpandAll').addEventListener('click', function () {
        allNodes().forEach(open);
    });
    document.getElementById('rotCollapseAll').addEventListener('click', function () {
        allNodes().forEach(close);
    });
    document.getElementById('rotDefault').addEventListener('click', function () {
        allNodes().forEach(close);   /* default = all collapsed */
    });
}());
</script>
                        <div class="row elog-form__row elog-form__row--1">
                            <div class="col-md-12">
                                <label class="control-label">Alternate Procedure(s) Name ( if not found in above tree ) </label>
                                <div class="controls">
                                    <input type="text" name="alt_procedure" value="<?= htmlspecialchars((string)($old['alt_procedure'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="field__control" />
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label">Send to Supervisor <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="std_post" id="std_post" class="field__control">
                                        <option value="No"<?= ($old['std_post'] ?? 'No') !== 'Yes' ? ' selected' : '' ?>>No&nbsp;</option>
                                        <option value="Yes"<?= ($old['std_post'] ?? 'No') === 'Yes' ? ' selected' : '' ?>>Yes&nbsp;</option>
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
