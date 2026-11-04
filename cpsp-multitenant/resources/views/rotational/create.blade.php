@extends('layouts.app')
@section('title', 'Add Rotational Training | Isra e-Logbook')
@section('group_rotational', 'is-active is-open')
@section('nav_rotational_add', 'is-active')
@section('main_class', 'elog-main--fluid')

@section('content')
<div class="elog-page-head">
    <h1 class="elog-page-title">Add Rotational Training</h1>
    <a class="btn btn--blue" href="{{ route('rotational.index', ['program' => request('program')]) }}">List All</a>
</div>

@php
$rotationalGroups = [
    10 => [
        'label' => 'Cardiology',
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
        'label' => 'Clinical Haematology',
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
        'label' => 'Dermatology',
        'children' => [
            2559 => 'Psoriasis',
            2560 => 'Scabies',
            2561 => 'HS Purpura',
            2562 => 'Erythema Nodosum',
            2563 => 'Fixed Drug Eruption',
        ]
    ],
    16 => [
        'label' => 'Gastroenterology',
        'children' => [
            44 => 'Peritonal Aspiration',
            45 => 'Liver Biopsy',
            46 => 'Upper GI Endoscopy',
            47 => 'colonoscopy / sigmoidoscopy',
            48 => 'variceal banding / Sclerothrepy',
        ]
    ],
    12 => [
        'label' => 'Nephrology',
        'children' => [
            59 => 'Haemodialysis',
            60 => 'Renal Biopsy',
            61 => 'Insertion of double lumen catheter',
        ]
    ],
    11 => [
        'label' => 'Neurology',
        'children' => [
            62 => 'CT Scan interpretation',
            63 => 'MRI interpretation',
            64 => 'EEG interpretation',
            65 => 'EMG interpretation',
        ]
    ],
    15 => [
        'label' => 'Oncology',
        'children' => [
            55 => 'Chemeotherapy',
            56 => 'Radiotherapy',
        ]
    ],
    13 => [
        'label' => 'Pulmonology',
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
        'label' => 'Cardiology (Advanced)',
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
        'label' => 'Dermatology (Advanced)',
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
        'label' => 'Endocrinology',
        'children' => [
            2547 => 'Interpretation of thyroid function tests / thyroid isotope scan / thyroid ultrasound / thyroid FNA-C',
            2548 => 'Interpretation of pituitary function tests / stimulation/suppression testing of pituitary',
            2549 => 'Interpretation of adrenal function tests / stimulation / suppression testing of adrenals',
            2550 => 'Evaluation of disorders of Gonadal dysfunction',
            2551 => 'Disorders of growth and sexual differentiation / development',
            2552 => 'Interpretation of calcium metabolism (calcium and phosphorus lab tests)',
            2553 => 'Interpretation of DEXA scan / MRI pituitary / MRI or CT Adrenals',
            2554 => 'Interpretation of glucose lab tests / HbA1c / OGTT for diagnosis of diabetes and its complications',
            2555 => 'Clinical and laboratory evaluation of patients with diabetes to evaluate glycemic, lipemic, hypertension and obesity control and its complications',
            2556 => 'Formulate a comprehensive management plan for patients with diabetes',
            2557 => 'Clinical and laboratory evaluation and management of patients with gestational diabetes',
            2558 => 'Prescribing and adjusting insulin for management with diabetes',
        ]
    ],
    39 => [
        'label' => 'Gastroenterology (Advanced)',
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
        'label' => 'Intensive Care',
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
        'label' => 'Nephrology (Advanced)',
        'children' => [
            170 => 'Haemodialysis',
            171 => 'Renal Biopsy',
            172 => 'Insertion of double lumen catheter',
            173 => 'Peritonial Dialysis',
        ]
    ],
    38 => [
        'label' => 'Neurology (Advanced)',
        'children' => [
            159 => 'CAT scan head',
            160 => 'Magnetic resonance imaging (MRI) brain/spine',
            161 => 'Electroencephalography (EGG)',
            176 => 'Electromyography Nerve conduction studies(EMG/NCS)',
        ]
    ],
    40 => [
        'label' => 'Oncology (Advanced)',
        'children' => [
            167 => 'Chemeotherapy',
            168 => 'Radiotherapy',
        ]
    ],
    43 => [
        'label' => 'Psychiatry',
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
@endphp

@if(session('flash_err'))
<div class="alert alert-danger elog-flash elog-alert-errors" role="alert" style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px;">
    <div style="font-weight: 700; font-size: 15px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-triangle-exclamation"></i> {{ session('flash_err') }}
    </div>
</div>
@endif

<div class="vd_content-section elog-content-section clearfix">
    <form class="form-horizontal elog-form" action="{{ route('rotational.store') }}" method="post" id="rotationalAddForm" novalidate>
        @csrf
        @if(request('program'))
            <input type="hidden" name="program" value="{{ request('program') }}">
        @endif

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
                                        @foreach($formTypes ?? \App\Services\LookupService::formTypes() as $val => $lab)
                                            <option value="{{ $val }}" {{ (string)old('form_type', $formOld['form_type'] ?? '') === (string)$val ? 'selected' : '' }}>{{ $lab }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Program <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="entry_for_prog_id" id="entry_for_prog_id" class="required field__control" required>
                                        <option value="">- Select Program -</option>
                                        @php
                                            $progs = $availablePrograms ?? ($tenant ? $tenant->getAvailablePrograms() : ['MD' => 'MD', 'IMM' => 'IMM']);
                                            $activeProg = strtolower((string) request('program', ''));
                                            $currProgVal = old('entry_for_prog_id', $formOld['entry_for_prog_id'] ?? '');
                                        @endphp
                                        @foreach($progs as $progCode => $progLabel)
                                            <option value="{{ $progCode }}" {{ (string)$currProgVal === (string)$progCode || (empty($currProgVal) && $activeProg === strtolower($progCode)) ? 'selected' : '' }}>{{ $progCode }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Hospt. Reg. No / Autopsy / MLC No <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="hospt_reg_no" name="hospt_reg_no" class="required field__control" required value="{{ old('hospt_reg_no', $formOld['hospt_reg_no'] ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--3">
                            <div class="col-md-6">
                                <label class="control-label">Date of Admission <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="date_of_admission" name="date_of_admission" readonly class="required field__control dateBritish" placeholder="dd-mm-yyyy" required value="{{ old('date_of_admission', $formOld['date_of_admission'] ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">Gender <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="pt_gender" id="pt_gender" class="required field__control" required>
                                        <option value="">- Select -</option>
                                        @foreach($genders ?? \App\Services\LookupService::genders() as $g)
                                            <option value="{{ $g }}" {{ (string)old('pt_gender', $formOld['pt_gender'] ?? '') === (string)$g ? 'selected' : '' }}>{{ $g }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Age <span class="vd_red">*</span></label>
                                <div class="input-group elog-age-row">
                                    <input type="text" id="pt_age" name="pt_age" class="required field__control elog-age-num" required value="{{ old('pt_age', $formOld['pt_age'] ?? '') }}">
                                    <select name="pt_age_type" id="pt_age_type" class="field__control elog-age-unit">
                                        @foreach($ageUnits ?? \App\Services\LookupService::ageUnits() as $unit)
                                            <option value="{{ $unit }}" {{ (string)old('pt_age_type', $formOld['pt_age_type'] ?? 'Year[s]') === (string)$unit ? 'selected' : '' }}>{{ $unit }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label">Diagnosis / Suspected Diagnosis / Cause <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="pt_diagnosis" name="pt_diagnosis" class="required field__control" required value="{{ old('pt_diagnosis', $formOld['pt_diagnosis'] ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Under Supervision (name)</label>
                                <div class="controls">
                                    <input type="text" id="under_sup_name" name="under_sup_name" class="field__control" placeholder="Enter supervisor name" value="{{ old('under_sup_name', $formOld['under_sup_name'] ?? (optional($assignedSupervisor)->profile?->full_name ?: optional($assignedSupervisor)->username ?: '')) }}">
                                    @if(optional($assignedSupervisor)->id)
                                        <input type="hidden" name="supervisor_id" id="supervisor_id" value="{{ old('supervisor_id', $formOld['supervisor_id'] ?? optional($assignedSupervisor)->id) }}">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label">Level</label>
                                <div class="controls">
                                    <select name="level_id" id="level_id" class="field__control">
                                        <option value="">- Select -</option>
                                        @foreach($levels ?? \App\Services\LookupService::levels() as $val => $lab)
                                            <option value="{{ $val }}" {{ (string)old('level_id', $formOld['level_id'] ?? '') === (string)$val ? 'selected' : '' }}>{{ $lab }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Outcome</label>
                                <div class="controls">
                                    <select name="outcome_id" id="outcome_id" class="field__control">
                                        <option value="">- Select -</option>
                                        @foreach($outcomes ?? \App\Services\LookupService::outcomes() as $val => $lab)
                                            <option value="{{ $val }}" {{ (string)old('outcome_id', $formOld['outcome_id'] ?? '') === (string)$val ? 'selected' : '' }}>{{ $lab }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--1">
                            <div class="col-md-12">
                                <label class="control-label">Brief Description <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <textarea id="brief_desc" name="brief_desc" class="field__control" rows="8">{{ old('brief_desc', $formOld['brief_desc'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        @php
                            $oldRotIds = array_map('intval', (array) old('rot_id', $formOld['rot_id'] ?? []));
                            $oldRotDetailIds = array_map('intval', (array) old('rot_detail_id', $formOld['rot_detail_id'] ?? []));
                        @endphp

                        <div class="row elog-form__row elog-form__row--1">
                            <div class="col-md-12">
                                <label class="control-label">Competancy Group and Details <span class="vd_red">*</span></label>
                                <div class="controls competency-wrap">
                                    <p class="competency-hint">( Click on &quot;<span class="competency-hint__plus">+</span>&quot; sign to view competancy details. )</p>
                                    <div class="rot-tree-toolbar">
                                        <button type="button" class="btn btn--toolbar" id="rotExpandAll">Expand All</button>
                                        <button type="button" class="btn btn--toolbar" id="rotCollapseAll">Collapse All</button>
                                        <button type="button" class="btn btn--toolbar" id="rotDefault">Default</button>
                                    </div>

                                    <ul id="rotTree" class="rot-tree">
                                        @foreach($rotationalGroups as $parentId => $groupInfo)
                                        <li class="rot-tree__node">
                                            <button type="button" class="competency-tree__toggle rot-tree__toggle" data-toggle-branch aria-expanded="false">+</button>
                                            <input type="checkbox" name="rot_id[]" value="{{ $parentId }}" id="rotg_{{ $parentId }}" {{ in_array((int)$parentId, $oldRotIds) ? 'checked' : '' }}>
                                            <label class="rot-tree__label" for="rotg_{{ $parentId }}">{{ $groupInfo['label'] }}</label>
                                            <ul class="rot-tree__branch" style="display: none;">
                                                @foreach($groupInfo['children'] as $cid => $clabel)
                                                <li>
                                                    <input type="checkbox" name="rot_detail_id[]" value="{{ $cid }}" id="rotd_{{ $cid }}" {{ in_array((int)$cid, $oldRotDetailIds) ? 'checked' : '' }}>
                                                    <label for="rotd_{{ $cid }}">{{ $clabel }}</label>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--1">
                            <div class="col-md-12">
                                <label class="control-label">Alternate Procedure(s) Name ( if not found in above tree ) </label>
                                <div class="controls">
                                    <input type="text" name="alt_procedure" id="alt_procedure" class="field__control" value="{{ old('alt_procedure', $formOld['alt_procedure'] ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label">Send to Supervisor <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="std_post" id="std_post" class="field__control">
                                        <option value="No" {{ (string)old('std_post', $formOld['std_post'] ?? 'No') !== 'Yes' ? 'selected' : '' }}>No</option>
                                        <option value="Yes" {{ (string)old('std_post', $formOld['std_post'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
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
                                <button class="btn btn--submit" type="submit" name="submit" id="rotationalAddForm_submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tree = document.getElementById('rotTree');
    var form = document.getElementById('rotationalAddForm');

    if (tree) {
        tree.querySelectorAll('.rot-tree__toggle').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var node = this.closest('.rot-tree__node');
                if (!node) return;
                var branch = node.querySelector('.rot-tree__branch');
                if (!branch) return;

                var isHidden = (branch.style.display === 'none' || branch.hidden);
                if (isHidden) {
                    branch.style.display = 'block';
                    branch.hidden = false;
                    node.classList.add('rot-open');
                    this.textContent = '−';
                    this.setAttribute('aria-expanded', 'true');
                } else {
                    branch.style.display = 'none';
                    branch.hidden = true;
                    node.classList.remove('rot-open');
                    this.textContent = '+';
                    this.setAttribute('aria-expanded', 'false');
                }
            });
        });

        var btnExpand = document.getElementById('rotExpandAll');
        if (btnExpand) {
            btnExpand.addEventListener('click', function (e) {
                e.preventDefault();
                tree.querySelectorAll('.rot-tree__branch').forEach(function (b) {
                    b.style.display = 'block';
                    b.hidden = false;
                });
                tree.querySelectorAll('.rot-tree__toggle').forEach(function (t) {
                    t.textContent = '−';
                    t.setAttribute('aria-expanded', 'true');
                });
            });
        }

        var btnCollapse = document.getElementById('rotCollapseAll');
        if (btnCollapse) {
            btnCollapse.addEventListener('click', function (e) {
                e.preventDefault();
                tree.querySelectorAll('.rot-tree__branch').forEach(function (b) {
                    b.style.display = 'none';
                    b.hidden = true;
                });
                tree.querySelectorAll('.rot-tree__toggle').forEach(function (t) {
                    t.textContent = '+';
                    t.setAttribute('aria-expanded', 'false');
                });
            });
        }

        var btnDefault = document.getElementById('rotDefault');
        if (btnDefault) {
            btnDefault.addEventListener('click', function (e) {
                e.preventDefault();
                tree.querySelectorAll('.rot-tree__branch').forEach(function (b) {
                    b.style.display = 'none';
                    b.hidden = true;
                });
                tree.querySelectorAll('.rot-tree__toggle').forEach(function (t) {
                    t.textContent = '+';
                    t.setAttribute('aria-expanded', 'false');
                });
                tree.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
                    cb.checked = false;
                });
            });
        }

        tree.querySelectorAll('.rot-tree__node > input[type="checkbox"]').forEach(function (parentCb) {
            parentCb.addEventListener('change', function () {
                var li = this.closest('.rot-tree__node');
                if (!li) return;
                var checked = this.checked;
                li.querySelectorAll('.rot-tree__branch input[type="checkbox"]').forEach(function (childCb) {
                    childCb.checked = checked;
                });
            });
        });

        // Expand branches with checked checkboxes on load
        tree.querySelectorAll('.rot-tree__branch').forEach(function(branch) {
            if (branch.querySelector('input[type="checkbox"]:checked')) {
                branch.style.display = 'block';
                branch.hidden = false;
                var node = branch.closest('.rot-tree__node');
                if (node) {
                    node.classList.add('rot-open');
                    var toggleBtn = node.querySelector('.rot-tree__toggle');
                    if (toggleBtn) {
                        toggleBtn.textContent = '−';
                        toggleBtn.setAttribute('aria-expanded', 'true');
                    }
                }
            }
        });
    }

    // Client-side validation: ensures all required fields are filled without losing typed data
    if (form) {
        function resetFormSubmitBtn() {
            var submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = false;
                if (submitBtn.dataset.originalHtml) {
                    submitBtn.innerHTML = submitBtn.dataset.originalHtml;
                } else if (submitBtn.textContent.trim() === 'Submitting...') {
                    submitBtn.textContent = 'Submit';
                }
            }
            if (typeof window.resetSubmitButtons === 'function') {
                window.resetSubmitButtons();
            }
        }

        resetFormSubmitBtn();

        function setFieldInvalid(el, isInvalid) {
            if (!el) return;
            var trigger = el.id ? document.getElementById('resp_trigger_' + el.id) : null;
            if (!trigger && el.parentElement && el.parentElement.classList.contains('resp-select-container')) {
                trigger = el.parentElement.querySelector('.resp-select-trigger');
            }
            if (isInvalid) {
                el.style.border = '1.5px solid #dc2626';
                el.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.12)';
                if (trigger) {
                    trigger.classList.add('has-error');
                    trigger.style.border = '1.5px solid #dc2626';
                    trigger.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.12)';
                }
            } else {
                el.style.border = '';
                el.style.boxShadow = '';
                if (trigger) {
                    trigger.classList.remove('has-error');
                    trigger.style.border = '';
                    trigger.style.boxShadow = '';
                }
            }
        }

        form.addEventListener('submit', function (e) {
            var firstInvalid = null;

            function checkField(id) {
                var el = document.getElementById(id);
                if (!el || !el.value || el.value.trim() === '') {
                    if (el) {
                        setFieldInvalid(el, true);
                        if (!firstInvalid) {
                            firstInvalid = (el.id && document.getElementById('resp_trigger_' + el.id)) || el;
                        }
                    }
                } else if (el) {
                    setFieldInvalid(el, false);
                }
            }

            checkField('form_type');
            checkField('entry_for_prog_id');
            checkField('hospt_reg_no');
            checkField('date_of_admission');
            checkField('pt_gender');
            checkField('pt_age');
            checkField('pt_diagnosis');
            checkField('level_id');
            checkField('outcome_id');
            checkField('brief_desc');

            if (firstInvalid) {
                e.preventDefault();
                e.stopImmediatePropagation();
                resetFormSubmitBtn();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                if (typeof firstInvalid.focus === 'function') {
                    firstInvalid.focus({ preventScroll: true });
                }
                return false;
            }
        });

        // Clear error outline and keep submit button ready on typing/selecting
        form.querySelectorAll('input, select, textarea').forEach(function (field) {
            field.addEventListener('input', function () {
                resetFormSubmitBtn();
                if (this.value && this.value.trim() !== '') {
                    setFieldInvalid(this, false);
                }
            });
            field.addEventListener('change', function () {
                resetFormSubmitBtn();
                if (this.value && this.value.trim() !== '') {
                    setFieldInvalid(this, false);
                }
            });
        });
    }
});
</script>
@endpush
