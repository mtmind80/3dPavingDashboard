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
            @lang('translation.dashboard7')
        @endslot
        @slot('li_1')
            3D Paving
        @endslot
        @slot('li_2')
            @lang('translation.dashboard7')
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
            <div class="tab-pane active" id="dashboard_7" role="tabpanel">
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">@lang('translation.dashboard7')</h4>
                                <div id="donut_chart_workorders_by_county_7" class="apex-charts"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb20">@lang('translation.dashboard7') </h4>
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                        <tr>
                                            <th class="tc">@lang('translation.Customer') @lang('translation.type')</th>
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
                                                <td class="tc">{{ $value['county'] }}</td>
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


                if ($('#donut_chart_workorders_by_county_7').html() === "") {
                    var donutChartWorkordersByCounty = new ApexCharts(document.querySelector('#donut_chart_workorders_by_county_7'), donutWorkorderByCountyOptions);
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

            </script>
@endsection


