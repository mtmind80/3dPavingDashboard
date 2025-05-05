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
                                        {{ $proposalDetail->html_cost }}
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

            <div class="card">
                <div class="card-header alert-light">
                    @include('estimator.form_striping_formulas')
                </div>

            </div>


        </div>
    </div>
@endsection

