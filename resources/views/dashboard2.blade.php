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
            @lang('translation.dashboard2')
        @endslot
        @slot('li_1')
            3D Paving
        @endslot
        @slot('li_2')
            @lang('translation.dashboard2')
        @endslot
    @endcomponent


    <div class="row admin-form">
        <div class="col-md-12">
            <div class="card tabs-container">

                <div class="card-body">
                    <!-- Nav tabs -->
                    @include('layouts.nav_tabs')

                    <!-- Tab panes -->
                    <div class="tab-content text-muted">
                        <div class="tab-pane" id="dashboard_1" role="tabpanel">

                        </div>
                    </div>
                    <div class="tab-content active text-muted">
                        <div class="tab-pane active" id="dashboard_2" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="media">
                                                        <div class="media-body overflow-hidden">
                                                            <p class="text-truncate font-size-14 mb-2">@lang('translation.proposals_created')</p>
                                                            <h4 class="mb-0">{{ $proposalsCreated }}</h4>
                                                        </div>
                                                        <div class="text-primary">
                                                            <i class="ri-briefcase-4-line font-size-24"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body border-top py-3">
                                                    <div class="text-truncate">
                                                        @if ($selectedYear)
                                                            <span class="text-muted ml-2">{{$selectedYear}}</span>
                                                        @endif

                                                        {{--  <span class="badge badge-soft-success font-size-11"><i class="mdi mdi-menu-up"> </i> 2.4% </span>
                                                        <span class="text-muted ml-2">@lang('translation.previous_period')</span>
                                                        --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="media">
                                                        <div class="media-body overflow-hidden">
                                                            <p class="text-truncate font-size-14 mb-2">@lang('translation.workorderslabel')</p>
                                                            <h4 class="mb-0">{{ $workOrdersCreated }}</h4>
                                                        </div>
                                                        <div class="text-primary">
                                                            <i class="ri-stack-line font-size-24"></i>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card-body border-top py-3">
                                                    <div class="text-truncate">
                                                        @if ($selectedYear)
                                                            <span class="text-muted ml-2">{{$selectedYear}}</span>
                                                        @endif

                                                        <span class="text-muted ml-2">{{$percent}}% Converted</span>
                                                        {{--  <span class="badge badge-soft-success font-size-11"><i class="mdi mdi-menu-up"> </i> 2.4% </span>
                                                        <span class="text-muted ml-2">@lang('translation.previous_period')</span>
                                                        --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="media">
                                                        <div class="media-body overflow-hidden">
                                                            <p class="text-truncate font-size-14 mb-2">@lang('translation.sales_revenue')</p>
                                                            <h4 class="mb-0"> {{ \App\Helpers\Currency::format($totalSalesRevenue ?? '0.0') }}</h4>
                                                        </div>
                                                        <div class="text-primary">
                                                            <i class="ri-store-2-line font-size-24"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body border-top py-3">
                                                    <div class="text-truncate">
                                                        @if ($selectedYear)
                                                            <span class="text-muted ml-2">{{$selectedYear}}</span>
                                                        @endif

                                                        {{--  <span class="badge badge-soft-success font-size-11"><i class="mdi mdi-menu-up"> </i> 2.4% </span>
                                                        <span class="text-muted ml-2">@lang('translation.previous_period')</span>
                                                        --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- dashboard for field managers --}}
                                        @if (auth()->user()->role_id == 5)
                                            Field manager
                                        @endif
                                        {{-- dashboard for field labor --}}
                                        @if (auth()->user()->role_id == 6)
                                            Field Labor
                                        @endif

                                    </div>
                                    <!-- end row -->

                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12">

                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title mb-4">Sales Goals</h4>
                                            &nbsp; You have reached {{$salespercent}}% of your sales goal !

                                            <div id="sales_goals" class="apex-charts" dir="ltr"></div>
                                        </div>
                                        <!--
                                        GOAL:{{ \App\Helpers\Currency::format($salesgoals ?? '0.0') }}
                                        SOLD:{{ \App\Helpers\Currency::format($totalSalesRevenue ?? '0.0') }}
                                        -->

                                    </div><!--end card-->
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

        var LineChartSales = {!! $LineChartSales !!};

        var options = {
            series: [{
                data: LineChartSales.data
            }],
            chart: {
                type: 'bar',
                height: 200
            },
            plotOptions: {
                bar: {
                    borderRadius: 3,
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: true
            },
            xaxis: {
                categories: LineChartSales.labels,
            }
        };
        if ($('#sales_goals').html() === "") {
            var chart = new ApexCharts(document.querySelector("#sales_goals"), options);
            chart.render();
        }

        var body = $('body');

        var assignedTo = $('#assigned_to');
        var returnTo = $('#form_managers_modal_return_to');
        returnTo.val("{{ route('dashboard_2') }}");


        body.on('click', '.actions .action[data-action="archive"]', function () {
            uiConfirm({
                callback: 'confirmArchive',
                text: $(this).attr('data-text'),
                params: [$(this).attr('data-route')]
            });
        });

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


