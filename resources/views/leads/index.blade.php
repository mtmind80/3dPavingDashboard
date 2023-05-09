@extends('layouts.master')

@section('title') 3D Paving Leads @endsection

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
                            @if (auth()->user()->isAdmin())
                                <x-href-button url="{{ route('lead_create') }}" class="{{$site_button_class}}"><i class="fas fa-plus"></i>@lang('translation.create') @lang('translation.new') @lang('translation.lead')</x-href-button>
                            @endif
                        </div>
                        <div class="col-md-4 col-sm-6 mb20">
                            <x-search :needle="$needle" search-route="{{ route('lead_search') }}" cancel-route="{{ route('lead_list') }}" ></x-search>
                        </div>
                    </div>
                    <table class="list-table table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th class="td-sortable tc w360">{!! \App\Traits\SortableTrait::link('first_name', 'Lead Name') !!}</th>
                            <th class="td-sortable tc w360">{!! \App\Traits\SortableTrait::link('leads.previous_assigned_to|users.fname', 'Previous Assigned To') !!}</th>
                            <th class="td-sortable tc w360">{!! \App\Traits\SortableTrait::link('leads.assigned_to|users.fname', 'Assigned To') !!}</th>
                            <th class="td-sortable tc w200">{!! \App\Traits\SortableTrait::link('leads.status_id|lead_status.status', 'Status') !!}</th>
                            <th class="td-sortable tc w100">{!! \App\Traits\SortableTrait::link('onsite', 'Onsite') !!}</th>
                            <th class="td-sortable tc">Last Note</th>
                            <th class="actions tc">@lang('translation.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($leads as $lead)
                            <tr {{ !empty($lead->status->color) ? ' style=background-color:#'.$lead->status->color.' ' : '' }}data-id="{{ $lead->id }}">
                                <td class="tc"><a href="{{ route('lead_details', ['lead' => $lead->id]) }}">{{ $lead->full_name }}</a></td>
                                <td class="tc">{{ $lead->previousAssignedTo->full_name ?? null }}</td>
                                <td class="tc">{{ $lead->assignedTo->full_name ?? null }}</td>
                                <td class="tc">{{ $lead->status->status ?? null }}</td>
                                <td class="tc">{{  !empty($lead->onsite)  ? 'YES' : 'no'}}</td>
                                <td class="tc">{{ $lead->lastNote->note ?? null }}<br/>{{ $lead->lastNote->created_at ?? null}}</td>
                                <td class="centered actions">
                                    <ul class="nav navbar-nav">
                                        <li class="dropdown">
                                            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                            <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                <li>
                                                    <a href="javascript:" class="action" data-action="add-note" data-route="{{ route('lead_field_note_store', ['lead' => $lead->id]) }}" data-lead_name="{{ $lead->full_name }}">
                                                        <span class="fas fa-sticky-note"></span>@lang('translation.add') @lang('translation.note')
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('lead_details', ['lead' => $lead->id]) }}">
                                                        <span class="fas fa-eye"></span>@lang('translation.view') @lang('translation.details')
                                                    </a>
                                                </li>
                                                @if (auth()->user()->isAdmin())
                                                    <li>
                                                        <a href="javascript:" class="action" data-action="assign-to" data-route="{{ route('lead_assign_to', ['lead' => $lead->id]) }}" data-lead_name="{{ $lead->full_name }}">
                                                            <span class="fas fa-user"></span>@lang('translation.assign')
                                                            <span class="hidden assign-to">{!! $lead->assign_to !!}</span>
                                                        </a>
                                                    </li>
                                                    <li class="menu-separator"></li>
                                                    <li>
                                                        <a href="javascript:" class="action" data-action="archive" data-route="{{ route('lead_archive',['lead' => $lead->id]) }}" data-text="Are you sure you want to archive this lead?<br>Lead: <b>{{ $lead->full_name }}</b>">
                                                            <span class="far fa-save"></span>@lang('translation.archive')
                                                        </a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a href="{{ route('start_from_lead', ['lead' => $lead->id]) }}">
                                                        <span class="fas fa-circle-notch"></span>@lang('translation.create') @lang('translation.proposal')
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

                    <x-paginator :collection="$leads" route-name="lead_list" :needle="$needle"></x-paginator>
                </div>
            </div>
        </div>
    </div>
    @include('modals.form_lead_note_modal')
    @include('modals.form_managers_modal')
@stop

@section('page-js')
    <script>
        $(document).ready(function(){
            var body = $('body');

            var noteModal = $('#formNoteModal');
            var noteForm = $('#admin_form_note_modal');
            var note = $('#note');

            body.on('click', '.actions .action[data-action="add-note"]', function(){
                let el = $(this);
                let leadNameContainer = $('#formNoteModalLabel').find('span');
                let url = el.data('route');
                let leadName = el.data('lead_name');

                noteForm.attr('action', url);
                leadNameContainer.text(leadName);
                noteModal.modal('show');
            });

            noteModal.on('show.bs.modal', function(){
                noteForm.find('em.state-error').remove();
                noteForm.find('.field.state-error').removeClass('state-error');
            })

            noteModal.on('hidden.bs.modal', function(){
                noteForm.find('em.state-error').remove();
                noteForm.find('.field.state-error').removeClass('state-error');
            })

            noteForm.validate({
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
                        noteForm.submit();
                    }
                }
            });

            var assignedTo = $('#assigned_to');

            var managersModal = $('#formManagersModal');
            var managersForm = $('#admin_form_managers_modal');

            body.on('click', '.actions .action[data-action="assign-to"]', function(){
                let el = $(this);
                let leadNameContainer = $('#formManagersModalLabel').find('span');
                let url = el.data('route');
                let leadName = el.data('lead_name');

                managersForm.attr('action', url);
                leadNameContainer.text(leadName);
                managersModal.modal('show');
            });

            managersModal.on('show.bs.modal', function(){
                assignedTo.val('');
                managersForm.find('em.state-error').remove();
                managersForm.find('.field.state-error').removeClass('state-error');
            })

            managersModal.on('hidden.bs.modal', function(){
                assignedTo.val('');
                managersForm.find('em.state-error').remove();
                managersForm.find('.field.state-error').removeClass('state-error');
            })

            managersForm.validate({
                rules: {
                    managers: {
                        required : true,
                        plainText: true
                    }
                },
                messages: {
                    managers: {
                        required : "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_entry')"
                    }
                },
                submitHandler: function(form){
                    let errors = false;

                    if (!errors) {
                        managersForm.submit();
                    }
                }
            });

            body.on('click', '.actions .action[data-action="archive"]', function(){
                uiConfirm({callback: 'confirmArchive', text: $(this).attr('data-text'), params: [$(this).attr('data-route')]});
            });
        });

        function confirmArchive(url)
        {
            window.location = url;
        }
    </script>
@stop

