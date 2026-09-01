@extends('layouts.app')
@section('title', 'Journal Club | Isra e-Logbook')
@section('group_journal', 'is-active is-open')
@section('nav_journal_list', 'is-active')
@section('main_class', 'elog-main--fluid')

@section('content')
<div class="elog-page-head">
    <h1 class="elog-page-title">Journal Club</h1>
    <a class="btn btn--add" href="{{ route('journal.create', ['program' => request('program')]) }}">Add New</a>
</div>

<section class="elog-panel">
    <div class="elog-panel__head">
        <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-list"></i></span>
        <span class="elog-panel__head-title">Journal Club</span>
    </div>
    <div class="elog-panel__body">
        <div class="elog-table-wrap">
            <table class="elog-table">
                <thead>
                    <tr>
                        <th style="width: 48px;">#</th>
                        <th style="width: 140px;">Date of Discussion</th>
                        <th style="width: 160px;">Facilitated By</th>
                        <th>Reference Of The Article Discussed</th>
                        <th style="width: 130px;">Entry Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->date_of_diss ? $row->date_of_diss->format('d-m-Y') : '—' }}</td>
                        <td>{{ $row->fac_by }}</td>
                        <td>{{ Str::limit($row->ref_of_art_disc ?: ($row->topic . ($row->ref_article ? ' - ' . $row->ref_article : '')), 120) }}</td>
                        <td>
                            <div class="cell-status">
                                <div>Add: {{ $row->created_at?->format('d-m-Y') }}</div>
                                <span class="badge badge--{{ $row->entry_status === 'Approved' ? 'ok' : ($row->entry_status === 'Draft' ? 'muted' : 'warn') }}">
                                    {{ $row->entry_status ?: 'Draft' }}
                                </span>
                                @if($row->approved_at)
                                    <div>Approved: {{ $row->approved_at->format('d-m-Y') }}</div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="elog-table__empty">No journal club entries found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
