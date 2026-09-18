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

@if($errors->any() || !empty($formErrors) || session('flash_err'))
<div class="alert alert-danger elog-flash elog-alert-errors" role="alert" style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px;">
    <div style="font-weight: 700; font-size: 15px; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-triangle-exclamation"></i> Please fill all the required fields.
    </div>
    <ul style="margin: 0; padding-left: 24px;">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
        @if(!empty($formErrors))
            @foreach($formErrors as $fe)
                @if($fe !== 'Please fill all the required fields.' && !in_array($fe, $errors->all()))
                    <li>{{ $fe }}</li>
                @endif
            @endforeach
        @endif
    </ul>
</div>
@endif

<div id="clientErrorBox" style="display:none; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px;" role="alert">
    <div style="font-weight: 700; font-size: 15px; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-triangle-exclamation"></i> Please fill all the required fields.
    </div>
    <ul id="clientErrorList" style="margin: 0; padding-left: 24px;"></ul>
</div>

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
                                           value="{{ old('date_of_diss', $formOld['date_of_diss'] ?? '') }}"
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
                                           value="{{ old('fac_by', $formOld['fac_by'] ?? '') }}"
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
                                              placeholder="View example from right column">{{ old('ref_of_art_disc', $formOld['ref_of_art_disc'] ?? '') }}</textarea>
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
                                        <option value="No" {{ (string)old('std_post', $formOld['std_post'] ?? 'No') !== 'Yes' ? 'selected' : '' }}>No&nbsp;</option>
                                        <option value="Yes" {{ (string)old('std_post', $formOld['std_post'] ?? '') === 'Yes' ? 'selected' : '' }}>Yes&nbsp;</option>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('mform_journal');
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

    form.addEventListener('submit', function (e) {
        var missing = [];
        var errorBox = document.getElementById('clientErrorBox');
        var errorList = document.getElementById('clientErrorList');

        function checkField(id, label) {
            var el = document.getElementById(id);
            if (!el || !el.value || el.value.trim() === '') {
                missing.push(label);
                if (el) {
                    el.style.border = '1px solid #d9534f';
                }
            } else if (el) {
                el.style.border = '';
            }
        }

        checkField('date_of_diss', 'Date of Discussion is required.');
        checkField('fac_by', 'Facilitated by is required.');

        if (missing.length > 0) {
            e.preventDefault();
            e.stopImmediatePropagation();
            resetFormSubmitBtn();

            if (errorBox && errorList) {
                errorList.innerHTML = '';
                missing.forEach(function (msg) {
                    var li = document.createElement('li');
                    li.textContent = msg;
                    errorList.appendChild(li);
                });
                errorBox.style.display = 'block';
                errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                alert('Please fill all the required fields:\n- ' + missing.join('\n- '));
            }
            return false;
        } else if (errorBox) {
            errorBox.style.display = 'none';
        }
    });

    form.querySelectorAll('input, select, textarea').forEach(function (field) {
        field.addEventListener('input', function () {
            resetFormSubmitBtn();
            if (this.value && this.value.trim() !== '') {
                this.style.border = '';
            }
        });
        field.addEventListener('change', function () {
            resetFormSubmitBtn();
            if (this.value && this.value.trim() !== '') {
                this.style.border = '';
            }
        });
    });
});
</script>
@endpush
