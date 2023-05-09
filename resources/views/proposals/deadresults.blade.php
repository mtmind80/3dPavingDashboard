@extends('layouts.master')

@section('title')
    3D Paving Proposals
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.inactive')  @lang('translation.Proposals')
        @endslot
        @slot('li_1')
            <a href="/dashboard">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            <a href="{{route("proposals")}}">@lang('translation.active')
                @lang('translation.Proposals')</a>
        @endslot
    @endcomponent


    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-sm-6 mb20">
                            <x-href-button url="{{ route('proposals') }}" class="{{$site_button_class}}"><i class="fas fa-plus"></i>@lang('translation.Search') @lang('translation.active')  @lang('translation.proposals')</x-href-button>
                        </div>
                        <div class="col-md-4 col-sm-6 mb20">
                            <x-search :needle="$needle" search-route="{{ route('inactiveproposal_search') }}" cancel-route="{{ route('inactive_proposals') }}" ></x-search>
                        </div>

                    </div>
                    {{ $records->links() }}
                    <div class="row">


                        <table id='proposaltable' class="table list-table table-bordered dt-responsive nowrap table-striped"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                           
                            <thead class="table-light">
                            <tr>

                                <th class="sorting_disabled tc"  style="width: 32px;" >
                                    @lang('translation.View_More')
                                </th>

                                <th class="td-sortable tc" style="width: 60px;">
                                    {!! \App\Traits\SortableTrait::link('proposals.contact_id|contacts.first_name', 'Client') !!}
                                </th>
                                <th class="td-sortable tc" style="width: 140px;">
                                    {!! \App\Traits\SortableTrait::link('name', 'Name') !!}
                                </th>
                                <th class="sorting_disabled tc"  style="width: 67px;" >
                                    {!! \App\Traits\SortableTrait::link('proposals.salesmanager_id|users.fname', 'Manager') !!}
                                </th>
                                <th class="sorting_disabled tc"  style="width: 128px;" >
                                    @lang('translation.location')
                                </th>
                                <th class="td-sortable tc"  style="width: 32px;" >
                                    {!! \App\Traits\SortableTrait::link('proposal_statuses_id|proposal_statuses.status', 'Status') !!}
                                </th>
                                <th class="actions">Actions</th>
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
                                    <tr>
                                        <td class="tc">
                                            <a  href="{{ route('show_proposal',['id'=>$record->id]) }}"
                                                    title="@lang('translation.edit')"
                                                    class="text-dark fw-bold">
                                                @lang('translation.view') @lang('translation.proposal')</a>
                                                <br/><a  href="{{ route('show_proposal',['id'=>$record->id]) }}"
                                                 title="@lang('translation.edit')">ID:{{$record->id}}</a>
                                        </br>
                                            Created:{{$record->proposal_date->format('m-d-Y')}}
                                        </td>

                                        <td class="tc">
                                            <A href="{{route('contact_details', ['contact'=>$record->contact_id])}}">
                                                {{ App\Models\Contact::find($record->contact_id)->FullName }}
                                                </a>
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
                                        <td class="centered actions">
                                            <ul class="nav navbar-nav">
                                                <li class="dropdown">
                                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                                    <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                        <li>
                                                            <a href="{{ route('show_proposal', ['id' => $record->id]) }}">
                                                                <span class="fas fa-sticky-note"></span>@lang('translation.view')
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('clone_proposal', ['id' => $record->id]) }}">
                                                                <span class="far fa-eye"></span>@lang('translation.clone')
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

