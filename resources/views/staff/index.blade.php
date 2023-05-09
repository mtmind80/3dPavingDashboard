@extends('layouts.master')

@section('title') 3D Paving Contact's Staff @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') @lang('translation.staff') @endslot
        @slot('li_1') <a href="{{ route('dashboard') }}">@lang('translation.Dashboard')</a>@endslot
        @slot('li_2') <a href="{{ route('contact_list') }}">@lang('translation.Contacts')</a>@endslot
        @slot('li_3') "{{ $contact->full_name }}" @endslot
        @slot('li_4') @lang('translation.staffs') @endslot @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-5 col-md-6 col-sm-6 mb20">
                            <div class="form-group select2-wraper">
                                <select class="form-control select2">
                                    <option value="0">@lang('translation.select') @lang('translation.staff')</option>
                                    @foreach ($availableStaffMembers as $staff)
                                        <option value="{{ $staff->id }}">{{ $staff->full_name }}[br]{{ $staff->full_address_one_line }}</option>
                                    @endforeach
                                </select>
                                <x-href-button id="attach_staff" class="btn-success"><i class="fas fa-link"></i>@lang('translation.add')</x-href-button>
                            </div>
                        </div>
                        <div class="col-lg-2 not-lg-hidden mb20"></div>
                        {{--


                        <div class="col-md-8 col-sm-6 mb20">
                            <x-href-button url="{{ route('contact_create', ['returnTo' => Request::url()]) }}" class="btn-success"><i class="fas fa-plus"></i>@lang('translation.new')</x-href-button>
                        </div>
                        --}}
                        <div class="col-lg-5 col-md-6 col-sm-6 mb20">
                            <x-search :needle="$needle" search-route="{{ route('staff_search', ['contact_id' => $contact_id]) }}" cancel-route="{{ route('staff_list', ['contact_id' => $contact_id]) }}" ></x-search>
                        </div>
                    </div>
                    <table class="list-table table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th class="td-sortable tc w400">{!! \App\Traits\SortableTrait::link('first_name', 'Name', ['contact_id' => $contact_id]) !!}</th>
                                <th class="td-sortable tc">{!! \App\Traits\SortableTrait::link('county', 'Address', ['contact_id' => $contact_id]) !!}</th>
                                <th class="td-sortable tc w140">{!! \App\Traits\SortableTrait::link('phone', 'Phones', ['contact_id' => $contact_id]) !!}</th>
                                <th class="td-sortable tc w220">{!! \App\Traits\SortableTrait::link('email', 'Emails', ['contact_id' => $contact_id]) !!}</th>
                                <th class="td-sortable tc w300">{!! \App\Traits\SortableTrait::link('contacts.contact_type_id|contact_types.type', 'Type', ['contact_id' => $contact_id]) !!}</th>
                                <th class="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($staffMembers as $staff)
                                <tr class="{{ $staff->isDeleted() ? 'disabled' : '' }}" data-id="{{ $staff->id }}">
                                    <td class="tc"><a href="{{ route('contact_details', ['contact' => $staff->id, 'returnTo' => Request::url()]) }}">{{ $staff->full_name }}</a>{!! !empty($staff->company->full_name) ? '<br><span class="fs13">retaled to: '.$staff->company->full_name.'</span>' : null !!}</td>
                                    <td class="tc">{!! $staff->full_address_two_line !!}</td>
                                    <td class="tc">{!! $staff->phones_two_lines !!}</td>
                                    <td class="tc">{!! $staff->emails_two_lines !!}</td>
                                    <td class="tc">{{ !empty($staff->contactType) ? $staff->contactType->type : null }}</td>
                                    <td class="centered actions">
                                        <ul class="nav navbar-nav">
                                            <li class="dropdown">
                                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                                <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                    <li>
                                                        <a href="javascript:" class="action" data-action="confirm" data-id="{{ $staff->id }}" data-callback="confirmDetach" data-text="Are you sure you want to detach <b>{{ $staff->full_name }}</b> from <b>{{ $staff->company->full_name }}</b>?">
                                                            <span class="fas fa-unlink"></span>@lang('translation.detach_from_company')
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

                    <x-paginator :collection="$staffMembers" route-name="contact_list" :params="['route_params' => ['contact_id' => $contact_id]]" :needle="$needle"></x-paginator>

                    <form method="POST" action="{{ route('contact_detach_from_company') }}" accept-charset="UTF-8" id="detachFromCompanyForm">
                        @csrf
                        <input id="form_detach_contact_id" name="contact_id" type="hidden">
                        <input id="form_detach_return_to" name="return_to" type="hidden" value="{{ Request::url() }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('script')
    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js')}}"></script>
@endsection

@section('page-js')
    <script>
        function formatDesign(item) {
            var selectionText = item.text.split(".");
            var $returnString = selectionText[0] + "</br>" + selectionText[1];

            return $returnString;
        }

        function templateResult(item, container) {
            // replace the placeholder with the break-tag and put it into an jquery object
            return $('<span class="select2-item-main">' + item.text.replace('[br]', '</span><br/><span class="select2-item-secondary">') + '</span>');
        }

        function templateSelection(item, container) {
            // replace your placeholder with nothing, so your select shows the whole option text
            return item.text.replace('[br]', '');
        }

        $(document).ready(function(){
            $('.select2').select2({
                templateResult: templateResult,
                templateSelection: templateSelection
            });


        });

        function confirmDetach(contact_id)
        {
            $('#form_detach_contact_id').val(contact_id);
            $('#detachFromCompanyForm').submit();
        }
    </script>
@stop

