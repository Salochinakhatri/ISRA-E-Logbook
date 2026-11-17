@extends('layouts.app')

@section('title', 'Workshops, Synopsis & Dissertation | ' . ($tenant->name ?? 'e-Logbook'))
@section('nav_academic_progression', 'is-active')

@push('styles')
<style>
.trainee-academic-page {
    padding: 16px 14px 48px;
}

.ac-hero-card {
    background: linear-gradient(135deg, #0b6040 0%, #0d7a52 100%);
    border-radius: 10px;
    padding: 22px 26px;
    color: #ffffff;
    margin-bottom: 24px;
    box-shadow: 0 4px 14px rgba(11, 96, 64, 0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.ac-section-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    margin-bottom: 24px;
    overflow: hidden;
}

.ac-section-header {
    background: #f8fafc;
    border-bottom: 1.5px solid #e2e8f0;
    padding: 14px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.ac-section-title {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.ac-section-title i {
    color: #0b6040;
}

.ac-btn-add {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background-color: #0b6040;
    color: #ffffff;
    border: none;
    padding: 7px 14px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.2s;
}

.ac-btn-add:hover {
    background-color: #084d33;
    color: #ffffff;
}

.ac-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
    margin: 0;
}

.ac-table th {
    background: #f1f5f9;
    color: #334155;
    font-weight: 700;
    font-size: 12.5px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    padding: 12px 16px;
    border-bottom: 2px solid #cbd5e1;
    text-align: left;
}

.ac-table td {
    padding: 12px 16px;
    border-bottom: 1px solid #e2e8f0;
    color: #1e293b;
    vertical-align: middle;
}

.ac-table tbody tr:hover {
    background: #f8fafc;
}

.ac-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px;
    border-radius: 14px;
    font-size: 12px;
    font-weight: 600;
}

.ac-badge-completed, .ac-badge-approved {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.ac-badge-progress, .ac-badge-submitted {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.ac-badge-discuss {
    background: #fff7ed;
    color: #c2410c;
    border: 1px solid #fed7aa;
}

.ac-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(3px);
    z-index: 1050;
    justify-content: center;
    align-items: center;
    padding: 16px;
}

.ac-modal-overlay.is-active {
    display: flex;
}

.ac-modal-box {
    background: #ffffff;
    width: 100%;
    max-width: 560px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 20px 35px rgba(0,0,0,0.2);
}

.ac-modal-box-header {
    background: #0b6040;
    color: #ffffff;
    padding: 14px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.ac-modal-box-body {
    padding: 20px;
}

.ac-form-group {
    margin-bottom: 16px;
}

.ac-form-group label {
    display: block;
    font-weight: 600;
    font-size: 13px;
    color: #334155;
    margin-bottom: 6px;
}

.ac-form-control {
    width: 100%;
    padding: 9px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 13.5px;
}

.ac-form-control:focus {
    outline: none;
    border-color: #0b6040;
    box-shadow: 0 0 0 3px rgba(11,96,64,0.15);
}
</style>
@endpush

@section('content')
<div class="trainee-academic-page">
    {{-- Hero Card --}}
    <div class="ac-hero-card">
        <div>
            <h1 style="font-size: 22px; font-weight: 700; margin: 0 0 6px 0;">Workshops, Synopsis & Dissertation</h1>
            <p style="margin: 0; font-size: 13.5px; opacity: 0.9;">Manage your mandatory CPSP academic milestones and progression details.</p>
        </div>
        <div>
            <span style="background: rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 20px; font-weight: 600; font-size: 13px;">
                <i class="fa-solid fa-id-badge"></i> {{ $user->username }}
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

    {{-- 1. Workshops Details --}}
    <div class="ac-section-card">
        <div class="ac-section-header">
            <h2 class="ac-section-title">
                <i class="fa-solid fa-chalkboard-user"></i> Workshop Details
            </h2>
            <button type="button" class="ac-btn-add" onclick="openAddWorkshopModal()">
                <i class="fa-solid fa-plus"></i> Add Workshop
            </button>
        </div>
        <div style="overflow-x: auto;">
            <table class="ac-table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">#</th>
                        <th>Workshop Name</th>
                        <th style="width: 180px;">Time Duration</th>
                        <th style="width: 180px;">Status</th>
                        <th style="width: 140px; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($workshops as $index => $w)
                    <tr>
                        <td style="text-align: center; font-weight: 700; color: #64748b;">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $w->workshop_name }}</strong>
                            @if(!empty($w->remarks))
                                @php
                                    $isWApp = str_contains(strtolower((string)$w->status), 'approved');
                                @endphp
                                <div style="margin-top: 5px; padding: 6px 12px; background: {{ $isWApp ? '#f0fdf4' : '#fffbeb' }}; border: 1px solid {{ $isWApp ? '#bbf7d0' : '#fde68a' }}; border-left: 3px solid {{ $isWApp ? '#16a34a' : '#d97706' }}; border-radius: 5px; font-size: 12px; color: {{ $isWApp ? '#166534' : '#78350f' }};">
                                    <strong style="color: {{ $isWApp ? '#15803d' : '#b45309' }};"><i class="fa-solid fa-comment-dots"></i> Supervisor Remarks:</strong> {{ $w->remarks }}
                                </div>
                            @endif
                        </td>
                        <td><i class="fa-regular fa-clock" style="color: #64748b; margin-right: 4px;"></i> {{ $w->time_duration }}</td>
                        <td>
                            @php
                                $wSt = strtolower((string)$w->status);
                                $wBadge = (str_contains($wSt, 'approved') || str_contains($wSt, 'completed')) ? 'ac-badge-completed' : (str_contains($wSt, 'discuss') ? 'ac-badge-discuss' : 'ac-badge-progress');
                                $wIcon = (str_contains($wSt, 'approved') || str_contains($wSt, 'completed')) ? 'fa-circle-check' : (str_contains($wSt, 'discuss') ? 'fa-comments' : 'fa-clock');
                            @endphp
                            <span class="ac-status-badge {{ $wBadge }}">
                                <i class="fa-solid {{ $wIcon }}"></i> {{ $w->status ?: 'Registered' }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            @if(str_contains($wSt, 'discuss') || str_contains($wSt, 'resubmit'))
                                <button type="button" class="ac-btn-add" style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a; padding: 4px 10px; font-size: 12px;"
                                        onclick="openEditWorkshopModal({{ $w->id }}, @js($w->workshop_name), @js($w->time_duration), @js($w->status), @js($w->status_date ? $w->status_date->format('Y-m-d') : ''))">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit &amp; Resubmit
                                </button>
                            @elseif(str_contains($wSt, 'approved'))
                                <span style="font-size: 12px; color: #166534; font-weight: 600;"><i class="fa-solid fa-lock"></i> Approved</span>
                            @else
                                <span style="font-size: 12px; color: #64748b; font-weight: 600;" title="Editable only when supervisor sets to Discuss and Resubmit"><i class="fa-solid fa-clock"></i> Submitted</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 24px; color: #64748b; font-style: italic;">
                            No workshop records added yet. Click "+ Add Workshop" above to add your workshops.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 2. Synopsis Details --}}
    <div class="ac-section-card">
        <div class="ac-section-header">
            <h2 class="ac-section-title">
                <i class="fa-solid fa-file-waveform"></i> Synopsis Details
            </h2>
            <button type="button" class="ac-btn-add" onclick="openAddSynopsisModal()">
                <i class="fa-solid fa-plus"></i> Submit Synopsis
            </button>
        </div>
        <div style="overflow-x: auto;">
            <table class="ac-table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">#</th>
                        <th>Synopsis Name</th>
                        <th style="width: 180px;">Status</th>
                        <th style="width: 150px;">Status Date</th>
                        <th style="width: 140px; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($synopses as $index => $s)
                    <tr>
                        <td style="text-align: center; font-weight: 700; color: #64748b;">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $s->synopsis_name }}</strong>
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
                                $sSt = strtolower((string)$s->status);
                                $sBadge = str_contains($sSt, 'approved') ? 'ac-badge-completed' : (str_contains($sSt, 'discuss') ? 'ac-badge-discuss' : 'ac-badge-progress');
                                $sIcon = str_contains($sSt, 'approved') ? 'fa-circle-check' : (str_contains($sSt, 'discuss') ? 'fa-comments' : 'fa-clock');
                            @endphp
                            <span class="ac-status-badge {{ $sBadge }}">
                                <i class="fa-solid {{ $sIcon }}"></i> {{ $s->status ?: 'Submitted to RTMC' }}
                            </span>
                        </td>
                        <td>
                            <i class="fa-regular fa-calendar-days" style="color: #64748b; margin-right: 4px;"></i>
                            {{ $s->status_date ? $s->status_date->format('d/m/Y') : ($s->created_at ? $s->created_at->format('d/m/Y') : '—') }}
                        </td>
                        <td style="text-align: center;">
                            @if(str_contains($sSt, 'discuss') || str_contains($sSt, 'resubmit'))
                                <button type="button" class="ac-btn-add" style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a; padding: 4px 10px; font-size: 12px;"
                                        onclick="openEditSynopsisModal({{ $s->id }}, @js($s->synopsis_name), @js($s->status), @js($s->status_date ? $s->status_date->format('Y-m-d') : ''))">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit &amp; Resubmit
                                </button>
                            @elseif(str_contains($sSt, 'approved'))
                                <span style="font-size: 12px; color: #166534; font-weight: 600;"><i class="fa-solid fa-lock"></i> Approved</span>
                            @else
                                <span style="font-size: 12px; color: #64748b; font-weight: 600;" title="Editable only when supervisor sets to Discuss and Resubmit"><i class="fa-solid fa-clock"></i> Submitted</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 24px; color: #64748b; font-style: italic;">
                            No synopsis records submitted yet. Click "+ Submit Synopsis" to enter your topic.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 3. Dissertations Details --}}
    <div class="ac-section-card">
        <div class="ac-section-header">
            <h2 class="ac-section-title">
                <i class="fa-solid fa-book-open-reader"></i> Dissertations Details
            </h2>
            <button type="button" class="ac-btn-add" onclick="openAddDissertationModal()">
                <i class="fa-solid fa-plus"></i> Submit Dissertation
            </button>
        </div>
        <div style="overflow-x: auto;">
            <table class="ac-table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">#</th>
                        <th>Application Date &amp; Title</th>
                        <th style="width: 180px;">Status</th>
                        <th style="width: 150px;">Status Date</th>
                        <th style="width: 140px; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dissertations as $index => $d)
                    <tr>
                        <td style="text-align: center; font-weight: 700; color: #64748b;">{{ $index + 1 }}</td>
                        <td>
                            <div>
                                <i class="fa-regular fa-calendar-check" style="color: #64748b; margin-right: 4px;"></i>
                                <strong>{{ $d->application_date ? $d->application_date->format('d/m/Y') : ($d->created_at ? $d->created_at->format('d/m/Y') : '—') }}</strong>
                            </div>
                            @if($d->dissertation_title)
                                <div style="font-size: 12px; color: #64748b; margin-top: 2px;">{{ $d->dissertation_title }}</div>
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
                                $dSt = strtolower((string)$d->status);
                                $dBadge = str_contains($dSt, 'approved') ? 'ac-badge-completed' : (str_contains($dSt, 'discuss') ? 'ac-badge-discuss' : 'ac-badge-progress');
                                $dIcon = str_contains($dSt, 'approved') ? 'fa-circle-check' : (str_contains($dSt, 'discuss') ? 'fa-comments' : 'fa-clock');
                            @endphp
                            <span class="ac-status-badge {{ $dBadge }}">
                                <i class="fa-solid {{ $dIcon }}"></i> {{ $d->status ?: 'Submitted' }}
                            </span>
                        </td>
                        <td>
                            <i class="fa-regular fa-calendar-days" style="color: #64748b; margin-right: 4px;"></i>
                            {{ $d->status_date ? $d->status_date->format('d/m/Y') : '—' }}
                        </td>
                        <td style="text-align: center;">
                            @if(str_contains($dSt, 'discuss') || str_contains($dSt, 'resubmit'))
                                <button type="button" class="ac-btn-add" style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a; padding: 4px 10px; font-size: 12px;"
                                        onclick="openEditDissertationModal({{ $d->id }}, @js($d->application_date ? $d->application_date->format('Y-m-d') : date('Y-m-d')), @js($d->dissertation_title ?? ''), @js($d->status), @js($d->status_date ? $d->status_date->format('Y-m-d') : ''))">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit &amp; Resubmit
                                </button>
                            @elseif(str_contains($dSt, 'approved'))
                                <span style="font-size: 12px; color: #166534; font-weight: 600;"><i class="fa-solid fa-lock"></i> Approved</span>
                            @else
                                <span style="font-size: 12px; color: #64748b; font-weight: 600;" title="Editable only when supervisor sets to Discuss and Resubmit"><i class="fa-solid fa-clock"></i> Submitted</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 24px; color: #64748b; font-style: italic;">
                            No dissertation application recorded yet. Click "+ Submit Dissertation" above.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add / Edit Workshop Modal --}}
<div class="ac-modal-overlay" id="modalAddWorkshop">
    <div class="ac-modal-box">
        <div class="ac-modal-box-header">
            <h3 id="modalWorkshopHeading" style="margin: 0; font-size: 16px; font-weight: 700;"><i class="fa-solid fa-chalkboard-user"></i> Add Workshop</h3>
            <button type="button" style="background:none;border:none;color:#fff;font-size:18px;cursor:pointer;" onclick="closeModal('modalAddWorkshop')">&times;</button>
        </div>
        <form method="POST" action="{{ route('academic.workshop.store', ['id' => $user->id]) }}">
            @csrf
            <input type="hidden" name="id" id="workshopFormId" value="">
            <div class="ac-modal-box-body">
                <div class="ac-form-group">
                    <label>Workshop Name *</label>
                    <input type="text" name="workshop_name" id="workshopFormName" class="ac-form-control" required placeholder="e.g. Basic Surgical Skills (BSS)">
                </div>
                <div class="ac-form-group">
                    <label>Time Duration *</label>
                    <input type="text" name="time_duration" id="workshopFormDuration" class="ac-form-control" required placeholder="e.g. 3 Days, 4 Days, 2 Weeks">
                </div>
                <div class="ac-form-group">
                    <label>Status *</label>
                    <select name="status" id="workshopFormStatus" class="ac-form-control" required>
                        <option value="Completed">Completed</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Registered">Registered</option>
                    </select>
                </div>
                <div class="ac-form-group">
                    <label>Status Date</label>
                    <input type="date" name="status_date" id="workshopFormDate" class="ac-form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 18px;">
                    <button type="button" class="ac-form-control" style="width: auto; background: #fff; cursor: pointer;" onclick="closeModal('modalAddWorkshop')">Cancel</button>
                    <button type="submit" class="ac-btn-add" style="border: none;" id="workshopSubmitBtn">Save Workshop</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Add / Edit Synopsis Modal --}}
<div class="ac-modal-overlay" id="modalAddSynopsis">
    <div class="ac-modal-box">
        <div class="ac-modal-box-header">
            <h3 id="modalSynopsisHeading" style="margin: 0; font-size: 16px; font-weight: 700;"><i class="fa-solid fa-file-waveform"></i> Submit Synopsis</h3>
            <button type="button" style="background:none;border:none;color:#fff;font-size:18px;cursor:pointer;" onclick="closeModal('modalAddSynopsis')">&times;</button>
        </div>
        <form method="POST" action="{{ route('academic.synopsis.store', ['id' => $user->id]) }}">
            @csrf
            <input type="hidden" name="id" id="synopsisFormId" value="">
            <div class="ac-modal-box-body">
                <div class="ac-form-group">
                    <label>Synopsis Title / Name *</label>
                    <textarea name="synopsis_name" id="synopsisFormName" class="ac-form-control" rows="3" required placeholder="Enter full approved or submitted synopsis title..."></textarea>
                </div>
                <div class="ac-form-group">
                    <label>Status *</label>
                    <select name="status" id="synopsisFormStatus" class="ac-form-control" required>
                        <option value="Submitted to RTMC">Submitted to RTMC</option>
                        <option value="Under Review">Under Review</option>
                        <option value="Approved">Approved</option>
                    </select>
                </div>
                <div class="ac-form-group">
                    <label>Status Date</label>
                    <input type="date" name="status_date" id="synopsisFormDate" class="ac-form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 18px;">
                    <button type="button" class="ac-form-control" style="width: auto; background: #fff; cursor: pointer;" onclick="closeModal('modalAddSynopsis')">Cancel</button>
                    <button type="submit" class="ac-btn-add" style="border: none;" id="synopsisSubmitBtn">Save Synopsis</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Add / Edit Dissertation Modal --}}
<div class="ac-modal-overlay" id="modalAddDissertation">
    <div class="ac-modal-box">
        <div class="ac-modal-box-header">
            <h3 id="modalDissertationHeading" style="margin: 0; font-size: 16px; font-weight: 700;"><i class="fa-solid fa-book-open-reader"></i> Submit Dissertation</h3>
            <button type="button" style="background:none;border:none;color:#fff;font-size:18px;cursor:pointer;" onclick="closeModal('modalAddDissertation')">&times;</button>
        </div>
        <form method="POST" action="{{ route('academic.dissertation.store', ['id' => $user->id]) }}">
            @csrf
            <input type="hidden" name="id" id="dissertationFormId" value="">
            <div class="ac-modal-box-body">
                <div class="ac-form-group">
                    <label>Application Date *</label>
                    <input type="date" name="application_date" id="dissertationFormDate" class="ac-form-control" required value="{{ date('Y-m-d') }}">
                </div>
                <div class="ac-form-group">
                    <label>Dissertation Title</label>
                    <textarea name="dissertation_title" id="dissertationFormTitle" class="ac-form-control" rows="2" placeholder="Title of dissertation manuscript..."></textarea>
                </div>
                <div class="ac-form-group">
                    <label>Status *</label>
                    <select name="status" id="dissertationFormStatus" class="ac-form-control" required>
                        <option value="Submitted">Submitted</option>
                        <option value="Under Evaluation">Under Evaluation</option>
                        <option value="Approved / Accepted">Approved / Accepted</option>
                    </select>
                </div>
                <div class="ac-form-group">
                    <label>Status Date</label>
                    <input type="date" name="status_date" id="dissertationFormStatusDate" class="ac-form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 18px;">
                    <button type="button" class="ac-form-control" style="width: auto; background: #fff; cursor: pointer;" onclick="closeModal('modalAddDissertation')">Cancel</button>
                    <button type="submit" class="ac-btn-add" style="border: none;" id="dissertationSubmitBtn">Save Dissertation</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openAddWorkshopModal() {
    document.getElementById('workshopFormId').value = '';
    document.getElementById('workshopFormName').value = '';
    document.getElementById('workshopFormDuration').value = '';
    document.getElementById('workshopFormStatus').value = 'Completed';
    document.getElementById('workshopFormDate').value = '{{ date('Y-m-d') }}';
    document.getElementById('modalWorkshopHeading').innerHTML = '<i class="fa-solid fa-chalkboard-user"></i> Add Workshop';
    document.getElementById('workshopSubmitBtn').textContent = 'Save Workshop';
    document.getElementById('modalAddWorkshop').classList.add('is-active');
}
function openEditWorkshopModal(id, name, duration, status, date) {
    document.getElementById('workshopFormId').value = id;
    document.getElementById('workshopFormName').value = name || '';
    document.getElementById('workshopFormDuration').value = duration || '';
    var statusSelect = document.getElementById('workshopFormStatus');
    var isDiscuss = (status || '').toLowerCase().indexOf('discuss') !== -1;
    if (status) {
        var optExists = Array.from(statusSelect.options).some(function(o) { return o.value === status; });
        statusSelect.value = optExists ? status : 'Completed';
    }
    if (date) {
        document.getElementById('workshopFormDate').value = date;
    }
    document.getElementById('modalWorkshopHeading').innerHTML = isDiscuss
        ? '<i class="fa-solid fa-pen-to-square"></i> Edit & Resubmit Workshop'
        : '<i class="fa-solid fa-pen-to-square"></i> Edit Workshop';
    document.getElementById('workshopSubmitBtn').textContent = isDiscuss
        ? 'Update & Resubmit'
        : 'Update Workshop';
    document.getElementById('modalAddWorkshop').classList.add('is-active');
}
function openAddSynopsisModal() {
    document.getElementById('synopsisFormId').value = '';
    document.getElementById('synopsisFormName').value = '';
    document.getElementById('synopsisFormStatus').value = 'Submitted to RTMC';
    document.getElementById('synopsisFormDate').value = '{{ date('Y-m-d') }}';
    document.getElementById('modalSynopsisHeading').innerHTML = '<i class="fa-solid fa-file-waveform"></i> Submit Synopsis';
    document.getElementById('synopsisSubmitBtn').textContent = 'Save Synopsis';
    document.getElementById('modalAddSynopsis').classList.add('is-active');
}
function openEditSynopsisModal(id, name, status, date) {
    document.getElementById('synopsisFormId').value = id;
    document.getElementById('synopsisFormName').value = name || '';
    var statusSelect = document.getElementById('synopsisFormStatus');
    var isDiscuss = (status || '').toLowerCase().indexOf('discuss') !== -1;
    if (status) {
        var optExists = Array.from(statusSelect.options).some(function(o) { return o.value === status; });
        statusSelect.value = optExists ? status : 'Submitted to RTMC';
    }
    if (date) {
        document.getElementById('synopsisFormDate').value = date;
    }
    document.getElementById('modalSynopsisHeading').innerHTML = isDiscuss
        ? '<i class="fa-solid fa-pen-to-square"></i> Edit & Resubmit Synopsis'
        : '<i class="fa-solid fa-pen-to-square"></i> Edit Synopsis';
    document.getElementById('synopsisSubmitBtn').textContent = isDiscuss
        ? 'Update & Resubmit'
        : 'Update Synopsis';
    document.getElementById('modalAddSynopsis').classList.add('is-active');
}
function openAddDissertationModal() {
    document.getElementById('dissertationFormId').value = '';
    document.getElementById('dissertationFormDate').value = '{{ date('Y-m-d') }}';
    document.getElementById('dissertationFormTitle').value = '';
    document.getElementById('dissertationFormStatus').value = 'Submitted';
    document.getElementById('dissertationFormStatusDate').value = '{{ date('Y-m-d') }}';
    document.getElementById('modalDissertationHeading').innerHTML = '<i class="fa-solid fa-book-open-reader"></i> Submit Dissertation';
    document.getElementById('dissertationSubmitBtn').textContent = 'Save Dissertation';
    document.getElementById('modalAddDissertation').classList.add('is-active');
}
function openEditDissertationModal(id, appDate, title, status, date) {
    document.getElementById('dissertationFormId').value = id;
    document.getElementById('dissertationFormDate').value = appDate || '{{ date('Y-m-d') }}';
    document.getElementById('dissertationFormTitle').value = title || '';
    var statusSelect = document.getElementById('dissertationFormStatus');
    var isDiscuss = (status || '').toLowerCase().indexOf('discuss') !== -1;
    if (status) {
        var optExists = Array.from(statusSelect.options).some(function(o) { return o.value === status; });
        statusSelect.value = optExists ? status : 'Submitted';
    }
    if (date) {
        document.getElementById('dissertationFormStatusDate').value = date;
    }
    document.getElementById('modalDissertationHeading').innerHTML = isDiscuss
        ? '<i class="fa-solid fa-pen-to-square"></i> Edit & Resubmit Dissertation'
        : '<i class="fa-solid fa-pen-to-square"></i> Edit Dissertation';
    document.getElementById('dissertationSubmitBtn').textContent = isDiscuss
        ? 'Update & Resubmit'
        : 'Update Dissertation';
    document.getElementById('modalAddDissertation').classList.add('is-active');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('is-active');
}
</script>
@endpush
@endsection
