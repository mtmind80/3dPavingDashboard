@extends('layouts.master')

@section('title')
    3D Paving Work Orders
@endsection

<style>
    .list-item {
        font-size: 1.25EM;
        margin-bottom: 9px;
    }
    .bg_lightning {
        color: #000000;
        background-color: #E8F8F5;
    }
</style>

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.menu_workorders')
        @endslot
        @slot('li_1')
            <a href="/dashboard">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            <a href="/workorders">@lang('translation.menu_workorders')</a>
        @endslot
    @endcomponent

    <div class="row admin-form">

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#proposal" role="tab">
                                <span class="d-block  list-item">
                                    <i class="ri-home-2-line"></i>
                                    @lang('translation.work_order')
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#services" role="tab">
                                <span class="d-block  list-item">
                                    <i class="ri-tools-line"></i>
                                    @lang('translation.services')
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#notes" role="tab">
                                <span class="d-block  list-item">
                                    <i class="ri-camera-2-line"></i>
                                    @lang('translation.notes') / @lang('translation.media')
                                </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#crm" role="tab">
                                <span class="d-block  list-item">
                                    <i class="ri-file-2-line"></i>
                                    @lang('translation.status') / @lang('translation.letters')
                                </span>
                            </a>
                        </li>
                    </ul>

                    <!-- Common for all tabs -->

                    <table width="100%" class="table-centered table-bordered font-size-20">
                        <tbpdy>
                            @if ($workOrder->proposal_statuses_id == 5)
                                <tr>
                                    <td colspan='2' class="tl">
                                        <a href="{{ route('edit_workorder', ['id' => $workOrder->id]) }}"
                                           title="@lang('translation.edit') @lang('translation.work_order')"
                                           class="{{ $site_button_class3 }}"
                                        >
                                            <i class="fas fa-plus"></i>
                                            @lang('translation.edit') @lang('translation.work_order')
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        </tbpdy>
                        <tfooter>
                            <tr>
                                <td class="tl w-50">
                                    {{ $workOrder->name }}
                                </td>
                                <td class="tl w-50">
                                    STATUS: {{ $workOrder->status?->status }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tl" colspan="2">
                                    <b>@lang('translation.workorderid')</b> {{ $workOrder->job_master_id }}
                                </td>
                            </tr>
                        </tfooter>
                    </table>

                    <!-- Work Order -->

                    <div class="tab-content plr0 pt30 pb0 text-muted">
                        <div class="tab-pane active" id="proposal" role="tabpanel">
                            <div class="row">
                                <table width="100%" class="table-centered table-bordered font-size-20">
                                    <tr>
                                        <td>@lang('translation.location')</td>
                                        <td>{!! $workOrder->Location?->full_location_two_lines !!}</td>
                                    </tr>
                                    <tr>
                                        <td>@lang('translation.proposaldate')</td>
                                        <td>{{ $workOrder->proposal_date->format('m/d/yy') }}</td>
                                    </tr>
                                    <tr>
                                        <td>@lang('translation.status')</td>
                                        <td>{{ $workOrder->status?->status }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('translation.salesmanager')</td>
                                        <td>{{ $workOrder->salesManager?->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>@lang('translation.salesperson')</td>
                                        <td>{{ $workOrder->salesPerson?->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>@lang('translation.client')</td>
                                        <td>
                                            @if ($workOrder->contact_id !== null)
                                                <a href="{{ route('contact_details',['contact' => $workOrder->contact_id]) }}">
                                                    {{ $workOrder->contact->full_name }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('translation.proposalclient')</td>
                                        <td>
                                            @if ($workOrder->customer_staff_id !== null)
                                                <a href="{{ route('contact_details',['contact' => $workOrder->customer_staff_id]) }}">
                                                    {{ $workOrder->customerStaff->full_name }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('translation.progressivebilling')</td>
                                        <td>{{ (bool)$workOrder->progressive_billing === true ? 'YES' : 'NO' }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            @lang('translation.permit') @lang('translation.required')
                                        </td>
                                        <td>@if ((bool)$workOrder->permit_required === true)
                                                YES
                                                @if (auth()->user()->isAdmin())
                                                    <button
                                                            class="{{ $site_button_class2 }} float-right"
                                                            id="addpermit"
                                                    >
                                                        @lang('translation.add') @lang('translation.permit')
                                                    </button>
                                                @endif
                                            @else
                                                NO
                                            @endif
                                        </td>
                                    </tr>

                                    @if ((bool)$workOrder->permit_required === true && $workOrder->permits->count() > 0)
                                        <tr>
                                            <td style="background-color:#e6f2ff;">
                                                Current @lang('translation.permits')
                                            </td>
                                            <td>
                                                <table width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th class="tc">View</th>
                                                        <th class="tc">County</th>
                                                        <th class="tc">Number</th>
                                                        <th class="tc">Status</th>
                                                        <th class="tc">Submitted</th>
                                                        <th class="tc">Expires</th>
                                                    </tr>
                                                    </thead>
                                                    <tbod >
                                                        @foreach($workOrder->permits as $permit)
                                                            <tr>
                                                                <td class="tc">
                                                                    <a href="{{ route('permit_show',['permit' => $permit->id]) }}">
                                                                        View Permit
                                                                    </a>
                                                                </td>
                                                                <td class="tc">{{ $permit->county }}</td>
                                                                <td class="tc">{{ $permit->number }}</td>
                                                                <td class="tc">{{ $permit->status }}</td>
                                                                <td class="tc">
                                                                    @if ($permit->submitted_on)
                                                                        {{ $permit->submitted_on->format('M-d-Y') }}
                                                                    @endif
                                                                </td>
                                                                <td class="tc">
                                                                    @if ($permit->expires_on)
                                                                        {{ $permit->expires_on->format('M-d-Y') }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbod>
                                                </table>
                                            </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td>@lang('translation.mot') @lang('translation.required')</td>
                                        <td>{{ (bool)$workOrder->mot_required === true ? 'YES' : 'NO' }}</td>
                                    </tr>
                                    <tr>
                                        <td>@lang('translation.on_alert')</td>
                                        @if ((bool)$workOrder->on_alert === true)
                                            <td class="bg-alert">
                                                YES &nbsp;&nbsp; Reason: {{ $workOrder->alert_reason }}
                                                <x-href-button
                                                        url="{{ route('proposal_alert_reset', ['proposal_id' => $workOrder->id]) }}"
                                                        class="btn-danger ptb2 fr"
                                                >
                                                    <i class="fas fa-times"></i>
                                                    Remove Alert
                                                </x-href-button>
                                            </td>
                                        @else
                                            <td>
                                                NO
                                                <x-href-button
                                                        id="set_alert_button"
                                                        class="btn-success ptb2 fr"
                                                >
                                                    <i class="fas fa-check"></i>
                                                    Set Alert
                                                </x-href-button>
                                            </td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>@lang('translation.nto') @lang('translation.sent') </td>
                                        <td>{{ (bool)$workOrder->nto_required === true ? 'YES' : 'NO' }}</td>
                                    </tr>

                                    @if ($workOrder->changeOrders->count() > 0)
                                        <tr>
                                            <td>Create Change Order</td>
                                            <td>
                                                <x-href-button
                                                        id="create_change"
                                                        class="btn-success ptb2 fr"
                                                >
                                                    <i class="fas fa-check"></i>
                                                    Create Change Order
                                                </x-href-button>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="background-color:#ccfff2;">
                                                Change Orders
                                            </td>
                                            <td>
                                                <table width="80%">
                                                    <tr>
                                                        <th class="tc">View</th>
                                                        <th class="tc">Name</th>
                                                        <th class="tc">Created</th>
                                                    </tr>
                                                    @foreach($workOrder->changeOrders as $changeOrder)
                                                        <tr>
                                                            <td class="tc">
                                                                <a href="{{ route('show_proposal', ['id' => $changeOrder->id]) }}">
                                                                    View Change Order
                                                                </a>
                                                            </td>
                                                            <td class="tc">{{ $changeOrder->name }}</td>
                                                            <td class="tc">{{ $changeOrder->created_at->format('Y-m-d') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </td>
                                        </tr>

                                    @endif
                                    {{--
                                    @elseif ($workOrder->parentWorkOrder !== null && $workOrder->parentWorkOrder->count() > 0)
                                        <!-- -->

                                    <tr>
                                        <td>Clone Work Order</td>
                                        <td>
                                            <a href="Javascript:AREYOUSURE('You are about to clone this work order. Are you sure?','{{ route('clone_proposal',['id' => $workOrder->id]) }}');" title="Clone this proposal">Clone This Work Order</a>
                                        </td>
                                    </tr>
                                    --}}
                                </table>

                            </div>
                        </div>

                        <!-- Services -->

                        <div class="tab-pane" id="services" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    @if ($workOrder->services->count() > 0)
                                        <table
                                            style="width:100%;"
                                            class="w100perc list-table table table-centered table-bordered"
                                        >
                                            <thead>
                                                <tr style="background:#E5E8E8;color:#000;">
                                                    <td class="tl"><b>@lang('translation.workorderservices')</b></td>
                                                    <td class="tl"><b>@lang('translation.fieldmanager')</b></td>
                                                    <td class="tc"><b>@lang('translation.cost')</b></td>
                                                    <td class="actions tc"><b>@lang('translation.actions')</b></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($workOrder->services as $service)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('view_service', ['proposal_id' => $workOrder->id, 'id' => $service->id]) }}">
                                                                {{ $service->service_name }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $service->fieldmanager !== null ? $service->fieldmanager->full_name : 'No Manager Assigned' }}</td>
                                                        <td class="tc">{{ $service->html_cost }}</td>
                                                        <td class="centered actions">
                                                            <ul class="nav navbar-nav">
                                                                <li class="dropdown">
                                                                    <a
                                                                        class="dropdown-toggle"
                                                                        data-toggle="dropdown"
                                                                        href="#"
                                                                    >
                                                                        <i class="fa fa-angle-down"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                                        @if ($workOrder->canBeScheduled())
                                                                            @if ((int)$service->status_id === 1)
                                                                                <li>
                                                                                    <a
                                                                                        href="{{ route('schedule_service', ['service_id' => $service->id]) }}"
                                                                                        class="action list-group-item-action"
                                                                                    >
                                                                                        <span  class="far fa-calendar-check mr8"></span>
                                                                                        @lang('translation.schedule')
                                                                                    </a>
                                                                                </li>
                                                                            @else
                                                                                <li>
                                                                                    <a
                                                                                        href="{{ route('schedule_service', ['service_id'=>$service->id]) }}"
                                                                                        class="list-group-item-action"
                                                                                    >
                                                                                        <span class="far fa-calendar-check mr8"></span>
                                                                                        @lang('translation.changeschedule')
                                                                                    </a>
                                                                                </li>
                                                                            @endif
                                                                        @endif
                                                                        <li>
                                                                            <a
                                                                                href="{{ route('view_service', ['proposal_id' => $workOrder->id, 'id' => $service->id]) }}"
                                                                                class="list-group-item-action"
                                                                            >
                                                                                <span class="fa fa-edit mr8"></span>
                                                                                @lang('translation.view') @lang('translation.service')
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a
                                                                                href="{{ route('workordermedia', ['proposal_id' => $workOrder->id, 'proposal_detail_id' => $service->id]) }}"
                                                                                class="list-group-item-action"
                                                                            >
                                                                                <span class="fa fa-upload mr8"></span>
                                                                                @lang('translation.upload')
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a
                                                                                href="{{ route('assignmanager', ['id' => $workOrder->id, 'detail_id' => $service->id]) }}"
                                                                                class="list-group-item-action"
                                                                            >
                                                                                <span class="far fa-address-book mr8"></span>
                                                                                @lang('translation.fieldmanager')
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a
                                                                                href="{{ route('workorder_field_report_list', ['proposal_detail_id' => $service->id]) }}"
                                                                            >
                                                                                <span class="fas fa-eye mr8"></span>
                                                                                @lang('translation.fieldreport')
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfooter>
                                                <tr>
                                                    <td class="tr" colspan="2">Grand Total:</td>
                                                    <td class="tl">{{ $workOrder->currency_total_details_costs }}</td>
                                                    <td class="tr"></td>
                                                </tr>
                                            </tfooter>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->

                        <div class="tab-pane" id="notes" role="tabpanel">
                            <div class="row">
                                <a
                                    href="javascript:" id="addnotebutton"
                                    class="btn btn-success action mb15"
                                    data-action="add-note"
                                    data-route="{{ route('proposal_note_store', ['proposal' => $workOrder->id]) }}"
                                    data-proposal_name="{{ $workOrder->name }}"
                                >
                                    <span class="fas fa-sticky-note"></span>
                                    @lang('translation.add') @lang('translation.note')
                                </a>
                            </div>
                            <div class="row">
                                <table style="width:100%" class="table table-centered table-bordered">
                                    <thead>
                                        <tr style="background:#E5E8E8;color:#000;">
                                            <td class="w-75"><b>@lang('translation.note')</b></td>
                                            <td class="tc"><b>@lang('translation.remind')</b></td>
                                            <td class="tc"><b>@lang('translation.createdby')</b></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($workOrder->notes as $note)
                                            <tr>
                                                <td>{{ $note->note }}</td>
                                                <td class="tc">{{ $note->reminder_date?->format('m/d/Y') }}</td>
                                                <td class="tc">{{ $note->user->full_name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row pt15">
                                <a
                                    href="javascript:"
                                    id="addmediabutton"
                                    class="btn  btn-success action mb15"
                                    data-action="add-media"
                                    data-route="{{ route('proposal_media_store', ['proposal' => $workOrder->id]) }}"
                                    data-proposal_name="{{ $workOrder->name }}"
                                >
                                    <span class="fas fa-sticky-note"></span>
                                    @lang('translation.upload')
                                </a>

                            </div>
                            <div class="row">
                                <table style="width:100%" class="table table-centered table-bordered">
                                    <thead>
                                        <tr style="background:#E5E8E8;color:#000;font-weight:bold;">
                                            <td>@lang('translation.media') @lang('translation.type')</td>
                                            <td>@lang('translation.Name')</td>
                                            <td>@lang('translation.filetype')</td>
                                            <td>@lang('translation.download')</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($workOrder->media as $media)
                                            <tr>
                                                <td>{{ $media->type?->type }}</td>
                                                <td>{{ $media->description }}</td>
                                                <td>{{ $media->file_ext }}</td>
                                                <td>
                                                    <a
                                                        href="{{ hostWithHttp }}/{{ $media->file_path . $media->file_name }}"
                                                        target='blank'
                                                    >
                                                        Download
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Status and Letters -->

                        <div class="tab-pane" id="crm" role="tabpanel">
                            <div class="row">
                                <h3>@lang('translation.status')</h3>
                                <ul>
                                    <li>
                                        <a href="{{ route('cancel_workorder',['id' => $workOrder->id]) }}">
                                            Cancel Workorder
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('create_payment',['id' => $workOrder->id]) }}">
                                            Record Payments
                                        </a>
                                    </li>
                                    @if ((bool)$workOrder->permit_required === true && $workOrder->permits !== null && $workOrder->permits->count() > 0)
                                        <li>
                                            <a href="{{ route('permit_invoice',['id' => $workOrder->id]) }}">
                                                Print Permits Invoice
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="row">
                                <h3>@lang('translation.letters')</h3>
                                <ul>
                                    <li>Thank You For Signing</li>
                                    <li>Change Order</li>
                                    <li>Service Begin Reminder</li>
                                    <li>MOT Reminder</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.form_media_modal', [
        'id' => $workOrder->id,
        'mediatypes' => $mediaTypes,
        'services' => $workOrder->services,
        'doctypes' => $docTypes,
    ])
    @include('modals.form_fieldmanagers_modal', [
       'fieldmanagersCB' => $fieldManagersCB,
    ])
    @include('modals.form_proposal_note_modal', ['proposal' => $workOrder])
    @include('modals.form_proposal_alert_reason_modal', ['proposal' => $workOrder])
@stop


@section('page-js')
    <script>
        function AREYOUSURE(msg, url) {
            Swal.fire({
                title: msg,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: "No",
                cancelButtonColor: "#A9DFBF",
                customClass: {
                    actions: 'my-actions',
                    cancelButton: 'order-1 right-gap',
                    confirmButton: 'order-2',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    return window.location.href = url;
                } else if (result.isDenied) {
                    Swal.fire('Cancelled', 'Cancelled', 'info')
                }
            })
        }

        $(document).ready(function () {
            var body = $('body');
            var noteModal = $('#formNoteModal');
            var noteForm = $('#admin_form_note_modal');
            var note = $('#note');

            var alertModal = $('#formAlertReasonModal');
            var alertForm = $('#admin_form_alert_reason_modal');
            var alertReason = $('#form_alert_reason_alert_reason');

            $('#create_change').click(function () {
                AREYOUSURE('You want to create a change order for this workorder? Are you sure?', '{{ route('create_changeorder',['id'=>$workOrder->id]) }}');
            });

            $('#set_alert_button').click(function () {
                alertModal.modal('show');
            });

            alertModal.on('show.bs.modal', function () {
                alertForm.find('em.state-error').remove();
                alertForm.find('.field.state-error').removeClass('state-error');
                alertReason.val('');
            })

            alertModal.on('hidden.bs.modal', function () {
                alertForm.find('em.state-error').remove();
                alertForm.find('.field.state-error').removeClass('state-error');
                alertReason.val('');
            })

            alertForm.validate({
                rules: {
                    alert_reason: {
                        required: true,
                        text: true
                    }
                },
                messages: {
                    alert_reason: {
                        required: "@lang('translation.field_required')",
                        text: "@lang('translation.invalid_entry')"
                    }
                },
                submitHandler: function (form) {
                    let errors = false;

                    if (!errors) {
                        alertForm.submit();
                    }
                }
            });


            $('#addpermit').click(function () {
                window.location.href = "{{ route('add_permit', ['id' => $workOrder->id]) }}";
            });


            $('#addnotebutton').click(function () {

                let el = $(this);
                let ProposalNoteContainer = $('#formNoteModalLabel').find('span');
                let url = el.data('route');
                let ProposalName = el.data('proposal_name');

                noteForm.attr('action', url);
                ProposalNoteContainer.text(ProposalName);
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
                        noteForm.submit();
                    }
                }
            });


            var mediaModal = $('#formMediaModal');
            var mediaForm = $('#admin_form_media_modal');
            var media = $('#media');

            $('#addmediabutton').click(function () {
                let el = $(this);
                let ProposalMediaContainer = $('#formMediaModalLabel').find('span');
                let url = el.data('route');
                let ProposalName = el.data('proposal_name');

                mediaForm.attr('action', url);
                ProposalMediaContainer.text(ProposalName);
                mediaModal.modal('show');
            });

            mediaModal.on('show.bs.modal', function () {
                mediaForm.find('em.state-error').remove();
                mediaForm.find('.field.state-error').removeClass('state-error');
            })

            mediaModal.on('hidden.bs.modal', function () {
                mediaForm.find('em.state-error').remove();
                mediaForm.find('.field.state-error').removeClass('state-error');
            })

            mediaForm.validate({
                rules: {
                    description: {
                        required: true,
                        plainText: true
                    }
                },
                messages: {
                    description: {
                        required: "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_entry')"
                    }
                },
                submitHandler: function (form) {
                    let errors = false;

                    if (!errors) {
                        mediaForm.submit();
                    }
                }
            });
        });
    </script>
@stop
