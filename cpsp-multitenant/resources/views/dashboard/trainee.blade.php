@extends('layouts.app')

@section('title', ($tenant->name ?? 'e-Logbook') . ' - Trainee Dashboard')
@section('nav_home', 'is-active')

@push('styles')
<style>
/* ==========================================================================
   Modernized Trainee Dashboard Styles
   Aligned with Supervisor & CPSP Theme (#0b6040)
   ========================================================================== */
.trainee-dash {
    padding: 6px 0 36px 0;
    font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
}

/* Breadcrumb */
.trainee-dash__breadcrumb {
    margin-bottom: 16px;
}
.trainee-dash__breadcrumb .breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 8px;
    list-style: none;
    padding: 0;
    margin: 0;
    font-size: 13px;
}
.trainee-dash__breadcrumb .breadcrumb-item a {
    color: #64748b;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: color 0.15s;
}
.trainee-dash__breadcrumb .breadcrumb-item a:hover {
    color: #0b6040;
}
.trainee-dash__breadcrumb .breadcrumb-item.separator {
    color: #cbd5e1;
    font-size: 10px;
}
.trainee-dash__breadcrumb .breadcrumb-item.active {
    color: #0f172a;
    font-weight: 600;
}

/* Hero Profile Banner */
.trainee-hero-banner {
    background: linear-gradient(135deg, #0b6040 0%, #117850 100%);
    border-radius: 14px;
    padding: 24px 26px;
    color: #ffffff;
    margin-bottom: 24px;
    box-shadow: 0 8px 24px -4px rgba(11, 96, 64, 0.22);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    position: relative;
    overflow: hidden;
}

.trainee-hero-banner::after {
    content: '';
    position: absolute;
    right: -40px;
    bottom: -40px;
    width: 220px;
    height: 220px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.05);
    pointer-events: none;
}

.trainee-hero__profile {
    display: flex;
    align-items: center;
    gap: 18px;
}

.trainee-hero__avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.18);
    border: 3px solid rgba(255, 255, 255, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 800;
    color: #ffffff;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.trainee-hero__info h1 {
    font-size: 22px;
    font-weight: 800;
    margin: 0 0 6px 0;
    letter-spacing: -0.01em;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.trainee-hero__role-badge {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    background: rgba(255, 255, 255, 0.22);
    color: #ffffff;
    padding: 3px 10px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.trainee-hero__meta-row {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    font-size: 13px;
    color: rgba(255, 255, 255, 0.9);
}

.trainee-hero__meta-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(0, 0, 0, 0.12);
    padding: 3px 10px;
    border-radius: 6px;
}

.trainee-hero__actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    z-index: 2;
}

.btn-hero-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #ffffff;
    color: #0b6040 !important;
    font-size: 13px;
    font-weight: 700;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    transition: all 0.2s ease;
}

.btn-hero-primary:hover {
    background: #f8fafc;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.18);
    color: #084d33 !important;
}

.btn-hero-glass {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff !important;
    font-size: 13px;
    font-weight: 600;
    padding: 10px 16px;
    border-radius: 8px;
    text-decoration: none;
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: all 0.2s ease;
}

.btn-hero-glass:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
    color: #ffffff !important;
}

/* Metric Stats Grid */
.trainee-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 26px;
}
@media (max-width: 1024px) {
    .trainee-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 600px) {
    .trainee-stats-grid {
        grid-template-columns: 1fr;
    }
}

.trainee-stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    transition: all 0.22s ease-in-out;
}

.trainee-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}
.trainee-stat-card--total::before { background: linear-gradient(90deg, #0b6040, #10b981); }
.trainee-stat-card--approved::before { background: linear-gradient(90deg, #10b981, #059669); }
.trainee-stat-card--pending::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
.trainee-stat-card--academic::before { background: linear-gradient(90deg, #6366f1, #4f46e5); }

.trainee-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px -3px rgba(15, 23, 42, 0.08);
}

.trainee-stat-card__head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.trainee-stat-card__label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #64748b;
}

.trainee-stat-card__icon-wrap {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}
.trainee-stat-card--total .trainee-stat-card__icon-wrap { background: #e6f4ee; color: #0b6040; }
.trainee-stat-card--approved .trainee-stat-card__icon-wrap { background: #ecfdf5; color: #10b981; }
.trainee-stat-card--pending .trainee-stat-card__icon-wrap { background: #fffbeb; color: #f59e0b; }
.trainee-stat-card--academic .trainee-stat-card__icon-wrap { background: #eef2ff; color: #6366f1; }

.trainee-stat-card__val {
    font-size: 30px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
    margin-bottom: 4px;
}

.trainee-stat-card__sub {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
}

/* Panels */
.trainee-panel {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    margin-bottom: 24px;
    box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.04);
    overflow: hidden;
}

.trainee-panel__header {
    background: linear-gradient(135deg, #0b6040 0%, #107c53 100%);
    color: #ffffff;
    padding: 14px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.trainee-panel__title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 15px;
    font-weight: 700;
    margin: 0;
    letter-spacing: 0.01em;
}

.trainee-panel__body {
    padding: 0;
}

/* Modules Table */
.trainee-module-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
}

.trainee-module-table thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 12px 20px;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
}

.trainee-module-table tbody td {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.trainee-module-table tbody tr:hover td {
    background: #fcfdfd;
}

.trainee-module-table tbody tr:last-child td {
    border-bottom: none;
}

.module-info-cell {
    display: flex;
    align-items: center;
    gap: 14px;
}

.module-icon-wrap {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    flex-shrink: 0;
}

.module-icon-wrap--training { background: #e6f4ee; color: #0b6040; }
.module-icon-wrap--rotational { background: #e0f2fe; color: #0284c7; }
.module-icon-wrap--journal { background: #f3e8ff; color: #7e22ce; }
.module-icon-wrap--presented { background: #ffedd5; color: #ea580c; }
.module-icon-wrap--published { background: #ccfbf1; color: #0f766e; }
.module-icon-wrap--academic { background: #ede9fe; color: #6d28d9; }
.module-icon-wrap--suggestions { background: #fef3c7; color: #b45309; }

.module-meta-title {
    font-weight: 700;
    color: #0f172a;
    font-size: 14.5px;
    text-decoration: none;
    display: block;
    margin-bottom: 2px;
}
.module-meta-title:hover {
    color: #0b6040;
}

.module-meta-desc {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
}

.badge-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 28px;
    padding: 0 10px;
    border-radius: 14px;
    font-size: 13.5px;
    font-weight: 800;
    color: #0b6040;
    background: #e6f4ee;
    border: 1px solid rgba(11, 96, 64, 0.15);
}

.badge-count--zero {
    color: #94a3b8;
    background: #f1f5f9;
    border-color: #e2e8f0;
}

.module-action-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-action-add {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #0b6040;
    color: #ffffff !important;
    font-size: 12.5px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.15s ease;
}

.btn-action-add:hover {
    background: #084d33;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(11, 96, 64, 0.25);
}

.btn-action-view {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #ffffff;
    color: #0f172a !important;
    border: 1px solid #cbd5e1;
    font-size: 12.5px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.15s ease;
}

.btn-action-view:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    color: #0b6040 !important;
}

/* Recent Activity List */
.trainee-activity-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.trainee-activity-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 22px;
    border-bottom: 1px solid #f1f5f9;
    gap: 16px;
    transition: background 0.15s;
}

.trainee-activity-item:last-child {
    border-bottom: none;
}

.trainee-activity-item:hover {
    background: #f8fafc;
}

.trainee-activity-left {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
}

.trainee-activity-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}

.trainee-activity-details {
    min-width: 0;
}

.trainee-activity-title {
    font-size: 14px;
    font-weight: 600;
    color: #0f172a;
    text-decoration: none;
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 480px;
}
.trainee-activity-title:hover {
    color: #0b6040;
}

.trainee-activity-sub {
    font-size: 12.5px;
    color: #64748b;
    margin: 2px 0 0 0;
}

.trainee-activity-right {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11.5px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 12px;
}

.status-badge--approved { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
.status-badge--awaiting { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
.status-badge--resubmit { background: #fae8ff; color: #86198f; border: 1px solid #f5d0fe; }
.status-badge--disapproved { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
.status-badge--draft { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

.trainee-activity-date {
    font-size: 12px;
    color: #94a3b8;
    white-space: nowrap;
}

.empty-activity-box {
    padding: 38px 20px;
    text-align: center;
    color: #64748b;
}
.empty-activity-box i {
    font-size: 36px;
    color: #cbd5e1;
    margin-bottom: 12px;
    display: block;
}
</style>
@endpush

@section('content')
<div class="trainee-dash">
    {{-- Breadcrumb --}}
    <nav class="trainee-dash__breadcrumb" aria-label="breadcrumb">
        <ul class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i> Home</a></li>
            <li class="breadcrumb-item separator"><i class="fa-solid fa-chevron-right"></i></li>
            <li class="breadcrumb-item active">Trainee Dashboard</li>
        </ul>
    </nav>

    {{-- Hero Profile Banner --}}
    @php
        $fullName = $user->profile?->full_name ?: $user->username;
        $words = preg_split('/\s+/', trim((string)$fullName));
        $initials = '';
        foreach (array_slice($words, 0, 2) as $w) {
            $initials .= strtoupper(substr($w, 0, 1));
        }
        if (!$initials) {
            $initials = 'TR';
        }
    @endphp
    <div class="trainee-hero-banner">
        <div class="trainee-hero__profile">
            <div class="trainee-hero__avatar">
                {{ $initials }}
            </div>
            <div class="trainee-hero__info">
                <h1>
                    {{ $fullName }}
                    <span class="trainee-hero__role-badge">Trainee Resident</span>
                </h1>
                <div class="trainee-hero__meta-row">
                    <span class="trainee-hero__meta-item">
                        <i class="fa-solid fa-user"></i> {{ $user->username }}
                    </span>
                    <span class="trainee-hero__meta-item">
                        <i class="fa-solid fa-hospital"></i> {{ $tenant->name ?? 'Isra e-Logbook' }}
                    </span>
                    <span class="trainee-hero__meta-item">
                        <i class="fa-solid fa-user-doctor"></i> Supervisor: <strong>{{ optional($assignedSupervisor?->profile)->full_name ?: ($assignedSupervisor?->username ?? 'Assigned Clinical Supervisor') }}</strong>
                    </span>
                    @if(!empty($lastEntryLabel))
                    <span class="trainee-hero__meta-item">
                        <i class="fa-regular fa-calendar-check"></i> Last Entry: {{ $lastEntryLabel }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="trainee-hero__actions">
            <a href="{{ route('training.create', request('program') ? ['program' => request('program')] : []) }}" class="btn-hero-primary">
                <i class="fa-solid fa-plus"></i> New Training Entry
            </a>
            <a href="{{ route('academic.trainee') }}" class="btn-hero-glass">
                <i class="fa-solid fa-graduation-cap"></i> Academic Milestones
            </a>
        </div>
    </div>

    {{-- 4 Metric Stats Grid --}}
    <div class="trainee-stats-grid">
        <div class="trainee-stat-card trainee-stat-card--total">
            <div class="trainee-stat-card__head">
                <span class="trainee-stat-card__label">Total Logbook Entries</span>
                <div class="trainee-stat-card__icon-wrap">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
            </div>
            <div class="trainee-stat-card__val">{{ $totalEntries }}</div>
            <p class="trainee-stat-card__sub">Across all 5 clinical categories</p>
        </div>

        <div class="trainee-stat-card trainee-stat-card--approved">
            <div class="trainee-stat-card__head">
                <span class="trainee-stat-card__label">Approved Entries</span>
                <div class="trainee-stat-card__icon-wrap">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="trainee-stat-card__val">{{ $statusCounts['approved'] }}</div>
            <p class="trainee-stat-card__sub">Verified and signed by supervisor</p>
        </div>

        <div class="trainee-stat-card trainee-stat-card--pending">
            <div class="trainee-stat-card__head">
                <span class="trainee-stat-card__label">Awaiting Approval</span>
                <div class="trainee-stat-card__icon-wrap">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
            <div class="trainee-stat-card__val">{{ $statusCounts['awaiting'] }}</div>
            <p class="trainee-stat-card__sub">Pending supervisor verification</p>
        </div>

        <div class="trainee-stat-card trainee-stat-card--academic">
            <div class="trainee-stat-card__head">
                <span class="trainee-stat-card__label">Workshops & Progress</span>
                <div class="trainee-stat-card__icon-wrap">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
            </div>
            <div class="trainee-stat-card__val">{{ $academicCounts['total'] }}</div>
            <p class="trainee-stat-card__sub">{{ $academicCounts['workshops'] }} Workshops &bull; {{ $academicCounts['synopsis'] + $academicCounts['dissertation'] }} Research</p>
        </div>
    </div>

    {{-- Modules Overview Table --}}
    <div class="trainee-panel">
        <div class="trainee-panel__header">
            <h2 class="trainee-panel__title">
                <i class="fa-solid fa-table-list"></i> E-Logbook Modules & Overview
            </h2>
            <span style="font-size: 12px; background: rgba(255,255,255,0.2); padding: 3px 10px; border-radius: 12px;">Active Academic Curriculum</span>
        </div>
        <div class="trainee-panel__body">
            <div class="table-responsive">
                <table class="trainee-module-table">
                    <thead>
                        <tr>
                            <th style="width: 50%;">Logbook Module / Section</th>
                            <th style="width: 20%; text-align: center;">Logged Entries</th>
                            <th style="width: 30%; text-align: right;">Quick Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- 1. Training --}}
                        <tr>
                            <td>
                                <div class="module-info-cell">
                                    <div class="module-icon-wrap module-icon-wrap--training">
                                        <i class="fa-solid fa-stethoscope"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('training.index', request('program') ? ['program' => request('program')] : []) }}" class="module-meta-title">
                                            Training Log
                                        </a>
                                        <p class="module-meta-desc">Clinical case admissions, procedures, and patient management</p>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge-count {{ ($counts['training'] ?? 0) > 0 ? '' : 'badge-count--zero' }}">
                                    {{ $counts['training'] ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <div class="module-action-buttons" style="justify-content: flex-end;">
                                    <a href="{{ route('training.create', request('program') ? ['program' => request('program')] : []) }}" class="btn-action-add">
                                        <i class="fa-solid fa-plus"></i> Add New
                                    </a>
                                    <a href="{{ route('training.index', request('program') ? ['program' => request('program')] : []) }}" class="btn-action-view">
                                        View All
                                    </a>
                                </div>
                            </td>
                        </tr>

                        {{-- 2. Rotational Training --}}
                        <tr>
                            <td>
                                <div class="module-info-cell">
                                    <div class="module-icon-wrap module-icon-wrap--rotational">
                                        <i class="fa-solid fa-arrows-spin"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('rotational.index', request('program') ? ['program' => request('program')] : []) }}" class="module-meta-title">
                                            Rotational Training
                                        </a>
                                        <p class="module-meta-desc">Departmental rotations, mandatory postings, and evaluations</p>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge-count {{ ($counts['rotational'] ?? 0) > 0 ? '' : 'badge-count--zero' }}">
                                    {{ $counts['rotational'] ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <div class="module-action-buttons" style="justify-content: flex-end;">
                                    <a href="{{ route('rotational.create', request('program') ? ['program' => request('program')] : []) }}" class="btn-action-add">
                                        <i class="fa-solid fa-plus"></i> Add New
                                    </a>
                                    <a href="{{ route('rotational.index', request('program') ? ['program' => request('program')] : []) }}" class="btn-action-view">
                                        View All
                                    </a>
                                </div>
                            </td>
                        </tr>

                        {{-- 3. Journal Club --}}
                        <tr>
                            <td>
                                <div class="module-info-cell">
                                    <div class="module-icon-wrap module-icon-wrap--journal">
                                        <i class="fa-solid fa-book-journal-whills"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('journal.index', request('program') ? ['program' => request('program')] : []) }}" class="module-meta-title">
                                            Journal Club
                                        </a>
                                        <p class="module-meta-desc">Critical literature appraisal, evidence reviews, and discussions</p>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge-count {{ ($counts['journal'] ?? 0) > 0 ? '' : 'badge-count--zero' }}">
                                    {{ $counts['journal'] ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <div class="module-action-buttons" style="justify-content: flex-end;">
                                    <a href="{{ route('journal.create', request('program') ? ['program' => request('program')] : []) }}" class="btn-action-add">
                                        <i class="fa-solid fa-plus"></i> Add New
                                    </a>
                                    <a href="{{ route('journal.index', request('program') ? ['program' => request('program')] : []) }}" class="btn-action-view">
                                        View All
                                    </a>
                                </div>
                            </td>
                        </tr>

                        {{-- 4. Paper/Poster Presented --}}
                        <tr>
                            <td>
                                <div class="module-info-cell">
                                    <div class="module-icon-wrap module-icon-wrap--presented">
                                        <i class="fa-solid fa-chalkboard-user"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('presented.index', request('program') ? ['program' => request('program')] : []) }}" class="module-meta-title">
                                            Paper / Poster Presented
                                        </a>
                                        <p class="module-meta-desc">Conference presentations, academic symposia, and posters</p>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge-count {{ ($counts['presented'] ?? 0) > 0 ? '' : 'badge-count--zero' }}">
                                    {{ $counts['presented'] ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <div class="module-action-buttons" style="justify-content: flex-end;">
                                    <a href="{{ route('presented.create', request('program') ? ['program' => request('program')] : []) }}" class="btn-action-add">
                                        <i class="fa-solid fa-plus"></i> Add New
                                    </a>
                                    <a href="{{ route('presented.index', request('program') ? ['program' => request('program')] : []) }}" class="btn-action-view">
                                        View All
                                    </a>
                                </div>
                            </td>
                        </tr>

                        {{-- 5. Paper Published --}}
                        <tr>
                            <td>
                                <div class="module-info-cell">
                                    <div class="module-icon-wrap module-icon-wrap--published">
                                        <i class="fa-solid fa-newspaper"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('published.index', request('program') ? ['program' => request('program')] : []) }}" class="module-meta-title">
                                            Paper Published
                                        </a>
                                        <p class="module-meta-desc">Peer-reviewed publications, medical articles, and indexing</p>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge-count {{ ($counts['published'] ?? 0) > 0 ? '' : 'badge-count--zero' }}">
                                    {{ $counts['published'] ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <div class="module-action-buttons" style="justify-content: flex-end;">
                                    <a href="{{ route('published.create', request('program') ? ['program' => request('program')] : []) }}" class="btn-action-add">
                                        <i class="fa-solid fa-plus"></i> Add New
                                    </a>
                                    <a href="{{ route('published.index', request('program') ? ['program' => request('program')] : []) }}" class="btn-action-view">
                                        View All
                                    </a>
                                </div>
                            </td>
                        </tr>

                        {{-- 6. Workshops & Progression --}}
                        <tr>
                            <td>
                                <div class="module-info-cell">
                                    <div class="module-icon-wrap module-icon-wrap--academic">
                                        <i class="fa-solid fa-graduation-cap"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('academic.trainee') }}" class="module-meta-title">
                                            Workshops & Dissertation
                                        </a>
                                        <p class="module-meta-desc">Mandatory CPSP workshops, research synopses, and dissertations</p>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge-count {{ ($academicCounts['total'] ?? 0) > 0 ? '' : 'badge-count--zero' }}">
                                    {{ $academicCounts['total'] ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <div class="module-action-buttons" style="justify-content: flex-end;">
                                    <a href="{{ route('academic.trainee') }}" class="btn-action-view" style="background: #0b6040; color: #fff !important; border-color: #0b6040;">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Open Milestones
                                    </a>
                                </div>
                            </td>
                        </tr>

                        {{-- 7. Suggestions --}}
                        <tr>
                            <td>
                                <div class="module-info-cell">
                                    <div class="module-icon-wrap module-icon-wrap--suggestions">
                                        <i class="fa-solid fa-lightbulb"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('suggestions.index', request('program') ? ['program' => request('program')] : []) }}" class="module-meta-title">
                                            Suggestions & Feedback
                                        </a>
                                        <p class="module-meta-desc">Submit constructive feedback or recommendations regarding the e-Logbook</p>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge-count {{ ($counts['suggestions'] ?? 0) > 0 ? '' : 'badge-count--zero' }}">
                                    {{ $counts['suggestions'] ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <div class="module-action-buttons" style="justify-content: flex-end;">
                                    <a href="{{ route('suggestions.index', request('program') ? ['program' => request('program')] : []) }}" class="btn-action-view">
                                        Feedback Page
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Activities Panel --}}
    <div class="trainee-panel">
        <div class="trainee-panel__header">
            <h2 class="trainee-panel__title">
                <i class="fa-solid fa-clock-rotate-left"></i> Recent Activity Feed
            </h2>
            <span style="font-size: 12px; color: rgba(255,255,255,0.85);">Latest 8 submissions</span>
        </div>
        <div class="trainee-panel__body">
            @if(empty($recentActivities) || count($recentActivities) === 0)
                <div class="empty-activity-box">
                    <i class="fa-solid fa-file-circle-plus"></i>
                    <h3 style="font-size: 16px; font-weight: 700; color: #334155; margin: 0 0 6px 0;">No activities recorded yet</h3>
                    <p style="font-size: 13.5px; margin: 0 0 16px 0;">Once you submit entries in any logbook module, they will automatically appear here with live verification status.</p>
                    <a href="{{ route('training.create', request('program') ? ['program' => request('program')] : []) }}" class="btn-action-add" style="display: inline-flex;">
                        <i class="fa-solid fa-plus"></i> Add Your First Entry
                    </a>
                </div>
            @else
                <ul class="trainee-activity-list">
                    @foreach($recentActivities as $act)
                        @php
                            $status = $act['status'] ?? 'Draft';
                            $statusClass = 'status-badge--draft';
                            $statusIcon = 'fa-pen-to-square';

                            if (stripos($status, 'approved') !== false) {
                                $statusClass = 'status-badge--approved';
                                $statusIcon = 'fa-check';
                            } elseif (stripos($status, 'awaiting') !== false || stripos($status, 'pending') !== false) {
                                $statusClass = 'status-badge--awaiting';
                                $statusIcon = 'fa-clock';
                            } elseif (stripos($status, 'resubmit') !== false || stripos($status, 'discuss') !== false) {
                                $statusClass = 'status-badge--resubmit';
                                $statusIcon = 'fa-rotate-left';
                            } elseif (stripos($status, 'disapproved') !== false) {
                                $statusClass = 'status-badge--disapproved';
                                $statusIcon = 'fa-xmark';
                            }
                        @endphp
                        <li class="trainee-activity-item">
                            <div class="trainee-activity-left">
                                <div class="trainee-activity-icon" style="background: {{ $act['badge_bg'] }}; color: {{ $act['badge_text'] }};">
                                    <i class="{{ $act['icon'] }}"></i>
                                </div>
                                <div class="trainee-activity-details">
                                    <div>
                                        <a href="{{ $act['url'] }}" class="trainee-activity-title" title="{{ $act['title'] }}">
                                            {{ $act['title'] }}
                                        </a>
                                    </div>
                                    <p class="trainee-activity-sub">
                                        <span style="font-weight: 600; color: #475569;">{{ $act['section'] }}</span> &bull; {{ $act['meta'] }}
                                    </p>
                                </div>
                            </div>

                            <div class="trainee-activity-right">
                                <span class="status-badge {{ $statusClass }}">
                                    <i class="fa-solid {{ $statusIcon }}"></i> {{ $status }}
                                </span>
                                <span class="trainee-activity-date">
                                    {{ $act['created_at']?->format('d-M-Y H:i') ?? '—' }}
                                </span>
                                <a href="{{ $act['url'] }}" class="btn-action-view" style="padding: 4px 10px; font-size: 11.5px;">
                                    View
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
