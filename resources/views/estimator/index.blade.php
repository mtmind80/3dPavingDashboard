@extends('layouts.master')

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.proposal') @lang('translation.estimator')
        @endslot
        @slot('li_1')
            <a href="/dashboard">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            <a href="{{route('show_proposal',['id'=> $proposalDetail->proposal_id])}}">@lang('translation.proposal')</a>
        @endslot
        @slot('li_3')
            @lang('translation.estimator')
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
        <form method="POST" action="{{route('checkform')}}" accept-charfset="UTF-8" id="estimator_form" class="admin-form">
            @csrf
            <input type="hidden" name="id" id="x_id" value="{{ $proposalDetail->id }}">
            <input type="hidden" name="service_name" id="x_service_name" value="{{ $proposalDetail->service_name }}">
            <input type="hidden" name="services_id" id="x_services_id" value="{{ $proposalDetail->services_id }}">
            <input type="hidden" name="proposal_id" id="x_proposal_id" value="{{ $proposalDetail->proposal_id }}">
            <input type="hidden" name="catchbasins" id="x_catchbasins" value="{{ $proposalDetail->catchbasins }}">
            <input type="hidden" name="linear_feet" id="x_linear_feet" value="{{ $proposalDetail->linear_feet }}">
            <input type="hidden" name="cost_per_linear_feet" id="x_cost_per_linear_feet" value="{{ $proposalDetail->cost_per_linear_feet }}">
            <input type="hidden" name="square_feet" id="x_square_feet" value="{{ $proposalDetail->square_feet }}">
            <input type="hidden" name="square_yards" id="x_square_yards" value="{{ $proposalDetail->square_yards }}">
            <input type="hidden" name="cubic_yards" id="x_cubic_yards" value="{{ $proposalDetail->cubic_yards }}">
            <input type="hidden" name="tons" id="x_tons" value="{{ $proposalDetail->tons }}">
            <input type="hidden" name="loads" id="x_loads" value="{{ $proposalDetail->loads }}">
            <input type="hidden" name="locations" id="x_locations" value="{{ $proposalDetail->locations }}">
            <input type="hidden" name="overhead" id="x_overhead" value="{{ $proposalDetail->overhead }}">

            <input type="hidden" name="depth" id="x_depth" value="{{ $proposalDetail->depth }}">
            <input type="hidden" name="profit" id="x_profit" value="{{ $proposalDetail->profit }}">
            <input type="hidden" name="days" id="x_days" value="{{ $proposalDetail->days }}">
            <input type="hidden" name="cost_per_day" id="x_cost_per_day" value="{{ $proposalDetail->cost_per_day }}">
            <input type="hidden" name="break_even" id="x_break_even" value="{{ $proposalDetail->break_even }}">
            <input type="hidden" name="bill_after" id="x_bill_after" value="{{ $proposalDetail->bill_after }}">
            <input type="hidden" name="proposal_text" id="x_proposal_text" value="{{ $proposalDetail->proposal_text }}">
            <input type="hidden" name="sealer" id="x_sealer" value="{{ $proposalDetail->sealer }}">
            <input type="hidden" name="sand" id="x_sand" value="{{ $proposalDetail->sand }}">
            <input type="hidden" name="additive" id="x_additive" value="{{ $proposalDetail->additive }}">
            <input type="hidden" name="primer" id="x_primer" value="{{ $proposalDetail->primer }}">
            <input type="hidden" name="phases" id="x_phases" value="{{ $proposalDetail->phases }}">
            <input type="hidden" name="yield" id="x_yield" value="{{ $proposalDetail->yield }}">
            <input type="hidden" name="fastset" id="x_fastset" value="{{ $proposalDetail->fastset }}">
            <input type="hidden" name="location_id" id="x_location_id" value="{{ $proposalDetail->location_id }}">
            <input type="hidden" name="cost" id="x_cost" value="{{ $proposalDetail->cost }}">
            <input type="hidden" name="material_cost" id="x_material_cost" value="{{ $proposalDetail->material_cost }}">
            <input type="hidden" name='toncost' id='x_toncost' value="{{ $proposalDetail->toncost }}">


            <input type="hidden" name='stayorleave' id='stayorleave' value="false">
            {{-- user will stay  after save --}}

            <input type="hidden" name="vehicle_total_cost" id="estimator_form_vehicle_total_cost" value="{{ $proposalDetail->total_cost_vehicles }}">
            <input type="hidden" name="equipment_total_cost" id="estimator_form_equipment_total_cost" value="{{ $proposalDetail->total_cost_equipment }}">
            <input type="hidden" name="labor_total_cost" id="estimator_form_labor_total_cost" value="{{ $proposalDetail->total_cost_labor }}">
            <input type="hidden" name="additional_cost_total_cost" id="estimator_form_additional_cost_total_cost" value="{{ $proposalDetail->total_additional_costs }}">
            <input type="hidden" name="materials_total_cost" id="estimator_form_materials_total_cost" value="{{ $proposalDetail->material_cost }}">
            <input type="hidden" name="subcontractor_total_cost" id="estimator_form_subcontractor_total_cost" value="{{ $proposalDetail->total_cost_subcontractor }}">
        </form>

        <div class="col-12">
            @include('_partials._alert')
            <div class="card">
                <div class="card-header alert-light">
                    @include('estimator.proposal_header')
                    @include('_partials._alert', ['alertId' => 'header_alert'])
                    @include('estimator.form_header')
                    @include('estimator.form_formulas')
                </div>
            </div>
            {{-- Proposal Text --}}
            <div class="card">
                <div class="card-header alert-light">
                    @include('_partials._alert', ['alertId' => 'service_text_alert'])
                    <h5>@lang('translation.service_description')</h5>
                    @include('estimator.form_service_text')
                </div>
            </div>
            <div class="card">
                <div id="vehicle_section" class="card-header alert-light ptb16">
                    @include('_partials._alert', ['alertId' => 'vehicle_alert'])
                    <h5>@lang('translation.vehicle')</h5>
                    @include('estimator.form_service_vehicles')
                </div>
            </div>
            <div class="card">
                <div class="card-header alert-light">
                    @include('_partials._alert', ['alertId' => 'equipment_alert'])
                    <h5>@lang('translation.equipment')</h5>
                    @include('estimator.form_service_equipment')
                    <div id="equipmentList"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header alert-light">
                    @include('_partials._alert', ['alertId' => 'labor_alert'])
                    <h5>@lang('translation.labor')</h5>
                    @include('estimator.form_service_labor')
                    <div id="laborList"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header alert-light">
                    @include('_partials._alert', ['alertId' => 'additional_costs_alert'])
                    <h5>@lang('translation.additionalcost')</h5>
                    @include('estimator.form_service_additional_costs')
                    <div id="otherList"></div>
                </div>
            </div>
            @if($proposalDetail->service->service_category_id != 10)
            <div class="card">
                <div class="card-header alert-light">
                    @include('_partials._alert', ['alertId' => 'subcontractors_alert'])
                    <h5>@lang('translation.subcontractors')</h5>
                    @include('estimator.form_service_subcontractors')
                    <div id="subcontractorsList"></div>
                </div>
            </div>
            @endif
        </div>
    </div>

    @include('estimator.wrapitup')

@endsection

@section('page-js')
    <script>
        var proposalDetailId = Number("{{ $proposalDetail->id }}");
        var serviceId = Number("{{ $proposalDetail->services_id }}");
        var serviceCategoryId = Number("{{ $proposalDetail->service->service_category_id }}");
        var serviceCategoryName = "{{ $service_category_name }}";
        var proposalId = Number("{{ $proposalDetail->proposal_id }}");

    </script>
@stop

