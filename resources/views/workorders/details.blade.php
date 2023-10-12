@extends('layouts.master')

@section('title') 3D Paving Contacts - New @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') @lang('translation.detail')@endslot
        @slot('li_1') <a href="{{ route('dashboard') }}">@lang('translation.Dashboard')</a>@endslot
        @slot('li_2') <a href="{{ route('show_workorder', ['id' => $proposalDetail->proposal_id]) }}">@lang('translation.show') @lang('translation.work_order')</a>@endslot
        @slot('li_3') @lang('translation.details') @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <!--  header -->
            <div class="card">
                @include('_partials._alert')
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <h6 class="">@lang('translation.work_order'): <span class="ml8 fwn info-color">{{ $proposalDetail->proposal->name }}</span></h6>
                            <h6 class="mt-3">@lang('translation.work_order') @lang('translation.service'): <span class="ml8 fwn info-color">{{ $proposalDetail->service->name }}</span></h6>
                        </div>
                        <div class="col-sm-4">
                            <div class="w240 tl" style="margin:0 0 0 auto">
                                <x-form-date-picker
                                    name="report_date"
                                    :params="[
                                    'id' => 'report_date',
                                    'label' => 'Report day',
                                    'iconClass' => 'fas fa-calendar',
                                    'value' => $reportDate,
                                ]"
                                ></x-form-date-picker>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- timesheets -->
            <div class="card">
                <div class="card-body">
                    @include('_partials._alert', ['alertId' => 'timesheet_alert'])
                    <div class="row">
                        <div class="col-sm-6">
                            <h5 class="mb-4">@lang('translation.new') @lang('translation.timesheet')</h5>
                        </div>
                        <div class="col-sm-6 tr">
                            Day: <b>{{ $reportDate->format('m/d/Y') }}</b>
                        </div>
                    </div>
                    <form method="POST" action="#" accept-charset="UTF-8" class="admin-form" id="time_sheet_form">
                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-sm-6 admin-form-item-widget">
                                <x-form-select
                                   name="employee_id"
                                   :items="$employeesCB"
                                   selected=""
                                   :params="['label' => 'Employee', 'required' => true]"
                                ></x-form-select>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-3 admin-form-item-widget">
                                <x-form-time-picker
                                    name="start_time"
                                    class=""
                                    :params="[
                                    'id' => 'start_time',
                                    'label' => 'From',
                                    'iconClass' => 'far fa-clock',
                                ]"
                                ></x-form-time-picker>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-3 admin-form-item-widget">
                                <x-form-time-picker
                                    name="end_time"
                                    class=""
                                    :params="[
                                    'id' => 'end_time',
                                    'label' => 'To',
                                    'iconClass' => 'far fa-clock',
                                ]"
                                ></x-form-time-picker>
                            </div>
                            <div class="col-lg-5 col-md-2 col-sm-4 admin-form-item-widget xs-hidden">
                            </div>
                        </div>
                        <div class="row buttons">
                            <div class="col-sm-12">
                                <x-button id="add_timesheet_button" class="btn-dark" type="button">
                                    <i class="fas fa-save"></i>
                                    Save
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card{{ empty($timeSheets) || $timeSheets->count() === 0 ? ' hidden' : '' }}">
                <div class="card-body">
                    <h5 class="mb-4">@lang('translation.timesheet')</h5>
                    <table class="list-table table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th class="tc">Employeee</th>
                            <th class="tc w200">Start</th>
                            <th class="tc w200">Finish</th>
                            <th class="tc w200">Hours</th>
                            <th class="tc w100">@lang('translation.action')</th>
                        </tr>
                        </thead>

                        <tbody id="timesheet_tbody">
                            @if (!empty($timeSheets) && $timeSheets->count() > 0)
                                @foreach ($timeSheets as $timeSheet)
                                    <tr id="timesheet_{{ $timeSheet->id }}">
                                        <td class="tc">{{ $timeSheet->employee->full_name ?? null }}</td>
                                        <td class="tc">{{ $timeSheet->html_start }}</td>
                                        <td class="tc">{{ $timeSheet->html_finish }}</td>
                                        <td class="tc">{{ round($timeSheet->actual_hours, 2) }}</td>
                                        <td class="centered">
                                            <button
                                                class="btn p0 btn-danger tc delete-timesheet-button" type="button"
                                                data-toggle="tooltip"
                                                title="Delete item"
                                                data-timesheet_id="{{ $timeSheet->id }}"
                                            >
                                                <i class="far fa-trash-alt dib m0 plr5"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END timesheets -->


        </div>
    </div>
@stop

@section('page-js')
    <script>
        $(document).ready(function () {
            // global
            $('#report_date').change(function(){
                let val = $(this).val();

                if (isUSDate(val)) {
                    window.location = "{{ $currentUrl }}" + '?report_date=' + val;
                }
            });

            var commonFormProperties = {
                report_date: "{{ $reportDate->format('m/d/Y') }}",
                proposal_id: "{{ $proposalDetail->proposal_id  }}",
                proposal_details_id: "{{ $proposalDetail->id  }}"
            };

            // timesheets

            var timeSheetAlert = $('#timesheet_alert');
            var timesheetTbody = $('#timesheet_tbody');

            timeSheetAlert.on('click', function(ev){
                ev.stopPropagation();
                ev.preventDefault();
                closeAlert(timeSheetAlert);
            });

            var timeSheetForm = $('#time_sheet_form');

            timeSheetForm.validate({
                rules: {
                    employee_id : {
                        required: true,
                        positive: true
                    },
                    start_time: {
                        required: true,
                        time    : true
                    },
                    end_time: {
                        required: true,
                        time    : true
                    }
                },
                messages: {
                    employee_id: {
                        required: "@lang('translation.field_required')",
                        positive: "@lang('translation.select_item')"
                    },
                    start_time: {
                        required: "@lang('translation.field_required')",
                        time: "@lang('translation.select_item')"
                    },
                    end_time: {
                        required: "@lang('translation.field_required')",
                        time: "@lang('translation.invalid_entry')"
                    }
                }
            });

            $('#add_timesheet_button').click(function () {
                if (! timeSheetForm.valid()) {
                    return false;
                }

                let formData = timeSheetForm.serializeObject();  // declare form variable first

                $.extend(formData, commonFormProperties);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    type: "POST",
                    url: "{{ route('ajax_workorder_timesheet_store') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        console.log(response);
                        if (typeof response.success === 'undefined'  ) {
                            showErrorAlert('Critical error has occurred.', timeSheetAlert);
                        } else if (response.success) {
                            let data = response.data;
                            let html = '';

                            html += '<tr id="timesheet_'+ data.id +'">';
                            html += '<td class="tc">'+ data.employee_full_name +'</td>';
                            html += '<td class="tc">'+ data.html_start +'</td>';
                            html += '<td class="tc">'+ data.html_finish +'</td>';
                            html += '<td class="tc">'+ data.actual_hours +'</td>';
                            html += '<td class="tc"><button data-timesheet_id="'+ data.id +'" class="btn p0 btn-danger tc delete-timesheet-button" type="button" data-toggle="tooltip" title="Delete item"><i class="far fa-trash-alt dib m0 plr5"></i></button></td>';
                            html += '</tr>';

                            timesheetTbody.append(html);

                            timeSheetForm.trigger('reset');

                            if (response.message) {
                                showSuccessAlert(response.message, timeSheetAlert);
                            }
                        } else {
                            // controller defined response error message
                            showErrorAlert(response.message, timeSheetAlert);
                        }
                    },
                    error: function (response){
                        @if (app()->environment() === 'local')
                            showErrorAlert(response.responseJSON.message, timeSheetAlert);
                        @else
                            showErrorAlert(response.message, timeSheetAlert);
                        @endif
                    }
                });

            });

            timesheetTbody.on('click', '.delete-timesheet-button', function () {
                let timesheetId = $(this).data('timesheet_id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        timesheet_id: timesheetId
                    },
                    type: "POST",
                    url: "{{ route('ajax_workorder_timesheet_destroy') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (typeof response.success === 'undefined'  ) {
                            showErrorAlert('Critical error has occurred.', timeSheetAlert);
                        } else if (response.success) {
                            timesheetTbody.find('tr#timesheet_'+response.timesheet_id).remove();

                            console.log(response.timesheet_id);

                            if (response.message) {
                                showSuccessAlert(response.message, timeSheetAlert);
                            }
                        } else {
                            // controller defined response error message
                            showErrorAlert(response.message, timeSheetAlert);
                        }
                    },
                    error: function (response){
                        @if (app()->environment() === 'local')
                            showErrorAlert(response.responseJSON.message, timeSheetAlert);
                        @else
                            showErrorAlert(response.message, timeSheetAlert);
                        @endif
                    }
                });
            });

        });
    </script>
@stop
