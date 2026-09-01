@extends('layouts.app')
@section('title', 'Add Paper/Poster Presented | Isra e-Logbook')
@section('group_presented', 'is-active is-open')
@section('nav_presented_add', 'is-active')
@section('main_class', 'elog-main--fluid')

@section('content')
<div class="elog-page-head">
    <h1 class="elog-page-title">Add Paper/Poster Presented</h1>
    <a class="btn btn--blue" href="{{ route('presented.index', ['program' => request('program')]) }}">List All</a>
</div>

<div class="vd_content-section elog-content-section clearfix">
    <form class="form-horizontal elog-form" action="{{ route('presented.store') }}" method="post" name="mform" id="mform_presented" novalidate>
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
                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label" for="rec_date">
                                    Presented Date <span class="vd_red">*</span>
                                </label>
                                <div class="controls">
                                    <input type="text"
                                           id="rec_date"
                                           name="rec_date"
                                           value="{{ $formOld['rec_date'] ?? '' }}"
                                           class="field__control dateBritish"
                                           placeholder="dd-mm-yyyy"
                                           autocomplete="off"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="rec_title">
                                    Title <span class="vd_red">*</span>
                                </label>
                                <div class="controls">
                                    <input type="text"
                                           id="rec_title"
                                           name="rec_title"
                                           value="{{ $formOld['rec_title'] ?? '' }}"
                                           class="field__control">
                                </div>
                            </div>
                        </div>

                        <div class="row elog-form__row elog-form__row--2">
                            <div class="col-md-6">
                                <label class="control-label" for="conf_name">
                                    Name Of Conference / Seminar / Symposium <span class="vd_red">*</span>
                                </label>
                                <div class="controls">
                                    <textarea id="conf_name"
                                              name="conf_name"
                                              class="field__control"
                                              rows="4">{{ $formOld['conf_name'] ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="rec_venue">
                                    Venue <span class="vd_red">*</span>
                                </label>
                                <div class="controls">
                                    <input type="text"
                                           id="rec_venue"
                                           name="rec_venue"
                                           value="{{ $formOld['rec_venue'] ?? '' }}"
                                           class="field__control">
                                </div>
                            </div>
                        </div>

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
                                    <strong>Warning!</strong> Once you select 'Yes' then you can not edit this entry again.
                                </div>
                            </div>
                        </div>

                        <div class="form-actions-condensed row elog-form__row elog-form__row--1 mgbt-xs-0">
                            <div class="col-sm-12">
                                <button class="btn btn--submit" type="submit" name="submit" id="mform_presented_submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
