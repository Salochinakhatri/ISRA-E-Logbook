@extends('layouts.app')

@section('title', ($tenant->name ?? 'e-Logbook') . ' - Supervisor Dashboard')
@section('nav_home', 'is-active')

@section('content')
<div class="supervisor-dash">
    {{-- Breadcrumb --}}
    <nav class="supervisor-dash__breadcrumb" aria-label="breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item separator">/</li>
            <li class="breadcrumb-item active" aria-current="page">e-Logbook Dashboard</li>
        </ol>
    </nav>

    {{-- Main Heading --}}
    <div class="supervisor-dash__header">
        <h1 class="supervisor-dash__title">{{ $tenant->name ?? 'CPSP e-Logbook' }}</h1>
        <span class="visually-hidden">CPSP e-Logbook</span>
        @if($stats['total_pending'] > 0)
            <div class="supervisor-dash__alert-badge">
                <i class="fa-solid fa-bell"></i>
                <span><strong>{{ $stats['total_pending'] }}</strong> {{ Str::plural('entry', $stats['total_pending']) }} awaiting your approval</span>
                <a href="{{ route('supervisor.entries') }}" class="btn-review-now">Review All</a>
            </div>
        @endif
    </div>

    {{-- 4 Stat Metric Cards (Exact colors and styling from CPSP e-Logbook) --}}
    <div class="supervisor-stats-grid">
        {{-- Card 1: Feedback about Trainee --}}
        <div class="sup-stat-card sup-stat-card--teal">
            <div class="sup-stat-card__body">
                <div class="sup-stat-card__icon">
                    <i class="fa-solid fa-thumbs-up"></i>
                </div>
                <div class="sup-stat-card__number">{{ $stats['feedback_trainee'] }}</div>
            </div>
            <div class="sup-stat-card__footer">
                FEEDBACK ABOUT TRAINEE
            </div>
        </div>

        {{-- Card 2: CME Credits --}}
        <div class="sup-stat-card sup-stat-card--orange">
            <div class="sup-stat-card__body">
                <div class="sup-stat-card__icon">
                    <i class="fa-solid fa-gauge-high"></i>
                </div>
                <div class="sup-stat-card__number">{{ $stats['cme_credits'] }}</div>
            </div>
            <div class="sup-stat-card__footer">
                CME CREDITS
            </div>
        </div>

        {{-- Card 3: Reports --}}
        <a href="{{ route('supervisor.entries', ['status' => 'all']) }}" class="sup-stat-card sup-stat-card--grey" style="text-decoration: none;">
            <div class="sup-stat-card__body">
                <div class="sup-stat-card__icon">
                    <i class="fa-solid fa-list-ul"></i>
                </div>
                <div class="sup-stat-card__number" style="font-size: 1.5rem; padding-top: 6px;">VIEW</div>
            </div>
            <div class="sup-stat-card__footer">
                REPORTS
            </div>
        </a>

        {{-- Card 4: Workshop Feedback --}}
        <div class="sup-stat-card sup-stat-card--blue">
            <div class="sup-stat-card__body">
                <div class="sup-stat-card__icon">
                    <i class="fa-solid fa-thumbs-up"></i>
                </div>
                <div class="sup-stat-card__number">{{ $stats['workshop_feedback'] }}</div>
            </div>
            <div class="sup-stat-card__footer">
                WORKSHOP FEEDBACK
            </div>
        </div>
    </div>

    {{-- Panel 1: Current Trainees - Awaiting Approval Entries --}}
    <div class="sup-panel" id="panelCurrentTrainees">
        <div class="sup-panel__header">
            <div class="sup-panel__title">
                <i class="fa-regular fa-circle-dot"></i>
                <span>Current Trainees - Awaiting Approval Entries</span>
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
                            <th style="width: 40px;">#</th>
                            <th>Name</th>
                            <th style="text-align: center;">Training</th>
                            <th style="text-align: center;">Rotational Training</th>
                            <th style="text-align: center;">Journal Club</th>
                            <th style="text-align: center;">Paper / Poster</th>
                            <th style="text-align: center;">Paper Published</th>
                            <th style="text-align: center;">Record of Training</th>
                            <th style="text-align: center;">Total</th>
                            <th style="text-align: center;">Workshops / Synopsis / Dissertation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($currentTrainees as $index => $trainee)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="sup-trainee-name">{{ $trainee['name'] }}</div>
                                <div class="sup-trainee-sub">ID: {{ $trainee['username'] }}</div>
                            </td>
                            <td style="text-align: center;">
                                @if($trainee['training'] > 0)
                                    <a href="{{ route('supervisor.entries', ['trainee_id' => $trainee['id'], 'type' => 'training', 'status' => 'Awaiting Approval']) }}" class="sup-count-badge sup-count-badge--warn" title="Click to review {{ $trainee['training'] }} training entries">
                                        {{ $trainee['training'] }}
                                    </a>
                                @else
                                    <span class="sup-count-zero">0</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($trainee['rotational'] > 0)
                                    <a href="{{ route('supervisor.entries', ['trainee_id' => $trainee['id'], 'type' => 'rotational', 'status' => 'Awaiting Approval']) }}" class="sup-count-badge sup-count-badge--warn" title="Click to review {{ $trainee['rotational'] }} rotational entries">
                                        {{ $trainee['rotational'] }}
                                    </a>
                                @else
                                    <span class="sup-count-zero">0</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($trainee['journal'] > 0)
                                    <a href="{{ route('supervisor.entries', ['trainee_id' => $trainee['id'], 'type' => 'journal', 'status' => 'Awaiting Approval']) }}" class="sup-count-badge sup-count-badge--warn" title="Click to review {{ $trainee['journal'] }} journal club entries">
                                        {{ $trainee['journal'] }}
                                    </a>
                                @else
                                    <span class="sup-count-zero">0</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($trainee['presented'] > 0)
                                    <a href="{{ route('supervisor.entries', ['trainee_id' => $trainee['id'], 'type' => 'presented', 'status' => 'Awaiting Approval']) }}" class="sup-count-badge sup-count-badge--warn" title="Click to review {{ $trainee['presented'] }} presented paper entries">
                                        {{ $trainee['presented'] }}
                                    </a>
                                @else
                                    <span class="sup-count-zero">0</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($trainee['published'] > 0)
                                    <a href="{{ route('supervisor.entries', ['trainee_id' => $trainee['id'], 'type' => 'published', 'status' => 'Awaiting Approval']) }}" class="sup-count-badge sup-count-badge--warn" title="Click to review {{ $trainee['published'] }} published paper entries">
                                        {{ $trainee['published'] }}
                                    </a>
                                @else
                                    <span class="sup-count-zero">0</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <span class="sup-count-zero">0</span>
                            </td>
                            <td style="text-align: center;">
                                @if($trainee['total'] > 0)
                                    <a href="{{ route('supervisor.entries', ['trainee_id' => $trainee['id'], 'status' => 'Awaiting Approval']) }}" class="sup-count-badge sup-count-badge--total" title="Review all {{ $trainee['total'] }} awaiting entries">
                                        {{ $trainee['total'] }}
                                    </a>
                                @else
                                    <span class="sup-count-zero">0</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group-action">
                                    <a href="{{ route('supervisor.entries', ['trainee_id' => $trainee['id']]) }}" class="btn-sup-action" title="View all entries by this trainee">
                                        <i class="fa-solid fa-list-check"></i> Review ({{ $trainee['total'] }})
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="sup-table__empty">No trainees found for this department.</td>
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
                <i class="fa-regular fa-circle-dot"></i>
                <span>Rotational Trainees - Awaiting Approval Entries</span>
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
                            <th style="width: 40px;">#</th>
                            <th>Name</th>
                            <th>ID / Username</th>
                            <th>RTMC No</th>
                            <th>Rotation Speciality</th>
                            <th>Rotation Time Period</th>
                            <th style="text-align: center;">Entries</th>
                            <th style="text-align: center;">Workshops / Synopsis / Dissertation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rotationalTrainees as $idx => $rt)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td><strong>{{ $rt['name'] }}</strong></td>
                            <td>{{ $rt['username'] }}</td>
                            <td>RTMC-{{ $rt['id'] }}</td>
                            <td>General Surgery / Medicine</td>
                            <td>Current Rotation</td>
                            <td style="text-align: center;">
                                <a href="{{ route('supervisor.entries', ['trainee_id' => $rt['id'], 'type' => 'rotational', 'status' => 'Awaiting Approval']) }}" class="sup-count-badge sup-count-badge--warn">
                                    {{ $rt['rotational'] }}
                                </a>
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('supervisor.entries', ['trainee_id' => $rt['id'], 'type' => 'rotational']) }}" class="btn-sup-action">
                                    <i class="fa-solid fa-eye"></i> View Entries
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="sup-table__empty">No rotational trainees currently awaiting approval.</td>
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
                <i class="fa-regular fa-circle-dot"></i>
                <span>Previous Trainees - Awaiting Approval Entries</span>
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
                            <th style="width: 40px;">#</th>
                            <th>Name</th>
                            <th style="text-align: center;">Training</th>
                            <th style="text-align: center;">Rotational Training</th>
                            <th style="text-align: center;">Journal Club</th>
                            <th style="text-align: center;">Paper / Poster</th>
                            <th style="text-align: center;">Paper Published</th>
                            <th style="text-align: center;">Record of Training</th>
                            <th style="text-align: center;">Total</th>
                            <th style="text-align: center;">Workshops / Synopsis / Dissertation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="10" class="sup-table__empty">No previous trainees awaiting approval entries.</td>
                        </tr>
                    </tbody>
                </table>
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
});
</script>
@endpush
@endsection
