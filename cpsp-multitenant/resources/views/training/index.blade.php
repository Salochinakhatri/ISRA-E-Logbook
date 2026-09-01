@extends('layouts.app')
@section('title', 'Training | Isra e-Logbook')
@section('group_training', 'is-active is-open')
@section('nav_training_list', 'is-active')
@section('main_class', 'elog-main--fluid')

@section('content')
@include('partials.program-badge', ['program' => request('program', $program ?? '')])
<div class="elog-page-head">
    <h1 class="elog-page-title">Training</h1>
    <a class="btn btn--add" href="{{ route('training.create', ['program' => request('program')]) }}">Add New</a>
</div>

<section class="elog-panel">
    <div class="elog-panel__head">
        <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-list"></i></span>
        <span class="elog-panel__head-title">Training</span>
    </div>
    <div class="elog-panel__body">
        <form class="training-filters" method="get" action="{{ route('training.index') }}">
            @if(request('program'))
                <input type="hidden" name="program" value="{{ request('program') }}">
            @endif

            <div class="training-filters__grid">
                <div class="field">
                    <label class="field__label" for="f_status">Entry Status</label>
                    <select class="field__control" name="f_status" id="f_status">
                        @foreach([''=>'All Entry Status','Draft'=>'Draft','Approved'=>'Approved','Awaiting Approval'=>'Awaiting Approval','Discuss and Resubmit'=>'Discuss and Resubmit'] as $val => $lab)
                            <option value="{{ $val }}" {{ ($filters['status'] ?? '') === (string)$val ? 'selected' : '' }}>{{ $lab }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="f_level">Level</label>
                    <select class="field__control" name="f_level" id="f_level">
                        <option value="">Level</option>
                        @foreach(['1'=>'Observer Status','2'=>'Assistant Status','3'=>'Performed under direct Supervision','4'=>'Performed under indirect supervision','5'=>'Performed independently','5555'=>'Other'] as $val => $lab)
                            <option value="{{ $val }}" {{ ($filters['level'] ?? '') === (string)$val ? 'selected' : '' }}>{{ $lab }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="f_post_from">Post Date From</label>
                    <input class="field__control dateBritish" type="text" name="f_post_from" id="f_post_from" placeholder="dd-mm-yyyy" value="{{ $filters['post_from'] ?? '' }}">
                </div>
                <div class="field">
                    <label class="field__label" for="f_post_to">Post Date To</label>
                    <input class="field__control dateBritish" type="text" name="f_post_to" id="f_post_to" placeholder="dd-mm-yyyy" value="{{ $filters['post_to'] ?? '' }}">
                </div>
                <div class="field">
                    <label class="field__label" for="f_adm_from">Admission Date From</label>
                    <input class="field__control dateBritish" type="text" name="f_adm_from" id="f_adm_from" placeholder="dd-mm-yyyy" value="{{ $filters['adm_from'] ?? '' }}">
                </div>
                <div class="field">
                    <label class="field__label" for="f_adm_to">Admission Date To</label>
                    <input class="field__control dateBritish" type="text" name="f_adm_to" id="f_adm_to" placeholder="dd-mm-yyyy" value="{{ $filters['adm_to'] ?? '' }}">
                </div>
                <div class="field">
                    <label class="field__label" for="f_reg">Hospt Reg No</label>
                    <input class="field__control" type="text" name="f_reg" id="f_reg" placeholder="Hospt Reg No" value="{{ $filters['reg'] ?? '' }}">
                </div>
            </div>

            <div class="training-filters__actions">
                <button class="btn btn--search" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                <a class="btn btn--clear" href="{{ route('training.index', ['program' => request('program')]) }}">Clear</a>
                @if($lastEntryLabel)
                    <span class="training-filters__notice">Last entry added on: <strong>{{ $lastEntryLabel }}</strong></span>
                @endif
            </div>
        </form>

        <div class="elog-table-wrap">
            <table class="elog-table">
                <thead>
                    <tr>
                        <th style="width: 52px;">#</th>
                        <th>Admit Date / Hpt. Reg No</th>
                        <th>Diagnosis</th>
                        <th>Brief Description</th>
                        <th>Competency Group/Details</th>
                        <th>Level</th>
                        <th>Entry Status</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="cell-admit">{{ $row->date_of_admission ? $row->date_of_admission->format('d-m-Y') : '—' }}</div>
                            <div class="cell-reg">{{ $row->hospt_reg_no }}</div>
                        </td>
                        <td>{{ $row->pt_diagnosis }}</td>
                        <td><div class="cell-brief">{{ Str::limit(strip_tags($row->brief_desc), 100) }}</div></td>
                        <td class="cell-comp">{!! app(\App\Services\CompetencyService::class)->formatCell($row->com_ids, $row->com_detail_ids, $compMap) !!}</td>
                        <td>{{ ['1'=>'Observer','2'=>'Assistant','3'=>'Direct','4'=>'Indirect','5'=>'Independent','5555'=>'Other'][$row->level_id] ?? $row->level_id }}</td>
                        <td>
                            <div class="cell-status">
                                <div>Add: {{ $row->created_at?->format('d-m-Y') }}</div>
                                @php
                                    $sBadge = match($row->entry_status) {
                                        'Approved' => 'ok',
                                        'Awaiting Approval' => 'warn',
                                        'Discuss and Resubmit' => 'info',
                                        'Disapproved' => 'danger',
                                        default => 'muted',
                                    };
                                @endphp
                                <span class="badge badge--{{ $sBadge }}">{{ $row->entry_status ?: 'Draft' }}</span>
                                @if($row->approved_at)
                                    <div>Appr: {{ $row->approved_at->format('d-m-Y') }}</div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <a class="btn btn--view" href="{{ route('training.show', $row->id) }}" title="View"><i class="fa-solid fa-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="elog-table__empty">No entries found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
