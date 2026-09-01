@extends('layouts.app')
@section('title', 'Add Journal Club Entry | Isra e-Logbook')
@section('group_journal', 'is-active is-open')
@section('nav_journal_add', 'is-active')
@section('main_class', 'elog-main--fluid')

@section('content')
<div class="elog-page-head">
    <h1 class="elog-page-title">Add Journal Club Entry</h1>
    <a class="btn btn--blue" href="{{ route('journal.index', ['program' => request('program')]) }}">List All</a>
</div>

@if($formErrors)
<div class="alert alert-danger elog-alert-errors" role="alert">
    <strong>Please fix the following errors:</strong>
    <ul class="elog-error-list">
        @foreach($formErrors as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="vd_content-section elog-content-section clearfix">
    <form class="form-horizontal elog-form" action="{{ route('journal.store') }}" method="post" name="mform" id="mform_journal" novalidate>
        @csrf
        @if(request('program'))
            <input type="hidden" name="program" value="{{ request('program') }}">
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="panel widget elog-panel elog-panel--form">
                    <div class="panel-heading vd_bg-grey elog-panel__head">
                        <h3 class="panel-title">
                            <span class="menu-icon"><i class="fa-solid fa-chart-bar"></i></span>
                            Entry Details
                        </h3>
                    </div>
                    <div class="panel-body">
                        <!-- Row 1: Date of Discussion + Facilitated by -->
                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label" for="date_of_diss">
                                    Date of Discussion <span class="vd_red">*</span>
                                </label>
                                <div class="controls">
                                    <input type="text"
                                           id="date_of_diss"
                                           name="date_of_diss"
                                           value="{{ $formOld['date_of_diss'] ?? '' }}"
                                           class="field__control dateBritish"
                                           placeholder="dd-mm-yyyy"
                                           autocomplete="off"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="fac_by">
                                    Facilitated by <span class="vd_red">*</span>
                                </label>
                                <div class="controls">
                                    <input type="text"
                                           id="fac_by"
                                           name="fac_by"
                                           value="{{ $formOld['fac_by'] ?? '' }}"
                                           class="field__control">
                                </div>
                            </div>
                        </div>

                        <!-- Row 2: Article Reference + Example -->
                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label" for="ref_of_art_disc">
                                    Full Reference Of The Article Discussed <span class="vd_red">*</span>
                                </label>
                                <div class="controls">
                                    <textarea id="ref_of_art_disc"
                                              name="ref_of_art_disc"
                                              class="field__control journal-textarea"
                                              rows="9"
                                              placeholder="View example from right column">{{ $formOld['ref_of_art_disc'] ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Example</label>
                                <div class="controls">
                                    <textarea class="field__control journal-textarea journal-example"
                                              rows="9"
                                              disabled>Title, Journal Name in which published, Author, PMID

Effect of stitch length on wound complications after closure of midline incisions: a randomized controlled trial.

Millbourn D, Cengiz Y, Israelsson LA.
Arch Surg. 2013 Nov;144(11):1056-9.

PMID: 19917943 [PubMed - indexed for MEDLINE]</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Row 3: Send to Supervisor + Warning -->
                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label" for="std_post">
                                    Send to Supervisor <span class="vd_red">*</span>
                                </label>
                                <div class="controls">
                                    <select name="std_post" id="std_post" class="field__control">
                                        <option value="No" {{ ($formOld['std_post'] ?? 'No') !== 'Yes' ? 'selected' : '' }}>No&nbsp;</option>
                                        <option value="Yes" {{ ($formOld['std_post'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes&nbsp;</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-warning elog-warn-inline">
                                    <span class="vd_alert-icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
                                    <strong>Warning!</strong> Once you select &apos;Yes&apos; then you can not edit this entry again.
                                </div>
                            </div>
                        </div>

                        <div class="form-actions-condensed row elog-form__row elog-form__row--1 mgbt-xs-0">
                            <div class="col-sm-12">
                                <button class="btn btn--submit" type="submit" name="submit" id="mform_journal_submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
