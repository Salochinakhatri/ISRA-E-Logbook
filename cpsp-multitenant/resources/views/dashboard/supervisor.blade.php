@extends('layouts.app')

@section('title', ($tenant->name ?? 'e-Logbook') . ' - Supervisor Dashboard')
@section('nav_home', 'is-active')

@push('styles')
<style>
/* Academic Review Modal Styles */
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
    opacity: 0;
    transition: opacity 0.25s ease;
}

.arm-modal-backdrop.is-open {
    display: flex;
    opacity: 1;
}

.arm-modal-dialog {
    background: #ffffff;
    width: 100%;
    max-width: 960px;
    max-height: 90vh;
    border-radius: 12px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transform: translateY(20px) scale(0.98);
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.arm-modal-backdrop.is-open .arm-modal-dialog {
    transform: translateY(0) scale(1);
}

.arm-modal-header {
    background: linear-gradient(135deg, #0b6040 0%, #0d7a52 100%);
    color: #ffffff;
    padding: 18px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.arm-modal-title {
    font-size: 18px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.arm-modal-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: #ffffff;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    transition: background 0.2s;
}

.arm-modal-close:hover {
    background: rgba(255, 255, 255, 0.35);
}

.arm-trainee-banner {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 12px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.arm-trainee-info {
    display: flex;
    align-items: center;
    gap: 14px;
}

.arm-trainee-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #0b6040;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.arm-trainee-name {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}

.arm-trainee-meta {
    font-size: 12.5px;
    color: #64748b;
    margin: 2px 0 0 0;
    display: flex;
    gap: 12px;
}

.arm-modal-body {
    padding: 20px 24px;
    overflow-y: auto;
    flex: 1;
}

.arm-section {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 20px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}

.arm-section:last-child {
    margin-bottom: 0;
}

.arm-section-header {
    background: #f1f5f9;
    padding: 10px 16px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.arm-section-title {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.arm-section-title i {
    color: #0b6040;
}

.arm-count-badge {
    background: #0b6040;
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 10px;
}

.arm-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    margin: 0;
}

.arm-table th {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    padding: 10px 14px;
    border-bottom: 1.5px solid #cbd5e1;
    text-align: left;
}

.arm-table td {
    padding: 11px 14px;
    border-bottom: 1px solid #e2e8f0;
    color: #1e293b;
    vertical-align: middle;
}

.arm-table tbody tr:last-child td {
    border-bottom: none;
}

.arm-table tbody tr:hover {
    background: #f8fafc;
}

.arm-col-hash {
    width: 48px;
    text-align: center;
    font-weight: 700;
    color: #64748b;
}

.arm-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px;
    border-radius: 16px;
    font-size: 11.5px;
    font-weight: 600;
}

.arm-status-completed,
.arm-status-approved {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.arm-status-in-progress,
.arm-status-submitted,
.arm-status-under-review {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.arm-status-pending {
    background: #e0f2fe;
    color: #0369a1;
    border: 1px solid #bae6fd;
}

.arm-empty-row {
    text-align: center;
    padding: 24px !important;
    color: #64748b;
    font-style: italic;
    background: #fafafa;
}

.arm-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 48px 24px;
    color: #0b6040;
}

.arm-loading i {
    font-size: 32px;
    margin-bottom: 12px;
}

.arm-modal-footer {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 12px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
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
    text-decoration: none;
    transition: all 0.2s;
}

.btn-arm-secondary:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
    color: #0f172a;
    text-decoration: none;
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
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
}

.btn-arm-primary:hover {
    background: #084d33;
    border-color: #084d33;
    color: #ffffff;
    text-decoration: none;
}

/* ==========================================================================
   Modernized Supervisor Dashboard Styles
   ========================================================================== */
.supervisor-dash {
    padding: 6px 0 36px 0;
}

/* Breadcrumb */
.supervisor-dash__breadcrumb {
    margin-bottom: 14px;
}
.supervisor-dash__breadcrumb .breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 8px;
    list-style: none;
    padding: 0;
    margin: 0;
    font-size: 13px;
}
.supervisor-dash__breadcrumb .breadcrumb-item a {
    color: #64748b;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: color 0.15s;
}
.supervisor-dash__breadcrumb .breadcrumb-item a:hover {
    color: #0b6040;
}
.supervisor-dash__breadcrumb .breadcrumb-item.separator {
    color: #cbd5e1;
    font-size: 10px;
}
.supervisor-dash__breadcrumb .breadcrumb-item.active {
    color: #0f172a;
    font-weight: 600;
}

/* Header & Alert */
.supervisor-dash__header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e2e8f0;
}
.supervisor-dash__title {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.02em;
    display: flex;
    align-items: center;
    gap: 8px;
}
.supervisor-dash__title-sub {
    font-weight: 400;
    color: #64748b;
    font-size: 20px;
}
.supervisor-dash__subtitle {
    margin: 4px 0 0 0;
    font-size: 13.5px;
    color: #64748b;
}

.supervisor-dash__alert-card {
    display: flex;
    align-items: center;
    gap: 14px;
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    border: 1px solid #fde68a;
    padding: 10px 18px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.08);
}
.supervisor-dash__alert-icon-box {
    position: relative;
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #f59e0b;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}
.alert-pulse-ring {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 10px;
    background: inherit;
    opacity: 0.5;
    animation: alert-pulse 2s cubic-bezier(0.24, 0, 0.38, 1) infinite;
}
@keyframes alert-pulse {
    0% { transform: scale(0.95); opacity: 0.8; }
    50% { transform: scale(1.25); opacity: 0; }
    100% { transform: scale(1.25); opacity: 0; }
}
.supervisor-dash__alert-text {
    display: flex;
    flex-direction: column;
}
.alert-highlight {
    font-weight: 800;
    font-size: 13.5px;
    color: #92400e;
}
.alert-desc {
    font-size: 12px;
    color: #b45309;
}
.btn-review-now {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #0b6040;
    color: #ffffff !important;
    font-size: 12.5px;
    font-weight: 700;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    box-shadow: 0 2px 6px rgba(11, 96, 64, 0.25);
    transition: all 0.2s;
    margin-left: 6px;
}
.btn-review-now:hover {
    background: #084d33;
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(11, 96, 64, 0.35);
    text-decoration: none;
}

/* 4 Metric Cards Grid */
.supervisor-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 26px;
}
@media (max-width: 1024px) {
    .supervisor-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 600px) {
    .supervisor-stats-grid {
        grid-template-columns: 1fr;
    }
}

.sup-stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 18px 20px;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    transition: all 0.22s ease-in-out;
}
.sup-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}
.sup-stat-card--feedback::before { background: linear-gradient(90deg, #10b981, #059669); }
.sup-stat-card--cme::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
.sup-stat-card--reports::before { background: linear-gradient(90deg, #6366f1, #4f46e5); }
.sup-stat-card--workshop::before { background: linear-gradient(90deg, #0ea5e9, #0284c7); }

.sup-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px -3px rgba(15, 23, 42, 0.08);
    border-color: #cbd5e1;
}
.sup-stat-card--clickable {
    cursor: pointer;
    text-decoration: none !important;
}

.sup-stat-card__top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}
.sup-stat-card__label {
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #64748b;
    padding-top: 2px;
}
.sup-stat-card__icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.sup-stat-card--feedback .sup-stat-card__icon {
    background: #ecfdf5;
    color: #059669;
}
.sup-stat-card--cme .sup-stat-card__icon {
    background: #fffbeb;
    color: #d97706;
}
.sup-stat-card--reports .sup-stat-card__icon {
    background: #eef2ff;
    color: #4f46e5;
}
.sup-stat-card--workshop .sup-stat-card__icon {
    background: #f0f9ff;
    color: #0284c7;
}

.sup-stat-card__number {
    font-size: 32px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
    margin-bottom: 12px;
}
.sup-stat-card__number--action {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 20px;
    font-weight: 700;
    color: #4f46e5;
}

.sup-stat-card__footer {
    border-top: 1px solid #f1f5f9;
    padding-top: 10px;
    margin-top: auto;
}
.sup-stat-card__meta {
    font-size: 11.5px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
}
.sup-stat-card--clickable:hover .sup-stat-card__number--action {
    color: #3730a3;
}

/* Collapsible Panels */
.sup-panel {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    margin-bottom: 24px;
    box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.04);
    overflow: hidden;
    transition: box-shadow 0.2s;
}
.sup-panel:hover {
    box-shadow: 0 6px 20px -2px rgba(15, 23, 42, 0.07);
}
.sup-panel__header {
    background: linear-gradient(135deg, #0b6040 0%, #107c53 100%);
    color: #ffffff;
    padding: 13px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 0;
}
.sup-panel__title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14.5px;
    font-weight: 700;
    letter-spacing: 0.01em;
}
.sup-panel__title-badge {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    font-size: 11px;
    font-weight: 600;
    padding: 2px 10px;
    border-radius: 14px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    margin-left: 6px;
}
.sup-panel__toggle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.18);
    border: none;
    color: #ffffff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    transition: background 0.15s;
}
.sup-panel__toggle:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Sup Table */
.sup-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    margin: 0;
}
.sup-table thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 12px 14px;
    border-bottom: 2px solid #e2e8f0;
    vertical-align: middle;
    white-space: nowrap;
}
.sup-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #1e293b;
}
.sup-table tbody tr:hover td {
    background-color: #f8fafc;
}
.sup-table tbody tr:last-child td {
    border-bottom: none;
}

/* Trainee Cell Component */
.sup-trainee-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}
.sup-trainee-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0b6040 0%, #107c53 100%);
    color: #ffffff;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 2px 5px rgba(11, 96, 64, 0.2);
}
.sup-trainee-info {
    display: flex;
    flex-direction: column;
}
.sup-trainee-name {
    font-weight: 700;
    color: #0f172a;
    font-size: 13.5px;
}
.sup-trainee-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    padding: 1px 6px;
    margin-top: 2px;
    width: fit-content;
}

/* Count Badges */
.sup-count-zero {
    color: #94a3b8;
    font-weight: 600;
    font-size: 14px;
}
.sup-count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 24px;
    padding: 0 8px;
    font-size: 12px;
    font-weight: 700;
    border-radius: 12px;
    text-decoration: none;
    transition: transform 0.15s, box-shadow 0.15s;
}
.sup-count-badge:hover {
    transform: scale(1.1);
    text-decoration: none;
}
.sup-count-badge--warn {
    background: #fffbeb;
    color: #b45309;
    border: 1.5px solid #fde68a;
    box-shadow: 0 1px 3px rgba(180, 83, 9, 0.08);
}
.sup-count-badge--warn:hover {
    background: #fef3c7;
    color: #92400e;
}
.sup-count-badge--total {
    background: #ecfdf5;
    color: #065f46;
    border: 1.5px solid #a7f3d0;
    font-weight: 800;
    box-shadow: 0 1px 3px rgba(6, 95, 70, 0.08);
}
.sup-count-badge--total:hover {
    background: #d1fae5;
    color: #064e3b;
}

/* Action Review Buttons in Table */
.btn-sup-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 600;
    text-decoration: none !important;
    cursor: pointer;
    transition: all 0.15s;
    white-space: nowrap;
}
.btn-sup-action--has-items {
    background: linear-gradient(135deg, #0b6040 0%, #107c53 100%);
    color: #ffffff !important;
    border: 1px solid #0b6040;
    box-shadow: 0 2px 6px rgba(11, 96, 64, 0.2);
}
.btn-sup-action--has-items:hover {
    background: #084d33;
    border-color: #084d33;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(11, 96, 64, 0.3);
}
.btn-sup-action--empty {
    background: #ffffff;
    color: #475569 !important;
    border: 1.5px solid #cbd5e1;
}
.btn-sup-action--empty:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    color: #0f172a !important;
}

/* Empty States in Tables */
.sup-table__empty-cell {
    padding: 40px 20px !important;
    text-align: center;
}
.sup-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    max-width: 440px;
    margin: 0 auto;
}
.sup-empty-state__icon {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 12px;
}
.sup-empty-state__title {
    font-size: 14px;
    font-weight: 700;
    color: #334155;
    margin: 0 0 4px 0;
}
.sup-empty-state__desc {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
}

/* Dashboard Footer */
.sup-footer {
    text-align: center;
    padding: 24px 0 10px 0;
    color: #94a3b8;
    font-size: 12.5px;
}
.sup-footer p {
    margin: 0;
}
</style>
@endpush

@section('content')
<div class="supervisor-dash">
    {{-- Breadcrumb --}}
    <nav class="supervisor-dash__breadcrumb" aria-label="breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i> Home</a></li>
            <li class="breadcrumb-item separator"><i class="fa-solid fa-chevron-right"></i></li>
            <li class="breadcrumb-item active" aria-current="page">Supervisor Dashboard</li>
        </ol>
    </nav>

    {{-- Main Heading & Alert Banner --}}
    <div class="supervisor-dash__header-container">
        <div class="supervisor-dash__header-left">
            <h1 class="supervisor-dash__title">{{ $tenant->name ?? 'CPSP' }} <span class="supervisor-dash__title-sub">e-Logbook Portal</span></h1>
            <p class="supervisor-dash__subtitle">Supervisory verification, trainee entries review & academic progression</p>
        </div>
        @if($stats['total_pending'] > 0)
            <div class="supervisor-dash__alert-card">
                <div class="supervisor-dash__alert-icon-box">
                    <span class="alert-pulse-ring"></span>
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div class="supervisor-dash__alert-text">
                    <span class="alert-highlight">{{ $stats['total_pending'] }} {{ Str::plural('Entry', $stats['total_pending']) }} Pending</span>
                    <span class="alert-desc">awaiting your review & approval</span>
                </div>
                <a href="{{ route('supervisor.entries') }}" class="btn-review-now">
                    <span>Review All</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        @endif
    </div>

    {{-- 4 Stat Metric Cards --}}
    <div class="supervisor-stats-grid">
        {{-- Card 1: Feedback about Trainee --}}
        <div class="sup-stat-card sup-stat-card--feedback">
            <div class="sup-stat-card__top">
                <span class="sup-stat-card__label">Feedback About Trainee</span>
                <div class="sup-stat-card__icon">
                    <i class="fa-solid fa-comments"></i>
                </div>
            </div>
            <div class="sup-stat-card__number">{{ $stats['feedback_trainee'] }}</div>
            <div class="sup-stat-card__footer">
                <span class="sup-stat-card__meta"><i class="fa-solid fa-circle-check"></i> Trainee feedback logged</span>
            </div>
        </div>

        {{-- Card 2: CME Credits --}}
        <div class="sup-stat-card sup-stat-card--cme">
            <div class="sup-stat-card__top">
                <span class="sup-stat-card__label">CME Credits</span>
                <div class="sup-stat-card__icon">
                    <i class="fa-solid fa-award"></i>
                </div>
            </div>
            <div class="sup-stat-card__number">{{ $stats['cme_credits'] }}</div>
            <div class="sup-stat-card__footer">
                <span class="sup-stat-card__meta"><i class="fa-solid fa-gauge-high"></i> Accumulated credits</span>
            </div>
        </div>

        {{-- Card 3: Reports --}}
        <a href="{{ route('supervisor.entries', ['status' => 'all']) }}" class="sup-stat-card sup-stat-card--reports sup-stat-card--clickable">
            <div class="sup-stat-card__top">
                <span class="sup-stat-card__label">Submitted Reports</span>
                <div class="sup-stat-card__icon">
                    <i class="fa-solid fa-file-waveform"></i>
                </div>
            </div>
            <div class="sup-stat-card__number sup-stat-card__number--action">
                <span>View All</span>
                <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 15px;"></i>
            </div>
            <div class="sup-stat-card__footer">
                <span class="sup-stat-card__meta"><i class="fa-solid fa-list-check"></i> Access logbook entries</span>
            </div>
        </a>

        {{-- Card 4: Workshop Feedback --}}
        <div class="sup-stat-card sup-stat-card--workshop">
            <div class="sup-stat-card__top">
                <span class="sup-stat-card__label">Workshop Feedback</span>
                <div class="sup-stat-card__icon">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
            </div>
            <div class="sup-stat-card__number">{{ $stats['workshop_feedback'] }}</div>
            <div class="sup-stat-card__footer">
                <span class="sup-stat-card__meta"><i class="fa-solid fa-thumbs-up"></i> Workshop submissions</span>
            </div>
        </div>
    </div>

    {{-- Panel 1: Current Trainees - Awaiting Approval Entries --}}
    <div class="sup-panel" id="panelCurrentTrainees">
        <div class="sup-panel__header">
            <div class="sup-panel__title">
                <i class="fa-solid fa-user-graduate"></i>
                <span>Current Trainees &mdash; Awaiting Approval Entries</span>
                @if(!$user->isAdmin())
                    <span class="sup-panel__title-badge"><i class="fa-solid fa-user-check" style="font-size: 10px; margin-right: 4px;"></i> My Assigned Trainees</span>
                @endif
            </div>
            <button type="button" class="sup-panel__toggle" data-target="#currentTraineesContent" aria-label="Toggle panel">
                <i class="fa-solid fa-minus"></i>
            </button>
        </div>
        <div class="sup-panel__content" id="currentTraineesContent">
            <div class="table-responsive">
                <table class="sup-table">
                    <thead>
                        <tr>
                            <th style="width: 44px; text-align: center;">#</th>
                            <th>Trainee Details</th>
                            <th style="text-align: center;">Training</th>
                            <th style="text-align: center;">Rotational</th>
                            <th style="text-align: center;">Journal Club</th>
                            <th style="text-align: center;">Paper / Poster</th>
                            <th style="text-align: center;">Published</th>
                            <th style="text-align: center;">Record</th>
                            <th style="text-align: center;">Total</th>
                            <th style="text-align: center;">Workshops / Synopsis / Dissertation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($currentTrainees as $index => $trainee)
                        @php
                            $words = explode(' ', trim($trainee['name']));
                            $initials = '';
                            if (count($words) >= 2) {
                                $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                            } else {
                                $initials = strtoupper(substr($trainee['name'], 0, 2));
                            }
                        @endphp
                        <tr>
                            <td style="text-align: center; font-weight: 700; color: #64748b;">{{ $index + 1 }}</td>
                            <td>
                                <div class="sup-trainee-cell">
                                    <div class="sup-trainee-avatar" title="{{ $trainee['name'] }}">{{ $initials }}</div>
                                    <div class="sup-trainee-info">
                                        <span class="sup-trainee-name">{{ $trainee['name'] }}</span>
                                        <span class="sup-trainee-pill"><i class="fa-solid fa-id-badge"></i> {{ $trainee['username'] }}</span>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                @if($trainee['training'] > 0)
                                    <a href="{{ route('supervisor.entries', ['trainee_id' => $trainee['id'], 'type' => 'training', 'status' => 'Awaiting Approval']) }}" class="sup-count-badge sup-count-badge--warn" title="Click to review {{ $trainee['training'] }} training entries">
                                        {{ $trainee['training'] }}
                                    </a>
                                @else
                                    <span class="sup-count-zero">&mdash;</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($trainee['rotational'] > 0)
                                    <a href="{{ route('supervisor.entries', ['trainee_id' => $trainee['id'], 'type' => 'rotational', 'status' => 'Awaiting Approval']) }}" class="sup-count-badge sup-count-badge--warn" title="Click to review {{ $trainee['rotational'] }} rotational entries">
                                        {{ $trainee['rotational'] }}
                                    </a>
                                @else
                                    <span class="sup-count-zero">&mdash;</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($trainee['journal'] > 0)
                                    <a href="{{ route('supervisor.entries', ['trainee_id' => $trainee['id'], 'type' => 'journal', 'status' => 'Awaiting Approval']) }}" class="sup-count-badge sup-count-badge--warn" title="Click to review {{ $trainee['journal'] }} journal club entries">
                                        {{ $trainee['journal'] }}
                                    </a>
                                @else
                                    <span class="sup-count-zero">&mdash;</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($trainee['presented'] > 0)
                                    <a href="{{ route('supervisor.entries', ['trainee_id' => $trainee['id'], 'type' => 'presented', 'status' => 'Awaiting Approval']) }}" class="sup-count-badge sup-count-badge--warn" title="Click to review {{ $trainee['presented'] }} presented paper entries">
                                        {{ $trainee['presented'] }}
                                    </a>
                                @else
                                    <span class="sup-count-zero">&mdash;</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($trainee['published'] > 0)
                                    <a href="{{ route('supervisor.entries', ['trainee_id' => $trainee['id'], 'type' => 'published', 'status' => 'Awaiting Approval']) }}" class="sup-count-badge sup-count-badge--warn" title="Click to review {{ $trainee['published'] }} published paper entries">
                                        {{ $trainee['published'] }}
                                    </a>
                                @else
                                    <span class="sup-count-zero">&mdash;</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <span class="sup-count-zero">&mdash;</span>
                            </td>
                            <td style="text-align: center;">
                                @if($trainee['total'] > 0)
                                    <a href="{{ route('supervisor.entries', ['trainee_id' => $trainee['id'], 'status' => 'Awaiting Approval']) }}" class="sup-count-badge sup-count-badge--total" title="Review all {{ $trainee['total'] }} awaiting entries">
                                        {{ $trainee['total'] }}
                                    </a>
                                @else
                                    <span class="sup-count-zero">&mdash;</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group-action" style="justify-content: center; display: flex;">
                                    <a href="{{ route('supervisor.academic-review', ['id' => $trainee['id']]) }}"
                                       class="btn-sup-action {{ $trainee['academic_count'] > 0 ? 'btn-sup-action--has-items' : 'btn-sup-action--empty' }} btn-academic-review-trigger"
                                       data-trainee-id="{{ $trainee['id'] }}"
                                       data-trainee-name="{{ $trainee['name'] }}"
                                       data-trainee-username="{{ $trainee['username'] }}"
                                       title="Review Workshops, Synopsis & Dissertation details for {{ $trainee['name'] }}">
                                        <i class="fa-solid fa-graduation-cap"></i> Review ({{ $trainee['academic_count'] }})
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="sup-table__empty-cell">
                                <div class="sup-empty-state">
                                    <div class="sup-empty-state__icon">
                                        <i class="fa-solid fa-user-doctor"></i>
                                    </div>
                                    <h5 class="sup-empty-state__title">No Trainees Found</h5>
                                    <p class="sup-empty-state__desc">There are currently no active trainees registered under your supervision.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Panel 2: Rotational Trainees - Awaiting Approval Entries --}}
    <div class="sup-panel" id="panelRotationalTrainees">
        <div class="sup-panel__header">
            <div class="sup-panel__title">
                <i class="fa-solid fa-arrows-rotate"></i>
                <span>Rotational Trainees &mdash; Awaiting Approval Entries</span>
            </div>
            <button type="button" class="sup-panel__toggle" data-target="#rotationalTraineesContent" aria-label="Toggle panel">
                <i class="fa-solid fa-minus"></i>
            </button>
        </div>
        <div class="sup-panel__content" id="rotationalTraineesContent">
            <div class="table-responsive">
                <table class="sup-table">
                    <thead>
                        <tr>
                            <th style="width: 44px; text-align: center;">#</th>
                            <th>Trainee Details</th>
                            <th>RTMC No</th>
                            <th>Rotation Speciality</th>
                            <th>Rotation Period</th>
                            <th style="text-align: center;">Entries</th>
                            <th style="text-align: center;">Workshops / Synopsis / Dissertation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rotationalTrainees as $idx => $rt)
                        <tr>
                            <td style="text-align: center; font-weight: 700; color: #64748b;">{{ $idx + 1 }}</td>
                            <td>
                                <div class="sup-trainee-cell">
                                    <div class="sup-trainee-avatar" style="background: linear-gradient(135deg, #0284c7, #0ea5e9);">{{ strtoupper(substr($rt['name'], 0, 2)) }}</div>
                                    <div class="sup-trainee-info">
                                        <span class="sup-trainee-name">{{ $rt['name'] }}</span>
                                        <span class="sup-trainee-pill"><i class="fa-solid fa-id-badge"></i> {{ $rt['username'] }}</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="sup-trainee-pill" style="font-weight: 600;">RTMC-{{ $rt['id'] }}</span></td>
                            <td><span style="font-weight: 600; color: #334155;">General Surgery / Medicine</span></td>
                            <td><span class="sup-trainee-pill" style="background: #ecfdf5; color: #065f46; border-color: #a7f3d0;"><i class="fa-regular fa-clock"></i> Current Rotation</span></td>
                            <td style="text-align: center;">
                                <a href="{{ route('supervisor.entries', ['trainee_id' => $rt['id'], 'type' => 'rotational', 'status' => 'Awaiting Approval']) }}" class="sup-count-badge sup-count-badge--warn">
                                    {{ $rt['rotational'] }}
                                </a>
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group-action" style="justify-content: center; display: flex;">
                                    <a href="{{ route('supervisor.academic-review', ['id' => $rt['id']]) }}"
                                       class="btn-sup-action {{ ($rt['academic_count'] ?? 0) > 0 ? 'btn-sup-action--has-items' : 'btn-sup-action--empty' }} btn-academic-review-trigger"
                                       data-trainee-id="{{ $rt['id'] }}"
                                       data-trainee-name="{{ $rt['name'] }}"
                                       data-trainee-username="{{ $rt['username'] }}"
                                       title="Review Workshops, Synopsis & Dissertation details for {{ $rt['name'] }}">
                                        <i class="fa-solid fa-graduation-cap"></i> Review ({{ $rt['academic_count'] ?? 0 }})
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="sup-table__empty-cell">
                                <div class="sup-empty-state">
                                    <div class="sup-empty-state__icon">
                                        <i class="fa-solid fa-arrows-rotate"></i>
                                    </div>
                                    <h5 class="sup-empty-state__title">No Rotational Trainees Awaiting Approval</h5>
                                    <p class="sup-empty-state__desc">There are currently no rotation-based logbook entries pending your supervisory review.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Panel 3: Previous Trainees - Awaiting Approval Entries --}}
    <div class="sup-panel" id="panelPreviousTrainees">
        <div class="sup-panel__header">
            <div class="sup-panel__title">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Previous Trainees &mdash; Awaiting Approval Entries</span>
            </div>
            <button type="button" class="sup-panel__toggle" data-target="#previousTraineesContent" aria-label="Toggle panel">
                <i class="fa-solid fa-minus"></i>
            </button>
        </div>
        <div class="sup-panel__content" id="previousTraineesContent">
            <div class="table-responsive">
                <table class="sup-table">
                    <thead>
                        <tr>
                            <th style="width: 44px; text-align: center;">#</th>
                            <th>Trainee Details</th>
                            <th style="text-align: center;">Training</th>
                            <th style="text-align: center;">Rotational</th>
                            <th style="text-align: center;">Journal Club</th>
                            <th style="text-align: center;">Paper / Poster</th>
                            <th style="text-align: center;">Published</th>
                            <th style="text-align: center;">Record</th>
                            <th style="text-align: center;">Total</th>
                            <th style="text-align: center;">Workshops / Synopsis / Dissertation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="10" class="sup-table__empty-cell">
                                <div class="sup-empty-state">
                                    <div class="sup-empty-state__icon">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                    </div>
                                    <h5 class="sup-empty-state__title">No Previous Trainees Awaiting Approval</h5>
                                    <p class="sup-empty-state__desc">All past trainees have fulfilled their requirements and have no entries pending approval.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Academic Review Modal --}}
    <div class="arm-modal-backdrop" id="academicReviewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="arm-modal-dialog" role="document">
            <div class="arm-modal-header">
                <h3 class="arm-modal-title">
                    <i class="fa-solid fa-graduation-cap"></i> Academic Progression Review
                </h3>
                <button type="button" class="arm-modal-close" id="btnArmClose" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="arm-trainee-banner">
                <div class="arm-trainee-info">
                    <div class="arm-trainee-avatar">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <div>
                        <h4 class="arm-trainee-name" id="armTraineeName">Loading Trainee...</h4>
                        <div class="arm-trainee-meta">
                            <span><i class="fa-solid fa-id-badge"></i> RTMC / ID: <strong id="armTraineeId">—</strong></span>
                            <span><i class="fa-solid fa-hospital-user"></i> Status: <span class="tab-badge" style="background:#e8f5e9;color:#166534;font-size:11px;padding:2px 8px;">Active Resident</span></span>
                        </div>
                    </div>
                </div>
                <div>
                    <a href="#" id="armFullPageBtn" class="btn-arm-primary" target="_blank" title="Manage, Add and Edit Academic Entries">
                        <i class="fa-solid fa-pen-to-square"></i> Manage / Add Entries
                    </a>
                </div>
            </div>

            <div class="arm-modal-body">
                <div id="armLoadingState" class="arm-loading">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    <span>Loading academic progression records...</span>
                </div>

                <div id="armContentState" style="display: none;">
                    {{-- 1. Workshop Details --}}
                    <div class="arm-section">
                        <div class="arm-section-header">
                            <h4 class="arm-section-title">
                                <i class="fa-solid fa-chalkboard-user"></i> Workshop Details
                            </h4>
                            <span class="arm-count-badge" id="armWorkshopCount">0</span>
                        </div>
                        <div style="overflow-x: auto;">
                            <table class="arm-table">
                                <thead>
                                    <tr>
                                        <th class="arm-col-hash">#</th>
                                        <th>Workshop Name</th>
                                        <th style="width: 200px;">Time Duration</th>
                                        <th style="width: 160px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="armWorkshopTbody">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- 2. Synopsis Details --}}
                    <div class="arm-section">
                        <div class="arm-section-header">
                            <h4 class="arm-section-title">
                                <i class="fa-solid fa-file-waveform"></i> Synopsis Details
                            </h4>
                            <span class="arm-count-badge" id="armSynopsisCount">0</span>
                        </div>
                        <div style="overflow-x: auto;">
                            <table class="arm-table">
                                <thead>
                                    <tr>
                                        <th class="arm-col-hash">#</th>
                                        <th>Synopsis Name</th>
                                        <th style="width: 160px;">Status</th>
                                        <th style="width: 160px;">Status Date</th>
                                    </tr>
                                </thead>
                                <tbody id="armSynopsisTbody">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- 3. Dissertations Details --}}
                    <div class="arm-section">
                        <div class="arm-section-header">
                            <h4 class="arm-section-title">
                                <i class="fa-solid fa-book-open-reader"></i> Dissertations Details
                            </h4>
                            <span class="arm-count-badge" id="armDissertationCount">0</span>
                        </div>
                        <div style="overflow-x: auto;">
                            <table class="arm-table">
                                <thead>
                                    <tr>
                                        <th class="arm-col-hash">#</th>
                                        <th style="width: 220px;">Application Date</th>
                                        <th style="width: 160px;">Status</th>
                                        <th style="width: 160px;">Status Date</th>
                                    </tr>
                                </thead>
                                <tbody id="armDissertationTbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="arm-modal-footer">
                <button type="button" class="btn-arm-secondary" id="btnArmFooterClose">
                    Close
                </button>
            </div>
        </div>
    </div>

    <footer class="sup-footer">
        <p>Copyright &copy; {{ date('Y') }} {{ $tenant->name ?? 'CPSP' }}. All Rights Reserved</p>
    </footer>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Accordion toggle behavior for minus / plus
    var toggles = document.querySelectorAll('.sup-panel__toggle');
    toggles.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = this.getAttribute('data-target');
            var target = document.querySelector(targetId);
            var icon = this.querySelector('i');
            if (!target) return;

            if (target.style.display === 'none') {
                target.style.display = 'block';
                if (icon) {
                    icon.classList.remove('fa-plus');
                    icon.classList.add('fa-minus');
                }
            } else {
                target.style.display = 'none';
                if (icon) {
                    icon.classList.remove('fa-minus');
                    icon.classList.add('fa-plus');
                }
            }
        });
    });

    // Academic Review Modal Logic
    var armModal = document.getElementById('academicReviewModal');
    var armCloseBtn = document.getElementById('btnArmClose');
    var armFooterCloseBtn = document.getElementById('btnArmFooterClose');
    var armLoading = document.getElementById('armLoadingState');
    var armContent = document.getElementById('armContentState');

    function closeArmModal() {
        if (!armModal) return;
        armModal.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    if (armCloseBtn) armCloseBtn.addEventListener('click', closeArmModal);
    if (armFooterCloseBtn) armFooterCloseBtn.addEventListener('click', closeArmModal);
    if (armModal) {
        armModal.addEventListener('click', function (e) {
            if (e.target === armModal) closeArmModal();
        });
    }
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && armModal && armModal.classList.contains('is-open')) {
            closeArmModal();
        }
    });

    var academicButtons = document.querySelectorAll('.btn-academic-review-trigger');
    academicButtons.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var traineeId = this.getAttribute('data-trainee-id');
            var traineeName = this.getAttribute('data-trainee-name') || 'Trainee';
            var traineeUsername = this.getAttribute('data-trainee-username') || '—';

            document.getElementById('armTraineeName').textContent = traineeName;
            document.getElementById('armTraineeId').textContent = traineeUsername;
            var fullPageUrl = '{{ url("/supervisor/trainees") }}/' + traineeId + '/academic-review';
            document.getElementById('armFullPageBtn').setAttribute('href', fullPageUrl);

            // Open modal in loading state
            armLoading.style.display = 'flex';
            armContent.style.display = 'none';
            armModal.classList.add('is-open');
            document.body.style.overflow = 'hidden';

            fetch('{{ url("/supervisor/trainees") }}/' + traineeId + '/academic-review?format=json', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function (res) {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(function (data) {
                // Populate Workshops
                var wTbody = document.getElementById('armWorkshopTbody');
                var wCount = document.getElementById('armWorkshopCount');
                wTbody.innerHTML = '';
                var workshops = data.workshops || [];
                wCount.textContent = workshops.length;

                if (workshops.length === 0) {
                    wTbody.innerHTML = '<tr><td colspan="4" class="arm-empty-row">No workshop records found for this trainee. <a href="' + fullPageUrl + '" target="_blank" style="color:#0b6040;font-weight:600;margin-left:8px;text-decoration:underline;"><i class="fa-solid fa-plus"></i> Add Workshop</a></td></tr>';
                } else {
                    workshops.forEach(function (w) {
                        var st = (w.status || 'Completed').toLowerCase();
                        var badgeCls = (st.includes('completed') || st.includes('passed')) ? 'arm-status-completed' : (st.includes('progress') ? 'arm-status-in-progress' : 'arm-status-pending');
                        var tr = document.createElement('tr');
                        tr.innerHTML = '<td class="arm-col-hash">' + w.hash + '</td>' +
                            '<td><strong>' + escapeHtml(w.workshop_name) + '</strong></td>' +
                            '<td><i class="fa-regular fa-clock" style="color:#64748b;margin-right:4px;"></i> ' + escapeHtml(w.time_duration) + '</td>' +
                            '<td><span class="arm-status-badge ' + badgeCls + '"><i class="fa-solid fa-circle-check"></i> ' + escapeHtml(w.status) + '</span></td>';
                        wTbody.appendChild(tr);
                    });
                }

                // Populate Synopsis
                var sTbody = document.getElementById('armSynopsisTbody');
                var sCount = document.getElementById('armSynopsisCount');
                sTbody.innerHTML = '';
                var synopses = data.synopses || [];
                sCount.textContent = synopses.length;

                if (synopses.length === 0) {
                    sTbody.innerHTML = '<tr><td colspan="4" class="arm-empty-row">No synopsis records found for this trainee. <a href="' + fullPageUrl + '" target="_blank" style="color:#0b6040;font-weight:600;margin-left:8px;text-decoration:underline;"><i class="fa-solid fa-plus"></i> Add Synopsis</a></td></tr>';
                } else {
                    synopses.forEach(function (s) {
                        var st = (s.status || 'Approved').toLowerCase();
                        var badgeCls = (st.includes('approved') || st.includes('accepted')) ? 'arm-status-approved' : 'arm-status-under-review';
                        var tr = document.createElement('tr');
                        tr.innerHTML = '<td class="arm-col-hash">' + s.hash + '</td>' +
                            '<td><strong>' + escapeHtml(s.synopsis_name) + '</strong></td>' +
                            '<td><span class="arm-status-badge ' + badgeCls + '"><i class="fa-solid fa-stamp"></i> ' + escapeHtml(s.status) + '</span></td>' +
                            '<td><i class="fa-regular fa-calendar-days" style="color:#64748b;margin-right:4px;"></i> ' + escapeHtml(s.status_date) + '</td>';
                        sTbody.appendChild(tr);
                    });
                }

                // Populate Dissertations
                var dTbody = document.getElementById('armDissertationTbody');
                var dCount = document.getElementById('armDissertationCount');
                dTbody.innerHTML = '';
                var dissertations = data.dissertations || [];
                dCount.textContent = dissertations.length;

                if (dissertations.length === 0) {
                    dTbody.innerHTML = '<tr><td colspan="4" class="arm-empty-row">No dissertation records found for this trainee. <a href="' + fullPageUrl + '" target="_blank" style="color:#0b6040;font-weight:600;margin-left:8px;text-decoration:underline;"><i class="fa-solid fa-plus"></i> Add Dissertation</a></td></tr>';
                } else {
                    dissertations.forEach(function (d) {
                        var st = (d.status || 'Submitted').toLowerCase();
                        var badgeCls = (st.includes('approved') || st.includes('accepted')) ? 'arm-status-approved' : 'arm-status-submitted';
                        var tr = document.createElement('tr');
                        var titleHtml = d.dissertation_title ? '<div style="font-size:11.5px;color:#64748b;margin-top:2px;">' + escapeHtml(d.dissertation_title) + '</div>' : '';
                        tr.innerHTML = '<td class="arm-col-hash">' + d.hash + '</td>' +
                            '<td><i class="fa-regular fa-calendar-check" style="color:#64748b;margin-right:4px;"></i> <strong>' + escapeHtml(d.application_date) + '</strong>' + titleHtml + '</td>' +
                            '<td><span class="arm-status-badge ' + badgeCls + '"><i class="fa-solid fa-file-circle-check"></i> ' + escapeHtml(d.status) + '</span></td>' +
                            '<td><i class="fa-regular fa-calendar-days" style="color:#64748b;margin-right:4px;"></i> ' + escapeHtml(d.status_date) + '</td>';
                        dTbody.appendChild(tr);
                    });
                }

                armLoading.style.display = 'none';
                armContent.style.display = 'block';
            })
            .catch(function (err) {
                console.error(err);
                armLoading.innerHTML = '<div style="color:#dc2626;"><i class="fa-solid fa-triangle-exclamation" style="font-size:32px;display:block;margin-bottom:8px;"></i>Unable to load records. <a href="' + fullPageUrl + '" style="color:#0b6040;text-decoration:underline;">Click here to view page directly</a>.</div>';
            });
        });
    });

    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>
@endpush
@endsection
