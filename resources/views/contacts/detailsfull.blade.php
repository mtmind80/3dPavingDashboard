@extends('layouts.master')

@section('title') 3D Paving Contact Details @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') @lang('translation.Contacts') @endslot
        @slot('li_1') <a href="{{ route('dashboard') }}">@lang('translation.Dashboard')</a>@endslot
        @slot('li_2') <a href="{{ route('contact_list') }}">@lang('translation.Contacts')</a>@endslot
        @slot('li_3') "{{ $contact->full_name }}" @endslot
        @slot('li_4') @lang('translation.details') @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        <li class="nav-item">
                            <a id="tab_link_profile" class="nav-link active" data-toggle="tab" href="#profile" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block">@lang('translation.Profile')</span>
                            </a>
                        </li>
                        @if (!empty($contact->notes))
                            <li class="nav-item">
                                <a id="tab_link_notes" class="nav-link" data-toggle="tab" href="#notes" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">@lang('translation.notes')</span>
                                </a>
                            </li>
                        @endif
                        @if (!empty($contact->staff))
                            <li class="nav-item">
                                <a id="tab_link_staff" class="nav-link" data-toggle="tab" href="#staff" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">Authorized Contact</span>
                                </a>
                            </li>
                        @endif
                        @if (!empty($contact->proposals))
                            <li class="nav-item">
                                <a id="tab_link_proposals" class="nav-link" data-toggle="tab" href="#proposals" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">@lang('translation.Proposals')</span>
                                </a>
                            </li>
                        @endif
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content plr0 pt30 pb0 text-muted">
                        <div class="tab-pane active" id="profile" role="tabpanel">
                            <div class="row">
                                <div class="col-md-8 col-sm-6 mb20">
                                    <x-href-button url="{{ route('contact_edit', ['contact' => $contact->id, 'returnTo' => Request::url(), 'tab' => 'profile']) }}" class="btn-info"><i class="fas fa-edit"></i>@lang('translation.edit')</x-href-button>
                                </div>
                                <div class="col-md-4 col-sm-6 mb20"></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-3 col-sm-6 admin-form-item-widget">
                                   <span class="fas fa-sticky-note"> </span> Contact Type :{{ $contact->contactType->type ?? null }}
                                </div>
                                <div class="col-lg-4 col-md-3 col-sm-6 admin-form-item-widget">
                                   <span class="fas fa-user"> </span> Contact Name: {{ $contact->full_name }}
                                </div>
                                @if($contact->related_to)
                                    <div class="col-lg-4 col-md-3 col-sm-6 admin-form-item-widget">
                                        <span class="fas fa-user"> </span> Related To: <a href ="{{route("contact_details",['contact' => $contact['related_to']])}}">{{ App\Models\Contact::find($contact['related_to'])->FullName }}</a>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
                                    <span class="fas fa-phone"> </span> Phone: {{ $contact->phone }}
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
                                    <span class="fas fa-phone"> </span> Alt Phone:{{ $contact->alt_phone }}
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
                                    <span class="fas fa-envelope"> </span> Email: <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
                                    <span class="fas fa-envelope"> </span> Alt Email: {{ $contact->alt_email }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 admin-form-item-widget">
                                    <span class="fas fa-building"> </span> Address: {{ $contact->full_address_one_line }}
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 admin-form-item-widget">
                                    <span class="fas fa-building"> </span> Billing Address: {{ $contact->full_billing_address_one_line }}
                                </div>
                            </div>
                            @if (!empty($contact->contact) || !empty($contact->relatedTo))
                                <div class="row">
                                    @if (!empty($contact->contact))
                                        <div class="col-lg-6 col-md-6 col-sm-12 admin-form-item-widget">
                                            <span class="fas fa-user-alt"> </span> Primary Contact: {{ $contact->contact ?? null }}
                                        </div>
                                    @endif
                                    @if (!empty($contact->relatedTo))
                                        <div class="col-lg-6 col-md-6 col-sm-12 admin-form-item-widget">
                                            <span class="fas fa-user-alt"> </span> <a class="a-link" href="{{ route('contact_details', ['contact' => $contact->relatedTo->id, 'returnTo' => $returnTo]) }}" data-toggle="tooltip" title="See contact details">{{ $contact->relatedTo->full_name }}</a>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if (!empty($contact->note))
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 admin-form-item-widget">
                                        <x-form-show class="mh-100" :params="['label' => 'Note', 'iconClass' => 'fas fa-sticky-note']">{{ $contact->note ?? null }}</x-form-show>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if (!empty($contact->notes))
                            <div class="tab-pane" id="notes" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-8 col-sm-6 mb20">
                                        <x-href-button
                                            id="add_note_button" class="btn-success"
                                            data-route="{{ route('contact_note_store') }}"
                                            data-id="{{ $contact->id }}"
                                            data-contact_name="{{ $contact->full_name }}"
                                        >
                                            <i class="fas fa-plus"></i>
                                            @lang('translation.add')
                                        </x-href-button>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb20"></div>
                                </div>
                                @foreach ($contact->notes as $notes)
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 admin-form-item-widget">
                                            <p class="mb4 fs14">{{ $notes->date_creator }}</p>
                                            <x-form-show class="mh-100" :params="['label' => 'none', 'iconClass' => 'fas fa-sticky-note']">{{ $notes->note ?? null }}</x-form-show>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if (!empty($contact->staff))
                            <div class="tab-pane" id="staff" role="tabpanel">
                                <div class="row mb10">
                                    <div class="col-lg-12 col-md-12 col-sm-12 mb20">
                                        <form method="POST" action="{{ route('contact_add_new_staff', ['contact' => $contact->id]) }}" accept-charset="UTF-8" id="addStaffForm" class="admin-form">
                                            @csrf
                                            <input type="hidden" name="contact_id" value="{{ $contact->id }}">
                                            <input type="hidden" name="returnTo" value="{{ $returnTo ?? null }}">
                                            <input type="hidden" name="tab" value="{{ $tabSelected ?? null }}">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-6 admin-form-item-widget">
                                                    <x-form-text
                                                        name="first_name"
                                                        class="check-contact"
                                                        :params="['label' => 'First Name', 'iconClass' => 'fas fa-user', 'required' => true]"
                                                    ></x-form-text>
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-6 admin-form-item-widget">
                                                    <x-form-text
                                                        name="last_name"
                                                        class="check-contact"
                                                        :params="['label' => 'Last Name', 'iconClass' => 'fas fa-user', 'required' => true]"
                                                    ></x-form-text>
                                                </div>
                                                <div class="col-lg-3 col-md-4 col-sm-6 admin-form-item-widget">
                                                    <x-form-text
                                                        name="email"
                                                        class="check-contact"
                                                        :params="['label' => 'Email', 'iconClass' => 'fas fa-envelope', 'required' => false]"
                                                    ></x-form-text>
                                                </div>
                                                <div class="col-lg-3 col-md-4 col-sm-6 admin-form-item-widget">
                                                    <x-form-text
                                                        name="phone"
                                                        :params="['label' => 'Phone', 'iconClass' => 'fas fa-phone', 'required' => false]"
                                                    ></x-form-text>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
                                                    <x-form-text
                                                        name="address1"
                                                        :params="['label' => 'Address', 'iconClass' => 'fas fa-map-marker-alt', 'required' => true]"
                                                    ></x-form-text>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
                                                    <x-form-text
                                                        name="title"
                                                        :params="['label' => 'Title', 'iconClass' => 'fas fa-circle', 'required' => false]"
                                                    ></x-form-text>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <x-button
                                                        type="submit"
                                                        class="btn-success"
                                                    >
                                                        <i class="fas fa-plus"></i>
                                                        Add Authorized Contact
                                                    </x-button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <table class="list-table table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th class="tc w220">Name</th>
                                        <th class="tc w280">Address</th>
                                        <th class="tc w120">Phones</th>
                                        <th class="tc w140">Emails</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($contact->staff as $staff)
                                        <tr>
                                            <td class="tc"><a href="{{route("contact_details",['contact' => $staff['id']])}}">{{ $staff->full_name }}</a></td>
                                            <td class="tc">{!! $staff->address1 !!}</td>
                                            <td class="tc">{!! $staff->phone !!}</td>
                                            <td class="tc">{!! $staff->email !!}</td>
                                            <td class="centered actions">
                                                <ul class="nav navbar-nav">
                                                    <li class="dropdown">
                                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                                        <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                            <li>
                                                                <a href="javascript:" class="action" data-action="confirm" data-id="{{ $staff->id }}" data-callback="confirmDetach" data-text="Are you sure you want to detach <b>{{ $staff->full_name }}</b> from <b>{{ $staff->company->full_name ?? 'unknown' }}</b>?">
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
                            </div>
                        @endif
                        @if (!empty($contact->proposals))
                            <div class="tab-pane" id="proposals" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-8 col-sm-6 mb20">
                                        <x-href-button url="{{ route('start_from_contact', ['contact' => $contact->id, 'returnTo' => Request::url(), 'tab' => 'proposals']) }}" class="btn-success"><i class="fas fa-edit"></i>@lang('translation.newproposal')</x-href-button>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb20"></div>
                                </div>
                                <table class="list-table table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th class="tc w400">Name</th>
                                            <th class="tc w180">Status</th>
                                            <th class="tc w180">Date</th>
                                            <th class="tc w220">Created By</th>
                                            <th class="tc w220">Sales Manager</th>
                                            <th class="tc">Location</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contact->proposals as $proposals)
                                            <tr>
                                                <td class="tc"><a href="{{ route('show_proposal',['id'=>$proposals->id]) }}">{{ $proposals->name }}</a></td>
                                                <td class="tc">{!! $proposals->status->status ?? null !!}</td>
                                                <td class="tc">{!! $proposals->html_date_two_lines !!}</td>
                                                <td class="tc">{!! $proposals->creator->full_name ?? null !!}</td>
                                                <td class="tc">{!! $proposals->salesManager->full_name ?? null !!}</td>
                                                <td class="tc">{!! $proposals->location->full_location_two_lines ?? null !!}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('contact_remove_staff', ['contact' => $contact->id]) }}" accept-charset="UTF-8" id="removeStaffForm">
        @csrf
        <input id="form_remove_staff_staff_id" name="staff_id" type="hidden">
        <input name="return_to" type="hidden" value="{{ Request::url() }}">
        <input name="tab" type="hidden" value="staff">
    </form>

    @include('modals.form_note_modal')
@stop

@section('css-files')
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet">
@stop

@section('js-plugin-files')
    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js')}}"></script>
@stop

@section('page-js')
    <script>
        $(document).ready(function(){
            // to add class "active"
            // variable from controller: $tabSelected: [profile notes staff proposals]
            // Nav tabs:   id="tab_link_profile"
            // Tab panes:  id="profile"

            const tabSelected = "{{ $tabSelected ?? '' }}";
            const contactId = "{{ $contact->id }}";

            if (tabSelected !== "") {
                $('#tab_link_'+tabSelected).click();
            }

            // form_note_modal_return_to form_note_modal_tab

            var modal = $('#formNoteModal');
            var addNoteform = $('#admin_form_note_modal');
            var formFieldNote = $('#note');
            var formFieldReturnTo = $('#form_note_modal_return_to');
            var formFieldTab = $('#form_note_modal_tab');
            var formFieldContactId = $('#form_note_contact_id');

            $('#add_note_button').click(function(){
                let el = $(this);
                let contactNameContainer = $('#formNoteModalLabel').find('span');
                let url = el.data('route');
                let contactName = el.data('contact_name');

                addNoteform.attr('action', url);
                contactNameContainer.text(contactName);
                formFieldReturnTo.val("{{ Request::url() }}");
                formFieldTab.val('notes');
                formFieldContactId.val(contactId);
                formFieldNote.text('');
                modal.modal('show');
            });

            modal.on('show.bs.modal', function(){
                formFieldNote.val('');
                addNoteform.find('em.state-error').remove();
                addNoteform.find('.field.state-error').removeClass('state-error');
            })

            modal.on('hidden.bs.modal', function(){
                formFieldNote.val('');
                addNoteform.find('em.state-error').remove();
                addNoteform.find('.field.state-error').removeClass('state-error');
            })

            addNoteform.validate({
                rules: {
                    note: {
                        required : true,
                        plainText: true
                    }
                },
                messages: {
                    note: {
                        required : "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_entry')"
                    }
                },
                submitHandler: function(form){
                    let errors = false;

                    if (!errors) {
                        form.submit();
                    }
                }
            });

            var staffId = $('#staff_id');

            staffId.select2({
                templateResult: templateResult,
                templateSelection: templateSelection
            });

            var addStaffForm = $('#addStaffForm');

            addStaffForm.validate({
                rules: {
                    first_name: {
                        required: true,
                        personName: true
                    },
                    last_name: {
                        required: false,
                        personName: true
                    },
                    address1: {
                        required: true,
                        plainText: true
                    },
                    email: {
                        required: false,
                        email: true
                    },
                    phone: {
                        required: false,
                        phone: true
                    },
                },
                messages: {
                    first_name: {
                        required: "@lang('translation.field_required')",
                        personName: "@lang('translation.invalid_entry')"
                    },
                    last_name: {
                        required: "@lang('translation.field_required')",
                        personName: 'Invalid last name.'
                    },
                    address1: {
                        required: "@lang('translation.field_required')",
                        plainText: 'Invalid address.'
                    },
                    email: {
                        email: 'Invalid email.'
                    },
                    phone: {
                        phone: 'Invalid phone.'
                    },
                    title: {
                        plainText: 'Invalid title.'
                    },
                },
                submitHandler: function(form){
                    console.log('en form');

                    let errors = false;

                    if (!errors) {
                        form.submit();
                    }
                }
            });

            staffId.change(function(ev){
                let ems = $('.select2-wraper').find('em.state-error');

                if ($(this).val() > 0) {
                    ems.remove();
                }
            });
        });

        function confirmDetach(staff_id)
        {
            $('#form_remove_staff_staff_id').val(staff_id);
            $('#removeStaffForm').submit();
        }

        function templateResult(item, container) {
            // replace the placeholder with the break-tag and put it into an jquery object
            return $('<span class="select2-item-main">' + item.text.replace('[br]', '</span><br/><span class="select2-item-secondary">') + '</span>');
        }

        function templateSelection(item, container) {
            // replace your placeholder with nothing, so your select shows the whole option text
            return item.text.replace('[br]', '');
        }

    </script>
@stop

