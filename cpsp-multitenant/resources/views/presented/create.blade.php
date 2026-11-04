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

@if(session('flash_err'))
<div class="alert alert-danger elog-flash elog-alert-errors" role="alert" style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px;">
    <div style="font-weight: 700; font-size: 15px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-triangle-exclamation"></i> {{ session('flash_err') }}
    </div>
</div>
@endif

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
                                           value="{{ old('rec_date', $formOld['rec_date'] ?? '') }}"
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
                                           value="{{ old('rec_title', $formOld['rec_title'] ?? '') }}"
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
                                              rows="4">{{ old('conf_name', $formOld['conf_name'] ?? '') }}</textarea>
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
                                           value="{{ old('rec_venue', $formOld['rec_venue'] ?? '') }}"
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
                                        <option value="No" {{ (string)old('std_post', $formOld['std_post'] ?? 'No') !== 'Yes' ? 'selected' : '' }}>No&nbsp;</option>
                                        <option value="Yes" {{ (string)old('std_post', $formOld['std_post'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes&nbsp;</option>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('mform_presented');
    if (!form) return;

    function resetFormSubmitBtn() {
        var submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = false;
            if (submitBtn.dataset.originalHtml) {
                submitBtn.innerHTML = submitBtn.dataset.originalHtml;
            } else if (submitBtn.textContent.trim() === 'Submitting...') {
                submitBtn.textContent = 'Submit';
            }
        }
        if (typeof window.resetSubmitButtons === 'function') {
            window.resetSubmitButtons();
        }
    }

    resetFormSubmitBtn();

    function setFieldInvalid(el, isInvalid) {
        if (!el) return;
        var trigger = el.id ? document.getElementById('resp_trigger_' + el.id) : null;
        if (!trigger && el.parentElement && el.parentElement.classList.contains('resp-select-container')) {
            trigger = el.parentElement.querySelector('.resp-select-trigger');
        }
        if (isInvalid) {
            el.style.border = '1.5px solid #dc2626';
            el.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.12)';
            if (trigger) {
                trigger.classList.add('has-error');
                trigger.style.border = '1.5px solid #dc2626';
                trigger.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.12)';
            }
        } else {
            el.style.border = '';
            el.style.boxShadow = '';
            if (trigger) {
                trigger.classList.remove('has-error');
                trigger.style.border = '';
                trigger.style.boxShadow = '';
            }
        }
    }

    form.addEventListener('submit', function (e) {
        var firstInvalid = null;

        function checkField(id) {
            var el = document.getElementById(id);
            if (!el || !el.value || el.value.trim() === '') {
                if (el) {
                    setFieldInvalid(el, true);
                    if (!firstInvalid) {
                        firstInvalid = (el.id && document.getElementById('resp_trigger_' + el.id)) || el;
                    }
                }
            } else if (el) {
                setFieldInvalid(el, false);
            }
        }

        checkField('rec_date');
        checkField('rec_title');
        checkField('conf_name');
        checkField('rec_venue');

        if (firstInvalid) {
            e.preventDefault();
            e.stopImmediatePropagation();
            resetFormSubmitBtn();
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (typeof firstInvalid.focus === 'function') {
                firstInvalid.focus({ preventScroll: true });
            }
            return false;
        }
    });

    form.querySelectorAll('input, select, textarea').forEach(function (field) {
        field.addEventListener('input', function () {
            resetFormSubmitBtn();
            if (this.value && this.value.trim() !== '') {
                setFieldInvalid(this, false);
            }
        });
        field.addEventListener('change', function () {
            resetFormSubmitBtn();
            if (this.value && this.value.trim() !== '') {
                setFieldInvalid(this, false);
            }
        });
    });
});
</script>
@endpush
