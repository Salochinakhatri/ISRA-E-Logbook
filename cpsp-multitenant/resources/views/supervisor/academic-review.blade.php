@extends('layouts.app')

@section('title', 'Academic Progression Review | ' . ($trainee->profile?->full_name ?: $trainee->username))
@section('nav_supervisor_entries', 'is-active')

@push('styles')
<style>
.academic-review-page {
    padding: 16px 12px 48px;
    max-width: 100%;
}

/* Header & Breadcrumb */
.academic-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    flex-wrap: wrap;
    gap: 12px;
}

.academic-page-title {
    font-size: 22px;
    font-weight: 700;
    color: #1a202c;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.academic-page-title i {
    color: #0b6040;
}

.academic-page-sub {
    font-size: 13.5px;
    color: #555e68;
    margin-top: 3px;
    margin-bottom: 0;
}

.academic-header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-academic-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: #ffffff;
    color: #0b6040;
    border: 1.5px solid #0b6040;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
}

.btn-academic-back:hover {
    background-color: #0b6040;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(11, 96, 64, 0.25);
    text-decoration: none;
}

.btn-academic-print {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: #0b6040;
    color: #ffffff;
    border: 1.5px solid #0b6040;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
}

.btn-academic-print:hover {
    background-color: #084d33;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(11, 96, 64, 0.35);
}

/* Trainee Hero Banner */
.trainee-hero-card {
    background: linear-gradient(135deg, #0b6040 0%, #0d7a52 100%);
    border-radius: 10px;
    padding: 20px 24px;
    color: #ffffff;
    margin-bottom: 24px;
    box-shadow: 0 4px 14px rgba(11, 96, 64, 0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.trainee-hero-main {
    display: flex;
    align-items: center;
    gap: 18px;
}

.trainee-hero-avatar {
    width: 62px;
    height: 62px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.22);
    border: 2px solid rgba(255, 255, 255, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    color: #ffffff;
    flex-shrink: 0;
}

.trainee-hero-title {
    font-size: 20px;
    font-weight: 700;
    margin: 0 0 4px 0;
    color: #ffffff;
    letter-spacing: -0.3px;
}

.trainee-hero-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    font-size: 13px;
    color: rgba(255, 255, 255, 0.9);
}

.trainee-hero-meta span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Section Cards */
.academic-section-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    margin-bottom: 24px;
    overflow: hidden;
}

.academic-section-header {
    background: #f8fafc;
    border-bottom: 1.5px solid #e2e8f0;
    padding: 14px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.academic-section-title {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.academic-section-title i {
    color: #0b6040;
    font-size: 17px;
}

.academic-badge-count {
    background-color: #0b6040;
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 12px;
}

/* Academic Tables */
.academic-table-wrap {
    overflow-x: auto;
}

.academic-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
    margin: 0;
}

.academic-table thead th {
    background-color: #f1f5f9;
    color: #334155;
    font-weight: 700;
    font-size: 12.5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    border-bottom: 2px solid #cbd5e1;
    white-space: nowrap;
    text-align: left;
}

.academic-table tbody td {
    padding: 13px 16px;
    border-bottom: 1px solid #e2e8f0;
    color: #1e293b;
    vertical-align: middle;
}

.academic-table tbody tr:hover {
    background-color: #f8fafc;
}

.academic-table tbody tr:last-child td {
    border-bottom: none;
}

.col-hash {
    width: 60px;
    font-weight: 700;
    color: #64748b;
    text-align: center;
}

.academic-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: capitalize;
}

.badge-completed,
.badge-approved {
    background-color: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.badge-discuss {
    background-color: #fff7ed;
    color: #c2410c;
    border: 1px solid #fed7aa;
}

.badge-submitted,
.badge-in-progress,
.badge-under-review {
    background-color: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.badge-pending,
.badge-awaiting {
    background-color: #e0f2fe;
    color: #0369a1;
    border: 1px solid #bae6fd;
}

/* Modal styles for status update */
.arm-modal-backdrop {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(4px);
    z-index: 1050;
    justify-content: center;
    align-items: center;
    padding: 20px;
}
.arm-modal-backdrop.is-open {
    display: flex;
}
.arm-modal-dialog {
    background: #ffffff;
    width: 100%;
    max-width: 500px;
    border-radius: 12px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.arm-modal-header {
    background: linear-gradient(135deg, #0b6040 0%, #0d7a52 100%);
    color: #ffffff;
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.arm-modal-title {
    font-size: 16px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.arm-modal-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: #ffffff;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
}
.arm-modal-body {
    padding: 20px;
}
.arm-modal-footer {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 12px 20px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}
.btn-arm-secondary {
    padding: 7px 16px;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    color: #334155;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}
.btn-arm-primary {
    padding: 7px 16px;
    background: #0b6040;
    border: 1.5px solid #0b6040;
    color: #ffffff;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-set-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    background: #fffbeb;
    color: #b45309;
    border: 1px solid #fde68a;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
}
.btn-set-status:hover {
    background: #fef3c7;
    border-color: #f59e0b;
    color: #92400e;
}

.badge-rejected {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.table-empty-row {
    text-align: center;
    padding: 32px 16px !important;
    color: #64748b;
    font-style: italic;
    background: #fafafa;
}

@media print {
    .btn-academic-back,
    .btn-academic-print,
    .app-sidebar,
    .app-header {
        display: none !important;
    }
    .academic-review-page {
        padding: 0 !important;
    }
    .trainee-hero-card {
        background: #0b6040 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
@endpush

@section('content')
<div class="academic-review-page">
    {{-- Header --}}
    <div class="academic-page-header">
        <div>
            <h1 class="academic-page-title">
                <i class="fa-solid fa-graduation-cap"></i> Trainee Academic Progression Review
            </h1>
            <p class="academic-page-sub">Comprehensive overview of workshops, synopsis, and dissertation status</p>
        </div>
        <div class="academic-header-actions">
            <a href="{{ route('dashboard') }}" class="btn-academic-back">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
            <a href="{{ route('supervisor.entries', ['trainee_id' => $trainee->id]) }}" class="btn-academic-back" title="View logbook entries">
                <i class="fa-solid fa-book-medical"></i> Logbook Entries
            </a>
        </div>
    </div>

    {{-- Trainee Hero Card --}}
    <div class="trainee-hero-card">
        <div class="trainee-hero-main">
            <div class="trainee-hero-avatar">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
            <div>
                <h2 class="trainee-hero-title">{{ $trainee->profile?->full_name ?: $trainee->username }}</h2>
                <div class="trainee-hero-meta">
                    <span><i class="fa-solid fa-id-badge"></i> RTMC / ID: <strong>{{ $trainee->username }}</strong></span>
                    @if($trainee->email)
                        <span><i class="fa-regular fa-envelope"></i> {{ $trainee->email }}</span>
                    @endif
                    @if($trainee->profile?->phone)
                        <span><i class="fa-solid fa-phone"></i> {{ $trainee->profile->phone }}</span>
                    @endif
                    <span><i class="fa-solid fa-hospital-user"></i> Program: {{ $trainee->profile?->program ?? 'Postgraduate Residency' }}</span>
                </div>
            </div>
        </div>
        <div>
            <span class="tab-badge" style="background: rgba(255,255,255,0.22); color: #fff; font-size: 13px; padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                <i class="fa-solid fa-shield-halved"></i> CPSP Verified
            </span>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Section 1: Workshop Details --}}
    <div class="academic-section-card" id="sectionWorkshops">
        <div class="academic-section-header">
            <h2 class="academic-section-title">
                <i class="fa-solid fa-chalkboard-user"></i> Workshop Details
            </h2>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="academic-badge-count">{{ count($workshops) }} Workshops</span>
            </div>
        </div>
        <div class="academic-table-wrap">
            <table class="academic-table">
                <thead>
                    <tr>
                        <th class="col-hash">#</th>
                        <th>Workshop Name</th>
                        <th style="width: 180px;">Time Duration</th>
                        <th style="width: 180px;">Status</th>
                        <th style="width: 160px; text-align: center;">Supervisor Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($workshops as $index => $w)
                    <tr>
                        <td class="col-hash">{{ $index + 1 }}</td>
                        <td>
                            <strong style="color: #0f172a;">{{ $w->workshop_name }}</strong>
                            @if(!empty($w->remarks))
                                @php
                                    $isWApp = str_contains(strtolower((string)$w->status), 'approved');
                                @endphp
                                <div style="margin-top: 5px; padding: 6px 12px; background: {{ $isWApp ? '#f0fdf4' : '#fffbeb' }}; border: 1px solid {{ $isWApp ? '#bbf7d0' : '#fde68a' }}; border-left: 3px solid {{ $isWApp ? '#16a34a' : '#d97706' }}; border-radius: 5px; font-size: 12px; color: {{ $isWApp ? '#166534' : '#78350f' }};">
                                    <strong style="color: {{ $isWApp ? '#15803d' : '#b45309' }};"><i class="fa-solid fa-comment-dots"></i> Supervisor Remarks:</strong> {{ $w->remarks }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <i class="fa-regular fa-clock" style="color: #64748b; margin-right: 6px;"></i>
                            {{ $w->time_duration ?: 'N/A' }}
                        </td>
                        <td>
                            @php
                                $wStatus = strtolower(trim((string)$w->status));
                                $badgeClass = match(true) {
                                    str_contains($wStatus, 'approved') || str_contains($wStatus, 'completed') => 'badge-approved',
                                    str_contains($wStatus, 'discuss') || str_contains($wStatus, 'resubmit') => 'badge-discuss',
                                    default => 'badge-pending',
                                };
                                $iconClass = match(true) {
                                    str_contains($wStatus, 'approved') || str_contains($wStatus, 'completed') => 'fa-circle-check',
                                    str_contains($wStatus, 'discuss') || str_contains($wStatus, 'resubmit') => 'fa-comments',
                                    default => 'fa-clock',
                                };
                            @endphp
                            <span class="academic-status-badge {{ $badgeClass }}">
                                <i class="fa-solid {{ $iconClass }}"></i> {{ $w->status ?: 'Awaiting Approval' }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            @if(strtolower(trim((string)$w->status)) === 'approved')
                                <span style="font-size: 12.5px; color: #166534; font-weight: 700;">
                                    <i class="fa-solid fa-lock"></i> Approved &amp; Locked
                                </span>
                            @else
                                <button type="button" class="btn-set-status" onclick="openUpdateAcademicStatusModal('workshop', {{ $w->id }}, @js($w->workshop_name), @js($w->status ?: 'Awaiting Approval'), @js($w->remarks ?? ''))">
                                    <i class="fa-solid fa-pen-to-square"></i> Set Status
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="table-empty-row">
                            <i class="fa-regular fa-folder-open" style="font-size: 24px; display: block; margin-bottom: 8px;"></i>
                            No workshop records submitted by this trainee yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Section 2: Synopsis Details --}}
    <div class="academic-section-card" id="sectionSynopsis">
        <div class="academic-section-header">
            <h2 class="academic-section-title">
                <i class="fa-solid fa-file-waveform"></i> Synopsis Details
            </h2>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="academic-badge-count">{{ count($synopses) }} Synopsis</span>
            </div>
        </div>
        <div class="academic-table-wrap">
            <table class="academic-table">
                <thead>
                    <tr>
                        <th class="col-hash">#</th>
                        <th>Synopsis Name</th>
                        <th style="width: 180px;">Status</th>
                        <th style="width: 150px;">Status Date</th>
                        <th style="width: 160px; text-align: center;">Supervisor Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($synopses as $index => $s)
                    <tr>
                        <td class="col-hash">{{ $index + 1 }}</td>
                        <td>
                            <strong style="color: #0f172a;">{{ $s->synopsis_name }}</strong>
                            @if(!empty($s->remarks))
                                @php
                                    $isSApp = str_contains(strtolower((string)$s->status), 'approved');
                                @endphp
                                <div style="margin-top: 5px; padding: 6px 12px; background: {{ $isSApp ? '#f0fdf4' : '#fffbeb' }}; border: 1px solid {{ $isSApp ? '#bbf7d0' : '#fde68a' }}; border-left: 3px solid {{ $isSApp ? '#16a34a' : '#d97706' }}; border-radius: 5px; font-size: 12px; color: {{ $isSApp ? '#166534' : '#78350f' }};">
                                    <strong style="color: {{ $isSApp ? '#15803d' : '#b45309' }};"><i class="fa-solid fa-comment-dots"></i> Supervisor Remarks:</strong> {{ $s->remarks }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @php
                                $sStatus = strtolower(trim((string)$s->status));
                                $sBadgeClass = match(true) {
                                    str_contains($sStatus, 'approved') || str_contains($sStatus, 'accepted') => 'badge-approved',
                                    str_contains($sStatus, 'discuss') || str_contains($sStatus, 'resubmit') => 'badge-discuss',
                                    default => 'badge-pending',
                                };
                                $sIconClass = match(true) {
                                    str_contains($sStatus, 'approved') || str_contains($sStatus, 'accepted') => 'fa-stamp',
                                    str_contains($sStatus, 'discuss') || str_contains($sStatus, 'resubmit') => 'fa-comments',
                                    default => 'fa-clock',
                                };
                            @endphp
                            <span class="academic-status-badge {{ $sBadgeClass }}">
                                <i class="fa-solid {{ $sIconClass }}"></i> {{ $s->status ?: 'Awaiting Approval' }}
                            </span>
                        </td>
                        <td>
                            <i class="fa-regular fa-calendar-days" style="color: #64748b; margin-right: 6px;"></i>
                            {{ $s->status_date ? $s->status_date->format('d/m/Y') : ($s->created_at ? $s->created_at->format('d/m/Y') : '—') }}
                        </td>
                        <td style="text-align: center;">
                            @if(strtolower(trim((string)$s->status)) === 'approved')
                                <span style="font-size: 12.5px; color: #166534; font-weight: 700;">
                                    <i class="fa-solid fa-lock"></i> Approved &amp; Locked
                                </span>
                            @else
                                <button type="button" class="btn-set-status" onclick="openUpdateAcademicStatusModal('synopsis', {{ $s->id }}, @js($s->synopsis_name), @js($s->status ?: 'Awaiting Approval'), @js($s->remarks ?? ''))">
                                    <i class="fa-solid fa-pen-to-square"></i> Set Status
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="table-empty-row">
                            <i class="fa-regular fa-folder-open" style="font-size: 24px; display: block; margin-bottom: 8px;"></i>
                            No synopsis records submitted by this trainee yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Section 3: Dissertations Details --}}
    <div class="academic-section-card" id="sectionDissertations">
        <div class="academic-section-header">
            <h2 class="academic-section-title">
                <i class="fa-solid fa-book-open-reader"></i> Dissertations Details
            </h2>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="academic-badge-count">{{ count($dissertations) }} Dissertation</span>
            </div>
        </div>
        <div class="academic-table-wrap">
            <table class="academic-table">
                <thead>
                    <tr>
                        <th class="col-hash">#</th>
                        <th>Application Date &amp; Title</th>
                        <th style="width: 180px;">Status</th>
                        <th style="width: 150px;">Status Date</th>
                        <th style="width: 160px; text-align: center;">Supervisor Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dissertations as $index => $d)
                    <tr>
                        <td class="col-hash">{{ $index + 1 }}</td>
                        <td>
                            <div>
                                <i class="fa-regular fa-calendar-check" style="color: #64748b; margin-right: 6px;"></i>
                                <strong>{{ $d->application_date ? $d->application_date->format('d/m/Y') : ($d->created_at ? $d->created_at->format('d/m/Y') : '—') }}</strong>
                            </div>
                            @if($d->dissertation_title)
                                <div style="font-size: 12px; color: #475569; margin-top: 4px;">
                                    {{ $d->dissertation_title }}
                                </div>
                            @endif
                            @if(!empty($d->remarks))
                                @php
                                    $isDApp = str_contains(strtolower((string)$d->status), 'approved');
                                @endphp
                                <div style="margin-top: 5px; padding: 6px 12px; background: {{ $isDApp ? '#f0fdf4' : '#fffbeb' }}; border: 1px solid {{ $isDApp ? '#bbf7d0' : '#fde68a' }}; border-left: 3px solid {{ $isDApp ? '#16a34a' : '#d97706' }}; border-radius: 5px; font-size: 12px; color: {{ $isDApp ? '#166534' : '#78350f' }};">
                                    <strong style="color: {{ $isDApp ? '#15803d' : '#b45309' }};"><i class="fa-solid fa-comment-dots"></i> Supervisor Remarks:</strong> {{ $d->remarks }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @php
                                $dStatus = strtolower(trim((string)$d->status));
                                $dBadgeClass = match(true) {
                                    str_contains($dStatus, 'approved') || str_contains($dStatus, 'accepted') => 'badge-approved',
                                    str_contains($dStatus, 'discuss') || str_contains($dStatus, 'resubmit') => 'badge-discuss',
                                    default => 'badge-pending',
                                };
                                $dIconClass = match(true) {
                                    str_contains($dStatus, 'approved') || str_contains($dStatus, 'accepted') => 'fa-file-circle-check',
                                    str_contains($dStatus, 'discuss') || str_contains($dStatus, 'resubmit') => 'fa-comments',
                                    default => 'fa-clock',
                                };
                            @endphp
                            <span class="academic-status-badge {{ $dBadgeClass }}">
                                <i class="fa-solid {{ $dIconClass }}"></i> {{ $d->status ?: 'Awaiting Approval' }}
                            </span>
                        </td>
                        <td>
                            <i class="fa-regular fa-calendar-days" style="color: #64748b; margin-right: 6px;"></i>
                            {{ $d->status_date ? $d->status_date->format('d/m/Y') : '—' }}
                        </td>
                        <td style="text-align: center;">
                            @if(strtolower(trim((string)$d->status)) === 'approved')
                                <span style="font-size: 12.5px; color: #166534; font-weight: 700;">
                                    <i class="fa-solid fa-lock"></i> Approved &amp; Locked
                                </span>
                            @else
                                <button type="button" class="btn-set-status" onclick="openUpdateAcademicStatusModal('dissertation', {{ $d->id }}, @js($d->dissertation_title ?: 'Dissertation Submission'), @js($d->status ?: 'Awaiting Approval'), @js($d->remarks ?? ''))">
                                    <i class="fa-solid fa-pen-to-square"></i> Set Status
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="table-empty-row">
                            <i class="fa-regular fa-folder-open" style="font-size: 24px; display: block; margin-bottom: 8px;"></i>
                            No dissertation records submitted by this trainee yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Supervisor Set Academic Status Modal --}}
<div class="arm-modal-backdrop" id="modalUpdateAcademicStatus" tabindex="-1">
    <div class="arm-modal-dialog" style="max-width: 480px;">
        <div class="arm-modal-header">
            <h3 class="arm-modal-title" id="statusModalHeading"><i class="fa-solid fa-pen-to-square"></i> Set Approval Status</h3>
            <button type="button" class="arm-modal-close" onclick="closeModal('modalUpdateAcademicStatus')">&times;</button>
        </div>
        <form method="POST" id="academicStatusForm" action="">
            @csrf
            <div class="arm-modal-body">
                <div style="margin-bottom: 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
                    <span style="font-size: 11.5px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; display: block;">Record:</span>
                    <div id="statusModalItemName" style="font-weight: 700; color: #0f172a; font-size: 13.5px; margin-top: 3px;"></div>
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display:block;font-weight:600;font-size:13px;margin-bottom:5px;color:#334155;">Approval Status <span style="color:#ef4444;">*</span></label>
                    <select name="status" id="academicStatusSelect" required style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:6px;font-size:13.5px;font-weight:600;">
                        <option value="Approved">Approved</option>
                        <option value="Discuss and Resubmit">Discuss and Resubmit</option>
                        <option value="Awaiting Approval">Awaiting Approval (Pending)</option>
                    </select>
                </div>

                <div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;">
                        <label style="font-weight:600;font-size:13px;margin:0;color:#334155;">Supervisor Remarks / Feedback <span style="font-size:11.5px;color:#64748b;font-weight:normal;">(Optional)</span></label>
                        <button type="button" onclick="document.getElementById('academicStatusRemarks').value='';" style="background:none;border:none;color:#0b6040;font-size:12px;font-weight:600;cursor:pointer;padding:0;">Clear remarks</button>
                    </div>
                    <textarea name="remarks" id="academicStatusRemarks" rows="3" style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:6px;font-size:13px;" placeholder="Leave empty for no remarks, or enter comments for the trainee..."></textarea>
                </div>

                <div style="margin-top: 10px; font-size: 11.5px; color: #64748b;">
                    <i class="fa-solid fa-circle-info"></i> Note: If remarks are provided, they will appear on both supervisor and trainee records. If left empty, no remarks will be shown.
                </div>
            </div>
            <div class="arm-modal-footer">
                <button type="button" class="btn-arm-secondary" onclick="closeModal('modalUpdateAcademicStatus')">Cancel</button>
                <button type="submit" class="btn-arm-primary"><i class="fa-solid fa-paper-plane"></i> Save Status</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
var originalRemarksPerItem = {};

function openUpdateAcademicStatusModal(type, itemId, itemName, currentStatus, currentRemarks) {
    var form = document.getElementById('academicStatusForm');
    var heading = document.getElementById('statusModalHeading');
    var nameEl = document.getElementById('statusModalItemName');
    var select = document.getElementById('academicStatusSelect');
    var remarks = document.getElementById('academicStatusRemarks');

    var typeLabel = type.charAt(0).toUpperCase() + type.slice(1);
    heading.innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Set ' + typeLabel + ' Status';
    nameEl.textContent = itemName || (typeLabel + ' #' + itemId);

    var cleanStatus = (currentStatus || '').trim();
    select.value = (cleanStatus === 'Approved' || cleanStatus === 'Discuss and Resubmit' || cleanStatus === 'Awaiting Approval')
        ? cleanStatus
        : 'Approved';

    // Store original remarks so switching dropdown behaves intuitively
    var itemKey = type + '_' + itemId;
    originalRemarksPerItem[itemKey] = currentRemarks || '';

    // If opening to Approve an item that was previously in Discuss and Resubmit,
    // don't carry over the old revision instructions into the remarks textarea by default,
    // but allow supervisor to enter any new approval remarks if desired
    if (select.value === 'Approved' && cleanStatus.toLowerCase().indexOf('discuss') !== -1) {
        remarks.value = '';
        remarks.placeholder = 'Optional remarks on approval (leave blank for none)...';
    } else {
        remarks.value = currentRemarks || '';
        remarks.placeholder = 'Optional remarks or revision instructions for the trainee...';
    }

    form.action = '{{ url("/supervisor/trainees/" . $trainee->id . "/academic-status") }}/' + type + '/' + itemId;

    var m = document.getElementById('modalUpdateAcademicStatus');
    if (m) m.classList.add('is-open');
}

document.addEventListener('DOMContentLoaded', function() {
    var statusSel = document.getElementById('academicStatusSelect');
    if (statusSel) {
        statusSel.addEventListener('change', function() {
            var remEl = document.getElementById('academicStatusRemarks');
            if (this.value === 'Approved') {
                remEl.placeholder = 'Optional remarks on approval (leave blank for none)...';
            } else if (this.value === 'Discuss and Resubmit') {
                remEl.placeholder = 'Provide instructions for trainee on what to revise...';
            } else {
                remEl.placeholder = 'Optional remarks (leave blank for none)...';
            }
        });
    }
});

function closeModal(id) {
    var m = document.getElementById(id);
    if (m) m.classList.remove('is-open');
}
</script>
@endpush
@endsection
