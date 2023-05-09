@extends('layouts.master')

@section('title')
    3D Paving Proposals
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.Proposals')
        @endslot
        @slot('li_1')
            <a href="/dashboard">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            @lang('translation.Proposals')
        @endslot
    @endcomponent


    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">


                        @if($paginate)

                            <div class="col-lg-8">
                                {{$records->links()}}

                            </div>

                            <div class="col-lg-4">
                                <a href="{{ route('proposals') }}" title="@lang('translation.SearchAgain')"
                                   class="{{$site_button_class}}"><i class="fas fa-plus"></i>@lang('translation.SearchAgain')
                                </a>
                            </div>
                        @else
                            <div class="col-lg-12 p-1">
                                <a href="{{ route('proposals') }}" title="@lang('translation.SearchAgain')"
                                   class="{{$site_button_class}}"><i class="fas fa-plus"></i>@lang('translation.SearchAgain')
                                </a>
                            </div>
                        @endif

                    </div>

                    <div class="row">
                        <table id='proposaltable'
                               class="table list-table table-bordered dt-responsive nowrap table-striped"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                            <thead class="table-light">
                            <tr>

                                <th class="sorting_disabled tc" style="width: 32px;">
                                    @lang('translation.View_More')
                                </th>

                                <th class="sorting_disabled tc" style="width: 60px;">
                                    @lang('translation.client')
                                </th>

                                <th class="sorting_disabled tc" style="width: 60px;">
                                    @lang('translation.proposal')
                                    @lang('translation.date')
                                </th>

                                <th class="sorting_disabled tc" style="width: 140px;">
                                    @lang('translation.Name')
                                </th>
                                <th class="sorting_disabled tc" style="width: 67px;">
                                    @lang('translation.manager')
                                </th>
                                <th class="sorting_disabled tc" style="width: 128px;">
                                    @lang('translation.location')
                                </th>
                                <th class="sorting_disabled tc" style="width: 32px;">
                                    @lang('translation.status')
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            @if(!$records)
                                <tr class="even">
                                    <td colspan='7' class="text-dark fw-bold">
                                        @lang('translation.norecordsfound')
                                    </td>
                                </tr>
                            @else
                                @foreach ($records as $record)

                                    @if($record->job_master_id)
                                        <tr style="background: #ABEBC6;">
                                            @else
                                        <tr>
                                            @endif
                                        <td class="tc">
                                            @if($record->job_master_id)
                                                <a class="ri-edit-box-fill"
                                                   href="{{ route('show_workorder',['id'=>$record->id]) }}"
                                                   title="@lang('translation.edit')"
                                                   >
                                                    @lang('translation.edit') @lang('translation.work_order')</a>
                                                
                                                @else
                                            <a 
                                                href="{{ route('show_proposal',['id'=>$record->id]) }}"
                                               title="@lang('translation.edit')"
                                               >
                                                @lang('translation.edit') @lang('translation.proposal')</a>
                                            @endif
                                            <br/>ID:{{$record->id}}</td>
                                        <td class="tc">
                                            <A href="{{route('contact_details', ['contact'=>$record->contact_id])}}"
                                               title="@lang('translation.edit') @lang('translation.client')">
                                                {{ App\Models\Contact::find($record->contact_id)->FullName }}
                                            </a>
                                        <td class="tc">
                                            {{$record->proposal_date->format('m-d-Y')}}
                                        </td>

                                        <td class="tc text-dark fw-bold">
                                            {{$record->name}}
                                        </td>
                                        <td class="tc text-dark fw-bold">
                                            @if($record->sales_manager_id)
                                                {{ App\Models\User::find($record->sales_manager_id)->FullName }}
                                            @else
                                                No Manager Assigned
                                            @endif
                                        </td>
                                        <td class="tc text-dark fw-bold">
                                            @if($record->location_id)
                                                    <?php $location = App\Models\Location::find($record->location_id); ?>
                                                {{$location['address_line1']}}<br/>
                                                @if($location['address_line2'])
                                                    {{$location['address_line2']}}<br/>
                                                @endif
                                                @if($location['city'])
                                                    {{$location['city']}},
                                                @endif
                                                {{$location['postal_code']}}
                                            @endif
                                        </td>
                                        <td class="tc text-dark fw-bold">
                                            @if($record->proposal_statuses_id)
                                                {{ App\Models\ProposalStatus::find($record->proposal_statuses_id)->status }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>

                        @if($paginate)
                            {{$records->links()}}
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

