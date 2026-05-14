<?php
declare(strict_types=1);

if (!defined('CPSP_FROM_DASHBOARD')) {
    header('Location: ../dashboard.php?tab=rotational&sub=add');
    exit;
}

require_once __DIR__ . '/../includes/training_constants.php';
require_once __DIR__ . '/../includes/csrf.php';

?>
<div class="vd_content-section elog-content-section clearfix">
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
                                            <option value="<?= htmlspecialchars($optVal, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) $lab, ENT_QUOTES, 'UTF-8') ?></option>
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
                                            <option value="<?= htmlspecialchars($optVal, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) $lab, ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Hospt. Reg. No / Autopsy / MLC No <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="hospt_reg_no" name="hospt_reg_no" class="required field__control" value="">
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--3">
                            <div class="col-md-6">
                                <label class="control-label">Date of Admission <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="date_of_admission" name="date_of_admission" value="" readonly class="required dateBritish field__control" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">Gender <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="pt_gender" id="pt_gender" class="required field__control" required>
                                        <option value="">- Select -</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Age <span class="vd_red">*</span></label>
                                <div class="input-group elog-age-row">
                                    <input type="text" id="pt_age" name="pt_age" value="" class="field__control elog-age-num required">
                                    <select name="pt_age_type" id="pt_age_type" class="field__control elog-age-unit">
                                        <option value="Year[s]">Year[s]</option>
                                        <option value="Month[s]">Month[s]</option>
                                        <option value="Week[s]">Week[s]</option>
                                        <option value="Day[s]">Day[s]</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label">Diagnosis / Suspected Diagnosis / Cause <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="pt_diagnosis" name="pt_diagnosis" value="" class="required field__control" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Under Supervision (name)</label>
                                <div class="controls">
                                    <input type="text" id="under_sup_name" name="under_sup_name" value="" class="field__control" />
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
                                            <option value="<?= htmlspecialchars($optVal, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) $lab, ENT_QUOTES, 'UTF-8') ?></option>
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
                                            <option value="<?= htmlspecialchars($optVal, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) $lab, ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__Row elog-form__row--1">
                            <div class="col-md-12">
                                <label class="control-label">Brief Description <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <textarea id="brief_desc" name="brief_desc" class="required" rows="8" required></textarea>
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

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="10">&nbsp;<span class="rot-tree__label">Cardiology(IMM)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="38">&nbsp;Thromvolysis in acute MI</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="39">&nbsp;Management of Arrythmias-Drug / Defibrillation</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="40">&nbsp;ECG recording and reporting</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="41">&nbsp;ETT</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="42">&nbsp;ECHO</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="43">&nbsp;CPR</li>
                                            </ul>
                                        </li>

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="360">&nbsp;<span class="rot-tree__label">Clinical Heamatology(IMM)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2540">&nbsp;Routine Haematology</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2541">&nbsp;Haemoglobinopathies</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2542">&nbsp;Coagulation disorders</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2543">&nbsp;Stem cell transplantation</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2544">&nbsp;Malignant haematology</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2545">&nbsp;Blood transfusion</li>
                                            </ul>
                                        </li>

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="361">&nbsp;<span class="rot-tree__label">Dermatology(IMM)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2559">&nbsp;Psoriasis</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2560">&nbsp;Scabies</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2561">&nbsp;HS Purpura</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2562">&nbsp;Erythema Nodosum</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2563">&nbsp;Fixed Drug Eruption</li>
                                            </ul>
                                        </li>

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="16">&nbsp;<span class="rot-tree__label">Gastroenterology(IMM)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="44">&nbsp;Peritonal Aspiration</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="45">&nbsp;Liver Biopsy</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="46">&nbsp;Upper GI Endoscopy</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="47">&nbsp;colonoscopy / sigmoidoscopy</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="48">&nbsp;variceal banding / Sclerothrepy</li>
                                            </ul>
                                        </li>

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="12">&nbsp;<span class="rot-tree__label">Nephrology(IMM)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="59">&nbsp;Haemodialysis</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="60">&nbsp;Renal Biopsy</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="61">&nbsp;Insertion of double lumen catheter</li>
                                            </ul>
                                        </li>

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="11">&nbsp;<span class="rot-tree__label">Neurology(IMM)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="62">&nbsp;CT Scan interpretation</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="63">&nbsp;MRI interpretation</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="64">&nbsp;EEG interpretation</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="65">&nbsp;EMG interpretation</li>
                                            </ul>
                                        </li>

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="15">&nbsp;<span class="rot-tree__label">Oncology(IMM)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="55">&nbsp;Chemeotherapy</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="56">&nbsp;Radiotherapy</li>
                                            </ul>
                                        </li>

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="13">&nbsp;<span class="rot-tree__label">Pulmonology(IMM)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="49">&nbsp;Pleural Aspiration</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="50">&nbsp;Pleural Biopsy</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="51">&nbsp;Chest Intubation</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="52">&nbsp;Broncoscopy</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="53">&nbsp;Pulmonolary function test</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="54">&nbsp;Blood gasses interpertation</li>
                                            </ul>
                                        </li>

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="36">&nbsp;<span class="rot-tree__label">Cardiology(FCPS - II)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="148">&nbsp;Thrombolysis in acute MI</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="149">&nbsp;Management of arrythmias- Drug / Defibrillation</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="150">&nbsp;ECG recordings and reporting</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="151">&nbsp;Excercise tolerence test (ETT)</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="152">&nbsp;Echocardiography</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="153">&nbsp;Cardio pulmonary resucitation (CPR)</li>
                                            </ul>
                                        </li>

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="320">&nbsp;<span class="rot-tree__label">Dermatology(FCPS - II)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2395">&nbsp;Cellulitis</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2396">&nbsp;Cutaneous drug reactions</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2397">&nbsp;Herpes zoster</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2398">&nbsp;Disseminated herpes simplex</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2399">&nbsp;Pruritis</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2400">&nbsp;Cutaneous manifestations of systemic disease</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2401">&nbsp;Drugs used for the management of these disorders</li>
                                            </ul>
                                        </li>

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="41">&nbsp;<span class="rot-tree__label">Endocrinology(FCPS - II)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2547">&nbsp;Interpretation of thyroid function tests / thyroid isotope scan / thyroid ultrasound / thyroid FNA-C</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2548">&nbsp;Interpretation of pituitary function tests / stimulation/suppression testing of pituitary</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2549">&nbsp;Interpretation of adrenal function tests / stimulation / suppression testing of adrenals</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2550">&nbsp;Evaluation of disorders of Gonadal dysfunction</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2551">&nbsp;Disorders of growth and sexual differentiation / development</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2552">&nbsp;(Interpretation of calcium metabolism (calcium and phosphorus lab tests</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2553">&nbsp;Interpretation of DEXA scan / MRI pituitary / MRI or CT Adrenals</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2554">&nbsp;Interpretation of glucose lab tests / HbA1c / OGTT for diagnosis of diabetes and its complications</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2555">&nbsp;Clinical and laboratory evaluation of patients with diabetes to evaluate glycemic, lipemic, hypertension and obesity control and its complications</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2556">&nbsp;Formulate a comprehensive management plan for patients with diabetes</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2557">&nbsp;Clinical and laboratory evaluation and management of patients with gestational diabetes</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2558">&nbsp;Prescribing and adjusting insulin for management with diabetes</li>
                                            </ul>
                                        </li>

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="39">&nbsp;<span class="rot-tree__label">Gaetroenterology(FCPS - II)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="162">&nbsp;Peritonial Aspiration</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="163">&nbsp;Liver Biopsy</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="164">&nbsp;Upper GI Endoscopy</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="165">&nbsp;Colonoscopy / sigmoidoscopy</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="166">&nbsp;Variceal banding / Sclerothrepy</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="2546">&nbsp;Endotracheal Intubation</li>
                                            </ul>
                                        </li>

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="35">&nbsp;<span class="rot-tree__label">Intensive Care(FCPS - II)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="142">&nbsp;Endotracheal Intubtion</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="143">&nbsp;Insertion of CVP line</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="144">&nbsp;Arterial puncture</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="145">&nbsp;Mechanical Ventilation</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="146">&nbsp;Cardio pulmonary resuscitation (CPR)</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="147">&nbsp;Blood gases interpertation</li>
                                            </ul>
                                        </li>

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="42">&nbsp;<span class="rot-tree__label">Nephrology(FCPS - II)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="170">&nbsp;Haemodialysis</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="171">&nbsp;Renal Biopsy</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="172">&nbsp;Insertion of double lumen catheter</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="173">&nbsp;Peritonial Dialysis</li>
                                            </ul>
                                        </li>

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="38">&nbsp;<span class="rot-tree__label">Neurology(FCPS - II)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="159">&nbsp;CAT scan head</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="160">&nbsp;Magnetic resonance imaging (MRI) brain/spine</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="161">&nbsp;Electroencephalography (EGG)</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="176">&nbsp;Electromyography Nerve conduction studies(EMG/NCS)</li>
                                            </ul>
                                        </li>

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="40">&nbsp;<span class="rot-tree__label">Oncology(FCPS - II)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="167">&nbsp;Chemeotherapy</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="168">&nbsp;Radiotherapy</li>
                                            </ul>
                                        </li>

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="43">&nbsp;<span class="rot-tree__label">Psychiatry(FCPS - II)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="174">&nbsp;Psychotherapy Sessions</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="175">&nbsp;Electro convulsive therapy (ECT)</li>
                                            </ul>
                                        </li>

                                        <li class="rot-tree__node">
                                            <span class="rot-tree__toggle" role="button" tabindex="0">+</span>
                                            <input type="checkbox" name="rot_id[]" value="37">&nbsp;<span class="rot-tree__label">Pulmonolgy(FCPS - II)</span>
                                            <ul class="rot-tree__branch" hidden>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="154">&nbsp;Pleural Aspiration</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="155">&nbsp;Pleural biopsy</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="156">&nbsp;Chest intubation</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="157">&nbsp;Broncoscopy</li>
                                                <li><input type="checkbox" name="rot_detail_id[]" value="158">&nbsp;Lung function test</li>
                                            </ul>
                                        </li>

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
                                    <input type="text" name="alt_procedure" value="" class="field__control" />
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label">Send to Supervisor <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="std_post" id="std_post" class="field__control">
                                        <option value="No">No&nbsp;</option>
                                        <option value="Yes">Yes&nbsp;</option>
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
