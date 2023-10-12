@extends('layouts.master')

@section('title') 3D Paving Contacts - New @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') @lang('translation.timesheet')@endslot
        @slot('li_1') <a href="{{ route('dashboard') }}">@lang('translation.Dashboard')</a>@endslot
        @slot('li_2') <a href="{{ route('show_workorder', ['id' => $proposalDetail->proposal_id]) }}">@lang('translation.show') @lang('translation.work_order')</a>@endslot
        @slot('li_3') @lang('translation.new') @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">

            <!--  header -->

            <div class="card">
                @include('_partials._alert')
                <div class="card-body">
                    <h6 class="">@lang('translation.work_order'): <span class="ml8 fwn info-color">{{ $proposalDetail->proposal->name }}</span></h6>
                    <h6 class="mt-3">@lang('translation.work_order') @lang('translation.service'): <span class="ml8 fwn info-color">{{ $proposalDetail->service->name }}</span></h6>
                    @if (!empty($reportDate))
                        <h6 class="mt-3">@lang('translation.report') @lang('translation.date'): <span class="ml8 fwn info-color">{{ $reportDate->toFormattedDayDateString() }}</span></h6>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="mb-4">@lang('translation.new')</h5>
                    <form method="POST" action="{{ route('workorder_timesheet_store') }}" accept-charset="UTF-8" id="adminForm" class="admin-form">
                        @csrf
                        <input type="hidden" name="proposal_id" id="proposal_id" value="{{ $proposalDetail->proposal_id ?? null }}">
                        <input type="hidden" name="proposal_details_id" id="proposal_details_id" value="{{ $proposalDetail->id ?? null }}">

                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
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
                            <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
                                <x-form-select name="employee_id"
                                    :items="$employeesCB"
                                    selected=""
                                    :params="['label' => 'Employee', 'required' => true]"
                                ></x-form-select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
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
                            <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
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
                            <div class="col-sm-12 tr">
                                <x-button id="cancel_button" class="btn-light"><i
                                        class="far fa-arrow-alt-circle-left "></i>{{ $cancel_caption ?? 'Cancel' }}</x-button>
                                <x-button id="submit_button" class="btn-dark" type="submit"><i class="fas fa-save"></i>{{ $submit_caption ?? 'Submit' }}
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if (!empty($reportDate))

                <!-- timesheets -->
                @if (!empty($timeSheets))
                    <div class="card">
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

                                <tbody>
                                    @foreach ($timeSheets as $timeSheet)
                                        <tr {{ !empty($timeSheet->status->color) ? ' style=background-color:#'.$timeSheet->status->color.' ' : '' }}data-id="{{ $timeSheet->id }}">
                                            <td class="tc">{{ $timeSheet->employee->full_name ?? null }}</td>
                                            <td class="tc">{{ $timeSheet->html_start }}</td>
                                            <td class="tc">{{ $timeSheet->html_finish }}</td>
                                            <td class="tc">{{ $timeSheet->actual_hours }}</td>
                                            <td class="centered">
                                                <form action="{{ route('workorder_timesheet_destroy',['workorder_timesheet_id' => $timeSheet->id]) }}" method="post">
                                                    @csrf
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button class="btn p0 btn-danger tc" type="submit" data-toggle="tooltip" title="Delete item"><i class="far fa-trash-alt dib m0 plr5"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <x-paginator
                                :collection="$timeSheets"
                                route-name="workorder_timesheet_list"
                                :params="[
                                        'route_params' => ['proposal_detail_id' => $proposalDetail->id],
                                        'pageLimits' => [25, 50, 100],
                                    ]"
                            ></x-paginator>
                        </div>
                    </div>
                @endif
            @else

            @endif
        </div>
    </div>
@stop

@section('page-js')
    <script>
        $(document).ready(function () {
            $('#report_date').change(function(){
                let val = $(this).val();

                if (isUSDate(val)) {
                    window.location = "{{ $currentUrl }}" + '?report_date=' + val;
                }
            });

            $('#adminForm').validate({
                rules: {
                    report_date: {
                        required: true,
                        date    : true
                    },
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
                    report_date: {
                        required: "@lang('translation.field_required')",
                        date    : "@lang('translation.invalid_entry')"
                    },
                    employee_id: {
                        required: "@lang('translation.field_required')",
                        positive: "@lang('translation.select_item')"
                    },
                    start_time: {
                        required: "@lang('translation.field_required')",
                        time    : "@lang('translation.select_item')"
                    },
                    end_time: {
                        required: "@lang('translation.field_required')",
                        time    : "@lang('translation.invalid_entry')"
                    }
                },
                submitHandler: function (form) {
                    let errors = false;

                    if (!errors) {
                        form.submit();
                    }
                }
            });

            $('#cancel_button').click(function () {
                window.location = "{{ $returnTo }}";
            });
        });
    </script>
@stop
