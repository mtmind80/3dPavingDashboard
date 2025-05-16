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
           Striping Pavement Markings
        @endslot
    @endcomponent

    <div class="row estimator-form admin-form service-view-page">
        <div class="col-12">
            <div class="card">
                <div class="card-header alert-light">
                    <div class="row mt10">
                        <div class="col-md-6">
                            <div>
                                <p class="fs18 mb5">
                                    <span class="fwb color-black mr5">@lang('translation.proposalname'):</span>
                                    {{ $proposal->name }}
                                </p>
                                <p class="fs18 mb5">
                                    <span class="fwb color-black mr5">Service Category:</span>
                                    {{ $service->category->name }}
                                </p>
                                <p class="fs18 mb5">
                                    <span class="fwb color-black mr5">Service Title:</span>
                                    {{ $proposalDetail->service_name }}
                                </p>
                                <p class="fwb fs18 mb5 color-black mr5">
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
                                    <span class="fwb color-black mr5">Created On:</span>
                                    {{ $proposal->proposal_date->format('m/d/yy') }}
                                </p>
                                <p class="fs18 mb5">
                                    <span class="fwb color-black mr5">Client:</span>
                                    {{ $proposal->contact->full_name }}
                                </p>
                                <p class="fwb fs18 mb5 color-black mr5">Client Primary Location:</p>
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
                        <div class="col-md-12">
                            <div>
                                <p class="fs18 mb5">
                                    <span class="fwb color-black mr5">Service Name:</span>
                                    {{ $proposalDetail->service->name }}
                                </p>
                            </div>
                        </div>
                        @if (auth()->user()->isAdmin())
                            <div class="col-md-2 col-sm-4">
                                <div class="">
                                    <p class="fs18 mb5">
                                        <span class="fwb color-black db">Customer Price:</span>
                                        {{ $proposalDetail->html_cost }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-4">
                                <div class="">
                                    <p class="fs18 mb5">
                                        <span class="fwb color-black db">Profit:</span>
                                        {{ $proposalDetail->profit }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-4">
                                <div class="">
                                    <p class="fs18 mb5">
                                        <span class="fwb color-black db">Breakeven:</span>
                                        {{ $proposalDetail->break_even }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-4">
                                <div class="">
                                    <p class="fs18 mb5">
                                        <span class="fwb color-black db">Striping Cost:</span>
                                        {{ $proposalDetail->html_stripping_cost }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-4">
                                <div class="">
                                    <p class="fs18 mb5">
                                        <span class="fwb color-black db">Over Head:</span>
                                        {{ $proposalDetail->overhead }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-4">
                                <div class="">
                                    <p class="fs18 mb5">
                                        <span class="fwb color-black db">Additional Cost:</span>
                                        {{ $proposalDetail->html_material_cost }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="col-md-3 col-sm-3">
                                <div class="">
                                    <p class="fs18 mb5">
                                        <span class="fwb color-black mr5">Over Head:</span>
                                        {{ $proposalDetail->overhead }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <div class="">
                                    <p class="fs18 mb5">
                                        <span class="fwb color-black mr5">Profit:</span>
                                        {{ $proposalDetail->profit }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <div class="">
                                    <p class="fs18 mb5">
                                        <span class="fwb color-black mr5">Breakeven:</span>
                                        {{ $proposalDetail->break_even }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <div class="">
                                    <p class="fs18 mb5">
                                        <span class="fwb color-black mr5">Over Head:</span>
                                        {{ $proposalDetail->overhead }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    @include('workorders.workorder_view_header')
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

            @if (isset($stripingServices) && $stripingServices->count() > 0)
                @foreach ($stripingServices as $stripingService)
                    <div class="card">
                        <div class="card-header alert-light">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h5 class="color-black fs20">
                                       {{ $stripingService->name }}
                                    </h5>
                                    @include('workorders.view_service_striping', ['services' => $stripingService->services, 'html_total_cost' => $stripingService->html_total_cost])
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>
@endsection

