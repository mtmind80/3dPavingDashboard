@extends('layouts.master')

@section('title') 3D Paving Contacts - New @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') @lang('translation.details')@endslot
        @slot('li_1') <a href="{{ route('dashboard') }}">@lang('translation.Dashboard')</a>@endslot
        @slot('li_2') <a href="{{ route('show_workorder', ['id' => $fieldReport->proposal_id]) }}">@lang('translation.work_order')</a>@endslot
        @slot('li_3') <a href="{{ route('workorder_field_report_list', ['proposal_detail_id' => $fieldReport->proposal_detail_id]) }}">@lang('translation.fieldreports')</a>@endslot
        @slot('li_4') @lang('translation.details') @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <!--  header -->
            <div class="card">
                @include('_partials._alert')
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h6 class="">@lang('translation.work_order'): <span class="ml8 fwn info-color">{{ $fieldReport->proposal->name }}</span></h6>
                            <h6 class="mt-3">@lang('translation.work_order') @lang('translation.service'): <span class="ml8 fwn info-color">{{ $fieldReport->proposal->name }}</span></h6>
                            <h6 class="mt-3">@lang('translation.fieldreport') @lang('translation.date'): <span class="ml8 fwn info-color">{{ $fieldReport->report_date->format('m/d/Y') }}</span></h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- timesheets -->

            <div class="card">
                <div class="card-body">
                    @include('_partials._alert', ['alertId' => 'timesheet_alert'])
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="mb-4">@lang('translation.timesheet')</h4>
                        </div>
                    </div>
                    <h5 class="mb-4">@lang('translation.add')</h5>
                    <form method="POST" action="#" accept-charset="UTF-8" class="admin-form" id="time_sheet_form">
                        <div class="row">
                            <input type="hidden" name="proposal_id" value="{{ $fieldReport->proposal_id }}">
                            <input type="hidden" name="proposal_detail_id" value="{{ $fieldReport->proposal_detail_id }}">
                            <input type="hidden" name="workorder_field_report_id" value="{{ $fieldReport->id }}">
                            <input type="hidden" name="report_date_str" value="{{ $fieldReport->report_date->format('m/d/Y') }}">

                            <div class="col-lg-4 col-md-6 col-sm-6 admin-form-item-widget">
                                <x-form-select
                                   name="employee_id"
                                   :items="$employeesCB"
                                   selected=""
                                   :params="['label' => 'Employee', 'required' => true]"
                                ></x-form-select>
                            </div>
                            <div class="col-lg-4 col-md-3 col-sm-3 admin-form-item-widget">
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
                            <div class="col-lg-4 col-md-3 col-sm-3 admin-form-item-widget">
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
                        </div>
                        <div class="row buttons">
                            <div class="col-sm-12">
                                <x-button
                                    id="add_timesheet_button"
                                    class="btn-dark"
                                    type="button"
                                >
                                    <i class="fas fa-save"></i>
                                    @lang('translation.save')
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="timesheet_card" class="card-body{{ empty($fieldReport->timeSheets) || $fieldReport->timeSheets->count() === 0 ? ' hidden' : '' }}">
                    <h5 class="mb-4">@lang('translation.list')</h5>
                    <table class="list-table table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th class="tc">Employeee</th>
                                <th class="tc w220">Start</th>
                                <th class="tc w220">Finish</th>
                                <th class="tc w220">Hours</th>
                                <th class="tc w160">@lang('translation.action')</th>
                            </tr>
                        </thead>
                        <tbody id="timesheet_tbody">
                            @include('workorders.field_report.timesheet._list', ['timeSheets' => $fieldReport->timeSheets])
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END timesheets -->

            <!-- Equipments -->
            <div class="card">
                <div class="card-body">
                    @include('_partials._alert', ['alertId' => 'equipment_alert'])
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="mb-4">@lang('translation.equipment')</h4>
                        </div>
                    </div>
                    <h5 class="mb-4">@lang('translation.add')</h5>
                    <form method="POST" action="#" accept-charset="UTF-8" class="admin-form" id="equipment_form">
                        <input type="hidden" name="proposal_id" value="{{ $fieldReport->proposal_id }}">
                        <input type="hidden" name="proposal_detail_id" value="{{ $fieldReport->proposal_detail_id }}">
                        <input type="hidden" name="workorder_field_report_id" value="{{ $fieldReport->id }}">
                        <input type="hidden" name="report_date_str" value="{{ $fieldReport->report_date->format('m/d/Y') }}">

                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-6 admin-form-item-widget">
                                <x-form-select name="equipment_id"
                                   :items="$equipmentCB"
                                   selected=""
                                   :params="['label' => 'Equipment', 'required' => true]"
                                ></x-form-select>
                            </div>
                            <div class="col-lg-4 col-md-3 col-sm-3 admin-form-item-widget">
                                <x-form-text
                                    name="hours"
                                    class="check-contact"
                                    :params="['label' => 'Hours', 'iconClass' => 'far fa-clock', 'required' => true]"
                                ></x-form-text>
                            </div>
                            <div class="col-lg-4 col-md-3 col-sm-3 admin-form-item-widget">
                                <x-form-text
                                    name="number_of_units"
                                    class="check-contact"
                                    :params="['label' => 'Number of units', 'iconClass' => 'fas fa-bookmark', 'required' => true]"
                                ></x-form-text>
                            </div>
                        </div>
                        <div class="row buttons">
                            <div class="col-sm-12">
                                <x-button
                                    id="add_equipment_button"
                                    class="btn-dark"
                                    type="button"
                                >
                                    <i class="fas fa-save"></i>
                                    @lang('translation.save')
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body{{ empty($fieldReport->equipments) || $fieldReport->equipments->count() === 0 ? ' hidden' : '' }}">
                    <h5 class="mb-4">@lang('translation.list')</h5>
                    <table class="list-table table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th class="tc">Equipment</th>
                            <th class="tc w220">Hours</th>
                            <th class="tc w220">Rate Type</th>
                            <th class="tc w220">Rate</th>
                            <th class="tc w200">Number of Units</th>
                            <th class="tc w160">@lang('translation.action')</th>
                        </tr>
                        </thead>
                        <tbody id="equipment_tbody">
                            @include('workorders.field_report.equipment._list', ['equipments' => $fieldReport->equipments])
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END equipment -->

            <!-- Material -->
            <div class="card">
                <div class="card-body">
                    @include('_partials._alert', ['alertId' => 'material_alert'])
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="mb-4">@lang('translation.materials')</h4>
                        </div>
                    </div>
                    <h5 class="mb-4">@lang('translation.add')</h5>
                    <form method="POST" action="#" accept-charset="UTF-8" class="admin-form" id="material_form">
                        <input type="hidden" name="proposal_id" value="{{ $fieldReport->proposal_id }}">
                        <input type="hidden" name="proposal_detail_id" value="{{ $fieldReport->proposal_detail_id }}">
                        <input type="hidden" name="workorder_field_report_id" value="{{ $fieldReport->id }}">
                        <input type="hidden" name="report_date_str" value="{{ $fieldReport->report_date->format('m/d/Y') }}">

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-7 admin-form-item-widget">
                                <x-form-select
                                    name="material_id"
                                    :items="$materialsCB"
                                    selected=""
                                    :params="['label' => 'Material', 'required' => true]"
                                ></x-form-select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-5 admin-form-item-widget">
                                <x-form-text
                                    name="quantity"
                                    class="check-contact"
                                    :params="[
                                        'label' => 'Quantity',
                                        'iconClass' => 'fas fa-bookmark',
                                        'required' => true,
                                    ]"
                                ></x-form-text>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 admin-form-item-widget">
                                <x-form-textarea
                                    name="note"
                                    class="check-contact"
                                    :params="[
                                        'label' => 'Note',
                                        'iconClass' => 'fa fa-bookmark',
                                        'required' => false,
                                    ]"
                                ></x-form-textarea>
                            </div>
                        </div>
                        <div class="row buttons">
                            <div class="col-sm-12">
                                <x-button
                                    id="add_material_button"
                                    class="btn-dark"
                                    type="button"
                                >
                                    <i class="fas fa-save"></i>
                                    @lang('translation.save')
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body{{ empty($fieldReport->materials) || $fieldReport->materials->count() === 0 ? ' hidden' : '' }}">
                    <h5 class="mb-4">@lang('translation.list')</h5>
                    <table class="list-table table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th class="tc w420">Material</th>
                            <th class="tc w150">Quantity</th>
                            <th class="tc w190">Total Cost</th>
                            <th class="tc">Note</th>
                            <th class="tc w160">@lang('translation.action')</th>
                        </tr>
                        </thead>
                        <tbody id="material_tbody">
                            @include('workorders.field_report.material._list', ['materials' => $fieldReport->materials])
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END material -->

            <!-- vehicle -->
            <div class="card">
                <div class="card-body">
                    @include('_partials._alert', ['alertId' => 'vehicle_alert'])
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="mb-4">@lang('translation.vehicle')</h4>
                        </div>
                    </div>
                    <h5 class="mb-4">@lang('translation.add')</h5>
                    <form method="POST" action="#" accept-charset="UTF-8" class="admin-form" id="vehicle_form">
                        <input type="hidden" name="proposal_id" value="{{ $fieldReport->proposal_id }}">
                        <input type="hidden" name="proposal_detail_id" value="{{ $fieldReport->proposal_detail_id }}">
                        <input type="hidden" name="workorder_field_report_id" value="{{ $fieldReport->id }}">
                        <input type="hidden" name="report_date_str" value="{{ $fieldReport->report_date->format('m/d/Y') }}">

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-7 admin-form-item-widget">
                                <x-form-select
                                    name="vehicle_id"
                                    :items="$vehiclesCB"
                                    selected=""
                                    :params="['label' => 'Vehicle', 'required' => true]"
                                ></x-form-select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-5 admin-form-item-widget">
                                <x-form-text
                                    name="number_of_vehicles"
                                    class="check-contact"
                                    :params="[
                                        'label'     => 'Number of Vehicles',
                                        'iconClass' => 'fas fa-bookmark',
                                        'required'  => true,
                                    ]"
                                ></x-form-text>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 admin-form-item-widget">
                                <x-form-textarea
                                    name="note"
                                    class="check-contact"
                                    :params="[
                                        'label' => 'Note',
                                        'iconClass' => 'fa fa-bookmark',
                                        'required' => false,
                                    ]"
                                ></x-form-textarea>
                            </div>
                        </div>
                        <div class="row buttons">
                            <div class="col-sm-12">
                                <x-button
                                    id="add_vehicle_button"
                                    class="btn-dark"
                                    type="button"
                                >
                                    <i class="fas fa-save"></i>
                                    @lang('translation.save')
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body{{ empty($fieldReport->vehicles) || $fieldReport->vehicles->count() === 0 ? ' hidden' : '' }}">
                    <h5 class="mb-4">@lang('translation.list')</h5>
                    <table class="list-table table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th class="tc w500">Vehicle</th>
                            <th class="tc w160">Number of Vehicles</th>
                            <th class="tc">Note</th>
                            <th class="tc w160">@lang('translation.action')</th>
                        </tr>
                        </thead>
                        <tbody id="vehicle_tbody">
                            @include('workorders.field_report.vehicle._list', ['vehicles' => $fieldReport->vehicles])
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END vehicle -->

            <!-- Subcontractors -->
            <div class="card">
                <div class="card-body">
                    @include('_partials._alert', ['alertId' => 'subcontractor_alert'])
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="mb-4">@lang('translation.contractors')</h4>
                        </div>
                    </div>
                    <h5 class="mb-4">@lang('translation.add')</h5>
                    <form method="POST" action="#" accept-charset="UTF-8" class="admin-form" id="subcontractor_form">
                        <input type="hidden" name="proposal_id" value="{{ $fieldReport->proposal_id }}">
                        <input type="hidden" name="proposal_detail_id" value="{{ $fieldReport->proposal_detail_id }}">
                        <input type="hidden" name="workorder_field_report_id" value="{{ $fieldReport->id }}">
                        <input type="hidden" name="report_date_str" value="{{ $fieldReport->report_date->format('m/d/Y') }}">

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-7 admin-form-item-widget">
                                <x-form-select
                                    name="contractor_id"
                                    :items="$contractorsCB"
                                    selected=""
                                    :params="['label' => __('translation.subcontractor'), 'required' => true]"
                                ></x-form-select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-5 admin-form-item-widget">
                                <x-form-text
                                    name="cost"
                                    class="check-contact"
                                    :params="[
                                        'label' => 'Cost',
                                        'iconClass' => 'fas fa-dollar-sign',
                                        'required' => true,
                                    ]"
                                ></x-form-text>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 admin-form-item-widget">
                                <x-form-textarea
                                    name="description"
                                    class="check-contact"
                                    :params="[
                                        'label' => 'Description',
                                        'iconClass' => 'fa fa-bookmark',
                                        'required' => true,
                                    ]"
                                ></x-form-textarea>
                            </div>
                        </div>
                        <div class="row buttons">
                            <div class="col-sm-12">
                                <x-button
                                    id="add_subcontractor_button"
                                    class="btn-dark"
                                    type="button"
                                >
                                    <i class="fas fa-save"></i>
                                    @lang('translation.save')
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body{{ empty($fieldReport->subcontractors) || $fieldReport->subcontractors->count() === 0 ? ' hidden' : '' }}">
                    <h5 class="mb-4">@lang('translation.list')</h5>
                    <table class="list-table table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th class="tc w500">Contractor</th>
                            <th class="tc w160">Cost</th>
                            <th class="tc">Description</th>
                            <th class="tc w160">@lang('translation.action')</th>
                        </tr>
                        </thead>
                        <tbody id="subcontractor_tbody">
                            @include('workorders.field_report.subcontractor._list', ['subcontractors' => $fieldReport->subcontractors])
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END subcontractors -->

            <!-- Additional costs -->
            <div class="card">
                <div class="card-body">
                    @include('_partials._alert', ['alertId' => 'additional_cost_alert'])
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="mb-4">@lang('translation.additionalcosts')</h4>
                        </div>
                    </div>
                    <h5 class="mb-4">@lang('translation.add')</h5>
                    <form method="POST" action="#" accept-charset="UTF-8" class="admin-form" id="additional_cost_form">
                    <input type="hidden" name="proposal_id" value="{{ $fieldReport->proposal_id }}">
                        <input type="hidden" name="proposal_detail_id" value="{{ $fieldReport->proposal_detail_id }}">
                        <input type="hidden" name="workorder_field_report_id" value="{{ $fieldReport->id }}">
                        <input type="hidden" name="report_date_str" value="{{ $fieldReport->report_date->format('m/d/Y') }}">

                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-sm-4 admin-form-item-widget">
                                <x-form-text
                                    name="cost"
                                    class="check-contact"
                                    :params="[
                                        'label' => 'Cost',
                                        'iconClass' => 'fas fa-dollar-sign',
                                        'required' => true,
                                    ]"
                                ></x-form-text>
                            </div>
                            <div class="col-lg-10 col-md-11 col-sm-8 admin-form-item-widget">
                                <x-form-textarea
                                    name="description"
                                    class="check-contact"
                                    :params="[
                                        'label' => 'Description',
                                        'iconClass' => 'fa fa-bookmark',
                                        'required' => true,
                                    ]"
                                ></x-form-textarea>
                            </div>
                        </div>
                        <div class="row buttons">
                            <div class="col-sm-12">
                                <x-button
                                    id="add_additional_cost_button"
                                    class="btn-dark"
                                    type="button"
                                >
                                    <i class="fas fa-save"></i>
                                    @lang('translation.save')
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body{{ empty($fieldReport->additionalCosts) || $fieldReport->additionalCosts->count() === 0 ? ' hidden' : '' }}">
                    <h5 class="mb-4">@lang('translation.list')</h5>
                    <table class="list-table table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th class="tl">Description</th>
                            <th class="tc w200">Cost</th>
                            <th class="tc w160">@lang('translation.action')</th>
                        </tr>
                        </thead>
                        <tbody id="additional_cost_tbody">
                            @include('workorders.field_report.additional_cost._list', ['additionalCosts' => $fieldReport->additionalCosts])
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END additionalCost -->

            <div id="bottom_empty_div" class="{{ !empty($fieldReport->subcontractors) && $fieldReport->subcontractors->count() > 0 ? ' hidden' : '' }}" style="height:140px;"></div>
        </div>
    </div>
@stop

@section('page-js')
    <script>
        $(document).ready(function () {
            var fieldReportId = Number("{{ $fieldReport->id }}");

            var commonFormProperties = {
                proposal_id: Number("{{ $fieldReport->proposal_id  }}"),
                proposal_detail_id: Number("{{ $fieldReport->proposal_detail_id }}"),
                workorder_field_report_id: fieldReportId
            };

            var bottomEmptyDiv = $('#bottom_empty_div');

            // timesheets

            var timeSheetAlert = $('#timesheet_alert');
            var timesheetTbody = $('#timesheet_tbody');
            var timeSheetCard = timesheetTbody.closest('.card-body');

            timeSheetAlert.on('click', function(ev){
                ev.stopPropagation();
                ev.preventDefault();
                closeAlert(timeSheetAlert);
            });

            var timeSheetForm = $('#time_sheet_form');

            timeSheetForm.validate({
                rules: {
                    employee_id: {
                        required: true,
                        positive: true
                    },
                    start_time: {
                        required: true,
                        time: true
                    },
                    end_time: {
                        required: true,
                        time: true
                    }
                },
                messages: {
                    employee_id: {
                        required: "@lang('translation.field_required')",
                        positive: "@lang('translation.select_item')"
                    },
                    start_time: {
                        required: "@lang('translation.field_required')",
                        time: "@lang('translation.invalid_entry')"
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

                let formData = timeSheetForm.serializeObject();
                $.extend(formData, commonFormProperties);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    type: "POST",
                    url: "{{ route('ajax_workorder_field_report_timesheet_store') }}",
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
                            timesheetTbody.html(response.html);
                            timeSheetForm.trigger('reset');
                            timeSheetCard.removeClass('hidden');

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
                        workorder_field_report_id: fieldReportId,
                        timesheet_id: timesheetId
                    },
                    type: "POST",
                    url: "{{ route('ajax_workorder_field_report_timesheet_destroy') }}",
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

            // Equipments

            var equipmentAlert = $('#equipment_alert');
            var equipmentTbody = $('#equipment_tbody');
            var equipmentCard = equipmentTbody.closest('.card-body');

            equipmentAlert.on('click', function(ev){
                ev.stopPropagation();
                ev.preventDefault();
                closeAlert(equipmentAlert);
            });

            var equipmentForm = $('#equipment_form');

            equipmentForm.validate({
                rules: {
                    equipment_id: {
                        required: true,
                        positive: true
                    },
                    hours: {
                        required: true,
                        float: true
                    },
                    number_of_units: {
                        required: true,
                        positive: true
                    }
                },
                messages: {
                    equipment_id: {
                        required: "@lang('translation.field_required')",
                        positive: "@lang('translation.select_item')"
                    },
                    hours: {
                        required: "@lang('translation.field_required')",
                        float: "@lang('translation.invalid_entry')"
                    },
                    number_of_units: {
                        required: "@lang('translation.field_required')",
                        positive: "@lang('translation.invalid_entry')"
                    }
                }
            });

            $('#add_equipment_button').click(function () {
                if (! equipmentForm.valid()) {
                    return false;
                }

                let formData = equipmentForm.serializeObject();
                $.extend(formData, commonFormProperties);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    type: "POST",
                    url: "{{ route('ajax_workorder_field_report_equipment_store') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (typeof response.success === 'undefined'  ) {
                            showErrorAlert('Critical error has occurred.', equipmentAlert);
                        } else if (response.success) {
                            equipmentTbody.html(response.html);
                            equipmentForm.trigger('reset');
                            equipmentCard.removeClass('hidden');

                            if (response.message) {
                                showSuccessAlert(response.message, equipmentAlert);
                            }
                        } else {
                            showErrorAlert(response.message, equipmentAlert);
                        }
                    },
                    error: function (response){
                        @if (app()->environment() === 'local')
                            showErrorAlert(response.responseJSON.message, equipmentAlert);
                        @else
                            showErrorAlert(response.message, equipmentAlert);
                        @endif
                    }
                });

            });

            equipmentTbody.on('click', '.delete-equipment-button', function () {
                let equipmentId = $(this).data('equipment_id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        workorder_field_report_id: fieldReportId,
                        equipment_id: equipmentId
                    },
                    type: "POST",
                    url: "{{ route('ajax_workorder_field_report_equipment_destroy') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (typeof response.success === 'undefined'  ) {
                            showErrorAlert('Critical error has occurred.', equipmentAlert);
                        } else if (response.success) {
                            equipmentTbody.find('tr#equipment_'+response.equipment_id).remove();

                            if (response.message) {
                                showSuccessAlert(response.message, equipmentAlert);
                            }
                        } else {
                            // controller defined response error message
                            showErrorAlert(response.message, equipmentAlert);
                        }
                    },
                    error: function (response){
                        @if (app()->environment() === 'local')
                            showErrorAlert(response.responseJSON.message, equipmentAlert);
                        @else
                            showErrorAlert(response.message, equipmentAlert);
                        @endif
                    }
                });
            });

            // Materials

            var materialAlert = $('#material_alert');
            var materialTbody = $('#material_tbody');
            var materialCard = materialTbody.closest('.card-body');

            materialAlert.on('click', function(ev){
                ev.stopPropagation();
                ev.preventDefault();
                closeAlert(materialAlert);
            });

            var materialForm = $('#material_form');

            materialForm.validate({
                rules: {
                    material_id: {
                        required: true,
                        positive: true
                    },
                    quantity: {
                        required: true,
                        float: true
                    },
                    //non required fielda
                    note: {
                        required: false,
                        plainText: true
                    }
                },
                messages: {
                    material_id: {
                        required: "@lang('translation.field_required')",
                        positive: "@lang('translation.select_item')"
                    },
                    quantity: {
                        required: "@lang('translation.field_required')",
                        float: "@lang('translation.invalid_entry')"
                    },
                    note: {
                        plainText: "@lang('translation.invalid_entry')"
                    }
                }
            });

            $('#add_material_button').click(function () {
                if (! materialForm.valid()) {
                    return false;
                }

                let formData = materialForm.serializeObject();
                $.extend(formData, commonFormProperties);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    type: "POST",
                    url: "{{ route('ajax_workorder_field_report_material_store') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (typeof response.success === 'undefined'  ) {
                            showErrorAlert('Critical error has occurred.', materialAlert);
                        } else if (response.success) {
                            materialTbody.html(response.html);
                            materialForm.trigger('reset');
                            materialCard.removeClass('hidden');

                            if (response.message) {
                                showSuccessAlert(response.message, materialAlert);
                            }
                        } else {
                            // controller defined response error message
                            showErrorAlert(response.message, materialAlert);
                        }
                    },
                    error: function (response){
                        @if (app()->environment() === 'local')
                            showErrorAlert(response.responseJSON.message, materialAlert);
                        @else
                            showErrorAlert(response.message, materialAlert);
                        @endif
                    }
                });

            });

            materialTbody.on('click', '.delete-material-button', function () {
                let materialId = $(this).data('material_id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        workorder_field_report_id: fieldReportId,
                        material_id: materialId
                    },
                    type: "POST",
                    url: "{{ route('ajax_workorder_field_report_material_destroy') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (typeof response.success === 'undefined'  ) {
                            showErrorAlert('Critical error has occurred.', materialAlert);
                        } else if (response.success) {
                            materialTbody.find('tr#material_'+response.material_id).remove();

                            if (response.message) {
                                showSuccessAlert(response.message, materialAlert);
                            }
                        } else {
                            // controller defined response error message
                            showErrorAlert(response.message, materialAlert);
                        }
                    },
                    error: function (response){
                        @if (app()->environment() === 'local')
                            showErrorAlert(response.responseJSON.message, materialAlert);
                        @else
                            showErrorAlert(response.message, materialAlert);
                        @endif
                    }
                });
            });

            // Vehicles

            var vehicleAlert = $('#vehicle_alert');
            var vehicleTbody = $('#vehicle_tbody');
            var vehicleCard = vehicleTbody.closest('.card-body');

            vehicleAlert.on('click', function(ev){
                ev.stopPropagation();
                ev.preventDefault();
                closeAlert(vehicleAlert);
            });

            var vehicleForm = $('#vehicle_form');

            vehicleForm.validate({
                rules: {
                    vehicle_id: {
                        required: true,
                        positive: true
                    },
                    number_of_vehicles: {
                        required: true,
                        positive: true
                    },
                    //non required fielda
                    note: {
                        required: false,
                        plainText: true
                    }
                },
                messages: {
                    vehicle_id: {
                        required: "@lang('translation.field_required')",
                        positive: "@lang('translation.select_item')"
                    },
                    number_of_vehicles: {
                        required: "@lang('translation.field_required')",
                        positive: "@lang('translation.invalid_entry')"
                    },
                    note: {
                        plainText: "@lang('translation.invalid_entry')"
                    }
                }
            });

            $('#add_vehicle_button').click(function () {
                if (! vehicleForm.valid()) {
                    return false;
                }

                let formData = vehicleForm.serializeObject();
                $.extend(formData, commonFormProperties);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    type: "POST",
                    url: "{{ route('ajax_workorder_field_report_vehicle_store') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (typeof response.success === 'undefined'  ) {
                            showErrorAlert('Critical error has occurred.', vehicleAlert);
                        } else if (response.success) {
                            vehicleTbody.html(response.html);
                            vehicleForm.trigger('reset');
                            vehicleCard.removeClass('hidden');

                            if (response.message) {
                                showSuccessAlert(response.message, vehicleAlert);
                            }
                        } else {
                            showErrorAlert(response.message, vehicleAlert);
                        }
                    },
                    error: function (response){
                        @if (app()->environment() === 'local')
                            showErrorAlert(response.responseJSON.message, vehicleAlert);
                        @else
                            showErrorAlert(response.message, vehicleAlert);
                        @endif
                    }
                });

            });

            vehicleTbody.on('click', '.delete-vehicle-button', function () {
                let vehicleId = $(this).data('vehicle_id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        workorder_field_report_id: fieldReportId,
                        vehicle_id: vehicleId
                    },
                    type: "POST",
                    url: "{{ route('ajax_workorder_field_report_vehicle_destroy') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (typeof response.success === 'undefined'  ) {
                            showErrorAlert('Critical error has occurred.', vehicleAlert);
                        } else if (response.success) {
                            vehicleTbody.find('tr#vehicle_'+response.vehicle_id).remove();

                            if (response.message) {
                                showSuccessAlert(response.message, vehicleAlert);
                            }
                        } else {
                            // controller defined response error message
                            showErrorAlert(response.message, vehicleAlert);
                        }
                    },
                    error: function (response){
                        @if (app()->environment() === 'local')
                            showErrorAlert(response.responseJSON.message, vehicleAlert);
                        @else
                            showErrorAlert(response.message, vehicleAlert);
                        @endif
                    }
                });
            });

            // Subcontractors

            var subcontractorAlert = $('#subcontractor_alert');
            var subcontractorTbody = $('#subcontractor_tbody');
            var subcontractorCard = subcontractorTbody.closest('.card-body');

            subcontractorAlert.on('click', function(ev){
                ev.stopPropagation();
                ev.preventDefault();
                closeAlert(subcontractorAlert);
            });

            var subcontractorForm = $('#subcontractor_form');

            subcontractorForm.validate({
                rules: {
                    contractor_id: {
                        required: true,
                        positive: true
                    },
                    cost: {
                        required: true,
                        float: true
                    },
                    description: {
                        required: true,
                        plainText: true
                    }
                },
                messages: {
                    contractor_id: {
                        required: "@lang('translation.field_required')",
                        positive: "@lang('translation.select_item')"
                    },
                    cost: {
                        required: "@lang('translation.field_required')",
                        float: "@lang('translation.invalid_entry')"
                    },
                    description: {
                        plainText: "@lang('translation.invalid_entry')"
                    }
                }
            });

            $('#add_subcontractor_button').click(function () {
                if (! subcontractorForm.valid()) {
                    return false;
                }

                let formData = subcontractorForm.serializeObject();

                $.extend(formData, commonFormProperties);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    type: "POST",
                    url: "{{ route('ajax_workorder_field_report_subcontractor_store') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (typeof response.success === 'undefined'  ) {
                            showErrorAlert('Critical error has occurred.', subcontractorAlert);
                        } else if (response.success) {
                            subcontractorTbody.html(response.html);
                            subcontractorForm.trigger('reset');
                            subcontractorCard.removeClass('hidden');

                            bottomEmptyDiv.addClass('hidden');

                            if (response.message) {
                                showSuccessAlert(response.message, subcontractorAlert);
                            }
                        } else {
                            showErrorAlert(response.message, subcontractorAlert);
                        }
                    },
                    error: function (response){
                        @if (app()->environment() === 'local')
                            showErrorAlert(response.responseJSON.message, subcontractorAlert);
                        @else
                            showErrorAlert(response.message, subcontractorAlert);
                        @endif
                    }
                });

            });

            subcontractorTbody.on('click', '.delete-subcontractor-button', function () {
                let subcontractorId = $(this).data('subcontractor_id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        workorder_field_report_id: fieldReportId,
                        subcontractor_id: subcontractorId
                    },
                    type: "POST",
                    url: "{{ route('ajax_workorder_field_report_subcontractor_destroy') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (typeof response.success === 'undefined'  ) {
                            showErrorAlert('Critical error has occurred.', subcontractorAlert);
                        } else if (response.success) {
                            subcontractorTbody.find('tr#subcontractor_'+response.subcontractor_id).remove();

                            if (response.total === 0) {
                                subcontractorCard.addClass('hidden');
                                bottomEmptyDiv.removeClass('hidden');
                            }

                            if (response.message) {
                                showSuccessAlert(response.message, subcontractorAlert);
                            }
                        } else {
                            // controller defined response error message
                            showErrorAlert(response.message, subcontractorAlert);
                        }
                    },
                    error: function (response){
                        @if (app()->environment() === 'local')
                            showErrorAlert(response.responseJSON.message, subcontractorAlert);
                        @else
                            showErrorAlert(response.message, subcontractorAlert);
                        @endif
                    }
                });
            });

            // Additional costs

            var additionalCostAlert = $('#additional_cost_alert');
            var additionalCostTbody = $('#additional_cost_tbody');
            var additionalCostCard = additionalCostTbody.closest('.card-body');

            additionalCostAlert.on('click', function(ev){
                ev.stopPropagation();
                ev.preventDefault();
                closeAlert(additionalCostAlert);
            });

            var additionalCostForm = $('#additional_cost_form');

            additionalCostForm.validate({
                rules: {
                    contractor_id: {
                        required: true,
                        positive: true
                    },
                    cost: {
                        required: true,
                        float: true
                    },
                    description: {
                        required: true,
                        plainText: true
                    }
                },
                messages: {
                    contractor_id: {
                        required: "@lang('translation.field_required')",
                        positive: "@lang('translation.select_item')"
                    },
                    cost: {
                        required: "@lang('translation.field_required')",
                        float: "@lang('translation.invalid_entry')"
                    },
                    description: {
                        plainText: "@lang('translation.invalid_entry')"
                    }
                }
            });

            $('#add_additional_cost_button').click(function () {
                if (! additionalCostForm.valid()) {
                    return false;
                }

                let formData = additionalCostForm.serializeObject();

                $.extend(formData, commonFormProperties);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    type: "POST",
                    url: "{{ route('ajax_workorder_field_report_additional_cost_store') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (typeof response.success === 'undefined'  ) {
                            showErrorAlert('Critical error has occurred.', additionalCostAlert);
                        } else if (response.success) {
                            additionalCostTbody.html(response.html);
                            additionalCostForm.trigger('reset');
                            additionalCostCard.removeClass('hidden');

                            bottomEmptyDiv.addClass('hidden');

                            if (response.message) {
                                showSuccessAlert(response.message, additionalCostAlert);
                            }
                        } else {
                            showErrorAlert(response.message, additionalCostAlert);
                        }
                    },
                    error: function (response){
                        @if (app()->environment() === 'local')
                        showErrorAlert(response.responseJSON.message, additionalCostAlert);
                        @else
                        showErrorAlert(response.message, additionalCostAlert);
                        @endif
                    }
                });

            });

            additionalCostTbody.on('click', '.delete-additional-cost-button', function () {
                let additionalCostId = $(this).data('additional_cost_id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        workorder_field_report_id: fieldReportId,
                        additional_cost_id: additionalCostId
                    },
                    type: "POST",
                    url: "{{ route('ajax_workorder_field_report_additional_cost_destroy') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (typeof response.success === 'undefined'  ) {
                            showErrorAlert('Critical error has occurred.', additionalCostAlert);
                        } else if (response.success) {
                            additionalCostTbody.find('tr#additional_cost_'+response.additional_cost_id).remove();

                            if (response.total === 0) {
                                additionalCostCard.addClass('hidden');
                                bottomEmptyDiv.removeClass('hidden');
                            }

                            if (response.message) {
                                showSuccessAlert(response.message, additionalCostAlert);
                            }
                        } else {
                            // controller defined response error message
                            showErrorAlert(response.message, additionalCostAlert);
                        }
                    },
                    error: function (response){
                        @if (app()->environment() === 'local')
                        showErrorAlert(response.responseJSON.message, additionalCostAlert);
                        @else
                        showErrorAlert(response.message, additionalCostAlert);
                        @endif
                    }
                });
            });
        });
    </script>
@stop
