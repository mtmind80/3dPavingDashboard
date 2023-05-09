@extends('layouts.master')

@section('title')
    @lang('translation.Dashboard')
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/jquery-vectormap/jquery-vectormap.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <!-- Responsive datatable examples -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    @component('components.breadcrumb_filter', [
            'yearsCB'         => $yearsCB ?? null,
            'selectedYear'    => $selectedYear ?? null,
            'creatorsCB'      => $creatorsCB ?? null,
            'creatorId'       => $creatorId ?? null,
            'salesManagersCB' => $salesManagersCB ?? null,
            'salesManagerId'  => $salesManagerId ?? null,
            'salesPersonsCB'  => $salesPersonsCB ?? null,
            'salesPersonId'   => $salesPersonId ?? null
        ])
        @slot('title')
            @lang('translation.dashboard3')
        @endslot
        @slot('li_1')
            3D Paving
        @endslot
        @slot('li_2')
            @lang('translation.dashboard3')
        @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-md-12">
            <div class="card tabs-container">
                <div class="card-body">
                    <!-- Nav tabs -->
                    @include('layouts.nav_tabs')

                    <!-- Tab panes -->
                    <div class="tab-content active text-muted">
                        <div class="tab-pane active" id="dashboard_3" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title mb-4">@lang('translation.work_orders_by_service_category')</h4>
                                            <div id="donut_chart_sales_by_service_category" class="apex-charts"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">

                                    <div class="card">
                                        <div class="card-body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th class="tc">Service</th>
                                                    <th class="tc">Sales</th>
                                                </tr>
                                                @php
                                                    $totalcost =0;
                                                @endphp
                                                @foreach($services as $service)
                                                    <tr>
                                                        <td class="tc">
                                                            {{$service['service']}}
                                                        </td>
                                                        <td class="tc">
                                                            @php
                                                                $totalcost = $totalcost + $service['cost'];
                                                            @endphp

                                                            {{ \App\Helpers\Currency::format($service['cost'] ?? '0.0') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td class="tc">
                                                        Total
                                                    </td>
                                                    <td class="tc">
                                                        {{ \App\Helpers\Currency::format($totalcost ?? '0.0') }}
                                                    </td>
                                                </tr>


                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    @include('modals.scrollable_modal')
@endsection
@section('script')
    <!-- plugin js -->
    <script src="{{ URL::asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>

    <!-- jquery.vectormap map -->
    <script src="{{ URL::asset('/assets/libs/jquery-vectormap/jquery-vectormap.min.js')}}"></script>

    <!-- Responsive examples -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js')}}"></script>

@endsection

@section('page-js')
    <script>
        var formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',

            // These options are needed to round to whole numbers if that's what you want.
            //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
            //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
        });


        /**  Donut chart -  Sales by service category  */

        var donutWorkorderByServiceCategory = {!! $donutWorkorderByServiceCategory !!};

        var donutWorkorderByServiceCategoryOptions = {
            series: donutWorkorderByServiceCategory.series, labels: donutWorkorderByServiceCategory.labels, chart: {
                height: 350, type: 'donut',
            }, plotOptions: {
                pie: {
                    donut: {
                        size: '50%'
                    }
                }
            }, dataLabels: {
                enabled: true
            }, legend: {
                show: true
            },
            tooltip: {
                x: {
                    show: false
                },
                y: {
                    formatter: function (value) {
                        return formatter.format(value);
                        ;
                    }
                }
            }
            // colors: ['#5664d2', '#1cbb8c', '#eeb902'],
        };

        if ($('#donut_chart_sales_by_service_category').html() === "") {
            var donutChartSalesByServiceCategory = new ApexCharts(document.querySelector('#donut_chart_sales_by_service_category'), donutWorkorderByServiceCategoryOptions);
            donutChartSalesByServiceCategory.render();
        }

        var body = $('body');

        var assignedTo = $('#assigned_to');
        var returnTo = $('#form_managers_modal_return_to');
        returnTo.val("{{ route('dashboard') }}");


        $('.component-breadcrumb-filters').on('change', 'select', function () {
            // window.location = $(this).find('option:selected').data('url')

            $(this).closest('.component-breadcrumb-filters').find('.submit-button').removeClass('hidden');
        });

        $('.component-breadcrumb-filters').on('click', '.submit-button', function () {
            let button = $(this)
            let container = button.closest('.component-breadcrumb-filters');
            let filters = container.find('select');
            let queryArray = [];
            let queryStr = '';
            let url = "{{ Request::url() }}";

            button.addClass('hidden');

            filters.each(function () {
                let el = $(this);
                let val = el.val();

                console.log(val);

                if (val !== 'all') {
                    queryArray.push(el.attr('name') + '=' + val);
                }
            });

            queryStr = queryArray.join('&');

            if (queryStr !== '') {
                url = url + '?' + queryStr;
            }

            window.location = url;
        });

        $('.county-link').click(function () {
            var el = $(this);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    selected_year: $('#selected_year').val(),
                    creator_id: $('#creator_id').val() != 'all' ? $('#creator_id').val() : null,
                    sales_manager_id: $('#sales_manager_id').val() != 'all' ? $('#sales_manager_id').val() : null,
                    sales_person_id: $('#sales_person_id').val() != 'all' ? $('#sales_person_id').val() : null,
                    county: el.data('county')
                },
                type: "POST",
                url: "{{ route('ajax_get_zips_per_county') }}",     // ajax_weather_city_forecast
                beforeSend: function (request) {
                    showSpinner();
                },
                complete: function () {
                    hideSpinner();
                    el.closest('.btn-group').removeClass('open');
                },
                success: function (response) {
                    if (response.success) {
                        if (response.html) {
                            let tbody = $('#tbodyDataByZip');
                            let county = $('#county_name');

                            tbody.empty().html(response.html);
                            county.html(response.county);

                            $('#modalDataByZip').modal('show');
                        } else {
                            uiAlert({type: 'info', title: 'Info', text: 'There is no data available.'});
                        }
                    } else {
                        uiAlert({type: 'error', title: 'Error', text: response.message});
                    }
                },
                error: function (data) {
                    hideSpinner();
                    alert('error');
                    console.log(data);
                }
            });
        });
    </script>
@endsection


