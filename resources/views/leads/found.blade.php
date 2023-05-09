@extends('layouts.master')

@section('title') 3D Paving Create Proposal From Lead @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') @lang('translation.Leads') @endslot
        @slot('li_1') <a href="{{ route('dashboard') }}">@lang('translation.Dashboard')</a>@endslot
        @slot('li_2') @lang('translation.Leads') @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-sm-6 mb20">
                                <x-href-button url="{{ route('start_from_lead', ['lead'=>$lead->id, 'override' => 1]) }}" class="btn-success"><i class="fas fa-plus"></i>@lang('translation.create') @lang('translation.new') @lang('translation.contact')</x-href-button>
                        </div>
                        <div class="col-md-4 col-sm-6 mb20">
                        </div>
                    </div>
                    <h5>We found some matches in our contacts database, if you see your lead in this list, 
                        select it to use the existing contact.
                        <br/>Or Click the "Create New Contact" button above to create a new contact in our database. </h5>
                    <table class="list-table table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th class="td-sortable tc w360">Contact Name</th>
                            <th class="td-sortable tc w360">Address</th>
                            <th class="td-sortable tc w360">Phone</th>
                            <th class="td-sortable tc w360">Email</th>
                            <th class="actions">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($contacts as $contact)
                            <tr>
                                <td class="tc"><a href="{{ route('start_from_contact', ['contact' => $contact['id'], 'lead'=>$lead->id]) }}">{{ $contact['first_name'] }}  {{$contact['last_name'] }}</a></td>
                                <td class="tc">{{ $contact['FullAddressTwoLine'] ?? null }}</td>
                                <td class="tc">{{ $contact['phone'] ?? null }}</td>
                                <td class="tc">{{ $contact['email'] ?? null }}</td>
                                <td class="centered actions">
                                    <ul class="nav navbar-nav">
                                        <li class="dropdown">
                                            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                            <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                <li>
                                                    <a href="{{ route('start_from_contact', ['contact' => $contact['id'], 'lead'=>$lead->id]) }}">
                                                        <span class="fas fa-eye"></span>@lang('translation.create') @lang('translation.proposal')
                                                    </a>
                                                </li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@stop


