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
                                <span class="d-block  list-item"><i class="ri-home-2-line"></i> @lang('translation.work_order')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#services" role="tab">
                                <span class="d-block  list-item"><i class="ri-tools-line"></i> @lang('translation.services')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#notes" role="tab">
                                <span class="d-block  list-item"><i class="ri-camera-2-line"></i> @lang('translation.notes') / @lang('translation.media')</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#crm" role="tab">
                                <span class="d-block  list-item"><i class="ri-file-2-line"></i> @lang('translation.status') / @lang('translation.letters')</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content plr0 pt30 pb0 text-muted">
                        <div class="tab-pane active" id="proposal" role="tabpanel">
                            <div class="row">
                                <table width="100%" class="table-centered table-bordered font-size-20">
                                    @if($proposal->proposal_statuses_id == 5)
                                        <tr>
                                            <td class="w-25">

                                                <a href="{{route('edit_workorder',['id'=> $proposal['id']])}}"
                                                   title="@lang('translation.edit') @lang('translation.proposal')"
                                                   class="{{$site_button_class}}">
                                                    <i class="fas fa-plus"></i> @lang('translation.edit') @lang('translation.work_order')
                                                </a>
                                            </td>
                                            <td class="tc w-75">
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="tc w-25">
                                            {{$proposal['name']}}
                                        </td>
                                        <td class="tc w-75">
                                            STATUS: {{ App\Models\ProposalStatus::find($proposal['proposal_statuses_id'])->status }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><b>@lang('translation.workorderid')</b></td>
                                        <td>
                                            Work Order ID:{{$proposal['job_master_id']}}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>@lang('translation.location')</td>
                                        <td>
                                            @if($proposal['location_id'])
                                                {!! App\Models\Location::find($proposal['location_id'])->FullLocationTwoLines !!}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('translation.proposaldate')</td>
                                        <td>{{ \Carbon\Carbon::parse($proposal['proposal_date'])->format('m/d/yy') }}</td>
                                    </tr>
                                    <tr>
                                        <td>@lang('translation.status')</td>
                                        <td>
                                            @if($proposal['proposal_statuses_id'])
                                                {{ App\Models\ProposalStatus::find($proposal['proposal_statuses_id'])->status }}
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>@lang('translation.salesmanager')</td>
                                        <td>
                                            @if($proposal['salesmanager_id'])
                                                {{ App\Models\User::find($proposal['salesmanager_id'])->FullName }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('translation.salesperson')</td>
                                        <td>
                                            @if($proposal['salesperson_id'])
                                                {{ App\Models\User::find($proposal['salesperson_id'])->FullName }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('translation.client')</td>
                                        <td>
                                            @if($proposal['contact_id'])
                                                <a href="{{route('contact_details',['contact'=>$proposal['contact_id']])}}">{{ App\Models\Contact::find($proposal['contact_id'])->FullName }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('translation.proposalclient')</td>
                                        <td>
                                            @if($proposal['customer_staff_id'])
                                                <a href="{{route('contact_details',['contact'=>$proposal['customer_staff_id']])}}">{{ App\Models\Contact::find($proposal['customer_staff_id'])->FullName }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('translation.progressivebilling')</td>
                                        <td>@if($proposal['progressive_billing'])
                                                YES
                                            @else
                                                NO
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            @lang('translation.permit') @lang('translation.required')
                                        </td>

                                        <td>@if($proposal['permit_required'])
                                                YES
                                                @if(auth()->user()->isAdmin())
                                                    <button class="{{$site_button_class}}" id="addpermit">Add Permit
                                                    </button>
                                                @endif
                                            @else
                                                NO
                                            @endif
                                        </td>
                                    </tr>

                                    @if($proposal['permit_required'] && $permits)
                                        <tr>
                                            <td style="background-color:#A4F9F9;">
                                                Current @lang('translation.permits')</td>
                                            <td>
                                                <table width="80%">
                                                    <tr>
                                                        <th class="tc">View</th>
                                                        <th class="tc">County</th>
                                                        <th class="tc">Number</th>
                                                        <th class="tc">Status</th>
                                                        <th class="tc">Submitted</th>
                                                        <th class="tc">Expires</th>
                                                    </tr>
                                                    @foreach($permits as $permit)
                                                        <tr>
                                                            <td class="tc">
                                                                <a href="{{route('permit_show',['permit'=>$permit->id])}}">View
                                                                    Permit</a>
                                                            </td>
                                                            <td class="tc">{{$permit->county}}</td>
                                                            <td class="tc">{{$permit->number}}</td>
                                                            <td class="tc">{{$permit->status}}</td>
                                                            <td class="tc">@if($permit->submitted_on)
                                                                    {{$permit->submitted_on->format('M-d-Y')}}
                                                                @endif</td>
                                                            <td class="tc">@if($permit->expires_on)
                                                                    {{$permit->expires_on->format('M-d-Y')}}
                                                                @endif</td>
                                                        </tr>
                                                    @endforeach
                                                </table>


                                            </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td>@lang('translation.mot') @lang('translation.required')</td>
                                        <td>@if($proposal['mot_required'])
                                                YES
                                            @else
                                                NO
                                            @endif
                                        </td>
                                    </tr>

                                        <tr>
                                            <td>@lang('translation.on_alert')</td>
                                            @if($proposal['on_alert'])
                                                <td class="bg-alert">
                                                    YES &nbsp;&nbsp; Reason: {{$proposal['alert_reason']}}
                                                    <x-href-button
                                                        url="{{ route('proposal_alert_reset', ['proposal_id' => $proposal['id']]) }}"
                                                        class="btn-danger ptb2 fr"><i class="fas fa-times"></i>Remove
                                                        Alert
                                                    </x-href-button>
                                            @else
                                                <td>
                                                    NO
                                                    <x-href-button id="set_alert_button" class="btn-success ptb2 fr"><i
                                                            class="fas fa-check"></i>Set Alert
                                                    </x-href-button>

                                                </td>
                                           @endif

                                        <tr>



                                        <tr>
                                        <td>@lang('translation.nto') @lang('translation.sent') </td>
                                        <td>@if($proposal['nto_required'])
                                                YES
                                            @else
                                                NO
                                            @endif
                                        </td>
                                    </tr>
                                    @if(!$proposal['changeorder'])
                                        <tr>
                                            <td>Create Change Order</td>
                                            <td>
                                                <a href="Javascript:AREYOUSURE('You want to create a change order for this workorder? Are you sure?','{{route('create_changeorder',['id'=>$proposal['id']])}}');"
                                                   title="Create Change Order for this Work Order">Create Change
                                                    Order</a>

                                            </td>
                                        </tr>


                                        @if($changeorders)

                                            <td style="background-color:#A4F9F9;">
                                                Change Orders
                                            </td>
                                            <td>
                                                <table width="80%">
                                                    <tr>
                                                        <th class="tc">View</th>
                                                        <th class="tc">Name</th>
                                                        <th class="tc">Created</th>
                                                    </tr>
                                                    @foreach($changeorders as $changeorder)
                                                        <tr>
                                                            <td class="tc">
                                                                <a href="{{route('show_proposal',['id'=>$changeorder->new_proposal_id])}}">
                                                                    View Change Order</a>
                                                            </td>
                                                            <td class="tc">{{\App\Models\Proposal::where('id', '=', $changeorder->new_proposal_id)->first()->name}}</td>
                                                            <td class="tc">{{\App\Models\Proposal::where('id', '=', $changeorder->new_proposal_id)->first()->created_at->format('Y-m-d')}}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>


                                            </td>
                                            </tr>

                                        @endif
                                    @endif
                                    <!--                                        <tr>
                                        <td>Clone Work Order</td>
                                        <td>
                                            <a href="Javascript:AREYOUSURE('You are about to clone this work order. Are you sure?','{{route('clone_proposal',['id'=>$proposal['id']])}}');" title="Clone this proposal">Clone This Work Order</a>

                                        </td>
                                    </tr>
-->
                                </table>

                            </div>
                        </div>
                        <div class="tab-pane" id="services" role="tabpanel">
                            <div class="row">

                                <div class="col-lg-12">
                                    @if($services)

                                        <table style="width:100%"
                                               class="list-table table table-centered table-bordered">
                                            <thead>
                                            <tr style="background:#E5E8E8;color:#000;">
                                                <td class="w250"><b>@lang('translation.workorderservices')</b></td>
                                                <td class="w50"><b>@lang('translation.status')</b></td>
                                                <td class="w230"><b>@lang('translation.location')</b></td>
                                                <td class="w200"><b>@lang('translation.fieldmanager')</b></td>
                                                <td class="w120"><b>@lang('translation.cost')</b></td>
                                                <td class="actions tc"><b>@lang('translation.actions')</b></td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $totalcost = 0;
                                            @endphp

                                            @foreach($services as $service)
                                                @php

                                                    $totalcost = $totalcost + $service->cost;
                                                @endphp
                                                <tr>

                                                    <td>
                                                        <a href="{{route('view_service', ['proposal_id'=>$proposal['id'],'id'=>$service->id])}}">{{$service->service_name}}</a>

                                                        </br>
                                                    </td>
                                                    <td>
                                                        @if($service->status_id)
                                                            {{ App\Models\ProposalDetailStatus::find($service->status_id)->status }}
                                                        @else
                                                            No Status
                                                    @endif
                                                    <td>
                                                        @if($service->location_id)
                                                            {!! App\Models\Location::find($service->location_id)->FullLocationTwoLines !!}
                                                        @else
                                                            No Location Specified
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($service->fieldmanager_id)
                                                            {{ App\Models\User::find($service->fieldmanager_id)->FullName }}
                                                        @else
                                                            No Manager Assigned
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\Currency::format($service->cost ?? '0.0') }}</br>
                                                    </td>
                                                    <td class="centered actions">
                                                        <ul class="nav navbar-nav">
                                                            <li class="dropdown">
                                                                <a class="dropdown-toggle" data-toggle="dropdown"
                                                                   href="#"><i class="fa fa-angle-down"></i></a>
                                                                <ul class="dropdown-menu animated animated-short flipInX"
                                                                    role="menu">
                                                                    @if($allowSchedule)
                                                                        @if($service->status_id == 1)
                                                                            <li>
                                                                                <a href="{{route('schedule_service', ['service_id'=>$service->id])}}"
                                                                                   class="action list-group-item-action">
                                                                                    <span
                                                                                        class="far fa-calendar-check"></span>
                                                                                    &nbsp; @lang('translation.schedule')
                                                                                </a>
                                                                            </li>
                                                                        @else
                                                                            <li>
                                                                                <a href="{{route('schedule_service', ['service_id'=>$service->id])}}"
                                                                                   class="list-group-item-action">
                                                                                    <span
                                                                                        class="far fa-calendar-check"></span>
                                                                                    &nbsp; @lang('translation.changeschedule')
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                    @else
                                                                        <li>
                                                                            <a href="Javascript:alert('Until a deposit is recorded and all permits are completed you cannot schedule this work. Check Payments and Permits');"
                                                                               class="list-group-item-action">
                                                                                <span
                                                                                    class="far fa-calendar-check"></span>
                                                                                &nbsp; @lang('translation.cantschedule')
                                                                            </a></li>

                                                                    @endif
                                                                    <li>
                                                                        <a href="{{route('workordermedia', ['proposal_id'=>$proposal['id'], 'proposal_detail_id'=>$service->id])}}"
                                                                           class="list-group-item-action">
                                                                            <span class="fa fa-upload"></span>
                                                                            &nbsp; @lang('translation.upload')
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{route('assignmanager', ['id'=>$proposal['id'], 'detail_id'=>$service->id])}}"
                                                                           class="list-group-item-action">
                                                                            <span class="far fa-address-book"></span>
                                                                            &nbsp; @lang('translation.fieldmanager')
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ route('workorder_details', ['proposal_detail_id' => $service->id]) }}">
                                                                            <span
                                                                                class="fas fa-eye mr10"></span>@lang('translation.fieldreport')
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr class="pt10 no-border">
                                                <td class="tr" colspan="4">Grand Total:</td>
                                                <td class="tc">{{ $currency_total_details_costs }}</td>
                                                <td class="tr"></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="notes" role="tabpanel">
                            <div class="row">
                                <a href="javascript:" id="addnotebutton" class="btn  btn-success action"
                                   data-action="add-note"
                                   data-route="{{ route('proposal_note_store', ['proposal' => $proposal['id']]) }}"
                                   data-proposal_name="{{ $proposal['name'] }}">
                                    <span
                                        class="fas fa-sticky-note"></span>@lang('translation.add') @lang('translation.note')
                                </a>

                            </div>
                            <div class="row">
                                <table style="width:100%" class="table table-centered table-bordered">
                                    <thead>
                                    <tr style="background:#E5E8E8;color:#000;">
                                        <td class="w-75"><b>@lang('translation.note')</b></td>
                                        <td><b>@lang('translation.remind')</b></td>
                                        <td><b>@lang('translation.createdby')</b></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($notes as $note)
                                        <tr>
                                            <td>
                                                {{$note->note}}</br>
                                            </td>
                                            <td>
                                                @if($note->reminder)
                                                    {{ \Carbon\Carbon::parse($note->reminder_date)->format('m/d/Y') }}</br>
                                                @else
                                                    NA
                                                @endif
                                            </td>

                                            <td>
                                                @if($note['created_by'])
                                                    {{ App\Models\User::find($note->created_by)->FullName }} </br>
                                                @endif
                                                {{ \Carbon\Carbon::parse($note->created_at)->format('m/d/Y') }}</br>
                                            </td>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <a href="javascript:" id="addmediabutton" class="btn  btn-success action"
                                   data-action="add-media"
                                   data-route="{{ route('proposal_media_store', ['proposal' => $proposal['id']]) }}"
                                   data-proposal_name="{{ $proposal['name'] }}">
                                    <span class="fas fa-sticky-note"></span> @lang('translation.upload')
                                </a>

                            </div>
                            <div class="row">

                                <!--                            <a href="{{route('workordermedia', ['proposal_id'=>$id,'proposal_detail_id'=> 0])}}"
                                   class="btn btn-success"><i
                                            class="fas fa-plus"></i> Add Media</a>
   -->
                                <table style="width:100%" class="table table-centered table-bordered">
                                    <thead>
                                    <tr style="background:#E5E8E8;color:#000;">
                                        <td><b></b>@lang('translation.media') @lang('translation.type')</b></td>
                                        <td><b>@lang('translation.Name')</b></td>
                                        <td><b>@lang('translation.filetype')</b></td>
                                        <td><b>@lang('translation.download')</b></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($medias as $media)
                                        <tr>
                                            <td>
                                                @if($media->media_type_id)
                                                    {{ App\Models\MediaType::find($media->media_type_id)->type}}
                                                @endif
                                            </td>

                                            <td>
                                                {{$media->description}}
                                            </td>
                                            <td>
                                                {{$media->file_ext}}
                                            </td>

                                            <td>
                                                <a target='blank'
                                                   href="{{$hostwithHttp}}/{{$media->file_path . $media->file_name}}">Download</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="tab-pane" id="crm" role="tabpanel">
                            <div class="row">

                                <h3>@lang('translation.status')</h3>

                                <ul>
                                    <li>Set Alert</li>
                                    <li>Cancel Workorder</li>
                                    <li>
                                        <a href="{{route('create_payment',['id'=>$proposal['id']])}}">Record
                                            Payments</a>
                                    </li>
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

    @include('modals.form_media_modal')
    @include('modals.form_fieldmanagers_modal')
    @include('modals.form_proposal_note_modal')
    @include('modals.form_proposal_alert_reason_modal')

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
                    return window.location.href = url; //'{{route('clone_proposal',['id'=>$proposal['id']])}}';
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

                window.location.href = '{{ route('add_permit',['id'=>$proposal->id]) }}';
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
                console.log(url);
                let ProposalName = el.data('proposal_name');
                console.log(ProposalName);

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

