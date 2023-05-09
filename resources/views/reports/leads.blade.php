@extends('layouts.master')

@section('title') 3D Paving Reports @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Reports @endslot
        @slot('li_1') <a href="/dashboard">Home</a> @endslot
        @slot('li_2') <a href="{{ route('reports') }}">@lang('translation.reports')</a> @endslot
        @slot('li_3') @lang('translation.leads') @lang('translation.report') @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- form section -->

                    <div class="row">
                        <div class="col-sm-12 mb0">
                            <h5 class="mb-4">@lang('translation.leadreport')</h5>
                            <form action="{{ route('leads_report_export') }}" method="post" id="reportForm">
                                @csrf
                                @include('_partials._alert')
                                <div class="row">
                                    <div class="col-lg-10 col-md-12 admin-form-item-widget"></div>
                                    <div class="col-lg-2 col-md-12 admin-form-item-widget">
                                        <x-form-select name="year" :items="$yearsCB" selected="{{ date('Y') }}" :params="['label' => 'Year', 'required' => false]"></x-form-select>
                                    </div>
                                </div>
                                <div class="row mb0">
                                    <div class="col-lg-3 col-md-4 admin-form-item-widget"></div>
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
                                    <h5>@lang('translation.leads') @lang('translation.report')</h5>
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
                            <th class="tc w400">@lang('translation.Source')</th>
                            <th class="tc w300">@lang('translation.Leads')</th>
                            <th class="tc w300">@lang('translation.Converted')</th>
                            <th class="tc w300">@lang('translation.sales')</th>
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
            var year = $('#year');
            var selectedYear = year.val();

            var tbody = $('#table_body');
            var noRowsP = $('#no_rows_p');
            var reportHeader = $('#report_header');
            var reportTable = $('#report_table');

            year.change(function(){
                resetAll();

                selectedYear = $(this).val();
            });

            $('#create_report').click(function (){
                if (selectedYear === '') {
                    showErrorAlert('You must select year.');
                    return false;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        year: year.val()
                    },
                    type: "POST",
                    url: "{{ route('leads_report_ajax_view') }}",
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
                            let html = '';
                            $.each(response.rows, function (index, row){
                                html +=
                                '<tr class="">' +
                                    '<td class="tc">'+ row.source +'</td>' +
                                    '<td class="tc">'+ row.leads +'</td>' +
                                    '<td class="tc">'+ row.converted +'</td>' +
                                    '<td class="tc">'+ row.sales_cost +'</td>' +
                                '</tr>'
                            });
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
                tbody.empty();
                noRowsP.removeClass('hidden');
                reportHeader.addClass('hidden');
                reportTable.addClass('hidden');
            }
        });
    </script>
@stop

