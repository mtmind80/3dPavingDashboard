@extends('layouts.master')

@section('title') 3D Paving Schedule Service @endsection


@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.schedule') {{ $proposalDetail->service_name }}</br> For:
            {{ $proposalDetail->proposal->name}}
        @endslot
        @slot('li_1')
            <a href="{{ route('dashboard') }}" xmlns="http://www.w3.org/1999/html">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_3')
            <a href="{{ route('show_workorder', ['id' => $proposalDetail->proposal->id]) }}">@lang('translation.work_order')</a>
        @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body alert-light">
                    @include('_partials._alert', ['alertId' => 'schedule_alert'])
                    <h5>@lang('translation.schedule')</h5>
                    <form
                        action="#"
                        method="post"
                        class="admin-form"
                        id="schedule_form"
                    >
                        @csrf
                        <input type="hidden" name="proposal_detail_id"  id="proposal_detail_id" value="{{ $proposalDetail->id }}">
                        <input type="hidden" name="schedule_id" id="schedule_id">

                        <div class="row">
                            <div class="col-xs-6 col-sm-3 col-md-2 admin-form-item-widget">
                                <x-form-date-picker
                                    name="from_date"
                                    :params="[
                                        'id' => 'from_date',
                                        'required' => true,
                                        'language' => 'en',
                                        'label' => 'Start Date',
                                        'iconClass' => 'fas fa-calendar',
                                    ]"
                                ></x-form-date-picker>
                            </div>
                            <div class="col-xs-6 col-sm-3 col-md-2 admin-form-item-widget">
                                <x-form-time-picker
                                    name="from_time"
                                    :params="[
                                        'id' => 'from_time',
                                        'required' => true,
                                        'language' => 'en',
                                        'label' => 'Start Time',
                                        'iconClass' => 'fas fa-clock',
                                    ]"
                                ></x-form-time-picker>
                            </div>
                            <div class="col-xs-6 col-sm-3 col-md-2 admin-form-item-widget">
                                <x-form-date-picker
                                    name="to_date"
                                    :params="[
                                        'id' => 'to_date',
                                        'required' => true,
                                        'language' => 'en',
                                        'label' => 'End Date',
                                        'iconClass' => 'fas fa-calendar',
                                    ]"
                                ></x-form-date-picker>
                            </div>
                            <div class="col-xs-6 col-sm-3 col-md-2 admin-form-item-widget">
                                <x-form-time-picker
                                    name="to_time"
                                    :params="[
                                        'id' => 'to_time',
                                        'required' => true,
                                        'language' => 'en',
                                        'label' => 'End Time',
                                        'iconClass' => 'fas fa-clock',
                                    ]"
                                ></x-form-time-picker>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 admin-form-item-widget">
                                <x-form-text
                                        name="title"
                                        :params="[
                                        'id' => 'title',
                                        'label' => 'Title',
                                        'hint' => '(max: 100 chars)',
                                        'iconClass' => 'far fa-sticky-note',
                                        'required' => true,
                                    ]"
                                ></x-form-text>
                            </div>
                            <div class="col-md-12 admin-form-item-widget">
                                <x-form-textarea
                                    name="note"
                                    :params="[
                                        'id' => 'note',
                                        'label' => 'Note',
                                        'hint' => '(max: 1000 chars)',
                                        'iconClass' => 'far fa-comment',
                                        'required' => false,
                                    ]"
                                ></x-form-textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 admin-form-item-widget">
                                <a id="schedule_add_button" href="javascript:" class="{{ $site_button_class }} schedule-submit">Add Schedule</a>
                                <a id="schedule_update_button" href="javascript:" class="btn btn-info hidden schedule-submit">Update Schedule</a>
                                <a id="schedule_cancel_button" href="javascript:" class="btn btn-light hidden ml6">Cancel</a>
                            </div>
                        </div>
                    </form>

                    @if ($proposalDetail->schedule !== null)
                        <div class="row pt30">
                            <div class="col-md-12">
                                <table class="table">
                                    <theader id="schedule_table_header">
                                        <tr>
                                            <th class="tc w200">Start</th>
                                            <th class="tc w200">End</th>
                                            <th class="tc">Title</th>
                                            <th class="tc w180">Created By</th>
                                            <th class="tc w180">Updated By</th>
                                            <th class="tc w140">Actions</th>
                                        </tr>
                                    </theader>
                                    <tbody id="schedule_table_body">
                                        @include('proposaldetails._schedule_service_tbody_content')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@stop


@section('page-js')
    <script>
        $(document).ready(function(){
            var proposalDetailId = $('#proposal_detail_id').val();

            var scheduleElForm = $('#schedule_form');
            var scheduleElFormScheduleId = $('#schedule_id');
            var scheduleElFormFromDate = $('#from_date');
            var scheduleElFormFromTime = $('#from_time');
            var scheduleElFormToDate = $('#to_date');
            var scheduleElFormToTime = $('#to_time');
            var scheduleElFormTitle = $('#title');
            var scheduleElFormNote = $('#note');

            var scheduleElTableHeader = $('#schedule_table_header');
            var scheduleElTableBody = $('#schedule_table_body');

            var scheduleSubmitButton = $('.schedule-submit');
            var scheduleAddButton = $('#schedule_add_button');
            var scheduleUpdateButton = $('#schedule_update_button');
            var scheduleCancelButton = $('#schedule_cancel_button');
            
            var scheduleAlert = $('#schedule_alert');

            scheduleAlert.on('click', function(ev){
                ev.stopPropagation();
                ev.preventDefault();
                closeAlert(scheduleAlert);
            });

            /** Add or Update schedule */

            scheduleSubmitButton.on('click', function(){
                scheduleElForm.validate({
                    rules: {
                        note: {
                            required: true,
                            plainText: true,
                            maxlength: 100
                        },
                        from_date: {
                            required: true,
                            usDate: true
                        },
                        from_time: {
                            required: true,
                            time: true
                        },
                        to_date: {
                            required: true,
                            usDate: true
                        },
                        to_time: {
                            required: true,
                            time: true
                        },
                        note: {
                            required: false,
                            plainText: true,
                            maxlength: 1000
                        }
                    },
                    messages: {
                        title: {
                            required: "@lang('translation.field_required')",
                            plainText: 'Invalid title.',
                            max: 'Title can not be larger than {0} chars.'
                        },
                        from_date: {
                            required: "@lang('translation.field_required')",
                            usDate: 'Invalid date'
                        },
                        from_time: {
                            required: "@lang('translation.field_required')",
                            time: 'Invalid time'
                        },
                        to_date: {
                            required: "@lang('translation.field_required')",
                            usDate: 'Invalid date'
                        },
                        to_time: {
                            required: "@lang('translation.field_required')",
                            time: 'Invalid time'
                        },
                        note: {
                            plainText: 'Invalid note.',
                            max: 'Note can not be larger than {0} chars.'
                        }
                    }
                });

                if (scheduleElForm.valid()) {
                    let formData = scheduleElForm.serializeObject();

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        type: "POST",
                        url: "{{ route('ajax_schedule_add_or_update') }}",
                        beforeSend: function (request){
                            showSpinner();
                        },
                        complete: function (){
                            hideSpinner();
                        },
                        success: function (response){
                            if (!response) {
                                showErrorAlert('Critical error has occurred.', scheduleAlert);
                            } else if (response.success) {
                                scheduleElTableBody.html(response.html);
                                activateTooltips();
                                scheduleResetForm();

                                scheduleAddButton.removeClass('hidden');
                                scheduleUpdateButton.addClass('hidden');
                                scheduleCancelButton.addClass('hidden');

                                if (response.message) {
                                    showSuccessAlert(response.message, scheduleAlert);
                                }
                            } else {
                                showErrorAlert(response.message, scheduleAlert);
                            }
                        },
                        error: function (response){
                            @if (app()->isLocal())
                                showErrorAlert(response.responseJSON.message, scheduleAlert);
                            @else
                                showErrorAlert('Critical error has occurred.', scheduleAlert);
                            @endif
                        }
                    });
                }
            });


            /** Populate for fields for editing */

            scheduleElTableBody.on('click', '.edit-schedule', function(){
                let el = $(this);
                scheduleElFormScheduleId.val(el.data('schedule_id'));
                scheduleElFormFromDate.val(el.data('from_date'));
                scheduleElFormFromTime.val(el.data('from_time'));
                scheduleElFormToDate.val(el.data('to_date'));
                scheduleElFormToTime.val(el.data('to_time'));
                scheduleElFormNote.val(el.data('note'));
                scheduleElFormTitle.val(el.data('title'));

                scheduleAddButton.addClass('hidden');
                scheduleUpdateButton.removeClass('hidden');
                scheduleCancelButton.removeClass('hidden');
            });


            /** Remove schedule */

            scheduleElTableBody.on('click', '.remove-schedule', function(){
                let el = $(this);
                let schedule_id = el.data('schedule_id');

                confirmation({
                    message: 'You are about to delete this schedule. Are you sure?',
                    title: 'Warning',
                    type: 'warning',
                    button_confirm_caption: 'Confirm',
                    button_cancel_caption: 'Cancel',
                    confirm_function: function(){
                        confirmDeleteSchedule(schedule_id);
                    },
                    cancel_function: () => showInfoAlert('Action cancelled.', scheduleAlert),
                    cancel_args: null,
                    lang: 'en'
                });
            });

            function confirmDeleteSchedule(schedule_id)
            {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        proposal_detail_id: proposalDetailId,
                        schedule_id: schedule_id
                    },
                    type: "POST",
                    url: "{{ route('ajax_schedule_remove') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (!response) {
                            showErrorAlert('Critical error has occurred.', scheduleAlert);
                        } else if (response.success) {
                            $('#schedule_id_' + response.schedule_id_).remove();

                            let scheduleRows = $('.schedule-row');

                            if (scheduleRows.length === 0) {
                                scheduleElTableHeader.addClass('hidden');
                            } else {
                                scheduleResetForm();
                                activateTooltips();
                            }

                            if (response.message) {
                                showSuccessAlert(response.message, scheduleAlert);
                            }
                        } else {
                            showErrorAlert(response.message, scheduleAlert);
                        }
                    },
                    error: function (response){
                        @if (env('APP_ENV') === 'local')
                            showErrorAlert(response.responseJSON.message, scheduleAlert);
                        @else
                            showErrorAlert('Critical error has occurred.', scheduleAlert);
                        @endif
                    }
                });
            }

            scheduleCancelButton.click(function(){
                scheduleResetForm();

                scheduleAddButton.removeClass('hidden');
                scheduleUpdateButton.addClass('hidden');
                scheduleCancelButton.addClass('hidden');
            });

            function scheduleResetForm()
            {
                scheduleElForm.trigger('reset');
                scheduleElFormScheduleId.val('');
            }

            function activateTooltips()
            {
                var Tooltips = $('[data-toggle="tooltip"]');

                if (Tooltips.length) {
                    Tooltips.tooltip({
                        container: $('body'),
                        template: '<div class="tooltip tooltip-custom" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
                    });
                }
            }
        });
    </script>
@stop

