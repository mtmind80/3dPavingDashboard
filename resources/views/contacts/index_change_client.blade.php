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
                    <div class="row">
                        <div class="col-md-8 col-sm-6 mb20">
                        </div>
                        <div class="col-md-4 col-sm-6 mb20">
                            <x-search :needle="$needle" search-route="{{ route('contact_search') }}" cancel-route="{{ route('contact_list') }}" ></x-search>
                        </div>
                    </div>
                    <table class="list-table table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th class="td-sortable tc w280">{!! \App\Traits\SortableTrait::link('first_name', 'Name') !!}</th>
                            <th class="td-sortable tc w400">{!! \App\Traits\SortableTrait::link('county', 'Address') !!}</th>
                            <th class="td-sortable tc w160">{!! \App\Traits\SortableTrait::link('phone', 'Phones') !!}</th>
                            <th class="td-sortable tc w380">{!! \App\Traits\SortableTrait::link('email', 'Emails') !!}</th>
                            <th class="td-sortable tc w200">{!! \App\Traits\SortableTrait::link('contacts.contact_type_id|contact_types.type', 'Contact Type') !!}</th>
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
                                <td class="tc">{{ !empty($contact->contactType) ? $contact->contactType->type : null }}</td>
                                <td class="centered actions">
                                    <ul class="nav navbar-nav">
                                        <li class="dropdown">
                                            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                            <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                <li>
                                                    <a href="javascript:" class="action">
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

                    <x-paginator :collection="$contacts" route-name="contact_list" :needle="$needle"></x-paginator>
                </div>
                </div>
            </div>

    </div>
@stop

@section('page-js')
    <script>
        $(document).ready(function(){
        });

    </script>
@stop
