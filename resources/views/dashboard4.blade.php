@extends('layouts.master')

@section('title')
    @lang('translation.Dashboard')
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
            @lang('translation.dashboard4')
        @endslot
        @slot('li_1')
            3D Paving
        @endslot
        @slot('li_2')
            @lang('translation.dashboard4')
        @endslot
    @endcomponent

    <div class="card tabs-container">
        <div class="card-body">
            <!-- Nav tabs -->
            @include('layouts.nav_tabs')
        </div>
    </div>
    </div>
    <div class="col-lg-12">
        <!-- Tab panes -->
        <div class="tab-content active text-muted">
            <div class="tab-pane active" id="dashboard_4" role="tabpanel">
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">@lang('translation.work_orders_by_county')</h4>
                                <div id="donut_chart_workorders_by_county" class="apex-charts"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb20">@lang('translation.sales_by_county') </h4>
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                        <tr>
                                            <th class="tc">@lang('translation.county')</th>
                                            <th class="tc">@lang('translation.work_orders')</th>
                                            <th class="tc">@lang('translation.sales')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $total = 0;
                                        ?>

                                        @foreach ($counties as $key => $value)
                                                <?php
                                                $total = $total + ($value['sales'] ?? '0.0');
                                                ?>
                                            <tr>
                                                <td class="tc"><a href="javascript:" class="county-link"
                                                                  data-county="{{ $key }}">{{ $key }}</a></td>
                                                <td class="tc">{{ $value['work_orders'] ?? '0' }}</td>
                                                <td class="tc">{{ \App\Helpers\Currency::format($value['sales'] ?? '0.0') }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="tc">TOTAL</td>
                                            <td class="tc"></td>
                                            <td class="tc">{{ \App\Helpers\Currency::format($total ?? '0.0') }}</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @include('modals.scrollable_modalcity')
@endsection

@section('script')
    <!-- plugin js -->
    <script src="{{ URL::asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>


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


                /**  Donut chart -  Workorders per county  */

                var donutWorkorderByCounty = {!! $donutWorkorderByCounty !!};

                var donutWorkorderByCountyOptions = {
                    series: donutWorkorderByCounty.series, labels: donutWorkorderByCounty.labels, chart: {
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
                    }
                    // colors: ['#5664d2', '#1cbb8c', '#eeb902'],
                };


                if ($('#donut_chart_workorders_by_county').html() === "") {
                    var donutChartWorkordersByCounty = new ApexCharts(document.querySelector('#donut_chart_workorders_by_county'), donutWorkorderByCountyOptions);
                    donutChartWorkordersByCounty.render();
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
                        url: "{{ route('ajax_get_zips_per_city') }}",     // ajax_weather_city_forecast
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
                                    let tbody = $('#tbodyDataByCity');
                                    let county = $('#county_name');

                                    tbody.empty().html(response.html);
                                    county.html(response.county);

                                    $('#modalDataByCity').modal('show');
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


