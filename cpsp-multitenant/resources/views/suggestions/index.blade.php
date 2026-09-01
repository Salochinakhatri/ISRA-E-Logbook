@extends('layouts.app')
@section('title', 'Suggestions | Isra e-Logbook')
@section('group_suggestions', 'is-active is-open')
@section('nav_suggestions_list', 'is-active')
@section('main_class', 'elog-main--fluid')

@section('content')
<div class="elog-page-head">
    <h1 class="elog-page-title">Suggestions</h1>
</div>

<section class="elog-panel">
    <div class="elog-panel__head">
        <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-lightbulb"></i></span>
        <span class="elog-panel__head-title">Submit a Suggestion</span>
    </div>
    <div class="elog-panel__body">
        <form class="form-horizontal elog-form" action="{{ route('suggestions.store') }}" method="post" id="suggestionsForm" novalidate>
            @csrf
            @if(request('program'))
                <input type="hidden" name="program" value="{{ request('program') }}">
            @endif

            <div class="row elog-form__row elog-form__row--1">
                <div class="col-md-12">
                    <label class="control-label" for="suggestion_text">Your Suggestion / Feedback <span class="vd_red">*</span></label>
                    <div class="controls">
                        <textarea id="suggestion_text" name="suggestion_text" class="field__control" rows="6" placeholder="Type your feedback here..." required></textarea>
                    </div>
                </div>
            </div>

            <div class="form-actions-condensed row elog-form__row elog-form__row--1 mgbt-xs-0">
                <div class="col-sm-12">
                    <button class="btn btn--submit" type="submit" id="suggestions_submit">Submit Suggestion</button>
                </div>
            </div>
        </form>
    </div>
</section>

<section class="elog-panel" style="margin-top: 1.5rem;">
    <div class="elog-panel__head">
        <span class="elog-panel__head-icon" aria-hidden="true"><i class="fa-solid fa-list"></i></span>
        <span class="elog-panel__head-title">Previous Suggestions</span>
    </div>
    <div class="elog-panel__body">
        <div class="elog-table-wrap">
            <table class="elog-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Suggestion / Feedback</th>
                        <th style="width: 140px;">Submitted On</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suggestions as $i => $s)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{!! nl2br(e($s->suggestion_text)) !!}</td>
                        <td>{{ $s->created_at?->format('d-m-Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="elog-table__empty">No suggestions submitted yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
