@extends('layouts.app')
@section('title', 'Paper/Poster Presented | Isra e-Logbook')
@section('group_presented', 'is-active is-open')
@section('nav_presented_list', 'is-active')
@section('main_class', 'elog-main--fluid')

@section('content')
<div class="elog-page-head">
    <h1 class="elog-page-title">Paper/Poster Presented</h1>
    <a class="btn btn--add" href="{{ route('presented.create', ['program' => request('program')]) }}">Add New</a>
</div>

<section class="elog-panel">
    <div class="elog-panel__head">
        <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-list"></i></span>
        <span class="elog-panel__head-title">Paper/Poster Presented</span>
    </div>
    <div class="elog-panel__body">
        <div class="elog-table-wrap">
            <table class="elog-table">
                <thead>
                    <tr>
                        <th style="width: 48px;">#</th>
                        <th style="width: 130px;">Presented Date</th>
                        <th>Title</th>
                        <th>Name Of Conference / Seminar</th>
                        <th>Venue</th>
                        <th style="width: 130px;">Entry Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->rec_date ? $row->rec_date->format('d-m-Y') : '—' }}</td>
                        <td>{{ $row->rec_title }}</td>
                        <td>{{ $row->conf_name ?: $row->rec_type }}</td>
                        <td>{{ $row->rec_venue }}</td>
                        <td>
                            <div class="cell-status">
                                <div>Add: {{ $row->created_at?->format('d-m-Y') }}</div>
                                <span class="badge badge--{{ $row->entry_status === 'Approved' ? 'ok' : ($row->entry_status === 'Draft' ? 'muted' : 'warn') }}">
                                    {{ $row->entry_status ?: 'Draft' }}
                                </span>
                                @if($row->approved_at)
                                    <div>Approved: {{ $row->approved_at->format('d-m-Y') }}</div>
                                @endif
                                @if(!empty($row->supervisor_remarks))
                                    <div style="margin-top: 6px; padding: 6px 8px; background: #fffbeb; border: 1px solid #fde68a; border-left: 3px solid #d97706; border-radius: 5px; font-size: 11.5px; color: #78350f; line-height: 1.35; text-align: left;">
                                        <div style="font-weight: 700; color: #b45309; margin-bottom: 2px;">
                                            <i class="fa-solid fa-comment-dots"></i> Supervisor Remarks:
                                        </div>
                                        <div>{{ $row->supervisor_remarks }}</div>
                                    </div>
                                @endif
                                @if(in_array($row->entry_status ?: 'Draft', ['Draft', 'Discuss and Resubmit'], true))
                                    <div style="margin-top: 6px;">
                                        <a href="{{ route('presented.edit', ['id' => $row->id, 'program' => request('program', $row->program)]) }}" class="btn btn--blue" style="padding: 3px 10px; font-size: 11.5px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fa-solid fa-pen-to-square"></i> {{ $row->entry_status === 'Discuss and Resubmit' ? 'Edit & Resubmit' : 'Edit' }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="elog-table__empty">No paper/poster presented entries found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
