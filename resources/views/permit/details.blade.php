@extends('layouts.master')

@section('title') 3D Paving Permit Details @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') @lang('translation.permit') @endslot
        @slot('li_1') <a href="{{ route('dashboard') }}">@lang('translation.Dashboard')</a>@endslot
        @slot('li_2') <a href="{{ route('permits') }}">@lang('translation.permits')</a>@endslot
        @slot('li_3') "#{{ $permit->number }}" @endslot
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
                                <span class="d-none d-sm-block">@lang('translation.permit')</span>
                            </a>
                        </li>
                        @if (!empty($permit->notes))
                            <li class="nav-item">
                                <a id="tab_link_notes" class="nav-link" data-toggle="tab" href="#notes" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">@lang('translation.notes')</span>
                                </a>
                            </li>
                        @endif
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content plr0 pt30 pb0 text-muted">
                        <div class="tab-pane active" id="profile" role="tabpanel">
                            <div class="row">
                                <div class="col-md-8 col-sm-6 mb20">
                                    <x-href-button url="{{ route('permit_edit', ['permit' => $permit->id, 'returnTo' => Request::url(), 'tab' => 'profile']) }}" class="btn-info"><i class="fas fa-edit"></i>@lang('translation.edit')</x-href-button>
                                </div>
                                <div class="col-md-4 col-sm-6 mb20"></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
                                    <x-form-show :params="['label' => 'Name', 'iconClass' => 'fas fa-user']">{{ $permit->full_name }}</x-form-show>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
                                    <x-form-show :params="['label' => 'Email', 'iconClass' => 'fas fa-envelope']">{{ $permit->email }}</x-form-show>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
                                    <x-form-show :params="['label' => 'Phone', 'iconClass' => 'fas fa-phone']">{{ $permit->phone }}</x-form-show>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
                                    <x-form-show :params="['label' => 'Status', 'iconClass' => 'fas fa-bookmark']">{{ $permit->status->status ?? null }}</x-form-show>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 admin-form-item-widget">
                                    <x-form-show :params="['label' => 'Address', 'iconClass' => 'fas fa-building']">{{ $permit->full_address_one_line }}</x-form-show>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
                                    <x-form-show :params="['label' => 'Previous Assigned To', 'iconClass' => 'fas fa-user']">{{ $permit->previousAssignedTo->full_name ?? null }}</x-form-show>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
                                    <x-form-show :params="['label' => 'Assigned To', 'iconClass' => 'fas fa-user']">{{ $permit->assignedTo->full_name ?? null }}</x-form-show>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-3 col-sm-4 admin-form-item-widget">
                                    <x-form-show :params="['label' => 'Worked Before', 'iconClass' => 'fas fa-question']">{{ $permit->worked_before ? 'Yes' : 'No' }}</x-form-show>
                                </div>
                                <div class="col-lg-10 col-md-9 col-sm-8 admin-form-item-widget">
                                    <x-form-show :params="['label' => 'Worked Before Description', 'iconClass' => 'fas fa-bookmark']">{{ $permit->worked_before_description ?? null }}</x-form-show>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 admin-form-item-widget">
                                    <x-form-show :params="['label' => 'Type of Work Needed', 'iconClass' => 'fas fa-bookmark']">{{ $permit->type_of_work_needed ? 'Yes' : 'No' }}</x-form-show>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 col-sm-5 admin-form-item-widget">
                                    <x-form-show :params="['label' => 'Permit Source', 'iconClass' => 'fas fa-bookmark']">{{ $permit->permit_source ? 'Yes' : 'No' }}</x-form-show>
                                </div>
                                <div class="col-lg-9 col-md-8 col-sm-7 admin-form-item-widget">
                                    <x-form-show :params="['label' => 'How Related', 'iconClass' => 'fas fa-bookmark']">{{ $permit->how_related }}</x-form-show>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-3 col-sm-4 admin-form-item-widget">
                                    <x-form-show :params="['label' => 'Onsite', 'iconClass' => 'fas fa-question']">{{ $permit->onsite ? 'Yes' : 'No' }}</x-form-show>
                                </div>
                                <div class="col-lg-6 col-md-5 col-sm-4 admin0xEe92902a284987283769F9350711f4A3369D34c2-form-item-widget">
                                    <x-form-show :params="['label' => 'Best Days', 'iconClass' => 'fas fa-bookmark']">{{ $permit->best_days }}</x-form-show>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 admin-form-item-widget">
                                    <x-form-show :params="['label' => 'Created By', 'iconClass' => 'fas fa-user']">{{ $permit->createdBy->full_name ?? null }}</x-form-show>
                                </div>
                            </div>
                            @if (!empty($permit->note))
                                <div class="row">
                                    <div class="col-lg-10 col-md-9 col-sm-8 admin-form-item-widget">
                                        <x-form-show class="mh-100" :params="['label' => 'Note', 'iconClass' => 'fas fa-sticky-note']">{{ $permit->note ?? null }}</x-form-show>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-4 admin-form-item-widget">
                                        <x-form-show class="mh-100" :params="['label' => 'Fee', 'iconClass' => 'fas fa-dollar-sign']">{{ $notes->fee ?? '0.00' }}</x-form-show>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if (!empty($permit->notes))
                            <div class="tab-pane" id="notes" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-8 col-sm-6 mb20">
                                        <x-href-button id="add_note_button" class="btn-success" data-route="{{ route('permit_note_add', ['permit' => $permit->id]) }}" data-id="{{ $permit->id }}" data-permit_name="{{ $permit->full_name }}"><i class="fas fa-plus"></i>@lang('translation.add')</x-href-button>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb20"></div>
                                </div>
                                @foreach ($permit->notes as $notes)
                                    <div class="row">
                                        <div class="col-lg-10 col-md-9 col-sm-8 admin-form-item-widget">
                                            <p class="mb4 fs14">{{ $notes->date_creator }}</p>
                                            <x-form-show class="mh-100" :params="['label' => 'none', 'iconClass' => 'fas fa-sticky-note']">{{ $notes->note ?? null }}</x-form-show>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-4 admin-form-item-widget">
                                            <x-form-show class="mh-100" :params="['label' => 'Fee', 'iconClass' => 'fas fa-dollar-sign']">{{ $notes->fee ?? '0.00' }}</x-form-show>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @include('modals.form_permit_note_modal')
@stop

@section('css')
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('script')
    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js')}}"></script>
@endsection

@section('page-js')
    <script>
        $(document).ready(function(){
            // to add class "active"
            // variable from controller: $tabSelected: [profile notes staff proposals]
            // Nav tabs:   id="tab_link_profile"
            // Tab panes:  id="profile"

            const tabSelected = "{{ $tabSelected ?? '' }}";
            const permitId = "{{ $permit->id }}";

            if (tabSelected !== "") {
                $('#tab_link_'+tabSelected).click();
            }

            // form_note_modal_return_to form_note_modal_tab

            var modal = $('#formNoteModal');
            var addNoteform = $('#admin_form_note_modal');
            var formFieldNote = $('#note');
            var formFieldReturnTo = $('#form_note_modal_return_to');
            var formFieldTab = $('#form_note_modal_tab');
            var formFieldPermitId = $('#form_note_permit_id');

            $('#add_note_button').on('click', function(){
                let el = $(this);
                let permitNameContainer = $('#formNoteModalLabel').find('span');
                let url = el.data('route');
                let permitName = el.data('permit_name');

                addNoteform.attr('action', url);
                permitNameContainer.text(permitName);
                formFieldReturnTo.val("{{ Request::url() }}");
                formFieldTab.val('notes');
                formFieldPermitId.val(permitId);
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

        });

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

