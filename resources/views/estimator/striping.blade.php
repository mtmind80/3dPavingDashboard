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
            @lang('translation.estimator') Striping Pavement Markings
        @endslot
    @endcomponent

    <div class="row">

        <form method="POST" action="{{route('save_striping')}}" id="estimator_form" class="custom-validation admin-form w-100">

        <div class="col-12 w-100">

                @csrf
                <input type="hidden" name='stayorleave' id='stayorleave' value="">
                <input type="hidden" name="proposal_detail_id" value="{{$proposalDetail->id}}">
                <input type="hidden" name="profit" id="x_profit" value="{{$proposalDetail->profit}}">
                <input type="hidden" name="x_proposal_text" id="x_proposal_text" value="{{$proposalDetail->proposal_text}}">
                <input type="hidden" name="material_cost" id="x_materials" value="{{$proposalDetail->material_cost}}">
                <input type="hidden" name="cost" id="x_cost" value="{{$proposalDetail->cost}}">
                <input type="hidden" name="proposal_id" value="{{$proposalDetail->proposal_id}}">
                <input type="hidden" name="overhead" id="x_overhead" value="{{ $proposalDetail->overhead }}">
                @include('_partials._alert')
            <div class="card">
                <div class="card-header alert-light">
                    @include('estimator.proposal_header')
                    @include('_partials._alert', ['alertId' => 'header_alert'])
                    @include('estimator.striping_form_header')
                </div>

            </div>
            {{-- Proposal Text --}}
            <div class="card">
                <div class="card-header alert-light">
                    @include('_partials._alert', ['alertId' => 'service_text_alert'])
                    @include('estimator.form_service_text')
                </div>
            </div>
            <div class="card">
                <div class="card-header alert-light">
                    @include('estimator.form_striping_formulas')
                </div>

            </div>


        </div>
        </form>

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

