@extends('layouts.app')
@section('title', 'Add Training Entry | Isra e-Logbook')
@section('group_training', 'is-active is-open')
@section('nav_training_add', 'is-active')
@section('main_class', 'elog-main--fluid')

@section('content')
@include('partials.program-badge', ['program' => request('program', $program ?? '')])
<div class="elog-page-head">
    <h1 class="elog-page-title">Add Training Entry</h1>
    <a class="btn btn--blue" href="{{ route('training.index', ['program' => request('program')]) }}">List All</a>
</div>

@if($formErrors)
<div class="alert alert-error elog-flash" role="alert">
    @foreach($formErrors as $e)
        <div>{{ $e }}</div>
    @endforeach
</div>
@endif

<div class="vd_content-section elog-content-section clearfix">
    <form class="form-horizontal elog-form" action="{{ route('training.store') }}" method="post" id="trainingAddForm" novalidate>
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
                                            <option value="{{ $val }}" {{ ($formOld['form_type'] ?? '') == $val ? 'selected' : '' }}>{{ $lab }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Hospt. Reg. No / Autopsy / MLC No <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="hospt_reg_no" name="hospt_reg_no" class="required field__control" required value="{{ $formOld['hospt_reg_no'] ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--3">
                            <div class="col-md-6">
                                <label class="control-label"><span id="date_caption">Date of Admission</span> <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="date_of_admission" name="date_of_admission" value="{{ $formOld['date_of_admission'] ?? '' }}" readonly class="required field__control dateBritish" placeholder="dd-mm-yyyy" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">Gender <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="pt_gender" id="pt_gender" class="required field__control" required>
                                        <option value="">- Select -</option>
                                        @foreach($genders ?? \App\Services\LookupService::genders() as $g)
                                            <option value="{{ $g }}" {{ ($formOld['pt_gender'] ?? '') === $g ? 'selected' : '' }}>{{ $g }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Age <span class="vd_red">*</span></label>
                                <div class="input-group elog-age-row">
                                    <input type="text" id="pt_age" name="pt_age" class="required field__control elog-age-num" required value="{{ $formOld['pt_age'] ?? '' }}">
                                    <select name="pt_age_type" id="pt_age_type" class="field__control elog-age-unit">
                                        @foreach($ageUnits ?? \App\Services\LookupService::ageUnits() as $unit)
                                            <option value="{{ $unit }}" {{ ($formOld['pt_age_type'] ?? 'Year[s]') === $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label">Diagnosis / Suspected Diagnosis / Cause <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <input type="text" id="pt_diagnosis" name="pt_diagnosis" class="required field__control" required value="{{ $formOld['pt_diagnosis'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Under Supervision (name)</label>
                                <div class="controls">
                                    <input type="text" id="under_sup_name" name="under_sup_name" class="field__control" value="{{ $formOld['under_sup_name'] ?? '' }}">
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
                                            <option value="{{ $val }}" {{ ($formOld['level_id'] ?? '') == $val ? 'selected' : '' }}>{{ $lab }}</option>
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
                                            <option value="{{ $val }}" {{ ($formOld['outcome_id'] ?? '') == $val ? 'selected' : '' }}>{{ $lab }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--1">
                            <div class="col-md-12">
                                <label class="control-label">Brief Description <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <textarea id="brief_desc" name="brief_desc" class="field__control" rows="8">{{ $formOld['brief_desc'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--1">
                            <div class="col-md-3">
                                <label class="control-label">Program <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="entry_for_prog_id" id="entry_for_prog_id" class="required field__control" required>
                                        <option value="">- Select Program -</option>
                                        @php
                                            $progs = $availablePrograms ?? ($tenant ? $tenant->getAvailablePrograms() : ['MD' => 'MD', 'IMM' => 'IMM']);
                                            $activeProg = strtolower((string) request('program', ''));
                                        @endphp
                                        @foreach($progs as $progCode => $progLabel)
                                            <option value="{{ $progCode }}" {{ ($formOld['entry_for_prog_id'] ?? '') === $progCode || $activeProg === strtolower($progCode) ? 'selected' : '' }}>{{ $progCode }}</option>
                                        @endforeach
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
                                        <ul id="competencyTreeRoot" class="competency-tree">
                                            @foreach($compTree as $group)
                                            <li class="competency-tree__node competency-tree__node--parent">
                                                <button type="button" class="competency-tree__toggle" data-toggle-branch aria-expanded="false">+</button>
                                                <input type="checkbox" name="com_id[]" value="{{ $group['id'] }}" id="comg_{{ $group['id'] }}">
                                                <label class="competency-tree__text" for="comg_{{ $group['id'] }}">{{ $group['label'] }}</label>
                                                <ul class="competency-tree__branch">
                                                    @foreach($group['children'] as $child)
                                                    <li class="competency-tree__node competency-tree__node--leaf">
                                                        <input type="checkbox" name="com_detail_id[]" value="{{ $child['id'] }}" id="comd_{{ $child['id'] }}">
                                                        <label for="comd_{{ $child['id'] }}">{{ $child['label'] }}</label>
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
                                    <label class="control-label">Alternate Competancy Group(s) and Details ( if not found in above tree )</label>
                                    <div class="controls">
                                        <input type="text" name="alt_procedure" value="{{ $formOld['alt_procedure'] ?? '' }}" class="field__control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label">Send to Supervisor <span class="vd_red">*</span></label>
                                <div class="controls">
                                    <select name="std_post" id="std_post" class="field__control">
                                        <option value="No" {{ ($formOld['std_post'] ?? 'No') !== 'Yes' ? 'selected' : '' }}>No</option>
                                        <option value="Yes" {{ ($formOld['std_post'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var root = document.getElementById('competencyTreeRoot');
    if (!root) return;

    // Direct toggle handler for every [+] / [−] button
    root.querySelectorAll('[data-toggle-branch]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var li = this.closest('.competency-tree__node--parent');
            if (!li) return;
            var branch = li.querySelector('.competency-tree__branch');
            if (!branch) return;

            var isHidden = (branch.style.display === 'none' || !branch.classList.contains('is-open') || window.getComputedStyle(branch).display === 'none');
            if (isHidden) {
                branch.style.display = 'block';
                branch.classList.add('is-open');
                this.textContent = '−';
                this.setAttribute('aria-expanded', 'true');
            } else {
                branch.style.display = 'none';
                branch.classList.remove('is-open');
                this.textContent = '+';
                this.setAttribute('aria-expanded', 'false');
            }
        });
    });

    // Expand All
    var btnExpand = document.getElementById('compExpandAll');
    if (btnExpand) {
        btnExpand.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            root.querySelectorAll('.competency-tree__branch').forEach(function (b) {
                b.style.display = 'block';
                b.classList.add('is-open');
            });
            root.querySelectorAll('[data-toggle-branch]').forEach(function (t) {
                t.textContent = '−';
                t.setAttribute('aria-expanded', 'true');
            });
        });
    }

    // Collapse All
    var btnCollapse = document.getElementById('compCollapseAll');
    if (btnCollapse) {
        btnCollapse.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            root.querySelectorAll('.competency-tree__branch').forEach(function (b) {
                b.style.display = 'none';
                b.classList.remove('is-open');
            });
            root.querySelectorAll('[data-toggle-branch]').forEach(function (t) {
                t.textContent = '+';
                t.setAttribute('aria-expanded', 'false');
            });
        });
    }

    // Default
    var btnDefault = document.getElementById('compDefault');
    if (btnDefault) {
        btnDefault.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            root.querySelectorAll('.competency-tree__branch').forEach(function (b) {
                b.style.display = 'none';
                b.classList.remove('is-open');
            });
            root.querySelectorAll('[data-toggle-branch]').forEach(function (t) {
                t.textContent = '+';
                t.setAttribute('aria-expanded', 'false');
            });
            root.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
                cb.checked = false;
            });
        });
    }

    // Auto check/uncheck child checkboxes when parent group checkbox is clicked
    root.querySelectorAll('.competency-tree__node--parent > input[type="checkbox"]').forEach(function (parentCb) {
        parentCb.addEventListener('change', function () {
            var li = this.closest('.competency-tree__node--parent');
            if (!li) return;
            var checked = this.checked;
            li.querySelectorAll('.competency-tree__branch input[type="checkbox"]').forEach(function (childCb) {
                childCb.checked = checked;
            });
        });
    });
});
</script>
@endpush
