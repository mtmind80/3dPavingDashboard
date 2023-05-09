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

    <div class="row estimator-form admin-form">
        <form method="POST" accept-charset="UTF-8" id="estimator_form" class="admin-form">
            @csrf
            <input type="hidden" name="id" id="id" value="{{ $proposalDetail->id }}">
            <input type="hidden" name="service_name" id="name" value="{{ $proposalDetail->service_name }}">
            <input type="hidden" name="services_id" id="services_id" value="{{ $proposalDetail->services_id }}">
            <input type="hidden" name="proposal_id" id="proposal_id" value="{{ $proposalDetail->proposal_id }}">
            <input type="hidden" name="catchbasins" id="catchbasins" value="{{ $proposalDetail->catchbasins }}">
            <input type="hidden" name="linear_feet" id="linear_feet" value="{{ $proposalDetail->linear_feet }}">
            <input type="hidden" name="cost_per_linear_feet" id="cost_per_linear_feet" value="{{ $proposalDetail->cost_per_linear_feet }}">
            <input type="hidden" name="square_feet" id="square_feet" value="{{ $proposalDetail->square_feet }}">
            <input type="hidden" name="square_yards" id="square_yards" value="{{ $proposalDetail->square_yards }}">
            <input type="hidden" name="cubic_yards" id="cubic_yards" value="{{ $proposalDetail->cubic_yards }}">
            <input type="hidden" name="tons" id="tons" value="{{ $proposalDetail->tons }}">
            <input type="hidden" name="loads" id="loads" value="{{ $proposalDetail->loads }}">
            <input type="hidden" name="locations" id="locations" value="{{ $proposalDetail->locations }}">
            
            <input type="hidden" name="depth" id="depth" value="{{ $proposalDetail->depth }}">
            <input type="hidden" name="profit" id="profit" value="{{ $proposalDetail->profit }}">
            <input type="hidden" name="days" id="days" value="{{ $proposalDetail->days }}">
            <input type="hidden" name="cost_per_day" id="cost_per_day" value="{{ $proposalDetail->cost_per_day }}">
            <input type="hidden" name="break_even" id="break_even" value="{{ $proposalDetail->break_even }}">
            <input type="hidden" name="bill_after" id="bill_after" value="{{ $proposalDetail->bill_after }}">
            <input type="hidden" name="proposal_text" id="proposal_text" value="{{ $proposalDetail->proposal_text }}">
            <input type="hidden" name="sealer" id="sealer" value="{{ $proposalDetail->sealer }}">
            <input type="hidden" name="sand" id="sand" value="{{ $proposalDetail->sand }}">
            <input type="hidden" name="additive" id="additive" value="{{ $proposalDetail->additive }}">
            <input type="hidden" name="primer" id="primer" value="{{ $proposalDetail->primer }}">
            <input type="hidden" name="phases" id="phases" value="{{ $proposalDetail->phases }}">
            <input type="hidden" name="yield" id="yield" value="{{ $proposalDetail->yield }}">
            <input type="hidden" name="fastset" id="fastset" value="{{ $proposalDetail->fastset }}">
            <input type="hidden" name="location_id" id="location_id" value="{{ $proposalDetail->location_id }}">
            <input type="hidden" name="cost" id="cost" value="{{ $proposalDetail->cost }}">
            <input type="hidden" name='toncost' id='toncost' value="{{ $proposalDetail->toncost }}">
            <input type="hidden" name='tackcost' id='tackcost' value="{{ $proposalDetail->tackcost }}">



            <input type="hidden" name="vehicle_total_cost" id="estimator_form_vehicle_total_cost" value="">
            <input type="hidden" name="equipment_total_cost" id="estimator_form_equipment_total_cost" value="">
            <input type="hidden" name="labor_total_cost" id="estimator_form_labor_total_cost" value="">
            <input type="hidden" name="additional_cost_total_cost" id="estimator_form_additional_cost_total_cost" value="">
            <input type="hidden" name="materials_cost_total_cost" id="estimator_form_materials_cost_total_cost" value="">

            
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
                    <h5>@lang('translation.service_description_template')</h5>
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

