@extends('layouts.master')

@section('title') 3D Paving Contacts @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') @lang('translation.Contacts') @endslot
        @slot('li_1') <a href="{{ route('dashboard') }}">@lang('translation.Dashboard')</a>@endslot
        @slot('li_2') @lang('translation.Contacts') @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Change Client on Proposal: {{ App\Models\Contact::find($proposal['contact_id'])->FullName }}</h4>
                    <br/>
                    1. Select a client from our database
                    <br/>
                    2. <a href="{{route('createforproposal',['proposal_id' => $proposal_id])}}">Create New Client</a>
                    <br/>
                    3. <a href="{{route('show_proposal',['id' => $proposal_id])}}">Keep Same Client Return to Proposal</a>
                    <div class="row">
                        <div class="col-sm-6 mb20 xs-hidden"></div>
                        <div class="col-sm-6 mb20">
                            <x-search :needle="$needle" search-route="{{ route('change_proposal_client', ['proposal_id' => $proposal_id]) }}" cancel-route="{{ route('change_proposal_client', ['proposal_id' => $proposal_id]) }}" ></x-search>
                        </div>
                    </div>

                    <table class="list-table table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th class="td-sortable tc w280">{!! \App\Traits\SortableTrait::link('first_name', 'Name', ['proposal_id' => $proposal_id]) !!}</th>
                                <th class="td-sortable tc w400">{!! \App\Traits\SortableTrait::link('county', 'Address', ['proposal_id' => $proposal_id]) !!}</th>
                                <th class="td-sortable tc w160">{!! \App\Traits\SortableTrait::link('phone', 'Phones', ['proposal_id' => $proposal_id]) !!}</th>
                                <th class="td-sortable tc w380">{!! \App\Traits\SortableTrait::link('email', 'Emails', ['proposal_id' => $proposal_id]) !!}</th>
                                <th class="td-sortable tc w200">{!! \App\Traits\SortableTrait::link('contacts.contact_type_id|contact_types.type', 'Contact Type', ['proposal_id' => $proposal_id]) !!}</th>
                                <th class="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contacts as $contact)

                                <tr>
                                    <td class="tc">{{ $contact->full_name }}</td>
                                    <td class="tc">{!! $contact->full_address_two_line !!}</td>
                                    <td class="tc">{!! $contact->phones_two_lines !!}</td>
                                    <td class="tc">{!! $contact->emails_two_lines !!}</td>
                                    <td class="tc">{{ $contact->contactType->type ?? null }}</td>
                                    <td class="centered actions">
                                        <ul class="nav navbar-nav">
                                            <li class="dropdown">
                                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                                <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                    <li>
                                                        <a href="{{ route('selectclient', ['contact_id'=>$contact->id, 'proposal_id' =>$proposal_id]) }}" class="action">
                                                            Choose Me
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <x-paginator :collection="$contacts" route-name="change_proposal_client" :params="['route_params' => ['proposal_id' => $proposal_id]]" :needle="$needle"></x-paginator>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page-js')
    <script>
        $(document).ready(function(){
            //
        });
    </script>
@stop

