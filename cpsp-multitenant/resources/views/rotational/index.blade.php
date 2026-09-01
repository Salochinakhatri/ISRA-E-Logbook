@extends('layouts.app')
@section('title', 'Rotational Training | Isra e-Logbook')
@section('group_rotational', 'is-active is-open')
@section('nav_rotational_list', 'is-active')
@section('main_class', 'elog-main--fluid')

@section('content')
<div class="elog-page-head">
    <h1 class="elog-page-title">Rotational Training</h1>
    <a class="btn btn--add" href="{{ route('rotational.create', ['program' => request('program')]) }}">Add New</a>
</div>

<section class="elog-panel">
    <div class="elog-panel__head">
        <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-list"></i></span>
        <span class="elog-panel__head-title">Rotational Training</span>
    </div>
    <div class="elog-panel__body">
        <form class="training-filters" method="get" action="{{ route('rotational.index') }}">
            @if(request('program'))
                <input type="hidden" name="program" value="{{ request('program') }}">
            @endif

            <div class="training-filters__grid rot-filters__row1">
                <div class="field">
                    <select class="field__control" name="f_status" id="rf_status">
                        @foreach([''=>'All Entry Status','Draft'=>'Draft','Approved'=>'Approved','Awaiting Approval'=>'Awaiting Approval','Discuss and Resubmit'=>'Discuss and Resubmit'] as $val => $lab)
                            <option value="{{ $val }}" {{ ($filters['status'] ?? '') === (string)$val ? 'selected' : '' }}>{{ $lab }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <input class="field__control dateBritish" type="text" name="f_post_from" id="rf_post_from" placeholder="Post Date From" value="{{ $filters['post_from'] ?? '' }}">
                </div>
                <div class="field">
                    <input class="field__control dateBritish" type="text" name="f_post_to" id="rf_post_to" placeholder="Post Date To" value="{{ $filters['post_to'] ?? '' }}">
                </div>
            </div>

            <div class="training-filters__grid rot-filters__row2">
                <div class="field">
                    <select class="field__control" name="f_level" id="rf_level">
                        <option value="">Level</option>
                        @foreach(['1'=>'Observer Status','2'=>'Assistant Status','3'=>'Performed under direct Supervision','4'=>'Performed under indirect supervision','5'=>'Performed independently','5555'=>'Other'] as $val => $lab)
                            <option value="{{ $val }}" {{ ($filters['level'] ?? '') === (string)$val ? 'selected' : '' }}>{{ $lab }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <input class="field__control dateBritish" type="text" name="f_adm_from" id="rf_adm_from" placeholder="Admission Date From" value="{{ $filters['adm_from'] ?? '' }}">
                </div>
                <div class="field">
                    <input class="field__control dateBritish" type="text" name="f_adm_to" id="rf_adm_to" placeholder="Admission Date To" value="{{ $filters['adm_to'] ?? '' }}">
                </div>
                <div class="field">
                    <input class="field__control" type="text" name="f_reg" id="rf_reg" placeholder="Hospt Reg No" value="{{ $filters['reg'] ?? '' }}">
                </div>
            </div>

            <div class="training-filters__actions">
                <button type="submit" class="btn btn--grey"><i class="fa-solid fa-magnifying-glass"></i> Filter</button>
                <a class="btn btn--grey" href="{{ route('rotational.index', ['program' => request('program')]) }}">
                    <i class="fa-solid fa-list"></i> Show all
                </a>

                <div class="training-filters__notice">
                    @if($lastEntryLabel)
                        <p class="training-filters__notice-line rot-notice--date">
                            Last entry add date (Training or rotation training): <strong>{{ $lastEntryLabel }}</strong>
                        </p>
                    @else
                        <p class="training-filters__notice-line rot-notice--date">
                            Last entry add date (Training or rotation training): <em>no entries yet</em>
                        </p>
                    @endif
                    <p class="training-filters__notice-warn rot-notice--warn">
                        If no new entry (Training or rotation training) is added within 90 days,
                        the e-logbook will be locked for further entries.
                        This lock is not affected by the date the entry is sent to the supervisor.
                    </p>
                </div>
            </div>
        </form>

        <div class="elog-table-wrap">
            <table class="elog-table">
                <thead>
                    <tr>
                        <th style="width: 48px;">#</th>
                        <th>Admit Date / Hpt. Reg No</th>
                        <th>Level</th>
                        <th>Outcome</th>
                        <th>Entry Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <div class="cell-admit">{{ $row->date_of_admission ? $row->date_of_admission->format('d-m-Y') : '—' }}</div>
                            <div class="cell-reg">{{ $row->hospt_reg_no }}</div>
                        </td>
                        <td>{{ ['1'=>'Observer Status','2'=>'Assistant Status','3'=>'Performed under direct Supervision','4'=>'Performed under indirect supervision','5'=>'Performed independently','5555'=>'Other'][$row->level_id] ?? $row->level_id }}</td>
                        <td>{{ ['2'=>'Admitted to inpatient facility','3'=>'Treated and called for follow-up','4'=>'Referred to other specialty unit','5'=>'Death of the patient','7'=>'Improved','8'=>'Discharged','9'=>'Treated','10'=>'Under Treatment','11'=>'Treatment Failure','12'=>'Follow Up','6'=>'Other'][$row->outcome_id] ?? $row->outcome_id }}</td>
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
                        <td colspan="5" class="elog-table__empty">No rotational training entries found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
