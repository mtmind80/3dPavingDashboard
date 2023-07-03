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
            @lang('translation.estimator') Striping
        @endslot
    @endcomponent
<script>
    //globals
    var current_subContractor_total =0;
    var current_labor_total =0;
    var current_vehicle_total =0;
    var current_equipment_total =0;
    var current_additional_total =0;



    // Create our number formatter.
    const formatCurrency = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        // These options are needed to round to whole numbers if that's what you want.
        //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
        //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
    });


</script>
    <div class="row estimator-form admin-form">
        <form method="POST" action="{{route('save_striping')}}" id="estimator_form" class="custom-validation admin-form">

            @csrf
            <input type="hidden" name="proposal_detail_id" value="{{$proposalDetail->id}}">
                <input type="hidden" name="x_proposal_text" value="{{$proposalDetail->proposal_text}}">
                 <input type="hidden" name="proposal_id" value="{{$proposalDetail->proposal_id}}">
        <div class="col-12">
            @include('_partials._alert')
            <div class="card">
                <div class="card-header alert-light">
                    @include('estimator.proposal_header')
                    @include('_partials._alert', ['alertId' => 'header_alert'])
                    @include('estimator.form_header')
                    @include('estimator.form_striping_formulas')
                </div>

            </div>

            {{-- Proposal Text --}}
            <div class="card">
                <div class="card-header alert-light">
                    @include('_partials._alert', ['alertId' => 'service_text_alert'])
                    @include('estimator.form_service_text')
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

