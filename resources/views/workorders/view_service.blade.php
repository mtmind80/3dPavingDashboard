@extends('layouts.master')

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.work_orders') @lang('translation.services')
        @endslot
        @slot('li_1')
            <a href="/dashboard">
                @lang('translation.Dashboard')
            </a>
        @endslot
        @slot('li_2')
            <a href="{{ route('show_workorder', ['id' => $proposalDetail->proposal_id]) }}">
                @lang('translation.work_orders')
            </a>
        @endslot
        @slot('li_3')
            @lang('translation.services')
        @endslot
    @endcomponent

    <div class="row estimator-form admin-form service-view-page">
        <div class="col-12">
            <!--  Service header -->
            <div class="card">
                <div class="card-header alert-light">
                    <div class="row mt10">
                        <div class="col-md-6">
                            <div>
                                <p class="fs18 mb5">
                                    <span class="fwb color-black">@lang('translation.proposalname'):</span>
                                    {{ $proposal->name }}
                                </p>
                                <p class="fs18 mb5">
                                    <span class="fwb color-black">Service Category:</span>
                                    {{ $service->category->name }}
                                </p>
                                <p class="fs18 mb5">
                                    <span class="fwb color-black">Service Title:</span>
                                    {{ $proposalDetail->service_name }}
                                </p>
                                <p class="fwb fs18 mb5 color-black">
                                    Service Location:
                                </p>
                                <p class="fs18 mb5">
                                    {!! $proposalDetail->location->full_location_two_lines !!}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <p class="fs18 mb5">
                                    <span class="fwb color-black">Created On:</span>
                                    {{ $proposal->proposal_date->format('m/d/yy') }}
                                </p>
                                <p class="fs18 mb5">
                                    <span class="fwb color-black">Client: </span>
                                    {{ $proposal->contact->full_name }}
                                </p>
                                <p class="fwb fs18 mb5 color-black">Client Primary Location:</p>
                                <p class="fs18 mb5">{{ $proposal->contact->full_address_one_line }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt15 mb10">
                        <div class="col-md-12">
                            <h2 class="mb0 ptb8 plr10 fs20" style="background:{{ $service->category->color }};">{{ $service->category->name }}</h2>
                        </div>
                    </div>
                    <div class="row mt20">
                        <div class="col-md-6">
                            <div>
                                <p class="fs18 mb5">
                                    <span class="fwb color-black">Service Name: </span>
                                    {{ $proposalDetail->service->name }}
                                </p>
                            </div>
                        </div>
                        @if (auth()->user()->isAdmin())
                            <div class="col-md-6">
                                <div class="">
                                    <p class="fs18 mb5">
                                        <span class="fwb color-black">Customer Price: </span>
                                        ${{ $proposalDetail->cost }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    @include('workorders.workorder_view_header')

                    @if ($proposal->progressive_billing)
                        <div class="row mt20 mb10">
                            <div class="col-md-12">
                                <p class="m0 fs16 plr15 ptb12" style="background:#E8F8F5;">
                                    Service completion will trigger Progressive Billing
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!--  Service text view -->
            <div class="card">
                <div class="card-header alert-light">
                    <div class="row">
                        <div class="col-lg-12">
                            <h5 class="color-black fs20">
                                @lang('translation.service_description')
                            </h5>
                            <div class="fs18">
                                {!! $proposalDetail->proposal_text !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--  Vehicles -->
            @if (isset($vehicles) && $vehicles->count() > 0)
                <div class="card">
                    <div class="card-header alert-light">
                        <div class="row">
                            <div class="col-lg-12">
                                <h5 class="color-black fs20">
                                    @lang('translation.vehicle')
                                </h5>
                                @include('workorders.view_service_vehicles')
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!--  Equipment -->
            @if (isset($equipments) && $equipments->count() > 0)
                <div class="card">
                    <div class="card-header alert-light">
                        <div class="row">
                            <div class="col-lg-12">
                                <h5 class="color-black fs20">
                                    @lang('translation.equipment')
                                </h5>
                                @include('workorders.view_service_equipments')
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!--  Labor -->
            @if (isset($labors) && $labors->count() > 0)
                <div class="card">
                    <div class="card-header alert-light">
                        <h5>@lang('translation.labor')</h5>
                        @include('workorders.view_service_labors')
                    </div>
                </div>
            @endif

            <!--  Additional Cost -->
            @if (isset($additionalCosts) && $additionalCosts->count() > 0)
                <div class="card">
                    <div class="card-header alert-light">
                        <h5 class="color-black fs20">
                            @lang('translation.additionalcost')
                        </h5>
                        @include('workorders.form_service_additional_costsView')
                    </div>
                </div>
            @endif

            <!--  Subcontractors -->
            @if (isset($subcontractors) && $subcontractors->count() > 0)
                <div class="card">
                    <div class="card-header alert-light">
                        <h5 class="color-black fs20">
                            @lang('translation.subcontractors')
                        </h5>
                        @include('workorders.form_service_subcontractorsView')
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection


