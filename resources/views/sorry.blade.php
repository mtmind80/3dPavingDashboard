@extends('layouts.master')

@section('title') @lang('translation.Dashboard') @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/jquery-vectormap/jquery-vectormap.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <!-- Responsive datatable examples -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('title') @lang('translation.Dashboard') @endslot
        @slot('li_1') <a href="/simple_dashboard">@lang('translation.Dashboard')</a>@endslot
        @slot('li_2') @lang('translation.Dashboard') @endslot
    @endcomponent
    
    
    <div class="row">
            </div>
            <!-- end row -->

            {{--
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">@lang('translation.revenue_analytics')</h4>
                    <div>
                        <div id="line-column-chart" class="apex-charts" dir="ltr" style="display:none;"></div>
                        <div id="line_column_chart" class="apex-charts" dir="ltr"></div>
                    </div>
                </div>

                <div class="card-body border-top text-center">
                    <div class="row"></div>
                </div>
            </div>
            --}}
        </div>


        <!-- end row -->
        <div class="col-xl-4">
            {{--
            <div class="card">
                <div class="card-body">
                    <div class="float-right">
                        <select class="custom-select custom-select-sm">
                            <option selected>Apr</option>
                            <option value="1">Mar</option>
                            <option value="2">Feb</option>
                            <option value="3">Jan</option>
                        </select>
                    </div>
                    <h4 class="card-title mb-4">@lang('translation.sales_analytics')</h4>

                    <div id="donut-chart" class="apex-charts" style="display:none;"></div>
                    <div id="donut_chart" class="apex-charts"></div>

                    <div class="row">
                        <div class="col-4">
                            <div class="text-center mt-4">
                                <p class="mb-2 text-truncate"><i
                                            class="mdi mdi-circle text-primary font-size-10 mr-1"></i> Asphalt</p>
                                <h5>42 %</h5>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center mt-4">
                                <p class="mb-2 text-truncate"><i
                                            class="mdi mdi-circle text-success font-size-10 mr-1"></i> Rock</p>
                                <h5>26 %</h5>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center mt-4">
                                <p class="mb-2 text-truncate"><i
                                            class="mdi mdi-circle text-warning font-size-10 mr-1"></i> Striping</p>
                                <h5>42 %</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    <h4 class="card-title mb-4">@lang('translation.earningsytd')</h4>
                    <div class="text-center">
                        <div class="row">
                            <div class="col-sm-6">
                                <div>
                                    <div class="mb-3">
                                        <div id="radialchart-1" class="apex-charts"></div>
                                    </div>

                                    <p class="text-muted text-truncate mb-2">@lang('translation.earningsytd')</p>
                                    <h5 class="mb-0">$2,523</h5>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mt-5 mt-sm-0">
                                    <div class="mb-3">
                                        <div id="radialchart-2" class="apex-charts"></div>
                                    </div>

                                    <p class="text-muted text-truncate mb-2">@lang('translation.earningsytd')</p>
                                    <h5 class="mb-0">$11,235</h5>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            --}}
        </div>
    </div>

@endsection

@section('script')
    <!-- Responsive examples -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js')}}"></script>

    <script src="{{ URL::asset('/assets/js/pages/dashboard.init.js')}}"></script>
@endsection



