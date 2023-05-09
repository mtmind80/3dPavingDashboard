@extends('layouts.master')

@section('title')
    3D Paving Work Orders
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.menu_workorders')
        @endslot
        @slot('li_1')
            <a href="/dashboard">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            <a href="/proposals">@lang('translation.menu_workorders')</a>
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
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block">@lang('translation.menu_workorders')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#services" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                <span class="d-none d-sm-block">@lang('translation.services')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#notes" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                <span class="d-none d-sm-block">@lang('translation.notes') / @lang('translation.media')</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#crm" role="tab">
                                <span class="d-block d-sm-none"><i class="ri-compass-2-line"></i></span>
                                <span class="d-none d-sm-block">@lang('translation.status') / @lang('translation.letters')</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content plr0 pt30 pb0 text-muted">
                        <div class="tab-pane active" id="proposal" role="tabpanel">
                            <div class="row">
                                <table width="100%" class="table-centered table-bordered font-size-20">
                                    <tr>
                                        <td class="tc">
                                            {{$proposal['name']}}
                                        </td>
                                        <td class="tc">
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
                                        <td>@lang('translation.permit') @lang('translation.required')</td>
                                        <td>@if($proposal['permit_required'])
                                                YES
                                                &nbsp; &nbsp;
                                                <button class="button info" id="addpermit">Add Permit</button>
                                            @else
                                                NO
                                            @endif
                                        </td>
                                    </tr>
                                    @if($proposal['permit_required'] && $permits)
                                        <tr>
                                            <td>@lang('translation.permits')</td>
                                            <td>
                                                <table width="80%">
                                                    <tr>
                                                        <th class="tc">View</th>
                                                        <th class="tc">County</th>
                                                        <th class="tc">Number</th>
                                                        <th class="tc">Status</th>
                                                    </tr>
                                                    @foreach($permits as $permit)
                                                        <tr>
                                                            <td class="tc"><a class="tc"><a
                                                                            href="{{route('permit_show',['permit'=>$permit->id])}}">View
                                                                        Permit</a></td>
                                                            <td class="tc">{{$permit->county}}</td>
                                                            <td class="tc">{{$permit->number}}</td>
                                                            <td class="tc">{{$permit->status}}</td>
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
                                        <td>@if($proposal['on_alert'])
                                                YES

                                            @else
                                                NO
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>@lang('translation.nto') @lang('translation.sent') </td>
                                        <td>@if($proposal['nto_required'])
                                                YES
                                            @else
                                                NO
                                            @endif
                                        </td>
                                    </tr>
                                </table>

                            </div>
                        </div>
                        <div class="tab-pane" id="services" role="tabpanel">
                            <div class="row">

                                <div class="col-lg-12">
                                    @if($services)

                                        <table style="width:100%" class="list-table table table-centered table-bordered">
                                            <thead>
                                            <tr style="background:#E5E8E8;color:#000;">
                                                <td class="w250"><b>@lang('translation.workorderservices')</b></td>
                                                <td class="w50"><b>@lang('translation.status')</b></td>
                                                <td class="w230"><b>@lang('translation.location')</b></td>
                                                <td class="w200"><b>@lang('translation.fieldmanager')</b></td>
                                                <td class="w120"><b>@lang('translation.cost')</b></td>
                                                <td class="actions tc" ><b>@lang('translation.actions')</b></td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($services as $service)
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
                                                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                                                <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                                    <li>
                                                                        <a href="{{ route('workorder_timesheet_entry_form', ['proposal_detail_id' => $service->id]) }}">
                                                                            <span class="fas fa-pallet mr10"></span>@lang('translation.timesheet')
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ route('workorder_equipment_entry_form', ['proposal_detail_id' => $service->id]) }}">
                                                                            <span class="fas fa-gamepad mr10"></span>@lang('translation.equipment')
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ route('workorder_material_entry_form', ['proposal_detail_id' => $service->id]) }}">
                                                                            <span class="fas fa-sitemap mr10"></span>@lang('translation.materials')
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ route('workorder_vehicle_entry_form', ['proposal_detail_id' => $service->id]) }}">
                                                                            <span class="fas fa-car mr10"></span>@lang('translation.vehicle')
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ route('workorder_subcontractor_entry_form', ['proposal_detail_id' => $service->id]) }}">
                                                                            <span class="fas fa-user-friends mr10"></span>@lang('translation.subcontractors')
                                                                        </a>
                                                                    </li>
                                                                    @if($service->status_id == 1)
                                                                        <li>
                                                                            <a href="{{route('schedule_service', ['service_id'=>$service->id])}}" class="action list-group-item-action">
                                                                                <span class="far fa-calendar-check"></span>
                                                                                &nbsp; @lang('translation.schedule')
                                                                            </a>
                                                                        </li>
                                                                    @else
                                                                        <li>
                                                                            <a href="{{route('schedule_service', ['service_id'=>$service->id])}}"
                                                                               class="list-group-item-action">
                                                                                <span class="far fa-calendar-check"></span>
                                                                                &nbsp; @lang('translation.changeschedule')
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                    <li>
                                                                        <a href="{{route('workordermedia', ['proposal_id'=>$proposal['id'], 'proposal_detail_id'=>$service->id])}}"
                                                                           class="list-group-item-action">
                                                                            <span class="far fa-eye"></span>
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
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </td>

                                                </tr>

                                            @endforeach
                                            </tbody>
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
                                    <span class="fas fa-sticky-note"></span>@lang('translation.add') @lang('translation.note')
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
                                    <li>Change Status</li>
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

@stop

@section('page-js')
    <script>

        $(document).ready(function () {

            var body = $('body');
            var noteModal = $('#formNoteModal');
            var noteForm = $('#admin_form_note_modal');
            var note = $('#note');


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

                console.log('We re arher');

                let el = $(this);
                let ProposalMediaContainer = $('#formMediaModalLabel').find('span');
                let url = el.data('route');
                console.log(url);
                let ProposalName = el.data('proposal_name');
                console.log(ProposalName);

                mediaForm.attr('action', url);
                ProposalMediaContainer.text(ProposalName);
                mediaModal.modal('show');
                console.log('we are here');

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

