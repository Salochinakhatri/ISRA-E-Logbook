@extends('layouts.app')

@section('title', 'Home | Isra e-Logbook')
@section('nav_home', 'is-active')

@section('content')
    {{-- Clean & Simple Home Dashboard for both Gynae and Internal Medicine --}}
    <section class="elog-panel">
        <div class="elog-panel__head">
            <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-house"></i></span>
            <span class="elog-panel__head-title">Home</span>
        </div>
        <div class="elog-panel__body">
            <p class="home-lead">Welcome, <strong>{{ session('username') }}</strong>.</p>
            <p class="home-text">Use the links below or the sidebar to add and manage your logbook entries.</p>
            <dl class="home-meta">
                <div>
                    <dt>User type</dt>
                    <dd>{{ session('user_type') ?? ($user->userType->name ?? 'Trainee') }}</dd>
                </div>
                @if(!empty($lastEntryLabel))
                    <div>
                        <dt>Last entry</dt>
                        <dd>{{ $lastEntryLabel }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </section>

    {{-- Dynamic Summary Statistics --}}
    <section class="elog-panel">
        <div class="elog-panel__head">
            <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-regular fa-circle-dot"></i></span>
            <span class="elog-panel__head-title">Summary</span>
        </div>
        <div class="elog-panel__body">
            <div class="elog-table-wrap">
                <table class="elog-table">
                    <thead>
                        <tr>
                            <th>Logbook Section</th>
                            <th style="text-align: center; width: 140px;">Entries Count</th>
                            <th style="text-align: center; width: 160px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <a href="{{ route('training.index', request('program') ? ['program' => request('program')] : []) }}" style="text-decoration: none; color: inherit; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <i class="fa-solid fa-table-cells" style="color: #2f8f56;"></i> Training
                                </a>
                            </td>
                            <td style="text-align: center; font-weight: bold; font-size: 1.1rem; color: #2f8f56;">{{ $counts['training'] ?? 0 }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('training.create', request('program') ? ['program' => request('program')] : []) }}" class="btn btn--add" style="padding: 0.25rem 0.65rem; font-size: 0.8rem; text-decoration: none;">+ Add</a>
                                <a href="{{ route('training.index', request('program') ? ['program' => request('program')] : []) }}" class="btn btn--blue" style="padding: 0.25rem 0.65rem; font-size: 0.8rem; text-decoration: none;">View</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="{{ route('rotational.index', request('program') ? ['program' => request('program')] : []) }}" style="text-decoration: none; color: inherit; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <i class="fa-solid fa-table-cells" style="color: #2f8f56;"></i> Rotational Training
                                </a>
                            </td>
                            <td style="text-align: center; font-weight: bold; font-size: 1.1rem; color: #2f8f56;">{{ $counts['rotational'] ?? 0 }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('rotational.create', request('program') ? ['program' => request('program')] : []) }}" class="btn btn--add" style="padding: 0.25rem 0.65rem; font-size: 0.8rem; text-decoration: none;">+ Add</a>
                                <a href="{{ route('rotational.index', request('program') ? ['program' => request('program')] : []) }}" class="btn btn--blue" style="padding: 0.25rem 0.65rem; font-size: 0.8rem; text-decoration: none;">View</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="{{ route('journal.index', request('program') ? ['program' => request('program')] : []) }}" style="text-decoration: none; color: inherit; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <i class="fa-solid fa-table-cells" style="color: #2f8f56;"></i> Journal Club
                                </a>
                            </td>
                            <td style="text-align: center; font-weight: bold; font-size: 1.1rem; color: #2f8f56;">{{ $counts['journal'] ?? 0 }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('journal.create', request('program') ? ['program' => request('program')] : []) }}" class="btn btn--add" style="padding: 0.25rem 0.65rem; font-size: 0.8rem; text-decoration: none;">+ Add</a>
                                <a href="{{ route('journal.index', request('program') ? ['program' => request('program')] : []) }}" class="btn btn--blue" style="padding: 0.25rem 0.65rem; font-size: 0.8rem; text-decoration: none;">View</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="{{ route('presented.index', request('program') ? ['program' => request('program')] : []) }}" style="text-decoration: none; color: inherit; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <i class="fa-solid fa-table-cells" style="color: #2f8f56;"></i> Paper/Poster Presented
                                </a>
                            </td>
                            <td style="text-align: center; font-weight: bold; font-size: 1.1rem; color: #2f8f56;">{{ $counts['presented'] ?? 0 }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('presented.create', request('program') ? ['program' => request('program')] : []) }}" class="btn btn--add" style="padding: 0.25rem 0.65rem; font-size: 0.8rem; text-decoration: none;">+ Add</a>
                                <a href="{{ route('presented.index', request('program') ? ['program' => request('program')] : []) }}" class="btn btn--blue" style="padding: 0.25rem 0.65rem; font-size: 0.8rem; text-decoration: none;">View</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="{{ route('published.index', request('program') ? ['program' => request('program')] : []) }}" style="text-decoration: none; color: inherit; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <i class="fa-solid fa-table-cells" style="color: #2f8f56;"></i> Paper Published
                                </a>
                            </td>
                            <td style="text-align: center; font-weight: bold; font-size: 1.1rem; color: #2f8f56;">{{ $counts['published'] ?? 0 }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('published.create', request('program') ? ['program' => request('program')] : []) }}" class="btn btn--add" style="padding: 0.25rem 0.65rem; font-size: 0.8rem; text-decoration: none;">+ Add</a>
                                <a href="{{ route('published.index', request('program') ? ['program' => request('program')] : []) }}" class="btn btn--blue" style="padding: 0.25rem 0.65rem; font-size: 0.8rem; text-decoration: none;">View</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="{{ route('suggestions.index', request('program') ? ['program' => request('program')] : []) }}" style="text-decoration: none; color: inherit; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <i class="fa-solid fa-lightbulb" style="color: #2f8f56;"></i> Suggestions
                                </a>
                            </td>
                            <td style="text-align: center; font-weight: bold; font-size: 1.1rem; color: #2f8f56;">{{ $counts['suggestions'] ?? 0 }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('suggestions.index', request('program') ? ['program' => request('program')] : []) }}" class="btn btn--blue" style="padding: 0.25rem 0.65rem; font-size: 0.8rem; text-decoration: none;">View</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Dynamic Recent Activities across All Logbook Sections --}}
    <section class="elog-panel">
        <div class="elog-panel__head">
            <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-clock-rotate-left"></i></span>
            <span class="elog-panel__head-title">Recent Activities</span>
        </div>
        <div class="elog-panel__body">
            @if(empty($recentActivities) || count($recentActivities) === 0)
                <p class="home-text">No recent activity yet. When you add entries, they will show here automatically.</p>
            @else
                <ul class="activity-list" style="list-style: none; padding: 0; margin: 0;">
                    @foreach($recentActivities as $act)
                        <li class="activity-item" style="display: flex; align-items: flex-start; gap: 0.85rem; padding: 0.75rem 0; border-bottom: 1px solid #f0f0f0;">
                            <div class="activity-item__icon" style="color: #2f8f56; font-size: 1.2rem; margin-top: 2px;">
                                <i class="{{ $act['icon'] }}"></i>
                            </div>
                            <div class="activity-item__content" style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                    <span style="font-size: 0.75rem; font-weight: 600; padding: 0.15rem 0.45rem; border-radius: 4px; background: {{ $act['badge_bg'] }}; color: {{ $act['badge_text'] }};">
                                        {{ $act['section'] }}
                                    </span>
                                    <a href="{{ $act['url'] }}" style="text-decoration: none; color: #333; font-weight: 600; font-size: 0.95rem;">
                                        {{ $act['title'] }}
                                    </a>
                                </div>
                                <p class="activity-item__meta" style="margin: 3px 0 0; font-size: 0.85rem; color: #777;">
                                    {{ $act['meta'] }}
                                </p>
                            </div>
                            <div class="activity-item__date" style="font-size: 0.8rem; color: #888; white-space: nowrap;">
                                {{ $act['created_at']?->format('d-M-Y H:i') ?? '—' }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>
@endsection
