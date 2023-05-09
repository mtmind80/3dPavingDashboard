@extends('layouts.master')

@section('title') 3D Paving Reports @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Reports @endslot
        @slot('li_1') <a href="/dashboard">Home</a> @endslot
        @slot('li_2') <a href="{{ route('reports') }}">@lang('translation.reports')</a> @endslot
        @slot('li_3') @lang('translation.activitybystatusreport') @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- form section -->

                    <div class="row">
                        <div class="col-sm-12 mb0">
                            <h5>@lang('translation.activitybystatusreport')</h5>
                            <p>This report is based on Proposal Created Date, and will show the current "Status" of all "Proposals Created" during that date range.</p>
                            <form action="{{ route('activity_by_status_report_export') }}" method="post" id="reportForm">
                                @csrf
                                @include('_partials._alert')
                                <div class="row">
                                    <div class="col-lg-3 col-md-12 admin-form-item-widget">
                                        <x-form-date-range-picker
                                            from="from_date"
                                            to="to_date"
                                            class=""
                                            :params="[
                                                'id' => 'activity_by_status_date_range_picker',
                                                'label' => 'Select Date Range',
                                                'iconClass' => 'fas fa-calendar',
                                                'min_date' => $minDate,
                                                'max_date' => $maxDate
                                            ]"
                                        ></x-form-date-range-picker>
                                    </div>
                                    <div class="col-lg-7 col-md-12 admin-form-item-widget">
                                        <x-form-radio-group
                                            id="radio_group"
                                            name="quarter"
                                            :radios="$radios"
                                            :params="['direction' => 'h', 'label' => 'Or Select Quarter']"
                                        ></x-form-radio-group>
                                    </div>
                                    <div class="col-lg-2 col-md-12 admin-form-item-widget">
                                        <x-form-select name="year" :items="$yearsCB" selected="{{ date('Y') }}" :params="['label' => 'Of Year', 'required' => false]"></x-form-select>
                                    </div>
                                </div>
                                <div class="row mb0">
                                    <div class="col-lg-3 col-md-4 admin-form-item-widget">
                                        <x-form-select name="salesperson_id" :items="$salespersonsCB" :params="['label' => __('translation.salesperson'), 'required' => false]"></x-form-select>
                                    </div>
                                    <div class="col-lg-7 col-md-5 admin-form-item-widget xs-hidden"> </div>
                                    <div class="col-lg-2 col-md-3 mb-2 tr">
                                        <x-href-button class="btn-primary not-xs-mt40" id="create_report">@lang('translation.create_report')</x-href-button>
                                        <x-button class="btn-info not-xs-mt40 ml8" type='reset' id="reset">@lang('translation.reset')</x-button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <hr class="mb-4">

                    <div id="report_header" class="row hidden">
                        <div class="col-sm-12 mb20">
                            <div class="d-flex flex-row flex-xxl-column justify-content-between align-items-center">
                                <div class="">
                                    <h5>@lang('translation.activitybystatusreport')</h5>

                                </div>
                                <div class="">
                                    <x-href-button id="export_report" class="btn-success"><i class="fas fa-file-excel"></i>@lang('translation.export')</x-href-button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- report -->
                    <table id="report_table" class="hidden list-table table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th class="tc w400">@lang('translation.activitybystatusreport')</th>
                            <th class="tc w300">@lang('translation.from') <span id="th_from"></span></th>
                            <th class="tc w300">@lang('translation.to') <span id="th_to"></span></th>
                            <th class="tc ">&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody id="table_body">
                        </tbody>
                    </table>

                    <p id="no_rows_p">Define the report criteria and click Create Report button</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script>
        $(document).ready(function(){
            var dateRangePicker = $('#activity_by_status_date_range_picker');
            var radioGroup = $('#radio_group');

            var year = $('#year');
            var selectedYear = year.val();

            var startDate;
            var endDate;
            var today = moment();

            var fromDate = $('#from_date');
            var toDate = $('#to_date');

            var salesPersonId = $('#salesperson_id');

            var tbody = $('#table_body');
            var noRowsP = $('#no_rows_p');
            var reportHeader = $('#report_header');
            var reportTable = $('#report_table');
            var thFrom = $('#th_from');
            var thTo = $('#th_to');

            dateRangePicker.on('apply.daterangepicker', function(ev, picker){
                radioGroup.find('input:radio').prop('checked', false);
            });

            radioGroup.on('click', 'input:radio', function(ev){
                let el = $(this);

                switch (el.val()) {   // selectedYear
                    case '1':
                        startDate = moment(1, "MM").year(selectedYear).startOf('month');
                        endDate = moment(3, "MM").year(selectedYear).endOf('month');
                        break;
                    case '2':
                        startDate = moment(4, "MM").year(selectedYear).startOf('month');
                        endDate = moment(6, "MM").year(selectedYear).endOf('month');
                        break;
                    case '3':
                        startDate = moment(7, "MM").year(selectedYear).startOf('month');
                        endDate = moment(9, "MM").year(selectedYear).endOf('month');
                        break;
                    case '4':
                        startDate = moment(10, "MM").year(selectedYear).startOf('month');
                        endDate = moment(12, "MM").year(selectedYear).endOf('month');
                        break;
                }

                if (moment(startDate).isAfter(today)) {
                    showErrorAlert.removeClass('hidden').find('.message').html('Start date cannot be after today.');
                    return false;
                }

                if (moment(endDate).isAfter(today)) {
                    endDate = today;
                }

                dateRangePicker.data('daterangepicker').setStartDate(startDate);
                dateRangePicker.data('daterangepicker').setEndDate(endDate);

                fromDate.val(startDate.format('YYYY-MM-DD'));
                toDate.val(endDate.format('YYYY-MM-DD'));
            });

            year.change(function(){
                resetAll();

                selectedYear = $(this).val();
            });

            $('#create_report').click(function (){
                if (fromDate.val() === '' || toDate.val() === '') {
                    showErrorAlert('You must select date range.');
                    return false;
                }

                if (!isISODate(fromDate.val()) && !isISODate(toDate.val())) {
                    showErrorAlert('date range are incorrect.');
                    return false;
                }

                if (typeof startDate === 'undefined') {
                    startDate = moment(fromDate.val(), "YYYY-MM-DD");
                }
                if (typeof endDate === 'undefined') {
                    endDate = moment(toDate.val(), "YYYY-MM-DD");
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        from_date: fromDate.val(),
                        to_date: toDate.val(),
                        salesperson_id: salesPersonId.val()
                    },
                    type: "POST",
                    url: "{{ route('activity_by_status_report_ajax_view') }}",
                    beforeSend: function (request){
                        showSpinnerWithText('Building report<br>Please wait...');
                        tbody.empty();
                        noRowsP.addClass('hidden');
                    },
                    complete: function (){
                        hideSpinnerWithText()
                    },
                    success: function (response){
                        if (response.success) {

                            $('#th_from').text(fromDate.val());
                            $('#th_to').text(toDate.val());

                            let html = '';
                            $.each(response.rows, function (sales_person_name, row){
                                html +=
                                '<tr class="tr-header alert-info">' +
                                    '<td class="tc">'+ "{{ __('translation.salesperson') }}" +'</td>' +
                                    '<td class="tc"><b>'+ sales_person_name +'</b></td>' +
                                    '<td class="tc">&nbsp;</td>' +
                                    '<td class="tc">&nbsp;</td>' +
                                '</tr>' +
                                '<tr class="tr-header">' +
                                    '<td class="tc">'+ "{{ __('translation.status') }}" +'</td>' +
                                    '<td class="tc">'+ "{{ __('translation.numberofjobs') }}" +'</td>' +
                                    '<td class="tc">'+ "{{ __('translation.formatted_cost') }}" +'</td>' +
                                    '<td class="tc">&nbsp;</td>' +
                                '</tr>' +
                                '<tr class="">' +
                                    '<td class="tc">Pending</td>' +
                                    '<td class="tc">'+ row.Pending.total_jobs +'</td>' +
                                    '<td class="tc">'+ row.Pending.formatted_cost +'</td>' +
                                    '<td class="tc">&nbsp;</td>' +
                                '</tr>' +
                                '<tr class="">' +
                                    '<td class="tc">Rejected</td>' +
                                    '<td class="tc">'+ row.Rejected.total_jobs +'</td>' +
                                    '<td class="tc">'+ row.Rejected.formatted_cost +'</td>' +
                                    '<td class="tc">&nbsp;</td>' +
                                '</tr>' +
                                '<tr class="">' +
                                    '<td class="tc">Active</td>' +
                                    '<td class="tc">'+ row.Active.total_jobs +'</td>' +
                                    '<td class="tc">'+ row.Active.formatted_cost +'</td>' +
                                    '<td class="tc">&nbsp;</td>' +
                                '</tr>' +
                                '<tr class="">' +
                                    '<td class="tc">Completed</td>' +
                                    '<td class="tc">'+ row.Completed.total_jobs +'</td>' +
                                    '<td class="tc">'+ row.Completed.formatted_cost +'</td>' +
                                    '<td class="tc">&nbsp;</td>' +
                                '</tr>' +
                                '<tr class="">' +
                                    '<td class="tc">Cancelled</td>' +
                                    '<td class="tc">'+ row.Cancelled.total_jobs +'</td>' +  // Active Completed Cancelled Billed Paid
                                    '<td class="tc">'+ row.Cancelled.formatted_cost +'</td>' +
                                    '<td class="tc">&nbsp;</td>' +
                                '</tr>' +
                                '<tr class="">' +
                                    '<td class="tc">Billed</td>' +
                                    '<td class="tc">'+ row.Billed.total_jobs +'</td>' +
                                    '<td class="tc">'+ row.Billed.formatted_cost +'</td>' +
                                    '<td class="tc">&nbsp;</td>' +
                                '</tr>' +
                                '<tr class="">' +
                                    '<td class="tc">Paid</td>' +
                                    '<td class="tc">'+ row.Paid.total_jobs +'</td>' +
                                    '<td class="tc">'+ row.Paid.formatted_cost +'</td>' +
                                    '<td class="tc">&nbsp;</td>' +
                                '</tr>' +
                                '<tr class="tr-footer">' +
                                    '<td class="tc">Total</td>' +
                                    '<td class="tc"><b>'+ sales_person_name +'</b></td>' +
                                    '<td class="tc">'+ row.formatted_global_cost +'</td>' +
                                    '<td class="tc">&nbsp;</td>' +
                                '</tr>';
                            });

                            thFrom.html(startDate.format('MM/DD/YYYY'));
                            thTo.html(endDate.format('MM/DD/YYYY'));

                            tbody.html(html);

                            reportHeader.removeClass('hidden');
                            reportTable.removeClass('hidden');
                        } else {
                            showErrorAlert(response.error);
                        }
                    },
                    error: function (data){
                        hideSpinnerWithText()
                        showErrorAlert('error');
                    }
                });

                // reportForm export_report

                $('#export_report').click(function(){
                    $('#reportForm').submit();
                });
            });
            function resetAll()
            {
                radioGroup.find('input:radio').each(function(){
                    let el = $(this);
                    $(this).prop('checked', false);
                });

                dateRangePicker.val('');

                fromDate.val('');
                toDate.val('');

                tbody.empty();
                noRowsP.removeClass('hidden');
                reportHeader.addClass('hidden');
                reportTable.addClass('hidden');
            }
        });
    </script>
@stop

