@extends('layouts.app')
@section('title', 'Paper Published | Isra e-Logbook')
@section('group_published', 'is-active is-open')
@section('nav_published_list', 'is-active')
@section('main_class', 'elog-main--fluid')

@section('content')
<div class="elog-page-head">
    <h1 class="elog-page-title">Paper Published</h1>
    <a class="btn btn--add" href="{{ route('published.create', ['program' => request('program')]) }}">Add New</a>
</div>

<section class="elog-panel">
    <div class="elog-panel__head">
        <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-list"></i></span>
        <span class="elog-panel__head-title">Paper Published</span>
    </div>
    <div class="elog-panel__body">
        <div class="elog-table-wrap">
            <table class="elog-table">
                <thead>
                    <tr>
                        <th style="width: 48px;">#</th>
                        <th style="width: 130px;">Published Date</th>
                        <th>Title</th>
                        <th>Full Reference</th>
                        <th style="width: 130px;">Entry Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->pub_date ? $row->pub_date->format('d-m-Y') : '—' }}</td>
                        <td>{{ $row->pub_title }}</td>
                        <td>{{ $row->full_ref ?: ($row->pub_journal . ($row->pub_authors ? ' (' . $row->pub_authors . ')' : '')) }}</td>
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
                        <td colspan="5" class="elog-table__empty">No paper published entries found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
