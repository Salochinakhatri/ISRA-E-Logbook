@extends('layouts.app')

@section('title', 'Trainee Submitted Entries | ' . ($tenant->name ?? 'e-Logbook'))
@section('nav_supervisor_entries', 'is-active')

@push('styles')
<style>
/* ==========================================================================
   Supervisor Review & Approvals Interface Styles
   ========================================================================== */
.supervisor-entries-page {
    padding: 12px 10px 48px;
    max-width: 100%;
}

/* Breadcrumb & Header */
.sup-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 12px;
}

.sup-page-sub {
    font-size: 13.5px;
    color: #555e68;
    margin-top: 3px;
    margin-bottom: 0;
}

/* Top Student Name Hero Banner */
.sup-student-hero {
    background: linear-gradient(135deg, #0b6040 0%, #117850 100%);
    border-radius: 8px;
    padding: 16px 20px;
    color: #ffffff;
    margin-bottom: 16px;
    box-shadow: 0 4px 12px rgba(11, 96, 64, 0.15);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.sup-student-hero__left {
    display: flex;
    align-items: center;
    gap: 14px;
}

.sup-student-hero__avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #ffffff;
    border: 2px solid rgba(255, 255, 255, 0.35);
    flex-shrink: 0;
}

.sup-student-hero__tag {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-weight: 700;
    color: #a7f3d0;
    margin-bottom: 2px;
}

.sup-student-hero__name {
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1.2;
    margin: 0;
    color: #ffffff;
}

.sup-student-hero__meta {
    font-size: 12.5px;
    color: rgba(255, 255, 255, 0.88);
    margin-top: 4px;
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.sup-student-hero__meta span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.sup-student-hero__right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.sup-student-hero__select-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.15);
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid rgba(255, 255, 255, 0.25);
}

.sup-student-hero__select-label {
    font-size: 12px;
    font-weight: 600;
    color: #ffffff;
    white-space: nowrap;
}

.sup-student-hero__select {
    background: #ffffff;
    color: #212529;
    font-weight: 600;
    font-size: 13px;
    padding: 6px 10px;
    border-radius: 4px;
    border: none;
    outline: none;
    min-width: 200px;
    cursor: pointer;
}

/* Filter Card */
.sup-filter-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 18px 20px;
    margin-bottom: 18px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}

.sup-filter-card__title {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.sup-filter-form__grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    align-items: flex-end;
}

@media (max-width: 1200px) {
    .sup-filter-form__grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 900px) {
    .sup-filter-form__grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .sup-filter-form__grid {
        grid-template-columns: 1fr;
    }
}

.sup-filter-form__field label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #475569;
    margin-bottom: 5px;
}

.sup-filter-actions {
    display: flex;
    gap: 10px;
    align-items: center;
    justify-content: flex-end;
}

.btn-back-dash {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 16px;
    background-color: #ffffff;
    color: #0b6040;
    border: 1.5px solid #0b6040;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s ease;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.btn-back-dash:hover {
    background-color: #0b6040;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(11, 96, 64, 0.2);
}

.btn-filter-reset {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 18px;
    background-color: #ffffff;
    color: #475569;
    border: 1.5px solid #cbd5e1;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.15s ease;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
}

.btn-filter-reset:hover {
    background-color: #f1f5f9;
    color: #0f172a;
    border-color: #94a3b8;
}

.btn-filter-apply {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 22px;
    background: #0b6040;
    color: #ffffff;
    border: 1.5px solid #0b6040;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
    box-shadow: 0 2px 4px rgba(11, 96, 64, 0.25);
}

.btn-filter-apply:hover {
    background: #084830;
    border-color: #084830;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(11, 96, 64, 0.3);
}

/* Active filter pills */
.sup-active-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 12px;
    align-items: center;
}

.sup-active-pill-label {
    font-size: 11.5px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.sup-filter-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    border-radius: 14px;
    padding: 3px 10px;
    font-size: 12px;
    color: #334155;
}

.sup-filter-pill a {
    color: #ef4444;
    text-decoration: none;
    font-weight: 700;
    margin-left: 2px;
}

/* ==========================================================================
   Sleek Segmented Status Navigation
   ========================================================================== */
.sup-status-nav-bar {
    margin-bottom: 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.sup-segmented-tabs {
    display: inline-flex;
    background: #f1f5f9;
    padding: 4px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    gap: 3px;
    flex-wrap: wrap;
}

.sup-segmented-tab {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border-radius: 6px;
    color: #475569;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.15s ease;
    border: 1px solid transparent;
}

.sup-segmented-tab:hover {
    color: #0f172a;
    background: rgba(255, 255, 255, 0.7);
}

.sup-segmented-tab.is-active {
    background: #ffffff;
    font-weight: 700;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.04);
}

.sup-segmented-tab.is-active.is-warn {
    color: #b45309;
    border-color: #fde68a;
}
.sup-segmented-tab.is-active.is-success {
    color: #15803d;
    border-color: #bbf7d0;
}
.sup-segmented-tab.is-active.is-info {
    color: #7e22ce;
    border-color: #e9d5ff;
}
.sup-segmented-tab.is-active.is-danger {
    color: #be123c;
    border-color: #fecdd3;
}
.sup-segmented-tab.is-active.is-all {
    color: #0f172a;
    border-color: #cbd5e1;
}

/* Sleek Counter Badges */
.sup-tab-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 6px;
    border-radius: 10px;
    font-size: 11.5px;
    font-weight: 700;
    line-height: 1;
}

.sup-tab-count--warn { background: #fef3c7; color: #b45309; }
.sup-tab-count--success { background: #dcfce7; color: #15803d; }
.sup-tab-count--info { background: #f3e8ff; color: #7e22ce; }
.sup-tab-count--danger { background: #ffe4e6; color: #be123c; }
.sup-tab-count--all { background: #e2e8f0; color: #475569; }

.is-active .sup-tab-count--warn { background: #f59e0b; color: #ffffff; }
.is-active .sup-tab-count--success { background: #10b981; color: #ffffff; }
.is-active .sup-tab-count--info { background: #a855f7; color: #ffffff; }
.is-active .sup-tab-count--danger { background: #f43f5e; color: #ffffff; }
.is-active .sup-tab-count--all { background: #334155; color: #ffffff; }

/* ==========================================================================
   Sleek Bulk Action Toolbar (Single Clean Horizontal Line)
   ========================================================================== */
.sup-bulk-toolbar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-bottom: 2px solid #e2e8f0;
    border-radius: 8px 8px 0 0;
    padding: 10px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    transition: all 0.2s ease;
}

.sup-bulk-toolbar.has-selected {
    background: #f0fdf4;
    border-color: #86efac;
    box-shadow: inset 0 2px 4px rgba(16, 185, 129, 0.05);
}

.sup-bulk-toolbar__left {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}

.sup-checkbox-label {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    user-select: none;
}

.sup-checkbox-label input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: #0b6040;
    cursor: pointer;
}

.sup-selected-indicator {
    font-size: 12px;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.sup-selected-indicator.has-items {
    color: #15803d;
    font-weight: 600;
}

.btn-clear-selection {
    background: none;
    border: none;
    color: #dc2626;
    font-size: 11.5px;
    cursor: pointer;
    text-decoration: underline;
    padding: 0 4px;
}

/* Right side action controls - strictly inline */
.sup-bulk-toolbar__right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: nowrap !important;
}

@media (max-width: 820px) {
    .sup-bulk-toolbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    .sup-bulk-toolbar__right {
        width: 100%;
        flex-wrap: wrap !important;
    }
}

.sup-bulk-select {
    width: 190px !important;
    max-width: 190px !important;
    font-size: 13px !important;
    padding: 6px 10px !important;
    height: 36px !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 6px !important;
    background-color: #ffffff !important;
    color: #1e293b !important;
    flex-shrink: 0 !important;
    outline: none !important;
}

.sup-bulk-select:focus {
    border-color: #0b6040 !important;
    box-shadow: 0 0 0 2px rgba(11, 96, 64, 0.15) !important;
}

.sup-bulk-remarks {
    width: 230px !important;
    max-width: 230px !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    height: 36px !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 6px !important;
    background-color: #ffffff !important;
    color: #1e293b !important;
    flex-shrink: 1 !important;
    outline: none !important;
}

.sup-bulk-remarks:focus {
    border-color: #0b6040 !important;
    box-shadow: 0 0 0 2px rgba(11, 96, 64, 0.15) !important;
}

.sup-bulk-apply-btn {
    height: 36px !important;
    padding: 0 16px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    border-radius: 6px !important;
    background: #0b6040 !important;
    color: #ffffff !important;
    border: none !important;
    cursor: pointer !important;
    transition: all 0.15s ease !important;
}

.sup-bulk-apply-btn:hover:not(:disabled) {
    background: #084830 !important;
    box-shadow: 0 2px 6px rgba(11, 96, 64, 0.25) !important;
}

.sup-bulk-apply-btn:disabled {
    background: #cbd5e1 !important;
    color: #64748b !important;
    cursor: not-allowed !important;
    opacity: 0.7 !important;
}

/* ==========================================================================
   Table & Cell Styling
   ========================================================================== */
.sup-table-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0 0 8px 8px;
    overflow-x: auto;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.sup-table--entries {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 13px;
}

.sup-table--entries thead th {
    background: #f8fafc;
    color: #334155;
    font-size: 11.5px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    font-weight: 700;
    padding: 12px 10px;
    border-bottom: 2px solid #e2e8f0;
    text-align: left;
    white-space: nowrap;
    vertical-align: middle;
}

.sup-table--entries tbody tr {
    transition: background-color 0.12s ease;
}

.sup-table--entries tbody tr:hover {
    background-color: #f8fafc;
}

.sup-table--entries tbody td {
    padding: 12px 10px;
    vertical-align: middle;
    text-align: left;
    border-bottom: 1px solid #edf2f7;
}

.sup-idx-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
}

.sup-idx-num {
    font-weight: 600;
    color: #64748b;
    font-size: 12px;
}

.cell-date-proc .sup-entry-date {
    font-weight: 600;
    color: #0f172a;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.cell-date-proc .sup-entry-proc {
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.cell-date-proc .proc-name {
    color: #334155;
    font-size: 12px;
    font-weight: 500;
}

.sup-entry-trainee-sub {
    font-size: 11.5px;
    color: #64748b;
    margin-top: 3px;
}

.cell-diagnosis .sup-entry-diagnosis strong {
    color: #0f172a;
    font-size: 13px;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.cell-brief .sup-entry-brief {
    color: #475569;
    font-size: 12px;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    max-width: 220px;
}

.cell-comp .sup-entry-comp {
    max-width: 250px;
    font-size: 12px;
    line-height: 1.35;
}

.cell-comp .comp-cell__group {
    font-weight: 600;
    color: #1e293b;
    font-size: 12px;
    margin-bottom: 2px;
}

.cell-comp .comp-cell__list {
    margin: 2px 0 0;
    padding-left: 14px;
    color: #475569;
    font-size: 11.5px;
}

.cell-comp .comp-cell__list li {
    margin-bottom: 1px;
}

.badge--level {
    background-color: #e0f2fe;
    color: #0369a1;
    font-weight: 600;
    font-size: 11.5px;
    padding: 3px 8px;
    border-radius: 4px;
    display: inline-block;
    white-space: nowrap;
}

.cell-status-reg .entry-status-badge-wrap {
    margin-bottom: 4px;
}

.cell-status-reg .sup-entry-regno {
    font-size: 11.5px;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}

.btn-icon-action--view {
    width: 34px;
    height: 34px;
    border-radius: 6px;
    background-color: #0b6040;
    color: #ffffff;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.15s ease;
    box-shadow: 0 1px 3px rgba(11, 96, 64, 0.25);
}

.btn-icon-action--view:hover {
    background-color: #084830;
    transform: translateY(-1px);
}

/* ==========================================================================
   Detail View Modal Styles
   ========================================================================== */
.modal-entry-inspect {
    max-width: 840px !important;
    width: 95%;
    max-height: 92vh;
    display: flex;
    flex-direction: column;
    padding: 0 !important;
    overflow: hidden;
    border-radius: 8px;
}

.modal-entry-inspect__header {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 16px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-entry-inspect__body {
    padding: 22px;
    overflow-y: auto;
    font-size: 13.5px;
    color: #1e293b;
    line-height: 1.55;
}

.sup-modal-student-banner {
    background: linear-gradient(135deg, #f0fdf4 0%, #e6fcf5 100%);
    border: 1px solid #bbf7d0;
    border-radius: 6px;
    padding: 14px 18px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.sup-modal-student-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.sup-modal-student-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #0b6040;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 700;
}

.sup-modal-student-name {
    font-size: 16px;
    font-weight: 700;
    color: #065f46;
    margin: 0;
}

.sup-modal-student-sub {
    font-size: 12px;
    color: #047857;
    margin-top: 2px;
}

.sup-modal-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .sup-modal-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .sup-modal-grid {
        grid-template-columns: 1fr;
    }
}

.sup-modal-field {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 10px 12px;
}

.sup-modal-field__label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 3px;
    display: block;
}

.sup-modal-field__value {
    font-size: 13.5px;
    font-weight: 600;
    color: #0f172a;
    word-break: break-word;
}

.sup-modal-section {
    margin-bottom: 18px;
}

.sup-modal-section__title {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.sup-modal-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 12px 14px;
    font-size: 13px;
    line-height: 1.6;
    white-space: pre-wrap;
    color: #334155;
}

.sup-modal-update-box {
    background: #fdfefe;
    border: 1px solid #bbf7d0;
    border-radius: 6px;
    padding: 18px;
    margin-top: 22px;
    box-shadow: 0 1px 3px rgba(11, 96, 64, 0.05);
}

.sup-modal-update-title {
    font-size: 14px;
    font-weight: 700;
    color: #0b6040;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.sup-modal-update-grid {
    display: grid;
    grid-template-columns: 1fr 2fr auto;
    gap: 12px;
    align-items: flex-end;
}

@media (max-width: 768px) {
    .sup-modal-update-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="supervisor-entries-page">
    {{-- Breadcrumbs --}}
    <nav class="supervisor-dash__breadcrumb" aria-label="breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item separator">/</li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ $tenant->name ?? 'e-Logbook' }}</a></li>
            <li class="breadcrumb-item separator">/</li>
            <li class="breadcrumb-item active" aria-current="page">Trainee Submitted Entries</li>
        </ol>
    </nav>

    <div class="sup-page-header">
        <div>
            <h1 class="supervisor-dash__title" style="font-size: 1.5rem; font-weight: 700; color: #0b6040; margin: 0;">
                Trainee Submitted Entries
            </h1>
            <p class="sup-page-sub">Review entries submitted by trainees for verification, discussion, or approval.</p>
        </div>
        <div class="sup-header-actions">
            <a href="{{ route('dashboard') }}" class="btn-back-dash">
                <i class="fa-solid fa-arrow-left"></i> <span>Back to Dashboard</span>
            </a>
        </div>
    </div>

    {{-- Top Student Name Banner --}}
    <div class="sup-student-hero">
        <div class="sup-student-hero__left">
            <div class="sup-student-hero__avatar">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
            <div>
                <div class="sup-student-hero__tag">
                    {{ $selectedTrainee ? 'Selected Trainee' : 'Trainee Overview' }}
                </div>
                <h2 class="sup-student-hero__name">
                    @if($selectedTrainee)
                        {{ $selectedTrainee->profile?->full_name ?: $selectedTrainee->username }}
                    @else
                        All Assigned Trainees ({{ $trainees->count() }})
                    @endif
                </h2>
                <div class="sup-student-hero__meta">
                    @if($selectedTrainee)
                        <span><i class="fa-solid fa-id-badge"></i> {{ $selectedTrainee->username }}</span>
                        @if($selectedTrainee->profile?->reg_no)
                            <span><i class="fa-solid fa-address-card"></i> Reg: {{ $selectedTrainee->profile->reg_no }}</span>
                        @endif
                        @if($selectedTrainee->profile?->program)
                            <span><i class="fa-solid fa-graduation-cap"></i> Program: {{ $selectedTrainee->profile->program }}</span>
                        @endif
                        <span><i class="fa-solid fa-envelope"></i> {{ $selectedTrainee->email }}</span>
                    @else
                        <span><i class="fa-solid fa-users"></i> Showing entries across all {{ $trainees->count() }} trainees assigned to you</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="sup-student-hero__right">
            <div class="sup-student-hero__select-wrap">
                <label for="topTraineePicker" class="sup-student-hero__select-label">
                    <i class="fa-solid fa-arrow-right-arrow-left"></i> Switch Student:
                </label>
                <select id="topTraineePicker" class="sup-student-hero__select" onchange="window.location.href = this.value;">
                    <option value="{{ route('supervisor.entries', array_merge(request()->except(['trainee_id', 'page']))) }}">
                        -- All Trainees ({{ $trainees->count() }}) --
                    </option>
                    @foreach($trainees as $t)
                        <option value="{{ route('supervisor.entries', array_merge(request()->except(['trainee_id', 'page']), ['trainee_id' => $t->id])) }}"
                            {{ (string)$traineeId === (string)$t->id ? 'selected' : '' }}>
                            {{ $t->profile?->full_name ?: $t->username }} ({{ $t->username }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Filter Toolbar Card --}}
    <div class="sup-filter-card">
        <div class="sup-filter-card__title">
            <i class="fa-solid fa-sliders" style="color: #0b6040;"></i> Filter Submitted Entries
        </div>

        <form method="get" action="{{ route('supervisor.entries') }}" class="sup-filter-form" id="supervisorFilterForm">
            <input type="hidden" name="status" id="hiddenStatusFilter" value="{{ $statusFilter }}">
            @if($traineeId)
                <input type="hidden" name="trainee_id" value="{{ $traineeId }}">
            @endif

            <div class="sup-filter-form__grid">
                {{-- Admission Date From --}}
                <div class="sup-filter-form__field">
                    <label for="admissionDateFrom"><i class="fa-regular fa-calendar"></i> Admission Date From</label>
                    <input type="date" name="admission_date_from" id="admissionDateFrom" class="field__control" value="{{ $admissionDateFrom }}">
                </div>

                {{-- Admission Date To --}}
                <div class="sup-filter-form__field">
                    <label for="admissionDateTo"><i class="fa-regular fa-calendar"></i> Admission Date To</label>
                    <input type="date" name="admission_date_to" id="admissionDateTo" class="field__control" value="{{ $admissionDateTo }}">
                </div>

                {{-- Diagnosis --}}
                <div class="sup-filter-form__field">
                    <label for="filterDiagnosis"><i class="fa-solid fa-stethoscope"></i> Diagnosis</label>
                    <input type="text" name="diagnosis" id="filterDiagnosis" class="field__control" placeholder="Search diagnosis / suspected..." value="{{ $diagnosis }}">
                </div>

                {{-- Hospt. Reg. No. --}}
                <div class="sup-filter-form__field">
                    <label for="filterHosptRegNo"><i class="fa-solid fa-hospital-user"></i> Hospt. Reg. No</label>
                    <input type="text" name="hospt_reg_no" id="filterHosptRegNo" class="field__control" placeholder="Reg. / Autopsy / MLC No..." value="{{ $hosptRegNo }}">
                </div>

                {{-- Post Date From --}}
                <div class="sup-filter-form__field">
                    <label for="postDateFrom"><i class="fa-regular fa-calendar-plus"></i> Post Date From</label>
                    <input type="date" name="post_date_from" id="postDateFrom" class="field__control" value="{{ $postDateFrom }}">
                </div>

                {{-- Post Date To --}}
                <div class="sup-filter-form__field">
                    <label for="postDateTo"><i class="fa-regular fa-calendar-plus"></i> Post Date To</label>
                    <input type="date" name="post_date_to" id="postDateTo" class="field__control" value="{{ $postDateTo }}">
                </div>

                {{-- Level --}}
                <div class="sup-filter-form__field">
                    <label for="filterLevel"><i class="fa-solid fa-layer-group"></i> Level</label>
                    <select name="level" id="filterLevel" class="field__control">
                        <option value="">All Levels</option>
                        @foreach($levels as $lvlKey => $lvlName)
                            <option value="{{ $lvlKey }}" {{ (string)$level === (string)$lvlKey ? 'selected' : '' }}>
                                {{ $lvlName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Category / Section --}}
                <div class="sup-filter-form__field">
                    <label for="filterType"><i class="fa-solid fa-folder-tree"></i> Section / Category</label>
                    <select name="type" id="filterType" class="field__control">
                        <option value="all" {{ $typeFilter === 'all' ? 'selected' : '' }}>All Sections</option>
                        <option value="training" {{ $typeFilter === 'training' ? 'selected' : '' }}>Training</option>
                        <option value="rotational" {{ $typeFilter === 'rotational' ? 'selected' : '' }}>Rotational Training</option>
                        <option value="journal" {{ $typeFilter === 'journal' ? 'selected' : '' }}>Journal Club</option>
                        <option value="presented" {{ $typeFilter === 'presented' ? 'selected' : '' }}>Paper Presented</option>
                        <option value="published" {{ $typeFilter === 'published' ? 'selected' : '' }}>Paper Published</option>
                    </select>
                </div>

                {{-- Search general --}}
                <div class="sup-filter-form__field" style="grid-column: span 2;">
                    <label for="filterSearch"><i class="fa-solid fa-magnifying-glass"></i> Keyword Search</label>
                    <input type="text" name="search" id="filterSearch" class="field__control" placeholder="Search keywords across procedures, notes, etc." value="{{ $search }}">
                </div>

                {{-- Filter Actions --}}
                <div class="sup-filter-actions" style="grid-column: span 2;">
                    <a href="{{ route('supervisor.entries', $traineeId ? ['trainee_id' => $traineeId] : []) }}" class="btn-filter-reset">
                        <i class="fa-solid fa-rotate-left"></i> <span>Reset</span>
                    </a>
                    <button type="submit" class="btn-filter-apply">
                        <i class="fa-solid fa-filter"></i> <span>Apply Filters</span>
                    </button>
                </div>
            </div>

            {{-- Active Filter Badges --}}
            @php
                $hasActiveFilters = $traineeId || $admissionDateFrom || $admissionDateTo || $diagnosis || $postDateFrom || $postDateTo || $level || $hosptRegNo || ($typeFilter && $typeFilter !== 'all') || $search;
            @endphp

            @if($hasActiveFilters)
                <div class="sup-active-pills">
                    <span class="sup-active-pill-label">Active Filters:</span>
                    @if($traineeId && $selectedTrainee)
                        <span class="sup-filter-pill">
                            Trainee: {{ $selectedTrainee->profile?->full_name ?: $selectedTrainee->username }}
                            <a href="{{ route('supervisor.entries', request()->except('trainee_id')) }}" title="Remove">&times;</a>
                        </span>
                    @endif
                    @if($admissionDateFrom || $admissionDateTo)
                        <span class="sup-filter-pill">
                            Adm: {{ $admissionDateFrom ?: 'Any' }} to {{ $admissionDateTo ?: 'Any' }}
                            <a href="{{ route('supervisor.entries', request()->except(['admission_date_from', 'admission_date_to'])) }}" title="Remove">&times;</a>
                        </span>
                    @endif
                    @if($diagnosis)
                        <span class="sup-filter-pill">
                            Diagnosis: "{{ $diagnosis }}"
                            <a href="{{ route('supervisor.entries', request()->except('diagnosis')) }}" title="Remove">&times;</a>
                        </span>
                    @endif
                    @if($postDateFrom || $postDateTo)
                        <span class="sup-filter-pill">
                            Post: {{ $postDateFrom ?: 'Any' }} to {{ $postDateTo ?: 'Any' }}
                            <a href="{{ route('supervisor.entries', request()->except(['post_date_from', 'post_date_to'])) }}" title="Remove">&times;</a>
                        </span>
                    @endif
                    @if($level)
                        <span class="sup-filter-pill">
                            Level: {{ $levels[$level] ?? $level }}
                            <a href="{{ route('supervisor.entries', request()->except('level')) }}" title="Remove">&times;</a>
                        </span>
                    @endif
                    @if($hosptRegNo)
                        <span class="sup-filter-pill">
                            Reg/Autopsy/MLC: "{{ $hosptRegNo }}"
                            <a href="{{ route('supervisor.entries', request()->except('hospt_reg_no')) }}" title="Remove">&times;</a>
                        </span>
                    @endif
                    @if($typeFilter && $typeFilter !== 'all')
                        <span class="sup-filter-pill">
                            Category: {{ ucfirst($typeFilter) }}
                            <a href="{{ route('supervisor.entries', request()->except('type')) }}" title="Remove">&times;</a>
                        </span>
                    @endif
                    @if($search)
                        <span class="sup-filter-pill">
                            Search: "{{ $search }}"
                            <a href="{{ route('supervisor.entries', request()->except('search')) }}" title="Remove">&times;</a>
                        </span>
                    @endif
                    <a href="{{ route('supervisor.entries') }}" class="btn btn-sm btn--outline" style="font-size: 11px; padding: 2px 8px; margin-left: 6px;">Clear All</a>
                </div>
            @endif
        </form>
    </div>

    {{-- Modern Segmented Status Filter Tabs Bar --}}
    <div class="sup-status-nav-bar">
        <div class="sup-segmented-tabs">
            <a href="{{ route('supervisor.entries', array_merge(request()->except('status'), ['status' => 'Awaiting Approval'])) }}" 
               class="sup-segmented-tab {{ $statusFilter === 'Awaiting Approval' ? 'is-active is-warn' : '' }}">
                <i class="fa-solid fa-clock" style="color: #f59e0b;"></i>
                <span>Awaiting Approval</span>
                <span class="sup-tab-count sup-tab-count--warn">{{ $statusCounts['awaiting'] }}</span>
            </a>

            <a href="{{ route('supervisor.entries', array_merge(request()->except('status'), ['status' => 'Approved'])) }}" 
               class="sup-segmented-tab {{ $statusFilter === 'Approved' ? 'is-active is-success' : '' }}">
                <i class="fa-solid fa-circle-check" style="color: #10b981;"></i>
                <span>Approved</span>
                <span class="sup-tab-count sup-tab-count--success">{{ $statusCounts['approved'] }}</span>
            </a>

            <a href="{{ route('supervisor.entries', array_merge(request()->except('status'), ['status' => 'Discuss and Resubmit'])) }}" 
               class="sup-segmented-tab {{ $statusFilter === 'Discuss and Resubmit' ? 'is-active is-info' : '' }}">
                <i class="fa-solid fa-comments" style="color: #8b5cf6;"></i>
                <span>Discuss & Resubmit</span>
                <span class="sup-tab-count sup-tab-count--info">{{ $statusCounts['resubmit'] }}</span>
            </a>

            <a href="{{ route('supervisor.entries', array_merge(request()->except('status'), ['status' => 'Disapproved'])) }}" 
               class="sup-segmented-tab {{ $statusFilter === 'Disapproved' ? 'is-active is-danger' : '' }}">
                <i class="fa-solid fa-ban" style="color: #f43f5e;"></i>
                <span>Disapproved</span>
                <span class="sup-tab-count sup-tab-count--danger">{{ $statusCounts['disapproved'] }}</span>
            </a>

            <a href="{{ route('supervisor.entries', array_merge(request()->except('status'), ['status' => 'all'])) }}" 
               class="sup-segmented-tab {{ $statusFilter === 'all' ? 'is-active is-all' : '' }}">
                <i class="fa-solid fa-layer-group" style="color: #64748b;"></i>
                <span>All Submitted</span>
                <span class="sup-tab-count sup-tab-count--all">{{ $statusCounts['all'] }}</span>
            </a>
        </div>
    </div>

    {{-- Bulk Action Form Wrapping the Entries Table --}}
    <form action="{{ route('supervisor.entries.bulk') }}" method="post" id="bulkActionForm">
        @csrf
        
        {{-- Unified Sleek Bulk Action Toolbar (Always strictly single horizontal line) --}}
        <div class="sup-bulk-toolbar" id="supBulkToolbar">
            <div class="sup-bulk-toolbar__left">
                <label class="sup-checkbox-label">
                    <input type="checkbox" id="selectAllCheckbox">
                    <span>Select All</span>
                </label>
                <div class="sup-selected-indicator" id="selectedIndicator">
                    <span id="selectedCountText">0 selected</span>
                    <button type="button" class="btn-clear-selection" id="clearSelectionBtn" style="display: none;">Deselect all</button>
                </div>
            </div>

            <div class="sup-bulk-toolbar__right">
                <select name="bulk_status" id="bulkStatusSelect" class="sup-bulk-select">
                    <option value="">Bulk Approval Options...</option>
                    <option value="Approved">Approve Selected</option>
                    <option value="Awaiting Approval">Mark as Pending</option>
                    <option value="Discuss and Resubmit">Discuss and Resubmit</option>
                    <option value="Disapproved">Disapprove Selected</option>
                </select>

                <input type="text" name="bulk_remarks" id="bulkRemarksInput" class="sup-bulk-remarks" placeholder="Optional supervisor remarks...">

                <button type="submit" class="sup-bulk-apply-btn" id="bulkApplyBtn" disabled>
                    <i class="fa-solid fa-check"></i> <span>Apply to Selected</span>
                </button>
            </div>
        </div>

        {{-- Entries Table --}}
        <div class="sup-table-card">
            <table class="sup-table sup-table--entries">
                <thead>
                    <tr>
                        <th style="width: 48px; text-align: left;">
                            <div class="sup-idx-wrap">
                                <input type="checkbox" id="headerCheckboxToggle" title="Select all entries" style="cursor: pointer;">
                                <span>#</span>
                            </div>
                        </th>
                        <th style="min-width: 170px; text-align: left;">Admission Date / Procedure</th>
                        <th style="min-width: 180px; text-align: left;">Diagnosis</th>
                        <th style="min-width: 170px; text-align: left;">Brief Desc</th>
                        <th style="min-width: 220px; text-align: left;">Competency Group / Detail</th>
                        <th style="width: 130px; text-align: left;">Level</th>
                        <th style="width: 160px; text-align: left;">Entry Status / Hospt. Reg. No.</th>
                        <th style="width: 70px; text-align: center;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $idx => $entry)
                    <tr class="entry-row" data-entry-id="{{ $entry['type'] }}:{{ $entry['id'] }}" id="entry-row-{{ $entry['type'] }}-{{ $entry['id'] }}">
                        {{-- 1. # and Checkbox --}}
                        <td class="cell-idx">
                            <div class="sup-idx-wrap">
                                <input type="checkbox" name="selected_entries[]" value="{{ $entry['type'] }}:{{ $entry['id'] }}" class="entry-checkbox" style="cursor: pointer;">
                                <span class="sup-idx-num">{{ $idx + 1 }}</span>
                            </div>
                        </td>

                        {{-- 2. Admission Date / Procedure --}}
                        <td class="cell-date-proc">
                            <div class="sup-entry-date">
                                <i class="fa-regular fa-calendar-check text-muted"></i>
                                <span>{{ $entry['date_formatted'] }}</span>
                            </div>
                            <div class="sup-entry-proc">
                                <span class="badge {{ $entry['badge_class'] }}" style="font-size: 10px; padding: 2px 6px;">
                                    {{ $entry['type_label'] }}
                                </span>
                                <span class="proc-name" title="{{ $entry['procedure_name'] ?: $entry['alt_procedure'] }}">
                                    {{ Str::limit($entry['procedure_name'] ?: ($entry['alt_procedure'] ?: '—'), 32) }}
                                </span>
                            </div>
                            @if(!$selectedTrainee)
                                <div class="sup-entry-trainee-sub" title="{{ $entry['trainee_name'] }} ({{ $entry['trainee_username'] }})">
                                    <i class="fa-regular fa-user"></i> {{ Str::limit($entry['trainee_name'], 22) }}
                                </div>
                            @endif
                        </td>

                        {{-- 3. Diagnosis --}}
                        <td class="cell-diagnosis">
                            <div class="sup-entry-diagnosis" title="{{ $entry['pt_diagnosis'] }}">
                                <strong>{{ Str::limit($entry['pt_diagnosis'], 60) }}</strong>
                            </div>
                        </td>

                        {{-- 4. Brief Desc --}}
                        <td class="cell-brief">
                            <div class="sup-entry-brief" title="{{ strip_tags($entry['brief_desc']) }}">
                                {{ Str::limit(strip_tags($entry['brief_desc']), 75) ?: '—' }}
                            </div>
                        </td>

                        {{-- 5. Competency Group / Detail --}}
                        <td class="cell-comp">
                            <div class="sup-entry-comp">
                                {!! $entry['competency_html'] !!}
                            </div>
                        </td>

                        {{-- 6. Level --}}
                        <td class="cell-level">
                            @if($entry['level_name'])
                                <span class="badge badge--level" title="{{ $entry['level_name'] }}">
                                    {{ $entry['level_name'] }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- 7. Entry Status / Hospt. Reg. No. --}}
                        <td class="cell-status-reg">
                            @php
                                $statusBadgeClass = match($entry['entry_status']) {
                                    'Approved' => 'badge--ok',
                                    'Awaiting Approval' => 'badge--warn',
                                    'Discuss and Resubmit' => 'badge--info',
                                    'Disapproved' => 'badge--danger',
                                    default => 'badge--muted',
                                };
                            @endphp
                            <div class="entry-status-badge-wrap">
                                <span class="badge {{ $statusBadgeClass }} js-row-status-badge">{{ $entry['entry_status'] }}</span>
                            </div>
                            <div class="sup-entry-regno" title="Hospt. Reg. No / Autopsy / MLC No">
                                <i class="fa-solid fa-hospital-user text-muted"></i>
                                <span>{{ $entry['hospt_reg_no'] ?: '—' }}</span>
                            </div>
                        </td>

                        {{-- 8. Detail (View Icon) --}}
                        <td class="cell-actions" style="text-align: center;">
                            <div class="sup-action-buttons" style="justify-content: center;">
                                <button type="button" class="btn-icon-action btn-icon-action--view js-view-entry" 
                                        data-type="{{ $entry['type'] }}" 
                                        data-id="{{ $entry['id'] }}"
                                        data-entry='@json($entry)'
                                        title="View Full Entry Details">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="sup-table__empty" style="text-align: center; padding: 36px 12px; color: #64748b;">
                            <i class="fa-solid fa-inbox" style="font-size: 2.2rem; display: block; margin-bottom: 10px; color: #cbd5e1;"></i>
                            No entries found matching the selected filter criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
</div>

{{-- ==========================================================================
   Full Entry Inspection Modal (View Icon Modal)
   ========================================================================== --}}
<div class="modal" id="entryDetailModal" role="dialog" aria-modal="true" hidden>
    <div class="modal__backdrop" data-close-detail-modal></div>
    <div class="modal__panel modal-entry-inspect">
        {{-- Modal Top Bar --}}
        <div class="modal-entry-inspect__header">
            <div>
                <h2 class="modal__title" id="detailModalHeading" style="color: #0b6040; margin: 0; font-size: 1.15rem; font-weight: 700;">
                    Trainee Entry Review & Inspection
                </h2>
                <div id="detailModalSubHeading" style="font-size: 12px; color: #64748b; margin-top: 2px;"></div>
            </div>
            <button type="button" data-close-detail-modal style="border: none; background: transparent; font-size: 22px; cursor: pointer; color: #64748b; line-height: 1;">&times;</button>
        </div>

        {{-- Modal Content Body --}}
        <div class="modal-entry-inspect__body" id="detailModalBody">
            {{-- Dynamic content injected via JS --}}
        </div>
    </div>
</div>

{{-- In-Page Status Confirmation Modal --}}
<div class="modal" id="quickConfirmModal" role="dialog" aria-modal="true" hidden>
    <div class="modal__backdrop" data-close-quick-modal></div>
    <div class="modal__panel" style="max-width: 440px;">
        <h2 class="modal__title" id="quickConfirmTitle" style="color: #0b6040; margin-bottom: 12px;">Confirm Action</h2>
        <p class="modal__text" id="quickConfirmText" style="font-size: 15px; margin-bottom: 20px; color: #444; line-height: 1.5;">
            Are you sure you want to proceed?
        </p>
        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <button type="button" class="btn btn--outline" data-close-quick-modal style="margin: 0;">Cancel</button>
            <button type="button" class="btn btn--submit" id="quickConfirmOkBtn" style="margin: 0; min-width: 100px;">Confirm</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Select all checkboxes & bulk toolbar logic
    var selectAll = document.getElementById('selectAllCheckbox');
    var itemCheckboxes = document.querySelectorAll('.entry-checkbox');
    var selectedCountText = document.getElementById('selectedCountText');
    var selectedIndicator = document.getElementById('selectedIndicator');
    var clearSelectionBtn = document.getElementById('clearSelectionBtn');
    var bulkToolbar = document.getElementById('supBulkToolbar');
    var bulkApplyBtn = document.getElementById('bulkApplyBtn');
    var bulkStatusSelect = document.getElementById('bulkStatusSelect');
    var headerCheckboxToggle = document.getElementById('headerCheckboxToggle');

    function updateSelectedState() {
        var count = 0;
        itemCheckboxes.forEach(function (cb) {
            if (cb.checked) count++;
        });

        if (count > 0) {
            selectedCountText.innerHTML = '<strong>' + count + '</strong> selected';
            if (selectedIndicator) selectedIndicator.classList.add('has-items');
            if (clearSelectionBtn) clearSelectionBtn.style.display = 'inline';
            if (bulkToolbar) bulkToolbar.classList.add('has-selected');
        } else {
            selectedCountText.textContent = '0 selected';
            if (selectedIndicator) selectedIndicator.classList.remove('has-items');
            if (clearSelectionBtn) clearSelectionBtn.style.display = 'none';
            if (bulkToolbar) bulkToolbar.classList.remove('has-selected');
        }

        if (bulkApplyBtn) {
            bulkApplyBtn.disabled = (count === 0 || !bulkStatusSelect.value);
        }
        if (selectAll) {
            selectAll.checked = (count > 0 && count === itemCheckboxes.length);
        }
        if (headerCheckboxToggle) {
            headerCheckboxToggle.checked = (count > 0 && count === itemCheckboxes.length);
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            var checked = this.checked;
            if (headerCheckboxToggle) headerCheckboxToggle.checked = checked;
            itemCheckboxes.forEach(function (cb) { cb.checked = checked; });
            updateSelectedState();
        });
    }

    if (headerCheckboxToggle) {
        headerCheckboxToggle.addEventListener('change', function () {
            var checked = this.checked;
            if (selectAll) selectAll.checked = checked;
            itemCheckboxes.forEach(function (cb) { cb.checked = checked; });
            updateSelectedState();
        });
    }

    if (clearSelectionBtn) {
        clearSelectionBtn.addEventListener('click', function () {
            itemCheckboxes.forEach(function (cb) { cb.checked = false; });
            if (selectAll) selectAll.checked = false;
            if (headerCheckboxToggle) headerCheckboxToggle.checked = false;
            updateSelectedState();
        });
    }

    itemCheckboxes.forEach(function (cb) {
        cb.addEventListener('change', updateSelectedState);
    });

    if (bulkStatusSelect) {
        bulkStatusSelect.addEventListener('change', updateSelectedState);
    }

    // Modal elements
    var detailModal = document.getElementById('entryDetailModal');
    var detailModalBody = document.getElementById('detailModalBody');
    var detailModalHeading = document.getElementById('detailModalHeading');
    var detailModalSubHeading = document.getElementById('detailModalSubHeading');

    // Quick confirmation modal
    var quickConfirmModal = document.getElementById('quickConfirmModal');
    var quickConfirmTitle = document.getElementById('quickConfirmTitle');
    var quickConfirmText = document.getElementById('quickConfirmText');
    var quickConfirmOkBtn = document.getElementById('quickConfirmOkBtn');
    var pendingStatusAction = null;

    // Bulk action confirmation
    var bulkActionForm = document.getElementById('bulkActionForm');
    if (bulkActionForm) {
        bulkActionForm.addEventListener('submit', function (e) {
            var selectedCount = 0;
            itemCheckboxes.forEach(function (cb) { if (cb.checked) selectedCount++; });
            var st = bulkStatusSelect ? bulkStatusSelect.value : '';
            if (st && !bulkActionForm.dataset.confirmed) {
                e.preventDefault();
                quickConfirmTitle.textContent = st === 'Approved' ? 'Confirm Bulk Approval' : 'Confirm Bulk Status Update';
                quickConfirmText.innerHTML = "Are you sure you want to update <strong>" + selectedCount + " selected entries</strong> to status <strong>'" + st + "'</strong>?";
                quickConfirmOkBtn.textContent = st === 'Approved' ? 'Yes, Approve Selected' : 'Confirm Update';
                quickConfirmOkBtn.style.backgroundColor = st === 'Approved' ? '#28a745' : '#0b6040';
                quickConfirmOkBtn.style.color = '#ffffff';

                pendingStatusAction = function () {
                    bulkActionForm.dataset.confirmed = 'true';
                    bulkActionForm.submit();
                };
                quickConfirmModal.hidden = false;
            }
        });
    }

    quickConfirmOkBtn.addEventListener('click', function () {
        if (pendingStatusAction) {
            var act = pendingStatusAction;
            pendingStatusAction = null;
            quickConfirmModal.hidden = true;
            act();
        }
    });

    document.querySelectorAll('[data-close-quick-modal]').forEach(function (el) {
        el.addEventListener('click', function () {
            quickConfirmModal.hidden = true;
            pendingStatusAction = null;
        });
    });

    // Helper to escape HTML safely
    function escapeHtml(str) {
        if (!str) return '';
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    // Helper status badge generator
    function getStatusBadge(status) {
        var cls = 'badge--muted';
        if (status === 'Approved') cls = 'badge--ok';
        else if (status === 'Awaiting Approval') cls = 'badge--warn';
        else if (status === 'Discuss and Resubmit') cls = 'badge--info';
        else if (status === 'Disapproved') cls = 'badge--danger';
        return '<span class="badge ' + cls + '">' + escapeHtml(status) + '</span>';
    }

    // View Icon Click Handler: Build complete inspection modal with all requested details
    document.querySelectorAll('.js-view-entry').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var data = JSON.parse(this.getAttribute('data-entry'));

            detailModalHeading.textContent = data.type_label + ' Entry Inspection (#' + data.id + ')';
            detailModalSubHeading.textContent = 'Submitted on ' + data.post_date;

            var html = '';

            // 1. Top Student Name Banner
            html += '<div class="sup-modal-student-banner">';
            html += '  <div class="sup-modal-student-info">';
            html += '    <div class="sup-modal-student-avatar"><i class="fa-solid fa-user-graduate"></i></div>';
            html += '    <div>';
            html += '      <div class="sup-modal-student-name">' + escapeHtml(data.trainee_name) + '</div>';
            html += '      <div class="sup-modal-student-sub">';
            html += '        <span><strong>User ID:</strong> ' + escapeHtml(data.trainee_username) + '</span>';
            if (data.trainee_roll_no) {
                html += '        <span style="margin-left: 10px;"><strong>Reg / Roll:</strong> ' + escapeHtml(data.trainee_roll_no) + '</span>';
            }
            if (data.trainee_program) {
                html += '        <span style="margin-left: 10px;"><strong>Program:</strong> ' + escapeHtml(data.trainee_program) + '</span>';
            }
            html += '      </div>';
            html += '    </div>';
            html += '  </div>';
            html += '  <div>';
            html += '    <span class="badge ' + data.badge_class + '" style="font-size: 12px; padding: 4px 10px;">' + escapeHtml(data.type_label) + '</span>';
            html += '  </div>';
            html += '</div>';

            // 2. Structured Metadata Grid
            html += '<div class="sup-modal-grid">';
            
            // Form Type
            html += '  <div class="sup-modal-field">';
            html += '    <span class="sup-modal-field__label"><i class="fa-solid fa-file-lines"></i> Form Type</span>';
            html += '    <span class="sup-modal-field__value">' + escapeHtml(data.form_type_label || data.type_label) + '</span>';
            html += '  </div>';

            // Hospt. reg. no. / autopsy
            html += '  <div class="sup-modal-field">';
            html += '    <span class="sup-modal-field__label"><i class="fa-solid fa-hospital-user"></i> Hospt. Reg. No. / Autopsy</span>';
            html += '    <span class="sup-modal-field__value">' + escapeHtml(data.hospt_reg_no || '—') + '</span>';
            html += '  </div>';

            // MLC No
            html += '  <div class="sup-modal-field">';
            html += '    <span class="sup-modal-field__label"><i class="fa-solid fa-scale-balanced"></i> MLC No.</span>';
            html += '    <span class="sup-modal-field__value">' + escapeHtml(data.mlc_no || data.hospt_reg_no || '—') + '</span>';
            html += '  </div>';

            // Date of Admission / Procedure
            html += '  <div class="sup-modal-field">';
            html += '    <span class="sup-modal-field__label"><i class="fa-regular fa-calendar-check"></i> Date of Admission / Procedure</span>';
            html += '    <span class="sup-modal-field__value">' + escapeHtml(data.date_formatted) + '</span>';
            html += '  </div>';

            // Gender / Age
            html += '  <div class="sup-modal-field">';
            html += '    <span class="sup-modal-field__label"><i class="fa-solid fa-venus-mars"></i> Gender / Age</span>';
            html += '    <span class="sup-modal-field__value">' + (data.gender ? escapeHtml(data.gender) + (data.age ? ', ' + escapeHtml(data.age) : '') : '—') + '</span>';
            html += '  </div>';

            // Diagnosis / Suspected Dia. / Cause
            html += '  <div class="sup-modal-field" style="grid-column: span 1;">';
            html += '    <span class="sup-modal-field__label"><i class="fa-solid fa-stethoscope"></i> Diagnosis / Suspected Dia. / Cause</span>';
            html += '    <span class="sup-modal-field__value" style="color: #0b6040;">' + escapeHtml(data.pt_diagnosis || data.title) + '</span>';
            html += '  </div>';

            // Under Supervision
            html += '  <div class="sup-modal-field">';
            html += '    <span class="sup-modal-field__label"><i class="fa-solid fa-user-doctor"></i> Under Supervision</span>';
            html += '    <span class="sup-modal-field__value">' + escapeHtml(data.under_sup_name || '—') + '</span>';
            html += '  </div>';

            // Level
            html += '  <div class="sup-modal-field">';
            html += '    <span class="sup-modal-field__label"><i class="fa-solid fa-layer-group"></i> Supervision Level</span>';
            html += '    <span class="sup-modal-field__value">' + (data.level_name ? '<span class="badge badge--level">' + escapeHtml(data.level_name) + '</span>' : '—') + '</span>';
            html += '  </div>';

            // Outcome
            html += '  <div class="sup-modal-field">';
            html += '    <span class="sup-modal-field__label"><i class="fa-solid fa-heart-pulse"></i> Outcome</span>';
            html += '    <span class="sup-modal-field__value">' + escapeHtml(data.outcome_name || '—') + '</span>';
            html += '  </div>';

            // Post Date
            html += '  <div class="sup-modal-field" style="grid-column: span 3;">';
            html += '    <span class="sup-modal-field__label"><i class="fa-regular fa-clock"></i> Post Date (Submitted)</span>';
            html += '    <span class="sup-modal-field__value">' + escapeHtml(data.post_date) + '</span>';
            html += '  </div>';

            html += '</div>'; // End sup-modal-grid

            // 3. Brief Description Box
            html += '<div class="sup-modal-section">';
            html += '  <div class="sup-modal-section__title"><i class="fa-solid fa-align-left text-muted"></i> Brief Description</div>';
            html += '  <div class="sup-modal-box">' + (escapeHtml(data.brief_desc) || '<span class="text-muted">No description provided.</span>') + '</div>';
            html += '</div>';

            // 4. Competency Groups and Details
            html += '<div class="sup-modal-section">';
            html += '  <div class="sup-modal-section__title"><i class="fa-solid fa-list-check text-muted"></i> Competency Groups and Details</div>';
            html += '  <div class="sup-modal-box" style="background: #fafafa;">';
            if (data.competency_groups && data.competency_groups.length > 0) {
                html += '    <div style="font-weight: 700; color: #1e293b; margin-bottom: 6px;">Groups:</div>';
                html += '    <ul style="margin: 0 0 10px 20px; padding: 0;">';
                data.competency_groups.forEach(function (grp) {
                    html += '      <li style="margin-bottom: 3px; font-weight: 600;">' + escapeHtml(grp) + '</li>';
                });
                html += '    </ul>';
            }
            if (data.competency_details && data.competency_details.length > 0) {
                html += '    <div style="font-weight: 700; color: #1e293b; margin-bottom: 6px;">Details:</div>';
                html += '    <ul style="margin: 0 0 0 20px; padding: 0;">';
                data.competency_details.forEach(function (dtl) {
                    html += '      <li style="margin-bottom: 3px;">' + escapeHtml(dtl) + '</li>';
                });
                html += '    </ul>';
            }
            if ((!data.competency_groups || data.competency_groups.length === 0) && (!data.competency_details || data.competency_details.length === 0)) {
                html += data.competency_html || '<span class="text-muted">No competency mapped.</span>';
            }
            html += '  </div>';
            html += '</div>';

            // 5. Update Status Section with Dropdown (Approved, Discuss & Resubmit, etc.) and Submit Button
            html += '<div class="sup-modal-update-box">';
            html += '  <div class="sup-modal-update-title">';
            html += '    <span><i class="fa-solid fa-pen-to-square"></i> Update Entry Status</span>';
            html += '    <span id="modalCurrentStatusBadge">' + getStatusBadge(data.entry_status) + '</span>';
            html += '  </div>';

            html += '  <form id="modalStatusFormAjax" data-type="' + data.type + '" data-id="' + data.id + '">';
            html += '    <div class="sup-modal-update-grid">';
            
            // Status Dropdown
            html += '      <div>';
            html += '        <label for="modalStatusDropdown" style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 5px;">';
            html += '          Change Status <span style="color: #ef4444;">*</span>';
            html += '        </label>';
            html += '        <select name="status" id="modalStatusDropdown" class="field__control" style="font-weight: 600;" required>';
            html += '          <option value="Approved"' + (data.entry_status === 'Approved' ? ' selected' : '') + '>Approved</option>';
            html += '          <option value="Discuss and Resubmit"' + (data.entry_status === 'Discuss and Resubmit' ? ' selected' : '') + '>Discuss and Resubmit</option>';
            html += '          <option value="Awaiting Approval"' + (data.entry_status === 'Awaiting Approval' ? ' selected' : '') + '>Awaiting Approval (Pending)</option>';
            html += '          <option value="Disapproved"' + (data.entry_status === 'Disapproved' ? ' selected' : '') + '>Disapproved</option>';
            html += '        </select>';
            html += '      </div>';

            // Supervisor Remarks Textarea
            html += '      <div>';
            html += '        <label for="modalStatusRemarks" style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 5px;">';
            html += '          Supervisor Remarks / Instructions';
            html += '        </label>';
            html += '        <input type="text" name="supervisor_remarks" id="modalStatusRemarks" class="field__control" placeholder="Enter remarks or revision feedback..." value="' + escapeHtml(data.supervisor_remarks || '') + '">';
            html += '      </div>';

            // Submit Button
            html += '      <div>';
            html += '        <button type="submit" class="btn btn--submit" id="modalStatusSubmitBtn" style="min-width: 110px; height: 38px; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">';
            html += '          <i class="fa-solid fa-paper-plane"></i> Submit';
            html += '        </button>';
            html += '      </div>';

            html += '    </div>';
            html += '    <div id="modalStatusFeedback" style="margin-top: 10px; font-size: 12.5px; font-weight: 600; display: none;"></div>';
            html += '  </form>';
            html += '</div>';

            detailModalBody.innerHTML = html;
            detailModal.hidden = false;

            // Wire up AJAX status submit inside the modal
            var statusForm = document.getElementById('modalStatusFormAjax');
            var submitBtn = document.getElementById('modalStatusSubmitBtn');
            var feedbackDiv = document.getElementById('modalStatusFeedback');
            var statusDropdown = document.getElementById('modalStatusDropdown');
            var remarksInput = document.getElementById('modalStatusRemarks');

            statusForm.addEventListener('submit', function (e) {
                e.preventDefault();
                var newStatus = statusDropdown.value;
                var remarksVal = remarksInput.value;
                var entryType = statusForm.getAttribute('data-type');
                var entryId = statusForm.getAttribute('data-id');

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Submitting...';
                feedbackDiv.style.display = 'none';

                fetch('/supervisor/entries/' + entryType + '/' + entryId + '/status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: newStatus,
                        supervisor_remarks: remarksVal
                    })
                })
                .then(function (res) {
                    if (!res.ok) throw new Error('HTTP error ' + res.status);
                    return res.json();
                })
                .then(function (result) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Updated!';
                    
                    // Show success feedback
                    feedbackDiv.style.display = 'block';
                    feedbackDiv.style.color = '#15803d';
                    feedbackDiv.innerHTML = '<i class="fa-solid fa-circle-check"></i> ' + (result.message || 'Status successfully updated.');

                    // Update modal status badge
                    var modalBadge = document.getElementById('modalCurrentStatusBadge');
                    if (modalBadge) {
                        modalBadge.innerHTML = getStatusBadge(newStatus);
                    }

                    // Update corresponding row in the table
                    var row = document.getElementById('entry-row-' + entryType + '-' + entryId);
                    if (row) {
                        var badgeEl = row.querySelector('.js-row-status-badge');
                        if (badgeEl) {
                            var cls = 'badge--muted';
                            if (newStatus === 'Approved') cls = 'badge--ok';
                            else if (newStatus === 'Awaiting Approval') cls = 'badge--warn';
                            else if (newStatus === 'Discuss and Resubmit') cls = 'badge--info';
                            else if (newStatus === 'Disapproved') cls = 'badge--danger';

                            badgeEl.className = 'badge ' + cls + ' js-row-status-badge';
                            badgeEl.textContent = newStatus;
                        }

                        // Also update data-entry attribute on the view button
                        var viewBtn = row.querySelector('.js-view-entry');
                        if (viewBtn) {
                            data.entry_status = newStatus;
                            data.supervisor_remarks = remarksVal;
                            viewBtn.setAttribute('data-entry', JSON.stringify(data));
                        }
                    }

                    setTimeout(function () {
                        submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Submit';
                    }, 2500);
                })
                .catch(function (err) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Submit';
                    feedbackDiv.style.display = 'block';
                    feedbackDiv.style.color = '#dc2626';
                    feedbackDiv.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Error updating status. Please try again.';
                });
            });
        });
    });

    // Close detail modal
    document.querySelectorAll('[data-close-detail-modal]').forEach(function (el) {
        el.addEventListener('click', function () {
            detailModal.hidden = true;
        });
    });
});
</script>
@endpush
@endsection
