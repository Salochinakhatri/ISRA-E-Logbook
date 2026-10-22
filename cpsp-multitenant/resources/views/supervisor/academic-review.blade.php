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

.badge-submitted,
.badge-in-progress,
.badge-under-review {
    background-color: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.badge-pending {
    background-color: #e0f2fe;
    color: #0369a1;
    border: 1px solid #bae6fd;
}

.badge-disapproved,
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

    {{-- Section 1: Workshop Details --}}
    <div class="academic-section-card" id="sectionWorkshops">
        <div class="academic-section-header">
            <h2 class="academic-section-title">
                <i class="fa-solid fa-chalkboard-user"></i> Workshop Details
            </h2>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="academic-badge-count">{{ count($workshops) }} Workshops</span>
                <button type="button" class="btn-academic-back" style="font-size: 12px; padding: 4px 10px;" onclick="openAddWorkshopModal()">
                    <i class="fa-solid fa-plus"></i> Add Workshop
                </button>
            </div>
        </div>
        <div class="academic-table-wrap">
            <table class="academic-table">
                <thead>
                    <tr>
                        <th class="col-hash">#</th>
                        <th>Workshop Name</th>
                        <th style="width: 200px;">Time Duration</th>
                        <th style="width: 160px;">Status</th>
                        <th style="width: 70px; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($workshops as $index => $w)
                    <tr>
                        <td class="col-hash">{{ $index + 1 }}</td>
                        <td>
                            <strong style="color: #0f172a;">{{ $w->workshop_name }}</strong>
                        </td>
                        <td>
                            <i class="fa-regular fa-clock" style="color: #64748b; margin-right: 6px;"></i>
                            {{ $w->time_duration ?: 'N/A' }}
                        </td>
                        <td>
                            @php
                                $wStatus = strtolower(trim((string)$w->status));
                                $badgeClass = match(true) {
                                    str_contains($wStatus, 'completed') || str_contains($wStatus, 'passed') => 'badge-completed',
                                    str_contains($wStatus, 'progress') || str_contains($wStatus, 'submitted') => 'badge-in-progress',
                                    str_contains($wStatus, 'pending') => 'badge-pending',
                                    default => 'badge-completed',
                                };
                            @endphp
                            <span class="academic-status-badge {{ $badgeClass }}">
                                <i class="fa-solid fa-circle-check"></i> {{ $w->status ?: 'Completed' }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <form method="POST" action="{{ route('academic.workshop.delete', ['id' => $trainee->id, 'workshopId' => $w->id]) }}" onsubmit="return confirm('Delete this workshop?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none;border:none;color:#ef4444;cursor:pointer;padding:4px;" title="Delete workshop">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="table-empty-row">
                            <i class="fa-regular fa-folder-open" style="font-size: 24px; display: block; margin-bottom: 8px;"></i>
                            No workshop records found for this trainee.
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
                <button type="button" class="btn-academic-back" style="font-size: 12px; padding: 4px 10px;" onclick="openAddSynopsisModal()">
                    <i class="fa-solid fa-plus"></i> Add Synopsis
                </button>
            </div>
        </div>
        <div class="academic-table-wrap">
            <table class="academic-table">
                <thead>
                    <tr>
                        <th class="col-hash">#</th>
                        <th>Synopsis Name</th>
                        <th style="width: 160px;">Status</th>
                        <th style="width: 160px;">Status Date</th>
                        <th style="width: 70px; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($synopses as $index => $s)
                    <tr>
                        <td class="col-hash">{{ $index + 1 }}</td>
                        <td>
                            <strong style="color: #0f172a;">{{ $s->synopsis_name }}</strong>
                            @if($s->remarks)
                                <div style="font-size: 12px; color: #64748b; margin-top: 4px;">
                                    <i class="fa-regular fa-comment-dots"></i> {{ $s->remarks }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @php
                                $sStatus = strtolower(trim((string)$s->status));
                                $sBadgeClass = match(true) {
                                    str_contains($sStatus, 'approved') || str_contains($sStatus, 'accepted') => 'badge-approved',
                                    str_contains($sStatus, 'review') || str_contains($sStatus, 'submitted') => 'badge-under-review',
                                    str_contains($sStatus, 'rejected') || str_contains($sStatus, 'disapproved') => 'badge-rejected',
                                    default => 'badge-approved',
                                };
                            @endphp
                            <span class="academic-status-badge {{ $sBadgeClass }}">
                                <i class="fa-solid fa-stamp"></i> {{ $s->status ?: 'Approved' }}
                            </span>
                        </td>
                        <td>
                            <i class="fa-regular fa-calendar-days" style="color: #64748b; margin-right: 6px;"></i>
                            {{ $s->status_date ? $s->status_date->format('d/m/Y') : ($s->created_at ? $s->created_at->format('d/m/Y') : '—') }}
                        </td>
                        <td style="text-align: center;">
                            <form method="POST" action="{{ route('academic.synopsis.delete', ['id' => $trainee->id, 'synopsisId' => $s->id]) }}" onsubmit="return confirm('Delete this synopsis?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none;border:none;color:#ef4444;cursor:pointer;padding:4px;" title="Delete synopsis">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="table-empty-row">
                            <i class="fa-regular fa-folder-open" style="font-size: 24px; display: block; margin-bottom: 8px;"></i>
                            No synopsis records found for this trainee.
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
                <button type="button" class="btn-academic-back" style="font-size: 12px; padding: 4px 10px;" onclick="openAddDissertationModal()">
                    <i class="fa-solid fa-plus"></i> Add Dissertation
                </button>
            </div>
        </div>
        <div class="academic-table-wrap">
            <table class="academic-table">
                <thead>
                    <tr>
                        <th class="col-hash">#</th>
                        <th style="width: 220px;">Application Date</th>
                        <th style="width: 160px;">Status</th>
                        <th style="width: 160px;">Status Date</th>
                        <th style="width: 70px; text-align: center;">Action</th>
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
                        </td>
                        <td>
                            @php
                                $dStatus = strtolower(trim((string)$d->status));
                                $dBadgeClass = match(true) {
                                    str_contains($dStatus, 'approved') || str_contains($dStatus, 'accepted') => 'badge-approved',
                                    str_contains($dStatus, 'submitted') || str_contains($dStatus, 'evaluation') => 'badge-in-progress',
                                    str_contains($dStatus, 'rejected') => 'badge-rejected',
                                    default => 'badge-in-progress',
                                };
                            @endphp
                            <span class="academic-status-badge {{ $dBadgeClass }}">
                                <i class="fa-solid fa-file-circle-check"></i> {{ $d->status ?: 'Submitted' }}
                            </span>
                        </td>
                        <td>
                            <i class="fa-regular fa-calendar-days" style="color: #64748b; margin-right: 6px;"></i>
                            {{ $d->status_date ? $d->status_date->format('d/m/Y') : '—' }}
                        </td>
                        <td style="text-align: center;">
                            <form method="POST" action="{{ route('academic.dissertation.delete', ['id' => $trainee->id, 'dissId' => $d->id]) }}" onsubmit="return confirm('Delete this dissertation?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none;border:none;color:#ef4444;cursor:pointer;padding:4px;" title="Delete dissertation">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="table-empty-row">
                            <i class="fa-regular fa-folder-open" style="font-size: 24px; display: block; margin-bottom: 8px;"></i>
                            No dissertation records found for this trainee.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Workshop Modal --}}
<div class="arm-modal-backdrop" id="modalAddWorkshop" tabindex="-1">
    <div class="arm-modal-dialog" style="max-width: 520px;">
        <div class="arm-modal-header">
            <h3 class="arm-modal-title"><i class="fa-solid fa-chalkboard-user"></i> Add Workshop</h3>
            <button type="button" class="arm-modal-close" onclick="closeModal('modalAddWorkshop')">&times;</button>
        </div>
        <form method="POST" action="{{ route('academic.workshop.store', ['id' => $trainee->id]) }}">
            @csrf
            <div class="arm-modal-body">
                <div style="margin-bottom: 14px;">
                    <label style="display:block;font-weight:600;font-size:13px;margin-bottom:4px;">Workshop Name *</label>
                    <input type="text" name="workshop_name" required style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:6px;" placeholder="e.g. Basic Surgical Skills (BSS)">
                </div>
                <div style="margin-bottom: 14px;">
                    <label style="display:block;font-weight:600;font-size:13px;margin-bottom:4px;">Time Duration *</label>
                    <input type="text" name="time_duration" required style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:6px;" placeholder="e.g. 3 Days, 4 Days">
                </div>
                <div style="margin-bottom: 14px;">
                    <label style="display:block;font-weight:600;font-size:13px;margin-bottom:4px;">Status *</label>
                    <select name="status" required style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:6px;">
                        <option value="Completed">Completed</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Registered">Registered</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-weight:600;font-size:13px;margin-bottom:4px;">Status Date</label>
                    <input type="date" name="status_date" value="{{ date('Y-m-d') }}" style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:6px;">
                </div>
            </div>
            <div class="arm-modal-footer">
                <button type="button" class="btn-arm-secondary" onclick="closeModal('modalAddWorkshop')">Cancel</button>
                <button type="submit" class="btn-arm-primary">Save Workshop</button>
            </div>
        </form>
    </div>
</div>

{{-- Add Synopsis Modal --}}
<div class="arm-modal-backdrop" id="modalAddSynopsis" tabindex="-1">
    <div class="arm-modal-dialog" style="max-width: 520px;">
        <div class="arm-modal-header">
            <h3 class="arm-modal-title"><i class="fa-solid fa-file-waveform"></i> Add Synopsis</h3>
            <button type="button" class="arm-modal-close" onclick="closeModal('modalAddSynopsis')">&times;</button>
        </div>
        <form method="POST" action="{{ route('academic.synopsis.store', ['id' => $trainee->id]) }}">
            @csrf
            <div class="arm-modal-body">
                <div style="margin-bottom: 14px;">
                    <label style="display:block;font-weight:600;font-size:13px;margin-bottom:4px;">Synopsis Name / Title *</label>
                    <textarea name="synopsis_name" rows="3" required style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:6px;" placeholder="Full synopsis title..."></textarea>
                </div>
                <div style="margin-bottom: 14px;">
                    <label style="display:block;font-weight:600;font-size:13px;margin-bottom:4px;">Status *</label>
                    <select name="status" required style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:6px;">
                        <option value="Approved">Approved</option>
                        <option value="Under Review">Under Review</option>
                        <option value="Submitted">Submitted to RTMC</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
                <div style="margin-bottom: 14px;">
                    <label style="display:block;font-weight:600;font-size:13px;margin-bottom:4px;">Status Date</label>
                    <input type="date" name="status_date" value="{{ date('Y-m-d') }}" style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:6px;">
                </div>
                <div>
                    <label style="display:block;font-weight:600;font-size:13px;margin-bottom:4px;">Remarks (Optional)</label>
                    <input type="text" name="remarks" style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:6px;" placeholder="Optional remarks...">
                </div>
            </div>
            <div class="arm-modal-footer">
                <button type="button" class="btn-arm-secondary" onclick="closeModal('modalAddSynopsis')">Cancel</button>
                <button type="submit" class="btn-arm-primary">Save Synopsis</button>
            </div>
        </form>
    </div>
</div>

{{-- Add Dissertation Modal --}}
<div class="arm-modal-backdrop" id="modalAddDissertation" tabindex="-1">
    <div class="arm-modal-dialog" style="max-width: 520px;">
        <div class="arm-modal-header">
            <h3 class="arm-modal-title"><i class="fa-solid fa-book-open-reader"></i> Add Dissertation</h3>
            <button type="button" class="arm-modal-close" onclick="closeModal('modalAddDissertation')">&times;</button>
        </div>
        <form method="POST" action="{{ route('academic.dissertation.store', ['id' => $trainee->id]) }}">
            @csrf
            <div class="arm-modal-body">
                <div style="margin-bottom: 14px;">
                    <label style="display:block;font-weight:600;font-size:13px;margin-bottom:4px;">Application Date *</label>
                    <input type="date" name="application_date" required value="{{ date('Y-m-d') }}" style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:6px;">
                </div>
                <div style="margin-bottom: 14px;">
                    <label style="display:block;font-weight:600;font-size:13px;margin-bottom:4px;">Dissertation Title</label>
                    <textarea name="dissertation_title" rows="2" style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:6px;" placeholder="Title of dissertation manuscript..."></textarea>
                </div>
                <div style="margin-bottom: 14px;">
                    <label style="display:block;font-weight:600;font-size:13px;margin-bottom:4px;">Status *</label>
                    <select name="status" required style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:6px;">
                        <option value="Submitted">Submitted</option>
                        <option value="Under Evaluation">Under Evaluation</option>
                        <option value="Approved">Approved / Accepted</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-weight:600;font-size:13px;margin-bottom:4px;">Status Date</label>
                    <input type="date" name="status_date" value="{{ date('Y-m-d') }}" style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:6px;">
                </div>
            </div>
            <div class="arm-modal-footer">
                <button type="button" class="btn-arm-secondary" onclick="closeModal('modalAddDissertation')">Cancel</button>
                <button type="submit" class="btn-arm-primary">Save Dissertation</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openAddWorkshopModal() {
    var m = document.getElementById('modalAddWorkshop');
    if (m) m.classList.add('is-open');
}
function openAddSynopsisModal() {
    var m = document.getElementById('modalAddSynopsis');
    if (m) m.classList.add('is-open');
}
function openAddDissertationModal() {
    var m = document.getElementById('modalAddDissertation');
    if (m) m.classList.add('is-open');
}
function closeModal(id) {
    var m = document.getElementById(id);
    if (m) m.classList.remove('is-open');
}
</script>
@endpush
@endsection
