@extends('layouts.master')

@section('title')
    3D Paving Proposals
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.menu_permits')
        @endslot
        @slot('li_1')
            <a href="{{route("dashboard")}}">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            @lang('translation.menu_permits')
        @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt5">
                        <div class="col-md-8 col-sm-6 mb15">

                        </div>
<!--
                        <div class="col-md-4 col-sm-6 mb15">
                            <x-search :needle="$needle" search-route="{{ route('permit_search') }}"
                                      cancel-route="{{ route('permits') }}"></x-search>
                        </div>
                        -->
                    </div>
                    <table class="list-table table table-bordered dt-responsive nowrap"
                           style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th class="sorting_disabled tc w350">@lang('translation.work_order')</th>
                            <th class="sorting_disabled tc w200"> @lang('translation.county')</th>
                            <th class="sorting_disabled tc w200"> @lang('translation.city')</th>
                            <th class="sorting_disabled tc w120">@lang('translation.type')</th>
                            <th class="sorting_disabled tc w150">@lang('translation.menu_permits') @lang('translation.number')</th>
                            <th class="sorting_disabled tc w200"> @lang('translation.status')
                                / @lang('translation.expires')</th>
                            <th class="actions">Actions</th>
                        </tr>
                        </thead>

                        <tbody>

                        @if(!count($permits))
                            <tr class="even">
                                <td colspan='7' class="text-dark fw-bold">@lang('translation.norecordsfound')</td>
                            </tr>
                        @else
                            @foreach ($permits as $permit)
                                @if (strtotime($permit->expires_on) <= strtotime(date('Y-m-d')) )
                                    <tr class="alert-danger">
                                @else
                                    <tr>
                                        @endif

                                        <td class="tc"><a
                                                href="{{route('permit_show',['permit'=>$permit->id])}}">{{ $permit->name }}</a>
                                            <br/>{{ $permit->job_master_id }}

                                        </td>
                                        <td class="tc">{{ $permit->county }}</td>
                                        <td class="tc text-dark fw-bold">{{ $permit->city }}</td>
                                        <td class="tc text-dark fw-bold">{{ $permit->type }}</td>
                                        <td class="tc text-dark fw-bold">{{ $permit->number }}</td>
                                        <td class="tc">
                                            {{ $permit->status }}
                                            <br/>
                                            Expires:{{ $permit->expires_on }}
                                        </td>
                                        <td class="centered actions">
                                            <ul class="nav navbar-nav">
                                                <li class="dropdown">
                                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i
                                                            class="fa fa-angle-down"></i></a>
                                                    <ul class="dropdown-menu animated animated-short flipInX"
                                                        role="menu">
                                                        @if (\App\Models\PermitNote::where('permit_id','=', $permit->id))

                                                            <li>
                                                                <a href="javascript:"
                                                                   class="action"
                                                                   data-action="list-notes"
                                                                   data-permit_id="{{ $permit->id }}"
                                                                   data-proposal_name="{{ $permit->name }}"
                                                                   data-permit_number="{{ $permit->number }}"
                                                                >
                                                                    <span
                                                                        class="fas fa-comments"></span>@lang('translation.notes')
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if(auth()->user()->isAdmin())
                                                            <li>
                                                                <a href="javascript:"
                                                                   class="action"
                                                                   data-action="add-note"
                                                                   data-route="{{ route('permit_note_add', ['permit' => $permit->id]) }}"
                                                                   data-proposal_name="{{ $permit->name }}"
                                                                   data-permit_number="{{ $permit->number }}"
                                                                >
                                                                    <span
                                                                        class="fas fa-sticky-note"></span>@lang('translation.add') @lang('translation.note')
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:"
                                                                   class="action"
                                                                   data-action="change-status"
                                                                   data-route="{{ route('permit_status_change', ['permit' => $permit->id]) }}"
                                                                   data-proposal_name="{{ $permit->name }}"
                                                                   data-permit_number="{{ $permit->number }}"
                                                                   data-status="{{ $permit->status }}"
                                                                >
                                                                    <span
                                                                        class="fas fa-retweet"></span>@lang('translation.change_status')
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <a href="{{route('permit_show',['permit'=>$permit->id])}}">
                                                                <span
                                                                    class="fas fa-eye"></span>@lang('translation.view') @lang('translation.permit')
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('show_workorder', ['id' => $permit->proposal_id]) }}">
                                                                <span
                                                                    class="fas fa-eye"></span>@lang('translation.view') @lang('translation.work_order')
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @include('modals.form_select_status_modal')
                                @endif
                        </tbody>
                    </table>


                </div>
                <table class="table w220">
                    <tr>
                        <td class="w180">Indicates permit expired</td>
                        <td class="alert-danger w40">&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    @include('modals.list_notes_modal')
    @include('modals.form_permit_note_modal')

@stop

@section('page-js')
    <script>
        $(document).ready(function () {
            var body = $('body');

            // list notes:

            var listNotesModal = $('#listNotesModal');
            var listNotesModalLabel = $('#listNotesModalLabel');
            var permitNumberContainer = listNotesModalLabel.find('span');
            var listNotesContainer = $('#listNotesModalContainer');
            var listNotesModalTotalFees = $('#listNotesModalTotalFees').find('span');

            body.on('click', '.actions .action[data-action="list-notes"]', function () {
                let el = $(this);
                let permitId = el.data('permit_id');
                let permitNumber = el.data('permit_number');
                let proposalName = el.data('proposal_name');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        permit_id: permitId
                    },
                    type: "POST",
                    url: "{{ route('ajax_permit_note_list') }}",
                    beforeSend: function (request) {
                        showSpinner();
                    },
                    complete: function () {
                        hideSpinner();
                    },
                    success: function (response) {
                        if (response.success) {

                            var notes = response.notes;
                            var html = '';
                            console.log(notes);

                            $.each(notes, function (index, note) {
                                html += '<div class="note-box">';
                                html += '    <p class="note-date-fee clearfix">';
                                html += '<span class="note-date">' + note.date_creator + '</span>';
                                html += '<span class="note-fee">Fee: ' + note.fee + '</span>';
                                html += '    </p>';
                                html += '   <div class="note-content">';
                                html += note.content;
                                html += '   </div>';
                                html += '</div>';
                            });

                            listNotesContainer.html(html);
                            listNotesModalTotalFees.text(response.total_fees);
                            permitNumberContainer.text(permitNumber);

                            listNotesModal.modal('show');
                        } else {
                            uiAlert({type: 'error', title: 'Error', text: response.message});
                        }
                    },
                    error: function (data) {
                        hideSpinner();
                        alert('error');
                        console.log(data);
                    }
                });

            });

            listNotesModal.on('show.bs.modal', function () {
                permitNumberContainer.empty();
                listNotesContainer.empty();
            })

            listNotesModal.on('hidden.bs.modal', function () {
                permitNumberContainer.empty();
                listNotesContainer.empty();
            })

            // add note

            var noteModal = $('#formNoteModal');
            var noteForm = $('#admin_form_note_modal');
            var note = $('#note');

            body.on('click', '.actions .action[data-action="add-note"]', function () {
                let el = $(this);
                let permitNumberContainer = $('#formNoteModalLabel').find('span');
                let url = el.data('route');
                let permitNumber = el.data('permit_number');

                noteForm.attr('action', url);
                permitNumberContainer.text(permitNumber);
                noteModal.modal('show');
            });

            noteModal.on('show.bs.modal', function () {
                noteForm.find('em.state-error').remove();
                noteForm.find('.field.state-error').removeClass('state-error');
            })

            noteModal.on('hidden.bs.modal', function () {
                noteForm.find('em.state-error').remove();
                noteForm.find('.field.state-error').removeClass('state-error');
            })

            noteForm.validate({
                rules: {
                    note: {
                        required: true,
                        plainText: true
                    }
                },
                messages: {
                    note: {
                        required: "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_entry')"
                    }
                },
                submitHandler: function (form) {
                    let errors = false;

                    if (!errors) {
                        form.submit();
                    }
                }
            });

            // status

            var statusModal = $('#formStatusModal');
            var statusForm = $('#admin_form_status_modal');

            body.on('click', '.actions .action[data-action="change-status"]', function () {
                let el = $(this);
                let permitNumberContainer = $('#formStatusModalLabel').find('span');
                let permitSelect = statusForm.find('select');
                let url = el.data('route');
                let permitNumber = el.data('permit_number');
                let currentStatus = el.data('status');

                $('#current_status').text(currentStatus);

                permitSelect.find('option[value="' + currentStatus + '"]').remove();

                statusForm.attr('action', url);
                permitNumberContainer.text(permitNumber);
                statusModal.modal('show');
            });

            statusModal.on('show.bs.modal', function () {
                statusForm.find('em.state-error').remove();
                statusForm.find('.field.state-error').removeClass('state-error');
            })

            statusModal.on('hidden.bs.modal', function () {
                statusForm.find('em.state-error').remove();
                statusForm.find('.field.state-error').removeClass('state-error');
            })

            statusForm.validate({
                rules: {
                    status: {
                        required: true,
                        plainText: true
                    }
                },
                messages: {
                    status: {
                        required: "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_entry')"
                    }
                },
                submitHandler: function (form) {
                    let errors = false;

                    if (!errors) {
                        form.submit();
                    }
                }
            });
        });
    </script>
@stop

