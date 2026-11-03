@extends('layouts.app')
@section('title', 'Add Training Entry | Isra e-Logbook')
@section('group_training', 'is-active is-open')
@section('nav_training_add', 'is-active')
@section('main_class', 'elog-main--fluid')

@push('styles')
<style>
/* ==========================================================================
   Modernized Training Create Form UI
   Consistent with CPSP Theme (#0b6040)
   ========================================================================== */
.training-create-module {
    padding: 6px 0 36px 0;
    font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
    color: #1e293b;
}

/* Breadcrumb */
.training-breadcrumb {
    margin-bottom: 16px;
}
.training-breadcrumb .breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 8px;
    list-style: none;
    padding: 0;
    margin: 0;
    font-size: 13px;
}
.training-breadcrumb .breadcrumb-item a {
    color: #64748b;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: color 0.15s;
}
.training-breadcrumb .breadcrumb-item a:hover {
    color: #0b6040;
}
.training-breadcrumb .breadcrumb-item.separator {
    color: #cbd5e1;
    font-size: 10px;
}
.training-breadcrumb .breadcrumb-item.active {
    color: #0f172a;
    font-weight: 600;
}

/* Header Banner */
.training-header-banner {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 22px 26px;
    margin-bottom: 22px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.training-header__left {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.training-badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #0b6040;
    background: #e6f4ee;
    padding: 4px 10px;
    border-radius: 20px;
    width: fit-content;
}

.training-header__title {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.01em;
}

.training-header__subtitle {
    font-size: 13.5px;
    color: #64748b;
    margin: 0;
}

.training-header__actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.btn-training-outline {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #ffffff;
    color: #0b6040 !important;
    font-size: 13.5px;
    font-weight: 700;
    padding: 9px 16px;
    border-radius: 8px;
    text-decoration: none;
    border: 1.5px solid #0b6040;
    transition: all 0.18s ease;
}
.btn-training-outline:hover {
    background: #e6f4ee;
    color: #084d33 !important;
    transform: translateY(-1px);
}

/* Alert Boxes */
.modern-alert {
    border-radius: 10px;
    padding: 16px 20px;
    margin-bottom: 22px;
    display: flex;
    gap: 14px;
    align-items: flex-start;
}
.modern-alert--danger {
    background-color: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
}
.modern-alert__icon {
    font-size: 18px;
    margin-top: 2px;
    flex-shrink: 0;
}
.modern-alert__content {
    flex: 1;
}
.modern-alert__title {
    font-weight: 700;
    font-size: 14px;
    margin-bottom: 6px;
}
.modern-alert__list {
    margin: 0;
    padding-left: 20px;
    font-size: 13px;
    line-height: 1.5;
}

/* Main Form Card */
.form-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.05);
    overflow: hidden;
    margin-bottom: 30px;
}

.form-card__head {
    background: linear-gradient(135deg, #0b6040 0%, #107c53 100%);
    color: #ffffff;
    padding: 16px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.form-card__title {
    font-size: 16px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-card__body {
    padding: 24px 28px;
}

/* Form Section Blocks */
.form-section {
    padding-bottom: 24px;
    margin-bottom: 24px;
    border-bottom: 1px solid #f1f5f9;
}
.form-section:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.form-section__header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
}
.form-section__icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #e6f4ee;
    color: #0b6040;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}
.form-section__title {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}
.form-section__subtitle {
    font-size: 12.5px;
    color: #64748b;
    margin: 2px 0 0 0;
}

/* Form Grid */
.form-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px 22px;
}
.form-grid-3 {
    display: grid;
    grid-template-columns: 2fr 1.2fr 1.8fr;
    gap: 18px 22px;
}
@media (max-width: 900px) {
    .form-grid-2,
    .form-grid-3 {
        grid-template-columns: 1fr;
    }
}

.form-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-label {
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    display: flex;
    align-items: center;
    gap: 4px;
}
.form-label .req-star {
    color: #dc2626;
    font-weight: 700;
}

.field-control {
    width: 100%;
    height: 42px;
    padding: 0 14px;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    font-size: 13.5px;
    font-family: inherit;
    color: #0f172a;
    background-color: #f8fafc;
    transition: all 0.18s ease;
    box-sizing: border-box;
}
.field-control:focus {
    outline: none;
    border-color: #0b6040;
    background-color: #ffffff;
    box-shadow: 0 0 0 3px rgba(11, 96, 64, 0.12);
}
select.field-control {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    padding-right: 36px;
    appearance: none;
}
textarea.field-control {
    height: auto;
    padding: 12px 14px;
    line-height: 1.5;
    resize: vertical;
}

/* Combined Age Input */
.elog-age-row {
    display: flex !important;
    gap: 8px !important;
    align-items: center !important;
    width: 100% !important;
}
.elog-age-row .elog-age-num {
    flex: 1 1 auto !important;
    min-width: 60px !important;
    height: 42px !important;
}
.elog-age-row .elog-age-unit {
    flex: 0 0 115px !important;
    width: 115px !important;
    height: 42px !important;
}

/* Competency Tree Section */
.competency-box {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 18px 20px;
}

.competency-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    padding-bottom: 14px;
    margin-bottom: 16px;
    border-bottom: 1px solid #e2e8f0;
}

.competency-hint {
    font-size: 12.5px;
    color: #475569;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin: 0;
}
.competency-hint i {
    color: #0b6040;
}

.tree-action-btns {
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-tree-tool {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ffffff;
    color: #334155;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    cursor: pointer;
    transition: all 0.15s ease;
}
.btn-tree-tool:hover {
    background: #0b6040;
    color: #ffffff;
    border-color: #0b6040;
}

/* Tree Structure */
.competency-tree {
    list-style: none;
    padding: 0;
    margin: 0;
    max-height: 480px;
    overflow-y: auto;
    padding-right: 6px;
}

.competency-tree__node--parent {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 8px;
    padding: 10px 14px;
    transition: border-color 0.15s ease;
}
.competency-tree__node--parent:hover {
    border-color: #cbd5e1;
}

.competency-tree__header {
    display: flex;
    align-items: center;
    gap: 10px;
}

.competency-tree__toggle {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    background: #e6f4ee;
    color: #0b6040;
    border: 1px solid #a7f3d0;
    font-size: 15px;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    line-height: 1;
    padding: 0;
    flex-shrink: 0;
    transition: background 0.15s ease;
}
.competency-tree__toggle:hover {
    background: #0b6040;
    color: #ffffff;
}

.competency-tree__header input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: #0b6040;
    cursor: pointer;
    margin: 0;
}

.competency-tree__text {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
    cursor: pointer;
    margin: 0;
    flex: 1;
}

.competency-tree__branch {
    list-style: none;
    padding: 8px 0 4px 34px;
    margin: 8px 0 0 0;
    border-top: 1px dashed #e2e8f0;
    display: none;
}

.competency-tree__branch.is-open {
    display: block;
}

.competency-tree__node--leaf {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 5px 8px;
    border-radius: 6px;
    font-size: 13px;
    color: #334155;
    transition: background 0.12s ease;
}
.competency-tree__node--leaf:hover {
    background: #f1f5f9;
}
.competency-tree__node--leaf input[type="checkbox"] {
    width: 15px;
    height: 15px;
    accent-color: #0b6040;
    cursor: pointer;
    margin: 0;
}
.competency-tree__node--leaf label {
    cursor: pointer;
    margin: 0;
    line-height: 1.4;
}

/* Submission Warning Notice */
.submission-warning-card {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 8px;
    padding: 14px 16px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    color: #92400e;
}
.submission-warning-card i {
    font-size: 16px;
    margin-top: 2px;
    color: #d97706;
}
.submission-warning-card strong {
    color: #78350f;
}

/* Form Action Buttons */
.form-actions-bar {
    display: flex;
    align-items: center;
    gap: 14px;
    padding-top: 24px;
    margin-top: 8px;
    border-top: 1px solid #e2e8f0;
}

.btn-form-submit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #0b6040 0%, #117850 100%);
    color: #ffffff;
    font-size: 14px;
    font-weight: 700;
    padding: 11px 24px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(11, 96, 64, 0.22);
    transition: all 0.2s ease;
}
.btn-form-submit:hover {
    background: linear-gradient(135deg, #084d33 0%, #0b6040 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(11, 96, 64, 0.28);
}

.btn-form-cancel {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ffffff;
    color: #64748b;
    font-size: 14px;
    font-weight: 600;
    padding: 11px 20px;
    border-radius: 8px;
    border: 1.5px solid #cbd5e1;
    text-decoration: none;
    transition: all 0.18s ease;
}
.btn-form-cancel:hover {
    background: #f8fafc;
    color: #1e293b;
    border-color: #94a3b8;
}
</style>
@endpush

@section('content')
<div class="training-create-module">
    {{-- Breadcrumb --}}
    <nav class="training-breadcrumb" aria-label="Breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i> Home</a></li>
            <li class="breadcrumb-item separator"><i class="fa-solid fa-chevron-right"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('training.index', ['program' => request('program')]) }}">Training</a></li>
            <li class="breadcrumb-item separator"><i class="fa-solid fa-chevron-right"></i></li>
            <li class="breadcrumb-item active">Add New Entry</li>
        </ol>
    </nav>

    {{-- Page Header Banner --}}
    <div class="training-header-banner">
        <div class="training-header__left">
            <span class="training-badge-pill"><i class="fa-solid fa-file-medical"></i> Clinical Training Module</span>
            <h1 class="training-header__title">Add Training Entry</h1>
            <p class="training-header__subtitle">Record patient encounter, procedure details, supervisor info, and map curriculum competencies.</p>
        </div>
        <div class="training-header__actions">
            @include('partials.program-badge', ['program' => request('program', $program ?? '')])
            <a class="btn-training-outline" href="{{ route('training.index', ['program' => request('program')]) }}">
                <i class="fa-solid fa-arrow-left"></i> List All Entries
            </a>
        </div>
    </div>

    {{-- Server Error Alert --}}
    @if($errors->any() || !empty($formErrors) || session('flash_err'))
    <div class="modern-alert modern-alert--danger" role="alert">
        <div class="modern-alert__icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div class="modern-alert__content">
            <div class="modern-alert__title">Please complete all required fields correctly.</div>
            <ul class="modern-alert__list">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
                @if(!empty($formErrors))
                    @foreach($formErrors as $fe)
                        @if($fe !== 'Please fill all the required fields.' && !in_array($fe, $errors->all()))
                            <li>{{ $fe }}</li>
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
    @endif

    {{-- Client-side Error Alert --}}
    <div id="clientErrorBox" class="modern-alert modern-alert--danger" style="display:none;" role="alert">
        <div class="modern-alert__icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div class="modern-alert__content">
            <div class="modern-alert__title">Please complete all required fields:</div>
            <ul id="clientErrorList" class="modern-alert__list"></ul>
        </div>
    </div>

    {{-- Form Container --}}
    <form class="elog-form" action="{{ route('training.store') }}" method="post" id="trainingAddForm" novalidate>
        @csrf
        @if(request('program'))
            <input type="hidden" name="program" value="{{ request('program') }}">
        @endif

        <div class="form-card">
            <div class="form-card__head">
                <h2 class="form-card__title">
                    <i class="fa-solid fa-clipboard-list"></i> Clinical Entry Form
                </h2>
                <span style="font-size: 12px; color: rgba(255,255,255,0.85);"><span style="color: #fca5a5;">*</span> Indicates required fields</span>
            </div>

            <div class="form-card__body">
                {{-- Section 1: Encounter & Patient Information --}}
                <div class="form-section">
                    <div class="form-section__header">
                        <div class="form-section__icon"><i class="fa-solid fa-hospital-user"></i></div>
                        <div>
                            <h3 class="form-section__title">Encounter & Patient Information</h3>
                            <p class="form-section__subtitle">Specify registration, admission timeline, and patient demographics.</p>
                        </div>
                    </div>

                    <div class="form-grid-2" style="margin-bottom: 18px;">
                        <div class="form-field">
                            <label class="form-label" for="form_type">Form Type <span class="req-star">*</span></label>
                            <select name="form_type" id="form_type" class="field-control" required>
                                <option value="">- Select Form Type -</option>
                                @foreach($formTypes ?? \App\Services\LookupService::formTypes() as $val => $lab)
                                    <option value="{{ $val }}" {{ (string)old('form_type', $formOld['form_type'] ?? '') === (string)$val ? 'selected' : '' }}>{{ $lab }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label" for="hospt_reg_no">Hospt. Reg. No / Autopsy / MLC No <span class="req-star">*</span></label>
                            <input type="text" id="hospt_reg_no" name="hospt_reg_no" class="field-control" placeholder="e.g. HOSP-2026-9812" required value="{{ old('hospt_reg_no', $formOld['hospt_reg_no'] ?? '') }}">
                        </div>
                    </div>

                    <div class="form-grid-3" style="margin-bottom: 18px;">
                        <div class="form-field">
                            <label class="form-label" for="date_of_admission"><span id="date_caption">Date of Admission</span> <span class="req-star">*</span></label>
                            <input type="text" id="date_of_admission" name="date_of_admission" value="{{ old('date_of_admission', $formOld['date_of_admission'] ?? '') }}" readonly class="field-control dateBritish" placeholder="dd-mm-yyyy" required>
                        </div>

                        <div class="form-field">
                            <label class="form-label" for="pt_gender">Gender <span class="req-star">*</span></label>
                            <select name="pt_gender" id="pt_gender" class="field-control" required>
                                <option value="">- Select -</option>
                                @foreach($genders ?? \App\Services\LookupService::genders() as $g)
                                    <option value="{{ $g }}" {{ (string)old('pt_gender', $formOld['pt_gender'] ?? '') === (string)$g ? 'selected' : '' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label" for="pt_age">Age <span class="req-star">*</span></label>
                            <div class="elog-age-row">
                                <input type="text" id="pt_age" name="pt_age" class="field-control elog-age-num" placeholder="Age" required value="{{ old('pt_age', $formOld['pt_age'] ?? '') }}">
                                <select name="pt_age_type" id="pt_age_type" class="field-control elog-age-unit no-custom-select">
                                    @foreach($ageUnits ?? \App\Services\LookupService::ageUnits() as $unit)
                                        <option value="{{ $unit }}" {{ (string)old('pt_age_type', $formOld['pt_age_type'] ?? 'Year[s]') === (string)$unit ? 'selected' : '' }}>{{ $unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-field">
                            <label class="form-label" for="entry_for_prog_id">Academic Program <span class="req-star">*</span></label>
                            <select name="entry_for_prog_id" id="entry_for_prog_id" class="field-control" required>
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
                </div>

                {{-- Section 2: Clinical Assessment & Supervision --}}
                <div class="form-section">
                    <div class="form-section__header">
                        <div class="form-section__icon"><i class="fa-solid fa-stethoscope"></i></div>
                        <div>
                            <h3 class="form-section__title">Clinical Assessment & Supervision</h3>
                            <p class="form-section__subtitle">Specify clinical diagnosis, supervision status, performance level, and case outcome.</p>
                        </div>
                    </div>

                    <div class="form-grid-2" style="margin-bottom: 18px;">
                        <div class="form-field">
                            <label class="form-label" for="pt_diagnosis">Diagnosis / Suspected Diagnosis / Cause <span class="req-star">*</span></label>
                            <input type="text" id="pt_diagnosis" name="pt_diagnosis" class="field-control" placeholder="Enter clinical diagnosis" required value="{{ old('pt_diagnosis', $formOld['pt_diagnosis'] ?? '') }}">
                        </div>

                        <div class="form-field">
                            <label class="form-label" for="under_sup_name">Under Supervision (Name)</label>
                            <input type="text" id="under_sup_name" name="under_sup_name" class="field-control" placeholder="Supervisor full name" value="{{ old('under_sup_name', $formOld['under_sup_name'] ?? (optional($assignedSupervisor)->profile?->full_name ?: optional($assignedSupervisor)->username ?: '')) }}">
                            @if(optional($assignedSupervisor)->id)
                                <input type="hidden" name="supervisor_id" id="supervisor_id" value="{{ old('supervisor_id', $formOld['supervisor_id'] ?? optional($assignedSupervisor)->id) }}">
                            @endif
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-field">
                            <label class="form-label" for="level_id">Performance Level <span class="req-star">*</span></label>
                            <select name="level_id" id="level_id" class="field-control" required>
                                <option value="">- Select Level -</option>
                                @foreach($levels ?? \App\Services\LookupService::levels() as $val => $lab)
                                    <option value="{{ $val }}" {{ (string)old('level_id', $formOld['level_id'] ?? '') === (string)$val ? 'selected' : '' }}>{{ $lab }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label" for="outcome_id">Patient Outcome <span class="req-star">*</span></label>
                            <select name="outcome_id" id="outcome_id" class="field-control" required>
                                <option value="">- Select Outcome -</option>
                                @foreach($outcomes ?? \App\Services\LookupService::outcomes() as $val => $lab)
                                    <option value="{{ $val }}" {{ (string)old('outcome_id', $formOld['outcome_id'] ?? '') === (string)$val ? 'selected' : '' }}>{{ $lab }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Case Description --}}
                <div class="form-section">
                    <div class="form-section__header">
                        <div class="form-section__icon"><i class="fa-solid fa-align-left"></i></div>
                        <div>
                            <h3 class="form-section__title">Brief Description</h3>
                            <p class="form-section__subtitle">Summary of clinical presentation, findings, operative steps, or management decisions.</p>
                        </div>
                    </div>

                    <div class="form-field">
                        <label class="form-label" for="brief_desc">Case / Procedure Summary <span class="req-star">*</span></label>
                        <textarea id="brief_desc" name="brief_desc" class="field-control" rows="6" placeholder="Provide a concise clinical summary..." required>{{ old('brief_desc', $formOld['brief_desc'] ?? '') }}</textarea>
                    </div>
                </div>

                {{-- Section 4: Competencies --}}
                <div class="form-section" id="competancy_div">
                    <div class="form-section__header">
                        <div class="form-section__icon"><i class="fa-solid fa-sitemap"></i></div>
                        <div>
                            <h3 class="form-section__title">Competency Group & Details</h3>
                            <p class="form-section__subtitle">Select curriculum competencies mapped to this clinical case encounter.</p>
                        </div>
                    </div>

                    @php
                        $oldComIds = array_map('intval', (array) old('com_id', $formOld['com_id'] ?? []));
                        $oldComDetailIds = array_map('intval', (array) old('com_detail_id', $formOld['com_detail_id'] ?? []));
                    @endphp

                    <div class="competency-box">
                        <div class="competency-toolbar">
                            <p class="competency-hint">
                                <i class="fa-solid fa-circle-info"></i> Click on <strong>"+"</strong> button to expand and choose specific competencies.
                            </p>
                            <div class="tree-action-btns">
                                <button type="button" class="btn-tree-tool" id="compExpandAll">
                                    <i class="fa-solid fa-angles-down"></i> Expand All
                                </button>
                                <button type="button" class="btn-tree-tool" id="compCollapseAll">
                                    <i class="fa-solid fa-angles-up"></i> Collapse All
                                </button>
                                <button type="button" class="btn-tree-tool" id="compDefault">
                                    <i class="fa-solid fa-rotate-left"></i> Reset
                                </button>
                            </div>
                        </div>

                        <ul id="competencyTreeRoot" class="competency-tree">
                            @foreach($compTree as $group)
                            <li class="competency-tree__node competency-tree__node--parent">
                                <div class="competency-tree__header">
                                    <button type="button" class="competency-tree__toggle" data-toggle-branch aria-expanded="false">+</button>
                                    <input type="checkbox" name="com_id[]" value="{{ $group['id'] }}" id="comg_{{ $group['id'] }}" {{ in_array((int)$group['id'], $oldComIds) ? 'checked' : '' }}>
                                    <label class="competency-tree__text" for="comg_{{ $group['id'] }}">
                                        {{ $group['label'] }}
                                        <span style="font-weight: 500; font-size: 11.5px; color: #64748b; margin-left: 6px;">({{ count($group['children']) }} items)</span>
                                    </label>
                                </div>
                                <ul class="competency-tree__branch">
                                    @foreach($group['children'] as $child)
                                    <li class="competency-tree__node competency-tree__node--leaf">
                                        <input type="checkbox" name="com_detail_id[]" value="{{ $child['id'] }}" id="comd_{{ $child['id'] }}" {{ in_array((int)$child['id'], $oldComDetailIds) ? 'checked' : '' }}>
                                        <label for="comd_{{ $child['id'] }}">{{ $child['label'] }}</label>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="form-field" style="margin-top: 18px;">
                        <label class="form-label" for="alt_procedure">Alternate Competency Group(s) and Details <span style="font-size: 11.5px; color: #64748b; font-weight: normal;">(Optional - if procedure is not listed above)</span></label>
                        <input type="text" id="alt_procedure" name="alt_procedure" value="{{ old('alt_procedure', $formOld['alt_procedure'] ?? '') }}" class="field-control" placeholder="Enter alternate clinical procedure or competency">
                    </div>
                </div>

                {{-- Section 5: Review & Send to Supervisor --}}
                <div class="form-section">
                    <div class="form-section__header">
                        <div class="form-section__icon"><i class="fa-solid fa-paper-plane"></i></div>
                        <div>
                            <h3 class="form-section__title">Supervisor Routing</h3>
                            <p class="form-section__subtitle">Choose whether to keep this entry as draft or forward it for evaluation.</p>
                        </div>
                    </div>

                    <div class="form-grid-2" style="align-items: center;">
                        <div class="form-field">
                            <label class="form-label" for="std_post">Send to Supervisor <span class="req-star">*</span></label>
                            <select name="std_post" id="std_post" class="field-control">
                                <option value="No" {{ (string)old('std_post', $formOld['std_post'] ?? 'No') !== 'Yes' ? 'selected' : '' }}>No (Save as Draft)</option>
                                <option value="Yes" {{ (string)old('std_post', $formOld['std_post'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes (Submit for Approval)</option>
                            </select>
                        </div>

                        <div class="submission-warning-card">
                            <i class="fa-solid fa-lock"></i>
                            <div>
                                <strong>Notice:</strong> Once you select <strong>'Yes'</strong> and submit, this entry will be locked and directly routed to your supervisor. You will not be able to edit it afterwards.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Actions Bar --}}
                <div class="form-actions-bar">
                    <button class="btn-form-submit" type="submit" name="submit2" id="mform_submit">
                        <i class="fa-solid fa-check"></i> Submit Training Entry
                    </button>
                    <a href="{{ route('training.index', ['program' => request('program')]) }}" class="btn-form-cancel">
                        Cancel
                    </a>
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
    var form = document.getElementById('trainingAddForm');

    if (root) {
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

        // Default Reset
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
        root.querySelectorAll('.competency-tree__node--parent input[name="com_id[]"]').forEach(function (parentCb) {
            parentCb.addEventListener('change', function () {
                var li = this.closest('.competency-tree__node--parent');
                if (!li) return;
                var checked = this.checked;
                li.querySelectorAll('.competency-tree__branch input[type="checkbox"]').forEach(function (childCb) {
                    childCb.checked = checked;
                });
            });
        });

        // Auto expand branches that contain checked checkboxes
        root.querySelectorAll('.competency-tree__branch').forEach(function(branch) {
            if (branch.querySelector('input[type="checkbox"]:checked')) {
                branch.style.display = 'block';
                branch.classList.add('is-open');
                var parentToggle = branch.closest('.competency-tree__node--parent');
                if (parentToggle) {
                    var toggleBtn = parentToggle.querySelector('[data-toggle-branch]');
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
                    submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Submit Training Entry';
                }
            }
            if (typeof window.resetSubmitButtons === 'function') {
                window.resetSubmitButtons();
            }
        }

        resetFormSubmitBtn();

        form.addEventListener('submit', function (e) {
            var missing = [];
            var errorBox = document.getElementById('clientErrorBox');
            var errorList = document.getElementById('clientErrorList');

            function checkField(id, label) {
                var el = document.getElementById(id);
                if (!el || !el.value || el.value.trim() === '') {
                    missing.push(label);
                    if (el) {
                        el.style.borderColor = '#dc2626';
                        el.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.12)';
                    }
                } else if (el) {
                    el.style.borderColor = '';
                    el.style.boxShadow = '';
                }
            }

            checkField('form_type', 'Form Type is required.');
            checkField('hospt_reg_no', 'Hospital Reg. No is required.');
            checkField('date_of_admission', 'Date of Admission is required.');
            checkField('pt_gender', 'Gender is required.');
            checkField('pt_age', 'Age is required.');
            checkField('pt_diagnosis', 'Diagnosis / Suspected Diagnosis / Cause is required.');
            checkField('level_id', 'Level is required.');
            checkField('outcome_id', 'Outcome is required.');
            checkField('brief_desc', 'Brief Description is required.');

            if (missing.length > 0) {
                e.preventDefault();
                e.stopImmediatePropagation();
                resetFormSubmitBtn();

                if (errorBox && errorList) {
                    errorList.innerHTML = '';
                    missing.forEach(function (msg) {
                        var li = document.createElement('li');
                        li.textContent = msg;
                        errorList.appendChild(li);
                    });
                    errorBox.style.display = 'flex';
                    errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    alert('Please fill all the required fields:\n- ' + missing.join('\n- '));
                }
                return false;
            } else if (errorBox) {
                errorBox.style.display = 'none';
            }
        });

        // Clear error outline on typing/selecting
        form.querySelectorAll('input, select, textarea').forEach(function (field) {
            field.addEventListener('input', function () {
                resetFormSubmitBtn();
                if (this.value && this.value.trim() !== '') {
                    this.style.borderColor = '';
                    this.style.boxShadow = '';
                }
            });
            field.addEventListener('change', function () {
                resetFormSubmitBtn();
                if (this.value && this.value.trim() !== '') {
                    this.style.borderColor = '';
                    this.style.boxShadow = '';
                }
            });
        });
    }
});
</script>
@endpush
