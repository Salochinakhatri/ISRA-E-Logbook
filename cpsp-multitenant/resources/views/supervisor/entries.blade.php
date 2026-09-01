@extends('layouts.app')

@section('title', 'Trainee Entries Review & Approval | CPSP e-Logbook')
@section('nav_supervisor_entries', 'is-active')

@section('content')
<div class="supervisor-entries-page">
    {{-- Breadcrumbs --}}
    <nav class="supervisor-dash__breadcrumb" aria-label="breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item separator">/</li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">CPSP e-Logbook</a></li>
            <li class="breadcrumb-item separator">/</li>
            <li class="breadcrumb-item active" aria-current="page">Review & Approvals</li>
        </ol>
    </nav>

    <div class="sup-page-header">
        <div>
            <h1 class="supervisor-dash__title">Trainee Submitted Entries</h1>
            <p class="sup-page-sub">Review entries submitted by trainees for verification, discussion, or approval.</p>
        </div>
        <div class="sup-header-actions">
            <a href="{{ route('dashboard') }}" class="btn btn--outline" style="text-decoration: none;">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <div class="sup-filter-card">
        <form method="get" action="{{ route('supervisor.entries') }}" class="sup-filter-form">
            <div class="sup-filter-form__grid">
                <div>
                    <label class="control-label" for="filterTrainee">Trainee</label>
                    <select name="trainee_id" id="filterTrainee" class="field__control">
                        <option value="">All Trainees</option>
                        @foreach($trainees as $t)
                            <option value="{{ $t->id }}" {{ (string)$traineeId === (string)$t->id ? 'selected' : '' }}>
                                {{ $t->profile?->full_name ?: $t->username }} ({{ $t->username }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="control-label" for="filterType">Section / Category</label>
                    <select name="type" id="filterType" class="field__control">
                        <option value="all" {{ $typeFilter === 'all' ? 'selected' : '' }}>All Sections</option>
                        <option value="training" {{ $typeFilter === 'training' ? 'selected' : '' }}>Training</option>
                        <option value="rotational" {{ $typeFilter === 'rotational' ? 'selected' : '' }}>Rotational Training</option>
                        <option value="journal" {{ $typeFilter === 'journal' ? 'selected' : '' }}>Journal Club</option>
                        <option value="presented" {{ $typeFilter === 'presented' ? 'selected' : '' }}>Paper Presented</option>
                        <option value="published" {{ $typeFilter === 'published' ? 'selected' : '' }}>Paper Published</option>
                    </select>
                </div>

                <div>
                    <label class="control-label" for="filterSearch">Search (Diagnosis/Topic/Reg)</label>
                    <input type="text" name="search" id="filterSearch" class="field__control" placeholder="Search keywords..." value="{{ $search }}">
                </div>

                <div class="sup-filter-actions">
                    <button type="submit" class="btn btn--submit"><i class="fa-solid fa-filter"></i> Apply Filters</button>
                    <a href="{{ route('supervisor.entries') }}" class="btn btn--reset" title="Reset Filters"><i class="fa-solid fa-rotate-left"></i></a>
                </div>
            </div>
            <input type="hidden" name="status" id="hiddenStatusFilter" value="{{ $statusFilter }}">
        </form>
    </div>

    {{-- Status Filter Tabs --}}
    <div class="sup-status-tabs">
        <a href="{{ route('supervisor.entries', array_merge(request()->except('status'), ['status' => 'Awaiting Approval'])) }}" 
           class="sup-tab-link {{ $statusFilter === 'Awaiting Approval' ? 'is-active is-warn' : '' }}">
            <i class="fa-solid fa-clock"></i> Awaiting Approval
            <span class="tab-badge tab-badge--warn">{{ $statusCounts['awaiting'] }}</span>
        </a>

        <a href="{{ route('supervisor.entries', array_merge(request()->except('status'), ['status' => 'Approved'])) }}" 
           class="sup-tab-link {{ $statusFilter === 'Approved' ? 'is-active is-success' : '' }}">
            <i class="fa-solid fa-circle-check"></i> Approved
            <span class="tab-badge tab-badge--success">{{ $statusCounts['approved'] }}</span>
        </a>

        <a href="{{ route('supervisor.entries', array_merge(request()->except('status'), ['status' => 'Discuss and Resubmit'])) }}" 
           class="sup-tab-link {{ $statusFilter === 'Discuss and Resubmit' ? 'is-active is-info' : '' }}">
            <i class="fa-solid fa-comments"></i> Discuss & Resubmit
            <span class="tab-badge tab-badge--info">{{ $statusCounts['resubmit'] }}</span>
        </a>

        <a href="{{ route('supervisor.entries', array_merge(request()->except('status'), ['status' => 'Disapproved'])) }}" 
           class="sup-tab-link {{ $statusFilter === 'Disapproved' ? 'is-active is-danger' : '' }}">
            <i class="fa-solid fa-ban"></i> Disapproved
            <span class="tab-badge tab-badge--danger">{{ $statusCounts['disapproved'] }}</span>
        </a>

        <a href="{{ route('supervisor.entries', array_merge(request()->except('status'), ['status' => 'all'])) }}" 
           class="sup-tab-link {{ $statusFilter === 'all' ? 'is-active' : '' }}">
            <i class="fa-solid fa-layer-group"></i> All Submitted
            <span class="tab-badge tab-badge--neutral">{{ $statusCounts['all'] }}</span>
        </a>
    </div>

    {{-- Bulk Action Form Wrapping the Entries Table --}}
    <form action="{{ route('supervisor.entries.bulk') }}" method="post" id="bulkActionForm">
        @csrf
        
        {{-- Bulk Action Toolbar --}}
        <div class="sup-bulk-toolbar">
            <div class="sup-bulk-toolbar__left">
                <label class="sup-checkbox-label">
                    <input type="checkbox" id="selectAllCheckbox">
                    <span>Select All</span>
                </label>
                <span class="sup-selected-count" id="selectedCountText">0 selected</span>
            </div>
            <div class="sup-bulk-toolbar__right">
                <select name="bulk_status" id="bulkStatusSelect" class="field__control sup-bulk-select">
                    <option value="">Bulk Approval Options...</option>
                    <option value="Approved">Approve Selected</option>
                    <option value="Awaiting Approval">Mark as Pending (Awaiting Approval)</option>
                    <option value="Discuss and Resubmit">Discuss and Resubmit Selected</option>
                    <option value="Disapproved">Disapprove Selected</option>
                </select>
                <input type="text" name="bulk_remarks" id="bulkRemarksInput" class="field__control sup-bulk-remarks" placeholder="Optional supervisor remarks...">
                <button type="submit" class="btn btn--submit sup-bulk-apply-btn" id="bulkApplyBtn" disabled>
                    <i class="fa-solid fa-check"></i> Apply to Selected
                </button>
            </div>
        </div>

        {{-- Entries Table --}}
        <div class="sup-table-card">
            <div class="table-responsive">
                <table class="sup-table sup-table--entries">
                    <thead>
                        <tr>
                            <th style="width: 35px;"></th>
                            <th style="width: 40px;">#</th>
                            <th>Trainee</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Diagnosis / Subject</th>
                            <th>Key Details</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th style="text-align: right; min-width: 170px;">Approval Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entries as $idx => $entry)
                        <tr class="entry-row" data-entry-id="{{ $entry['type'] }}:{{ $entry['id'] }}">
                            <td>
                                <input type="checkbox" name="selected_entries[]" value="{{ $entry['type'] }}:{{ $entry['id'] }}" class="entry-checkbox">
                            </td>
                            <td>{{ $idx + 1 }}</td>
                            <td>
                                <div class="sup-trainee-name">{{ $entry['trainee_name'] }}</div>
                                <div class="sup-trainee-sub">ID: {{ $entry['trainee_username'] }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $entry['badge_class'] }}">{{ $entry['type_label'] }}</span>
                            </td>
                            <td>
                                <div class="sup-entry-date">{{ $entry['date_formatted'] }}</div>
                                <div class="sup-entry-sub">Submitted: {{ $entry['created_formatted'] }}</div>
                            </td>
                            <td>
                                <div class="sup-entry-title">
                                    <strong>{{ Str::limit($entry['title'], 60) }}</strong>
                                </div>
                                <div class="sup-entry-brief text-muted">
                                    {{ Str::limit(strip_tags($entry['brief_desc']), 70) }}
                                </div>
                            </td>
                            <td>
                                <div class="sup-entry-submeta">{{ $entry['sub_meta'] }}</div>
                                @if($entry['level_name'])
                                    <div class="sup-entry-sub">Level: {{ $entry['level_name'] }}</div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusBadgeClass = match($entry['entry_status']) {
                                        'Approved' => 'badge--ok',
                                        'Awaiting Approval' => 'badge--warn',
                                        'Discuss and Resubmit' => 'badge--info',
                                        'Disapproved' => 'badge--danger',
                                        default => 'badge--muted',
                                    };
                                @endphp
                                <span class="badge {{ $statusBadgeClass }}">{{ $entry['entry_status'] }}</span>
                                @if($entry['approved_at'])
                                    <div class="sup-entry-sub" style="margin-top: 3px;">
                                        {{ $entry['approved_at'] }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($entry['supervisor_remarks'])
                                    <div class="sup-entry-remarks" title="{{ $entry['supervisor_remarks'] }}">
                                        <i class="fa-solid fa-comment-dots"></i> {{ Str::limit($entry['supervisor_remarks'], 45) }}
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div class="sup-action-buttons">
                                    {{-- Quick View Details Modal Trigger --}}
                                    <button type="button" class="btn-icon-action btn-icon-action--view js-view-entry" 
                                            data-type="{{ $entry['type'] }}" 
                                            data-id="{{ $entry['id'] }}"
                                            data-entry='@json($entry)'
                                            title="View Full Entry Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    {{-- Quick Approve Button --}}
                                    @if($entry['entry_status'] !== 'Approved')
                                    <button type="button" class="btn-icon-action btn-icon-action--approve js-quick-status" 
                                            data-type="{{ $entry['type'] }}" 
                                            data-id="{{ $entry['id'] }}" 
                                            data-status="Approved"
                                            title="Approve Entry">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                    @endif

                                    {{-- Quick Pending Button --}}
                                    @if($entry['entry_status'] !== 'Awaiting Approval')
                                    <button type="button" class="btn-icon-action btn-icon-action--pending js-quick-status" 
                                            data-type="{{ $entry['type'] }}" 
                                            data-id="{{ $entry['id'] }}" 
                                            data-status="Awaiting Approval"
                                            title="Mark as Awaiting Approval (Pending)">
                                        <i class="fa-solid fa-clock"></i>
                                    </button>
                                    @endif

                                    {{-- Discuss & Resubmit Modal Trigger --}}
                                    <button type="button" class="btn-icon-action btn-icon-action--discuss js-open-status-modal" 
                                            data-type="{{ $entry['type'] }}" 
                                            data-id="{{ $entry['id'] }}" 
                                            data-status="Discuss and Resubmit"
                                            data-remarks="{{ $entry['supervisor_remarks'] }}"
                                            title="Discuss and Resubmit">
                                        <i class="fa-solid fa-comments"></i>
                                    </button>

                                    {{-- Disapprove Modal Trigger --}}
                                    <button type="button" class="btn-icon-action btn-icon-action--disapprove js-open-status-modal" 
                                            data-type="{{ $entry['type'] }}" 
                                            data-id="{{ $entry['id'] }}" 
                                            data-status="Disapproved"
                                            data-remarks="{{ $entry['supervisor_remarks'] }}"
                                            title="Disapprove Entry">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="sup-table__empty">
                                <i class="fa-solid fa-inbox" style="font-size: 2rem; display: block; margin-bottom: 8px; color: #adb5bd;"></i>
                                No entries found matching the selected filter criteria.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

{{-- Hidden Form for Quick Status Update --}}
<form id="quickStatusForm" method="post" action="" style="display: none;">
    @csrf
    <input type="hidden" name="status" id="quickStatusInput">
    <input type="hidden" name="supervisor_remarks" id="quickRemarksInput">
</form>

{{-- Status Change with Remarks Modal --}}
<div class="modal" id="statusRemarksModal" role="dialog" aria-modal="true" hidden>
    <div class="modal__backdrop" data-close-status-modal></div>
    <div class="modal__panel" style="max-width: 500px;">
        <h2 class="modal__title" id="statusModalTitle" style="color: #0b6040; margin-bottom: 12px;">Update Entry Status</h2>
        <p class="modal__text" id="statusModalDesc" style="font-size: 14px; margin-bottom: 15px; color: #555;">
            Please specify any feedback or remarks for the trainee:
        </p>
        
        <form id="modalStatusForm" method="post" action="">
            @csrf
            <input type="hidden" name="status" id="modalStatusField">
            
            <div style="margin-bottom: 15px;">
                <label for="modalRemarksField" class="control-label" style="font-size: 13px; font-weight: 600; margin-bottom: 6px; display: block;">
                    Supervisor Remarks / Instructions
                </label>
                <textarea name="supervisor_remarks" id="modalRemarksField" class="field__control" rows="4" placeholder="Enter notes or explanation for the trainee (optional)..." style="width: 100%; border: 1px solid #ced4da; border-radius: 4px; padding: 8px; font-family: inherit; font-size: 14px;"></textarea>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" class="btn btn-forgot" data-close-status-modal style="margin: 0;">Cancel</button>
                <button type="submit" class="btn btn-login" id="modalSubmitBtn" style="margin: 0; min-width: 120px;">Confirm</button>
            </div>
        </form>
    </div>
</div>

{{-- Detailed Inspection Modal --}}
<div class="modal" id="entryDetailModal" role="dialog" aria-modal="true" hidden>
    <div class="modal__backdrop" data-close-detail-modal></div>
    <div class="modal__panel" style="max-width: 750px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e9ecef; padding-bottom: 12px; margin-bottom: 15px;">
            <h2 class="modal__title" id="detailModalHeading" style="color: #0b6040; margin: 0; font-size: 1.25rem;">Entry Details</h2>
            <button type="button" data-close-detail-modal style="border: none; background: transparent; font-size: 20px; cursor: pointer; color: #6c757d;">&times;</button>
        </div>

        <div id="detailModalBody" style="font-size: 14px; color: #333; line-height: 1.6;">
            {{-- Injected dynamically by JavaScript --}}
        </div>

        <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center;">
            <div id="detailModalStatusBadge"></div>
            <div style="display: flex; gap: 8px;" id="detailModalActionButtons">
                {{-- Injected dynamically --}}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Select all checkboxes
    var selectAll = document.getElementById('selectAllCheckbox');
    var itemCheckboxes = document.querySelectorAll('.entry-checkbox');
    var selectedCountText = document.getElementById('selectedCountText');
    var bulkApplyBtn = document.getElementById('bulkApplyBtn');
    var bulkStatusSelect = document.getElementById('bulkStatusSelect');

    function updateSelectedState() {
        var count = 0;
        itemCheckboxes.forEach(function (cb) {
            if (cb.checked) count++;
        });
        selectedCountText.textContent = count + ' selected';
        bulkApplyBtn.disabled = (count === 0 || !bulkStatusSelect.value);
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            var checked = this.checked;
            itemCheckboxes.forEach(function (cb) {
                cb.checked = checked;
            });
            updateSelectedState();
        });
    }

    itemCheckboxes.forEach(function (cb) {
        cb.addEventListener('change', function () {
            updateSelectedState();
            if (!this.checked && selectAll) {
                selectAll.checked = false;
            }
        });
    });

    if (bulkStatusSelect) {
        bulkStatusSelect.addEventListener('change', updateSelectedState);
    }

    // Quick Status buttons
    var quickButtons = document.querySelectorAll('.js-quick-status');
    var quickForm = document.getElementById('quickStatusForm');
    var quickStatusInput = document.getElementById('quickStatusInput');
    quickButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var type = this.getAttribute('data-type');
            var id = this.getAttribute('data-id');
            var status = this.getAttribute('data-status');
            
            if (confirm("Are you sure you want to set this entry's status to '" + status + "'?")) {
                quickForm.action = "/supervisor/entries/" + type + "/" + id + "/status";
                quickStatusInput.value = status;
                quickForm.submit();
            }
        });
    });

    // Status modal with remarks
    var statusModal = document.getElementById('statusRemarksModal');
    var modalStatusForm = document.getElementById('modalStatusForm');
    var modalStatusField = document.getElementById('modalStatusField');
    var modalRemarksField = document.getElementById('modalRemarksField');
    var statusModalTitle = document.getElementById('statusModalTitle');
    var statusModalDesc = document.getElementById('statusModalDesc');
    var modalSubmitBtn = document.getElementById('modalSubmitBtn');

    var openModalButtons = document.querySelectorAll('.js-open-status-modal');
    openModalButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var type = this.getAttribute('data-type');
            var id = this.getAttribute('data-id');
            var status = this.getAttribute('data-status');
            var existingRemarks = this.getAttribute('data-remarks') || '';

            modalStatusForm.action = "/supervisor/entries/" + type + "/" + id + "/status";
            modalStatusField.value = status;
            modalRemarksField.value = existingRemarks;

            if (status === 'Discuss and Resubmit') {
                statusModalTitle.textContent = 'Discuss and Resubmit';
                statusModalDesc.textContent = 'Send entry back to the trainee for discussion or modification. Please provide feedback:';
                modalSubmitBtn.textContent = 'Send for Revision';
                modalSubmitBtn.style.backgroundColor = '#6f42c1';
            } else if (status === 'Disapproved') {
                statusModalTitle.textContent = 'Disapprove Entry';
                statusModalDesc.textContent = 'Mark this entry as disapproved. Please state the reason:';
                modalSubmitBtn.textContent = 'Confirm Disapproval';
                modalSubmitBtn.style.backgroundColor = '#dc3545';
            } else {
                statusModalTitle.textContent = 'Update Status: ' + status;
                statusModalDesc.textContent = 'Provide optional supervisor remarks:';
                modalSubmitBtn.textContent = 'Confirm Status';
                modalSubmitBtn.style.backgroundColor = '#0b6040';
            }

            statusModal.hidden = false;
        });
    });

    // Close status modal
    document.querySelectorAll('[data-close-status-modal]').forEach(function (el) {
        el.addEventListener('click', function () {
            statusModal.hidden = true;
        });
    });

    // Detailed Inspection Modal
    var detailModal = document.getElementById('entryDetailModal');
    var detailModalBody = document.getElementById('detailModalBody');
    var detailModalHeading = document.getElementById('detailModalHeading');
    var detailModalStatusBadge = document.getElementById('detailModalStatusBadge');
    var detailModalActionButtons = document.getElementById('detailModalActionButtons');

    document.querySelectorAll('.js-view-entry').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var data = JSON.parse(this.getAttribute('data-entry'));
            detailModalHeading.textContent = data.type_label + ' Entry Details (#' + data.id + ')';

            var html = '<div class="sup-detail-grid">' +
                '<div><strong>Trainee:</strong> ' + data.trainee_name + ' (' + data.trainee_username + ')</div>' +
                '<div><strong>Date:</strong> ' + data.date_formatted + '</div>' +
                '<div><strong>Submitted On:</strong> ' + data.created_formatted + '</div>' +
                '<div><strong>Supervisor Listed:</strong> ' + (data.under_sup_name || '—') + '</div>';

            if (data.type === 'training' || data.type === 'rotational') {
                html += '<div style="grid-column: 1 / -1; margin-top: 8px;"><strong>Diagnosis / Procedure:</strong> ' + data.title + '</div>';
                html += '<div><strong>Supervision Level:</strong> ' + (data.level_name || '—') + '</div>';
                html += '<div><strong>Outcome:</strong> ' + (data.outcome_name || '—') + '</div>';
                if (data.alt_procedure) {
                    html += '<div style="grid-column: 1 / -1;"><strong>Alternative Procedure:</strong> ' + data.alt_procedure + '</div>';
                }
            } else {
                html += '<div style="grid-column: 1 / -1; margin-top: 8px;"><strong>Subject / Topic:</strong> ' + data.title + '</div>';
                html += '<div style="grid-column: 1 / -1;"><strong>Details:</strong> ' + data.sub_meta + '</div>';
            }

            html += '<div style="grid-column: 1 / -1; margin-top: 12px;">' +
                    '<strong>Brief Description / Portfolio Notes:</strong>' +
                    '<div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 4px; padding: 10px; margin-top: 4px; white-space: pre-wrap;">' +
                        (data.brief_desc || 'No additional description entered.') +
                    '</div>' +
                '</div>';

            if (data.supervisor_remarks) {
                html += '<div style="grid-column: 1 / -1; margin-top: 12px;">' +
                        '<strong style="color: #495057;">Current Supervisor Remarks:</strong>' +
                        '<div style="background: #fff3cd; border: 1px solid #ffeeba; border-radius: 4px; padding: 10px; margin-top: 4px; color: #856404;">' +
                            data.supervisor_remarks +
                        '</div>' +
                    '</div>';
            }

            html += '</div>';
            detailModalBody.innerHTML = html;

            detailModalStatusBadge.innerHTML = '<strong>Status:</strong> <span class="badge ' + data.badge_class + '">' + data.entry_status + '</span>';

            detailModalActionButtons.innerHTML = 
                '<button type="button" class="btn btn-sm btn--submit js-modal-action" data-type="' + data.type + '" data-id="' + data.id + '" data-status="Approved">Approve</button>' +
                '<button type="button" class="btn btn-sm btn--warn js-modal-action" data-type="' + data.type + '" data-id="' + data.id + '" data-status="Awaiting Approval">Set Pending</button>' +
                '<button type="button" class="btn btn-sm btn--outline js-modal-remarks-trigger" data-type="' + data.type + '" data-id="' + data.id + '" data-status="Discuss and Resubmit" data-remarks="' + (data.supervisor_remarks || '') + '">Discuss & Resubmit</button>';

            detailModal.hidden = false;

            // Wire up buttons inside detail modal
            detailModal.querySelectorAll('.js-modal-action').forEach(function (mBtn) {
                mBtn.addEventListener('click', function () {
                    var type = this.getAttribute('data-type');
                    var id = this.getAttribute('data-id');
                    var status = this.getAttribute('data-status');
                    quickForm.action = "/supervisor/entries/" + type + "/" + id + "/status";
                    quickStatusInput.value = status;
                    quickForm.submit();
                });
            });

            detailModal.querySelectorAll('.js-modal-remarks-trigger').forEach(function (mBtn) {
                mBtn.addEventListener('click', function () {
                    detailModal.hidden = true;
                    var type = this.getAttribute('data-type');
                    var id = this.getAttribute('data-id');
                    var status = this.getAttribute('data-status');
                    var rem = this.getAttribute('data-remarks');

                    modalStatusForm.action = "/supervisor/entries/" + type + "/" + id + "/status";
                    modalStatusField.value = status;
                    modalRemarksField.value = rem;
                    statusModalTitle.textContent = 'Discuss and Resubmit';
                    statusModalDesc.textContent = 'Specify feedback for trainee:';
                    statusModal.hidden = false;
                });
            });
        });
    });

    document.querySelectorAll('[data-close-detail-modal]').forEach(function (el) {
        el.addEventListener('click', function () {
            detailModal.hidden = true;
        });
    });
});
</script>
@endpush
@endsection
