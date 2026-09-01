@extends('layouts.app')
@section('title', 'View Training Entry | Isra e-Logbook')
@section('group_training', 'is-active is-open')
@section('main_class', 'elog-main--fluid')

@section('content')
<div class="elog-page-head">
    <h1 class="elog-page-title">View Training Entry</h1>
    <a class="btn btn--add" href="{{ route('training.index', ['program' => request('program')]) }}">List All</a>
</div>

<div class="elog-view">
    <div class="elog-view__grid">
        <div><span class="kv-label">Hospital Reg. No</span><span class="kv-val">{{ $entry->hospt_reg_no }}</span></div>
        <div><span class="kv-label">Admission</span><span class="kv-val">{{ $entry->date_of_admission ? $entry->date_of_admission->format('d-m-Y') : '—' }}</span></div>
        <div><span class="kv-label">Gender / Age</span><span class="kv-val">{{ $entry->pt_gender }} / {{ $entry->pt_age }} {{ $entry->pt_age_type }}</span></div>
        <div><span class="kv-label">Diagnosis</span><span class="kv-val">{{ $entry->pt_diagnosis }}</span></div>
        <div><span class="kv-label">Level</span><span class="kv-val">{{ ['1'=>'Observer Status','2'=>'Assistant Status','3'=>'Performed under direct Supervision','4'=>'Performed under indirect supervision','5'=>'Performed independently','5555'=>'Other'][$entry->level_id] ?? $entry->level_id }}</span></div>
        <div>
            <span class="kv-label">Status</span>
            <span class="kv-val">
                @php
                    $sClass = match($entry->entry_status) {
                        'Approved' => 'badge--ok',
                        'Awaiting Approval' => 'badge--warn',
                        'Discuss and Resubmit' => 'badge--info',
                        'Disapproved' => 'badge--danger',
                        default => 'badge--muted',
                    };
                @endphp
                <span class="badge {{ $sClass }}">{{ $entry->entry_status ?: 'Draft' }}</span>
                @if($entry->approved_at)
                    <small style="color: #666; display: block;">Approved on {{ $entry->approved_at->format('d-m-Y H:i') }}</small>
                @endif
            </span>
        </div>
    </div>

    @if($entry->supervisor_remarks)
    <h2 class="elog-view__h" style="color: #856404; margin-top: 15px;"><i class="fa-solid fa-comment-dots"></i> Supervisor Remarks</h2>
    <div class="elog-view__box" style="background: #fff3cd; border-color: #ffeeba; color: #856404;">
        {!! nl2br(e($entry->supervisor_remarks)) !!}
    </div>
    @endif

    <h2 class="elog-view__h">Brief description</h2>
    <div class="elog-view__box">{!! nl2br(e(strip_tags($entry->brief_desc))) !!}</div>
    <h2 class="elog-view__h">Competency</h2>
    <div class="elog-view__box">{!! app(\App\Services\CompetencyService::class)->formatCell($entry->com_ids, $entry->com_detail_ids, $compMap) !!}</div>

    @if(session('user_type') !== 'Trainee')
    <div style="margin-top: 25px; padding: 15px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; display: flex; align-items: center; justify-content: space-between; flex-wrap: gap: 10px;">
        <span style="font-weight: 600; color: #333;">Supervisor Actions for this Entry:</span>
        <div style="display: flex; gap: 8px;">
            <form action="{{ route('supervisor.entry.status', ['type' => 'training', 'id' => $entry->id]) }}" method="post" style="display: inline;">
                @csrf
                <input type="hidden" name="status" value="Approved">
                <button type="submit" class="btn btn--submit" style="padding: 6px 14px; font-size: 13px;"><i class="fa-solid fa-check"></i> Approve Entry</button>
            </form>
            <form action="{{ route('supervisor.entry.status', ['type' => 'training', 'id' => $entry->id]) }}" method="post" style="display: inline;">
                @csrf
                <input type="hidden" name="status" value="Awaiting Approval">
                <button type="submit" class="btn" style="background: #f0ad4e; color: #fff; padding: 6px 14px; font-size: 13px;"><i class="fa-solid fa-clock"></i> Set Pending</button>
            </form>
            <a href="{{ route('supervisor.entries', ['trainee_id' => $entry->user_id]) }}" class="btn btn--outline" style="padding: 6px 14px; font-size: 13px; text-decoration: none;">View Trainee Entries</a>
        </div>
    </div>
    @endif
</div>
@endsection
