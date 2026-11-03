@extends('layouts.app')
@section('title', 'Training Logbook | Isra e-Logbook')
@section('group_training', 'is-active is-open')
@section('nav_training_list', 'is-active')
@section('main_class', 'elog-main--fluid')

@push('styles')
<style>
/* ==========================================================================
   Modernized Training Index & List UI
   Consistent with CPSP Theme (#0b6040)
   ========================================================================== */
.training-module {
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

.btn-training-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #0b6040 0%, #117850 100%);
    color: #ffffff !important;
    font-size: 13.5px;
    font-weight: 700;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(11, 96, 64, 0.22);
    transition: all 0.2s ease;
}
.btn-training-primary:hover {
    background: linear-gradient(135deg, #084d33 0%, #0b6040 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(11, 96, 64, 0.28);
    color: #ffffff !important;
}

/* Card Styling */
.training-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    margin-bottom: 22px;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
    overflow: hidden;
}

.training-card__head {
    background: linear-gradient(135deg, #0b6040 0%, #107c53 100%);
    color: #ffffff;
    padding: 14px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.training-card__title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 15px;
    font-weight: 700;
    margin: 0;
    letter-spacing: 0.01em;
}

.training-card__head-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.badge-count {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.training-card__body {
    padding: 20px 22px;
}

/* Filter Form Grid */
.filter-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 18px;
}
@media (max-width: 1200px) {
    .filter-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 640px) {
    .filter-grid {
        grid-template-columns: 1fr;
    }
}

.filter-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.filter-label {
    font-size: 12.5px;
    font-weight: 600;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 6px;
}

.filter-label i {
    color: #0b6040;
    font-size: 12px;
}

.filter-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.filter-control {
    width: 100%;
    height: 40px;
    padding: 0 12px;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    font-size: 13.5px;
    font-family: inherit;
    color: #1e293b;
    background-color: #f8fafc;
    transition: all 0.18s ease;
    box-sizing: border-box;
}
.filter-control:focus {
    outline: none;
    border-color: #0b6040;
    background-color: #ffffff;
    box-shadow: 0 0 0 3px rgba(11, 96, 64, 0.12);
}
select.filter-control {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 16px;
    padding-right: 32px;
    appearance: none;
}

/* Filter Actions Bar */
.filter-actions-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 14px;
    padding-top: 14px;
    border-top: 1px solid #f1f5f9;
}

.filter-btns {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-filter-apply {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #0b6040;
    color: #ffffff;
    font-size: 13px;
    font-weight: 700;
    padding: 8px 16px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: background 0.18s ease;
}
.btn-filter-apply:hover {
    background: #084d33;
}

.btn-filter-reset {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ffffff;
    color: #64748b;
    font-size: 13px;
    font-weight: 600;
    padding: 8px 14px;
    border-radius: 6px;
    border: 1.5px solid #e2e8f0;
    text-decoration: none;
    transition: all 0.18s ease;
}
.btn-filter-reset:hover {
    background: #f8fafc;
    color: #1e293b;
    border-color: #cbd5e1;
}

.last-entry-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 12.5px;
    color: #475569;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 6px 12px;
    border-radius: 6px;
}
.last-entry-pill i {
    color: #0b6040;
}
.last-entry-pill strong {
    color: #0f172a;
}

/* Modern Data Table */
.training-table-wrap {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.modern-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 13px;
}

.modern-table thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
    font-size: 11.5px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 13px 16px;
    border-bottom: 2px solid #e2e8f0;
    white-space: nowrap;
    text-align: left;
}

.modern-table tbody tr {
    transition: background-color 0.15s ease;
}
.modern-table tbody tr:hover {
    background-color: #f8fafc;
}

.modern-table tbody td {
    padding: 14px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
}

.td-num {
    color: #94a3b8;
    font-weight: 600;
    font-size: 12px;
    width: 44px;
    text-align: center;
}

.entry-admit-box {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.entry-admit-date {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 700;
    color: #0f172a;
    font-size: 13px;
}
.entry-admit-date i {
    color: #0b6040;
    font-size: 12px;
}
.entry-reg-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11.5px;
    color: #475569;
    background: #f1f5f9;
    padding: 2px 8px;
    border-radius: 4px;
    border: 1px solid #e2e8f0;
    font-family: ui-monospace, SFMono-Regular, monospace;
    width: fit-content;
}

.entry-diag-title {
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 2px;
    line-height: 1.35;
}

.entry-brief-preview {
    color: #64748b;
    font-size: 12.5px;
    line-height: 1.4;
    max-width: 260px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Competency Formatting */
.comp-cell__group {
    font-weight: 700;
    color: #0b6040;
    font-size: 12px;
    margin-bottom: 3px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.comp-cell__group::before {
    content: "•";
    color: #0b6040;
    font-size: 16px;
    line-height: 1;
}
.comp-cell__list {
    margin: 0;
    padding-left: 14px;
    font-size: 11.5px;
    color: #475569;
}
.comp-cell__list li {
    margin-bottom: 2px;
}

/* Level Badges */
.level-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 6px;
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #e2e8f0;
    white-space: nowrap;
}
.level-pill--1 { background: #f0fdf4; color: #166534; border-color: #bbf7d0; } /* Observer */
.level-pill--2 { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; } /* Assistant */
.level-pill--3 { background: #fefce8; color: #854d0e; border-color: #fef08a; } /* Direct */
.level-pill--4 { background: #faf5ff; color: #6b21a8; border-color: #e9d5ff; } /* Indirect */
.level-pill--5 { background: #ecfdf5; color: #065f46; border-color: #a7f3d0; font-weight: 700; } /* Independent */

/* Status Pills */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    white-space: nowrap;
}
.status-pill--approved {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}
.status-pill--pending {
    background: #fffbeb;
    color: #92400e;
    border: 1px solid #fde68a;
}
.status-pill--discuss {
    background: #f0f9ff;
    color: #0369a1;
    border: 1px solid #bae6fd;
}
.status-pill--rejected {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}
.status-pill--draft {
    background: #f8fafc;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.entry-status-meta {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.entry-date-sub {
    font-size: 11px;
    color: #64748b;
    display: block;
}

/* Action View Button */
.btn-action-view {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #ffffff;
    color: #0b6040;
    border: 1.5px solid #cbd5e1;
    text-decoration: none;
    transition: all 0.18s ease;
}
.btn-action-view:hover {
    background: #0b6040;
    color: #ffffff;
    border-color: #0b6040;
    transform: scale(1.05);
}

/* Empty State */
.empty-state-card {
    text-align: center;
    padding: 48px 24px;
}
.empty-state-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    margin: 0 auto 16px auto;
}
.empty-state-title {
    font-size: 17px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 6px 0;
}
.empty-state-text {
    font-size: 13.5px;
    color: #64748b;
    max-width: 420px;
    margin: 0 auto 20px auto;
}
</style>
@endpush

@section('content')
<div class="training-module">
    {{-- Breadcrumb --}}
    <nav class="training-breadcrumb" aria-label="Breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i> Home</a></li>
            <li class="breadcrumb-item separator"><i class="fa-solid fa-chevron-right"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('training.index', ['program' => request('program')]) }}">Training</a></li>
            <li class="breadcrumb-item separator"><i class="fa-solid fa-chevron-right"></i></li>
            <li class="breadcrumb-item active">List All Entries</li>
        </ol>
    </nav>

    {{-- Page Header Banner --}}
    <div class="training-header-banner">
        <div class="training-header__left">
            <span class="training-badge-pill"><i class="fa-solid fa-stethoscope"></i> Clinical Training Module</span>
            <h1 class="training-header__title">Training Entries</h1>
            <p class="training-header__subtitle">Review, search, and manage your patient cases, clinical encounters, and competency logs.</p>
        </div>
        <div class="training-header__actions">
            @include('partials.program-badge', ['program' => request('program', $program ?? '')])
            <a class="btn-training-primary" href="{{ route('training.create', ['program' => request('program')]) }}">
                <i class="fa-solid fa-plus"></i> Add New Entry
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="training-card">
        <div class="training-card__head">
            <div class="training-card__title">
                <i class="fa-solid fa-sliders"></i> Search & Filter Records
            </div>
            @if(!empty($filters['status']) || !empty($filters['level']) || !empty($filters['reg']) || !empty($filters['post_from']) || !empty($filters['post_to']) || !empty($filters['adm_from']) || !empty($filters['adm_to']))
                <span class="badge-count"><i class="fa-solid fa-filter"></i> Filters Active</span>
            @endif
        </div>
        <div class="training-card__body">
            <form class="training-filters-form" method="get" action="{{ route('training.index') }}">
                @if(request('program'))
                    <input type="hidden" name="program" value="{{ request('program') }}">
                @endif

                <div class="filter-grid">
                    <div class="filter-field">
                        <label class="filter-label" for="f_status"><i class="fa-solid fa-circle-check"></i> Entry Status</label>
                        <select class="filter-control" name="f_status" id="f_status">
                            <option value="">All Entry Status</option>
                            @foreach(['Draft'=>'Draft','Approved'=>'Approved','Awaiting Approval'=>'Awaiting Approval','Discuss and Resubmit'=>'Discuss and Resubmit'] as $val => $lab)
                                <option value="{{ $val }}" {{ ($filters['status'] ?? '') === (string)$val ? 'selected' : '' }}>{{ $lab }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-field">
                        <label class="filter-label" for="f_level"><i class="fa-solid fa-layer-group"></i> Level</label>
                        <select class="filter-control" name="f_level" id="f_level">
                            <option value="">All Levels</option>
                            @foreach(['1'=>'Observer Status','2'=>'Assistant Status','3'=>'Performed under direct Supervision','4'=>'Performed under indirect supervision','5'=>'Performed independently','5555'=>'Other'] as $val => $lab)
                                <option value="{{ $val }}" {{ ($filters['level'] ?? '') === (string)$val ? 'selected' : '' }}>{{ $lab }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-field">
                        <label class="filter-label" for="f_reg"><i class="fa-solid fa-hashtag"></i> Hospt Reg No</label>
                        <input class="filter-control" type="text" name="f_reg" id="f_reg" placeholder="Search by Reg / MLC No" value="{{ $filters['reg'] ?? '' }}">
                    </div>

                    <div class="filter-field">
                        <label class="filter-label" for="f_adm_from"><i class="fa-regular fa-calendar"></i> Admission Date From</label>
                        <input class="filter-control dateBritish" type="text" name="f_adm_from" id="f_adm_from" placeholder="dd-mm-yyyy" value="{{ $filters['adm_from'] ?? '' }}">
                    </div>

                    <div class="filter-field">
                        <label class="filter-label" for="f_adm_to"><i class="fa-regular fa-calendar"></i> Admission Date To</label>
                        <input class="filter-control dateBritish" type="text" name="f_adm_to" id="f_adm_to" placeholder="dd-mm-yyyy" value="{{ $filters['adm_to'] ?? '' }}">
                    </div>

                    <div class="filter-field">
                        <label class="filter-label" for="f_post_from"><i class="fa-regular fa-calendar-days"></i> Post Date From</label>
                        <input class="filter-control dateBritish" type="text" name="f_post_from" id="f_post_from" placeholder="dd-mm-yyyy" value="{{ $filters['post_from'] ?? '' }}">
                    </div>

                    <div class="filter-field">
                        <label class="filter-label" for="f_post_to"><i class="fa-regular fa-calendar-days"></i> Post Date To</label>
                        <input class="filter-control dateBritish" type="text" name="f_post_to" id="f_post_to" placeholder="dd-mm-yyyy" value="{{ $filters['post_to'] ?? '' }}">
                    </div>
                </div>

                <div class="filter-actions-bar">
                    <div class="filter-btns">
                        <button type="submit" class="btn-filter-apply">
                            <i class="fa-solid fa-magnifying-glass"></i> Apply Filters
                        </button>
                        <a href="{{ route('training.index', ['program' => request('program')]) }}" class="btn-filter-reset">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </a>
                    </div>
                    @if($lastEntryLabel)
                    <div class="last-entry-pill">
                        <i class="fa-regular fa-clock"></i>
                        <span>Last entry added on: <strong>{{ $lastEntryLabel }}</strong></span>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Entries Table Card --}}
    <div class="training-card">
        <div class="training-card__head">
            <div class="training-card__title">
                <i class="fa-solid fa-table-list"></i> Clinical Case Entries
            </div>
            <div class="training-card__head-actions">
                <span class="badge-count">{{ $entries->count() }} {{ Str::plural('Entry', $entries->count()) }}</span>
            </div>
        </div>

        <div class="training-table-wrap">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th class="td-num">#</th>
                        <th>Admit Date / Hpt. Reg No</th>
                        <th>Diagnosis</th>
                        <th>Brief Description</th>
                        <th>Competency Group / Details</th>
                        <th>Level</th>
                        <th>Entry Status</th>
                        <th style="text-align: center; width: 70px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $index => $row)
                    <tr>
                        <td class="td-num">{{ $index + 1 }}</td>
                        <td>
                            <div class="entry-admit-box">
                                <span class="entry-admit-date">
                                    <i class="fa-regular fa-calendar"></i>
                                    {{ $row->date_of_admission ? $row->date_of_admission->format('d-m-Y') : '—' }}
                                </span>
                                <span class="entry-reg-pill">{{ $row->hospt_reg_no }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="entry-diag-title">{{ $row->pt_diagnosis }}</div>
                            @if($row->pt_gender || $row->pt_age)
                                <div style="font-size: 11.5px; color: #64748b;">
                                    {{ $row->pt_gender }} @if($row->pt_gender && $row->pt_age)&bull;@endif {{ $row->pt_age }} {{ $row->pt_age_type }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="entry-brief-preview" title="{{ strip_tags($row->brief_desc) }}">
                                {{ Str::limit(strip_tags($row->brief_desc), 90) }}
                            </div>
                        </td>
                        <td>
                            <div class="comp-cell-wrapper">
                                {!! app(\App\Services\CompetencyService::class)->formatCell($row->com_ids, $row->com_detail_ids, $compMap) !!}
                            </div>
                        </td>
                        <td>
                            @php
                                $levelShort = [
                                    '1' => 'Observer',
                                    '2' => 'Assistant',
                                    '3' => 'Direct Sup.',
                                    '4' => 'Indirect Sup.',
                                    '5' => 'Independent',
                                    '5555' => 'Other',
                                ];
                                $lvlLabel = $levelShort[$row->level_id] ?? ($row->level_id ?: '—');
                            @endphp
                            <span class="level-pill level-pill--{{ $row->level_id }}">
                                <i class="fa-solid fa-chart-line" style="font-size: 10px;"></i>
                                {{ $lvlLabel }}
                            </span>
                        </td>
                        <td>
                            <div class="entry-status-meta">
                                @php
                                    $sBadgeClass = match($row->entry_status) {
                                        'Approved' => 'status-pill--approved',
                                        'Awaiting Approval' => 'status-pill--pending',
                                        'Discuss and Resubmit' => 'status-pill--discuss',
                                        'Disapproved' => 'status-pill--rejected',
                                        default => 'status-pill--draft',
                                    };
                                    $sIcon = match($row->entry_status) {
                                        'Approved' => 'fa-circle-check',
                                        'Awaiting Approval' => 'fa-clock',
                                        'Discuss and Resubmit' => 'fa-comments',
                                        'Disapproved' => 'fa-circle-xmark',
                                        default => 'fa-file-lines',
                                    };
                                @endphp
                                <div>
                                    <span class="status-pill {{ $sBadgeClass }}">
                                        <i class="fa-solid {{ $sIcon }}"></i> {{ $row->entry_status ?: 'Draft' }}
                                    </span>
                                </div>
                                <span class="entry-date-sub">Added: {{ $row->created_at?->format('d-m-Y') }}</span>
                                @if($row->approved_at)
                                    <span class="entry-date-sub" style="color: #065f46; font-weight: 600;">Appr: {{ $row->approved_at->format('d-m-Y') }}</span>
                                @endif
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <a class="btn-action-view" href="{{ route('training.show', $row->id) }}" title="View Entry Details">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state-card">
                                <div class="empty-state-icon"><i class="fa-regular fa-folder-open"></i></div>
                                <h3 class="empty-state-title">No Training Entries Found</h3>
                                <p class="empty-state-text">There are no case records matching your filter criteria, or you haven't logged any clinical encounters yet.</p>
                                <a class="btn-training-primary" href="{{ route('training.create', ['program' => request('program')]) }}">
                                    <i class="fa-solid fa-plus"></i> Add New Entry
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
