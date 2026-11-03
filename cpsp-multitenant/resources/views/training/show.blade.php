@extends('layouts.app')
@section('title', 'View Training Entry | Isra e-Logbook')
@section('group_training', 'is-active is-open')
@section('main_class', 'elog-main--fluid')

@push('styles')
<style>
/* ==========================================================================
   Modernized Training View Entry UI
   Consistent with CPSP Theme (#0b6040)
   ========================================================================== */
.training-view-module {
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

/* Card Styling */
.view-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    margin-bottom: 22px;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
    overflow: hidden;
}

.view-card__head {
    background: linear-gradient(135deg, #0b6040 0%, #107c53 100%);
    color: #ffffff;
    padding: 14px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.view-card__title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 15px;
    font-weight: 700;
    margin: 0;
    letter-spacing: 0.01em;
}

.view-card__body {
    padding: 22px 24px;
}

/* Key-Value Details Grid */
.kv-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
}
@media (max-width: 1100px) {
    .kv-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 600px) {
    .kv-grid {
        grid-template-columns: 1fr;
    }
}

.kv-item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px 16px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    transition: all 0.15s ease;
}
.kv-item:hover {
    background: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
}
.kv-item--full {
    grid-column: 1 / -1;
}

.kv-label {
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 6px;
}
.kv-label i {
    color: #0b6040;
    font-size: 12px;
}

.kv-value {
    font-size: 14.5px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.35;
    word-break: break-word;
}
.kv-value-sub {
    font-size: 12px;
    font-weight: 500;
    color: #64748b;
    margin-top: 2px;
}

.reg-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: ui-monospace, SFMono-Regular, monospace;
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
    background: #e2e8f0;
    padding: 3px 10px;
    border-radius: 6px;
    width: fit-content;
}

/* Status Pills */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    width: fit-content;
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

/* Level Badges */
.level-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 6px;
    background: #e6f4ee;
    color: #0b6040;
    border: 1px solid #a7f3d0;
    width: fit-content;
}

/* Text Content Box */
.content-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 18px 20px;
    font-size: 14px;
    line-height: 1.65;
    color: #334155;
    white-space: pre-wrap;
}

/* Competency Formatting */
.comp-wrapper {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 18px 20px;
}
.comp-cell__group {
    font-weight: 800;
    color: #0b6040;
    font-size: 13.5px;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.comp-cell__group::before {
    content: "•";
    color: #0b6040;
    font-size: 18px;
    line-height: 1;
}
.comp-cell__list {
    margin: 0 0 14px 0;
    padding-left: 20px;
    font-size: 13px;
    color: #475569;
}
.comp-cell__list li {
    margin-bottom: 4px;
}

/* Supervisor Remarks Box */
.remarks-card {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 12px;
    padding: 18px 22px;
    margin-bottom: 22px;
    box-shadow: 0 2px 6px rgba(245, 158, 11, 0.05);
}
.remarks-card__head {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 700;
    color: #92400e;
    margin-bottom: 8px;
}
.remarks-card__content {
    font-size: 13.5px;
    color: #78350f;
    line-height: 1.6;
}

/* Supervisor Action Panel */
.supervisor-actions-bar {
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 12px;
    padding: 16px 20px;
    margin-top: 26px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
}
.supervisor-actions__label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
}
.supervisor-actions__btns {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-sup-approve {
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
.btn-sup-approve:hover {
    background: #084d33;
}

.btn-sup-pending {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f59e0b;
    color: #ffffff;
    font-size: 13px;
    font-weight: 700;
    padding: 8px 16px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: background 0.18s ease;
}
.btn-sup-pending:hover {
    background: #d97706;
}

.btn-sup-outline {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ffffff;
    color: #475569;
    font-size: 13px;
    font-weight: 600;
    padding: 8px 14px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    text-decoration: none;
    transition: all 0.18s ease;
}
.btn-sup-outline:hover {
    background: #f8fafc;
    color: #0f172a;
    border-color: #94a3b8;
}
</style>
@endpush

@section('content')
<div class="training-view-module">
    {{-- Breadcrumb --}}
    <nav class="training-breadcrumb" aria-label="Breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i> Home</a></li>
            <li class="breadcrumb-item separator"><i class="fa-solid fa-chevron-right"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('training.index', ['program' => request('program', $entry->program ?? '')]) }}">Training</a></li>
            <li class="breadcrumb-item separator"><i class="fa-solid fa-chevron-right"></i></li>
            <li class="breadcrumb-item active">View Entry #{{ $entry->hospt_reg_no }}</li>
        </ol>
    </nav>

    {{-- Page Header Banner --}}
    <div class="training-header-banner">
        <div class="training-header__left">
            <span class="training-badge-pill"><i class="fa-solid fa-file-waveform"></i> Clinical Case Record</span>
            <h1 class="training-header__title">View Training Entry</h1>
            <p class="training-header__subtitle">Review clinical encounter data, supervisor routing status, and curriculum competencies.</p>
        </div>
        <div class="training-header__actions">
            @include('partials.program-badge', ['program' => request('program', $entry->program ?? '')])
            <a class="btn-training-outline" href="{{ route('training.index', ['program' => request('program', $entry->program ?? '')]) }}">
                <i class="fa-solid fa-arrow-left"></i> List All Entries
            </a>
        </div>
    </div>

    {{-- Supervisor Remarks (if present) --}}
    @if($entry->supervisor_remarks)
    <div class="remarks-card">
        <div class="remarks-card__head">
            <i class="fa-solid fa-comment-dots"></i> Supervisor Remarks
        </div>
        <div class="remarks-card__content">
            {!! nl2br(e($entry->supervisor_remarks)) !!}
        </div>
    </div>
    @endif

    {{-- Patient & Case Details Card --}}
    <div class="view-card">
        <div class="view-card__head">
            <h2 class="view-card__title">
                <i class="fa-solid fa-hospital-user"></i> Patient & Encounter Overview
            </h2>
            @php
                $sBadgeClass = match($entry->entry_status) {
                    'Approved' => 'status-pill--approved',
                    'Awaiting Approval' => 'status-pill--pending',
                    'Discuss and Resubmit' => 'status-pill--discuss',
                    'Disapproved' => 'status-pill--rejected',
                    default => 'status-pill--draft',
                };
                $sIcon = match($entry->entry_status) {
                    'Approved' => 'fa-circle-check',
                    'Awaiting Approval' => 'fa-clock',
                    'Discuss and Resubmit' => 'fa-comments',
                    'Disapproved' => 'fa-circle-xmark',
                    default => 'fa-file-lines',
                };
            @endphp
            <span class="status-pill {{ $sBadgeClass }}">
                <i class="fa-solid {{ $sIcon }}"></i> {{ $entry->entry_status ?: 'Draft' }}
            </span>
        </div>

        <div class="view-card__body">
            <div class="kv-grid">
                <div class="kv-item">
                    <span class="kv-label"><i class="fa-solid fa-hashtag"></i> Hospital Reg. No</span>
                    <span class="kv-value">
                        <span class="reg-pill">{{ $entry->hospt_reg_no }}</span>
                    </span>
                </div>

                <div class="kv-item">
                    <span class="kv-label"><i class="fa-regular fa-calendar"></i> Admission Date</span>
                    <span class="kv-value">{{ $entry->date_of_admission ? $entry->date_of_admission->format('d-m-Y') : '—' }}</span>
                </div>

                <div class="kv-item">
                    <span class="kv-label"><i class="fa-solid fa-user"></i> Gender / Age</span>
                    <span class="kv-value">{{ $entry->pt_gender }} @if($entry->pt_gender && $entry->pt_age)/@endif {{ $entry->pt_age }} {{ $entry->pt_age_type }}</span>
                </div>

                <div class="kv-item">
                    <span class="kv-label"><i class="fa-solid fa-layer-group"></i> Performance Level</span>
                    <span class="kv-value">
                        @php
                            $levels = \App\Services\LookupService::levels();
                            $lvlText = $levels[$entry->level_id] ?? ($entry->level_id ?: '—');
                        @endphp
                        <span class="level-badge">
                            <i class="fa-solid fa-chart-line" style="font-size: 10px;"></i>
                            {{ $lvlText }}
                        </span>
                    </span>
                </div>

                <div class="kv-item kv-item--full">
                    <span class="kv-label"><i class="fa-solid fa-stethoscope"></i> Clinical Diagnosis / Cause</span>
                    <span class="kv-value" style="font-size: 16px; color: #0b6040;">{{ $entry->pt_diagnosis }}</span>
                </div>

                @if($entry->form_type)
                <div class="kv-item">
                    <span class="kv-label"><i class="fa-solid fa-file-lines"></i> Form Type</span>
                    <span class="kv-value">{{ \App\Services\LookupService::formTypes()[$entry->form_type] ?? $entry->form_type }}</span>
                </div>
                @endif

                @if($entry->outcome_id)
                <div class="kv-item">
                    <span class="kv-label"><i class="fa-solid fa-heart-pulse"></i> Patient Outcome</span>
                    <span class="kv-value">{{ \App\Services\LookupService::outcomes()[$entry->outcome_id] ?? $entry->outcome_id }}</span>
                </div>
                @endif

                @if($entry->under_sup_name || optional($entry->supervisor)->username)
                <div class="kv-item">
                    <span class="kv-label"><i class="fa-solid fa-user-doctor"></i> Under Supervision</span>
                    <span class="kv-value">{{ $entry->under_sup_name ?: optional($entry->supervisor->profile)->full_name ?: optional($entry->supervisor)->username }}</span>
                </div>
                @endif

                <div class="kv-item">
                    <span class="kv-label"><i class="fa-solid fa-clock-rotate-left"></i> Timeline</span>
                    <span class="kv-value" style="font-size: 13px;">
                        Added: {{ $entry->created_at?->format('d-m-Y') }}
                        @if($entry->approved_at)
                            <div class="kv-value-sub" style="color: #065f46; font-weight: 600;">
                                Approved: {{ $entry->approved_at->format('d-m-Y H:i') }}
                            </div>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Brief Description Card --}}
    <div class="view-card">
        <div class="view-card__head">
            <h2 class="view-card__title">
                <i class="fa-solid fa-align-left"></i> Case / Procedure Description
            </h2>
        </div>
        <div class="view-card__body">
            <div class="content-box">
                {!! nl2br(e(strip_tags($entry->brief_desc))) !!}
            </div>
        </div>
    </div>

    {{-- Competencies Card --}}
    <div class="view-card">
        <div class="view-card__head">
            <h2 class="view-card__title">
                <i class="fa-solid fa-sitemap"></i> Curriculum Competencies Covered
            </h2>
        </div>
        <div class="view-card__body">
            <div class="comp-wrapper">
                {!! app(\App\Services\CompetencyService::class)->formatCell($entry->com_ids, $entry->com_detail_ids, $compMap) !!}
            </div>

            @if($entry->alt_procedure)
            <div style="margin-top: 16px; padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 4px;">Alternate Procedure / Competency</span>
                <span style="font-size: 13.5px; font-weight: 600; color: #0f172a;">{{ $entry->alt_procedure }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Supervisor Action Controls (when logged in as Supervisor) --}}
    @if(session('user_type') !== 'Trainee')
    <div class="supervisor-actions-bar">
        <div class="supervisor-actions__label">
            <i class="fa-solid fa-user-check" style="color: #0b6040;"></i>
            <span>Supervisor Actions for this Entry:</span>
        </div>
        <div class="supervisor-actions__btns">
            <form action="{{ route('supervisor.entry.status', ['type' => 'training', 'id' => $entry->id]) }}" method="post" style="display: inline;">
                @csrf
                <input type="hidden" name="status" value="Approved">
                <button type="submit" class="btn-sup-approve">
                    <i class="fa-solid fa-check"></i> Approve Entry
                </button>
            </form>
            <form action="{{ route('supervisor.entry.status', ['type' => 'training', 'id' => $entry->id]) }}" method="post" style="display: inline;">
                @csrf
                <input type="hidden" name="status" value="Awaiting Approval">
                <button type="submit" class="btn-sup-pending">
                    <i class="fa-solid fa-clock"></i> Set Pending
                </button>
            </form>
            <a href="{{ route('supervisor.entries', ['trainee_id' => $entry->user_id]) }}" class="btn-sup-outline">
                <i class="fa-solid fa-list"></i> View Trainee Entries
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
