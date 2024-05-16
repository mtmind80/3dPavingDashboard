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
                            <x-href-button url="{{ route('contact_create', ['returnTo' => Request::url()]) }}" class="{{$site_button_class}}"><i class="fas fa-plus"></i>@lang('translation.new') @lang('translation.client')</x-href-button>
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
                            <tr class="{{ $contact->isDeleted() ? 'disabled' : '' }}" data-id="{{ $contact->id }}">
                                <td class="tc"><a href="{{ route('contact_details', ['contact' => $contact->id]) }}">{{ $contact->full_name }}</a>{!! !empty($contact->company->full_name) ? '<br><span class="fs13">retaled to: '.$contact->company->full_name.'</span>' : null !!}</td>
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
                                                    <a href="javascript:" class="action" data-action="add-note" data-route="{{ route('contact_field_note_update', ['contact' => $contact->id]) }}" data-contact_name="{{ $contact->full_name }}">
                                                        <span class="fas fa-sticky-note"></span>@lang('translation.note')
                                                        <span class="hidden contact-note">{!! $contact->note !!}</span>
                                                    </a>
                                                </li>
                                                {{--
                                                @if ($contact->isCompany())
                                                    <li>
                                                        <a href="javascript:" class="action" data-action="route" data-route="{{ route('staff_list', ['contact_id' => $contact->id]) }}">
                                                            <span class="fas fa-users"></span>@lang('translation.staffs')
                                                        </a>
                                                    </li>
                                                @elseif ($contact->hasCompany())
                                                    <li>
                                                        <a href="javascript:" class="action" data-action="confirm" data-id="{{ $contact->id }}" data-callback="confirmDetach" data-text="Are you sure you want to detach <b>{{ $contact->full_name }}</b> from <b>{{ $contact->company->full_name }}</b>?">
                                                            <span class="fas fa-unlink"></span>@lang('translation.detach_from_company')
                                                        </a>
                                                   </li>

                                                @endif
                                                --}}
                                                <li>
                                                    <a href="javascript:" class="action" data-action="route" data-route="{{ route('contact_details', ['contact' => $contact->id]) }}">
                                                        <span class="far fa-eye"></span>@lang('translation.details')
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:" class="action" data-action="route" data-route="{{ route('start_from_contact', ['contact' => $contact->id]) }}">
                                                        <span class="far fa-file"></span>@lang('translation.newproposal')
                                                    </a>
                                                </li>
                                                {{--
                                                <li>
                                                    <a href="javascript:" class="action" data-action="route" data-route="{{ route('contact_edit', ['contact' => $contact->id]) }}">
                                                        <span class="far fa-edit"></span>@lang('translation.edit')
                                                    </a>
                                                </li>
                                                --}}
                                                {{--
                                                @if ($contact->isActive())
                                                    <li class="menu-separator"></li>
                                                    <li>
                                                        <a href="javascript:" class="action" data-action="delete" data-id="{{ $contact->id }}" data-text="Are you sure you want to delete contact <b>{{ $contact->full_name }}</b>?">
                                                            <span class="far fa-times-circle"></span>Delete
                                                        </a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a href="javascript:" class="action" data-action="route"  data-route="{{ route('contact_restore', ['contact' => $contact->id]) }}">
                                                            <span class="fas fa-trash-restore"></span>Restore
                                                        </a>
                                                    </li>
                                                @endif
                                                --}}
                                            </ul>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <x-paginator :collection="$contacts" route-name="contact_list" :needle="$needle"></x-paginator>

                    <x-delete-form url="{{ route('contact_delete') }}"></x-delete-form>

                    <form method="POST" action="{{ route('contact_detach_from_company') }}" accept-charset="UTF-8" id="detachFromCompanyForm">
                        @csrf
                        <input id="form_detach_contact_id" name="contact_id" type="hidden">
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('modals.form_note_modal')
@stop

@section('page-js')
    <script>
        $(document).ready(function(){
            var modal = $('#formNoteModal');
            var form = $('#admin_form_note_modal');
            var note = $('#note');
            var currentNote;

            $('body').on('click', '.actions .action[data-action="add-note"]', function(){

                let el = $(this);
                let contactNameContainer = $('#formNoteModalLabel').find('span');
                let url = el.data('route');
                let contactName = el.data('contact_name');

                currentNote = el.find('.contact-note').html();

                form.attr('action', url);
                contactNameContainer.text(contactName);
                note.html(currentNote);
                modal.modal('show');
            });

            modal.on('show.bs.modal', function(){
                note.html(currentNote);
                form.find('em.state-error').remove();
                form.find('.field.state-error').removeClass('state-error');
            })

            modal.on('hidden.bs.modal', function(){
                note.html(currentNote);
                form.find('em.state-error').remove();
                form.find('.field.state-error').removeClass('state-error');
            })

            form.validate({
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
        });

        function confirmDetach(contact_id)
        {
            $('#form_detach_contact_id').val(contact_id);
            $('#detachFromCompanyForm').submit();
        }
    </script>
@stop

