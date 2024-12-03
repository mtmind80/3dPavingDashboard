@extends('layouts.master')

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.work_orders') @lang('translation.services')
        @endslot
        @slot('li_1')
            <a href="/dashboard">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            <a href="{{route('show_workorder',['id'=> $proposalDetail->proposal_id])}}">@lang('translation.work_orders')</a>
        @endslot
        @slot('li_3')
            @lang('translation.services')
        @endslot
    @endcomponent
<script>
    //globals
    var current_subContractor_total =0;
    var current_labor_total =0;
    var current_vehicle_total =0;
    var current_equipment_total =0;
    var current_additional_total =0;


</script>
    <div class="row estimator-form admin-form">
        <div class="font-weight-semibold"></div>
        <div class="col-12">
            @include('_partials._alert')
            <div class="card">
                <div class="card-header alert-light">
                    @include('estimator.proposal_header_View')
                    @include('_partials._alert', ['alertId' => 'header_alert'])
                    @include('estimator.form_headerView')
                    @include('estimator.form_formulasView')
                </div>
            </div>
            {{-- Proposal Text --}}
            <div class="card">
                <div class="card-header alert-light">
                    @include('_partials._alert', ['alertId' => 'service_text_alert'])
                    @include('estimator.form_service_textView')
                </div>
            </div>
            <div class="card">
                <div id="vehicle_section" class="card-header alert-light ptb16">
                    @include('_partials._alert', ['alertId' => 'vehicle_alert'])
                    <h5>@lang('translation.vehicle')</h5>
                    @include('estimator.form_service_vehiclesView')
                </div>
            </div>
            <div class="card">
                <div class="card-header alert-light">
                    @include('_partials._alert', ['alertId' => 'equipment_alert'])
                    <h5>@lang('translation.equipment')</h5>
                    @include('estimator.form_service_equipmentView')
                    <div id="equipmentList"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header alert-light">
                    @include('_partials._alert', ['alertId' => 'labor_alert'])
                    <h5>@lang('translation.labor')</h5>
                    @include('estimator.form_service_laborView')
                    <div id="laborList"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header alert-light">
                    @include('_partials._alert', ['alertId' => 'additional_costs_alert'])
                    <h5>@lang('translation.additionalcost')</h5>
                    @include('estimator.form_service_additional_costsView')
                    <div id="otherList"></div>
                </div>
            </div>
            @if($proposalDetail->service->service_category_id != 10)
            <div class="card">
                <div class="card-header alert-light">
                    @include('_partials._alert', ['alertId' => 'subcontractors_alert'])
                    <h5>@lang('translation.subcontractors')</h5>
                    @include('estimator.form_service_subcontractorsView')
                    <div id="subcontractorsList"></div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@section('script')

    <!-- jquery.vectormap map -->
    <script src="{{ URL::asset('/assets/libs/jquery-vectormap/jquery-vectormap.min.js')}}"></script>

    <!-- Responsive examples -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js')}}"></script>

@endsection

@section('page-js')
    <script>
        var proposalDetailId = Number("{{ $proposalDetail->id }}");
        var serviceId = Number("{{ $proposalDetail->services_id }}");
        var serviceCategoryId = Number("{{ $proposalDetail->service->service_category_id }}");
        var serviceCategoryName = "{{ $service_category_name }}";
        var proposalId = Number("{{ $proposalDetail->proposal_id }}");
        var mainAlert = $('#alert');

    </script>
@stop

