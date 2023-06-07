@extends('layouts.master')

@section('title')
    3D Paving Proposals
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.Proposals')
        @endslot
        @slot('li_1')
            <a href="/dashboard">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            <a href="/proposals">@lang('translation.Proposals')</a>
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
                                <span class="d-none d-sm-block">@lang('translation.proposal')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#services" id='servicestab' role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                <span class="d-none d-sm-block">@lang('translation.services')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#notes" id="notestab" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                <span class="d-none d-sm-block">@lang('translation.notes') / @lang('translation.media')</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#crm" id="crmtab" role="tab">
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
                                    @if($proposal['IsEditable'])
                                            <td>
                                                <a href="{{route('edit_proposal',['id'=> $proposal['id']])}}"
                                                   title="@lang('translation.edit') @lang('translation.proposal')"
                                                   class="{{$site_button_class}}"><i
                                                            class="fas fa-plus"></i>@lang('translation.edit') @lang('translation.proposal')
                                                </a>

                                            </td>

                                    @else

                                            <td class="alert-sucess">
                                                <strong>Services are not editable.</strong>
                                            </td>
                                    @endif

                                        <td class="tc">
                                            STATUS: {{ App\Models\ProposalStatus::find($proposal['proposal_statuses_id'])->status }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td><b>@lang('translation.proposalname')</b></td>
                                        <td>{{$proposal['name']}}
                                            @if($proposal['job_master_id'])
                                                <br/>Work Order ID:{{$proposal['job_master_id']}}
                                            @endif
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
                                            @else
                                                NO
                                            @endif
                                        </td>
                                    </tr>
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

<!--
                                    <tr>
                                        <td>@lang('translation.nto') @lang('translation.sent') </td>
                                        <td>@if($proposal['nto_required'])
                                                YES
                                            @else
                                                NO
                                            @endif
                                        </td>
                                    </tr>
                                    -->
                                </table>

                            </div>
                        </div>
                        <div class="tab-pane" id="services" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-8">
                                    @if($proposal['IsEditable'])
                                        <a href="{{route('new_service', ['proposal_id'=>$proposal['id']])}}"
                                           class="{{$site_button_class}}"><i
                                                    class="fas fa-plus"></i> Add Service</a>
                                    @endif
                                </div>
                                <div class="col-lg-4 float-right">
          
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-lg-12">
                                    @if($services)

                                        <table style="width:100%" class="table table-centered table-bordered">
                                            <thead>
                                            <tr style="background:#E5E8E8;color:#000;">
                                                <td><b>@lang('translation.proposalservices')</b></td>
                                                <td><b>@lang('translation.status')</b></td>
                                                <td><b>@lang('translation.location')</b></td>
                                                <td><b>@lang('translation.fieldmanager')</b></td>
                                                <td><b>@lang('translation.cost')</b></td>
                                                <td><b>@lang('translation.action')</b></td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($services as $service)
                                                <tr>

                                                    <td>
                                                            <a href="{{route('edit_service', ['proposal_id'=>$proposal['id'],'id'=>$service->id])}}">{{$service->service_name}}</a>

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
                                                                    @if($proposal['IsEditable'])
                                                                        <li>
                                                                            <a href="{{route('edit_service', ['proposal_id'=>$proposal['id'], 'id'=>$service->id])}}"
                                                                               class="list-group-item-action">
                                                                                <span class="fas fa-edit"></span>
                                                                                &nbsp; @lang('translation.edit')
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{route('refresh_material', ['id'=>$proposal['id']])}}"
                                                                               class="list-group-item-action">
                                                                                <span class="far fa-eye"></span>
                                                                                &nbsp; @lang('translation.RefreshMaterials')
                                                                            </a>
                                                                        </li>
                                                                    @else
                                                                        @if($service->status_id == 1)
                                                                            <li>
                                                                                <a href="{{route('schedule_service', ['service_id'=>$service->id])}}"
                                                                                   class="list-group-item-action">
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

                                                                        
                                                                    @endif

                                                                        <li>
                                                                            <a href="#" id="addmediabutton2"
                                                                               class="list-group-item-action"
                                                                               data-action="add-media" 
                                                                               data-route="{{ route('proposal_media_store', ['proposal' => $proposal['id']]) }}"
                                                                               data-proposal_name="{{ $proposal['name'] }}"
                                                                               data-service_id = "{{$service->id}}"
                                                                               data-service_name="{{$service->service_name}}"
                                                                               >
                                                                                <span class="far fa-address-card"></span>
                                                                                &nbsp; @lang('translation.upload')
                                                                            </a>
                                                                        </li>
                                                                        <!--
                                                                        <li>
                                                                            <a href="{{route('media', ['proposal_id'=>$proposal['id'], 'proposal_detail_id'=>$service->id])}}"
                                                                               class="list-group-item-action">
                                                                                <span class="far fa-eye"></span>
                                                                                &nbsp; @lang('translation.upload')
                                                                            </a>
                                                                        </li>
-->
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
                                <a href="javascript:" id="addnotebutton" class="{{$site_button_class}}" 
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
                                <a href="javascript:" id="addmediabutton" class="{{$site_button_class}}" data-action="add-media" data-route="{{ route('proposal_media_store', ['proposal' => $proposal['id']]) }}" data-proposal_name="{{ $proposal['name'] }}">
                                    <span class="fas fa-sticky-note"></span> @lang('translation.upload')
                                </a>

                            </div>
                            <div class="row">
                                <table style="width:100%" class="table table-centered table-bordered">
                                    <thead>
                                    <tr style="background:#E5E8E8;color:#000;">
                                        <td><b></b>@lang('translation.media') @lang('translation.type')</b></td>
                                        <td><b>@lang('translation.Name')</b></td>
                                        <td><b>@lang('translation.description')</b></td>
                                        <td><b>@lang('translation.File_Upload') @lang('translation.date')</b></td>
                                        <td><b>@lang('translation.service')</b></td>
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
                                                {{$media->original_name}}
                                            </td>
                                            <td>
                                                {{$media->description}}
                                            </td>
                                            <td>
                                                @if($media->created_by)
                                                    {{ App\Models\User::find($media->created_by)->FullName }} </br>
                                                @endif
                                                {{ \Carbon\Carbon::parse($media->created_at)->format('m/d/yy') }}
                                            </td>
                                            <td>
                                                @if($media->proposal_detail_id)
                                                    {{ App\Models\ProposalDetail::find($media->proposal_detail_id)->service_name}}
                                                    @else
All
                                                @endif
                                            </td>

                                            <td>
                                                <a target='blank' href="{{$hostwithHttp}}/{{$media->file_path . $media->file_name}}">Download</a>
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
    
    @include('modals.form_media_modal2')
    
    @include('modals.form_proposal_note_modal')

@stop

@section('page-js')
    <script>
        
        $(document).ready(function () {

            if(document.referrer.includes('proposaldetails/edit')) {
                $("#servicestab").click();
            }
            
            console.log(document.referrer);

            const urlParams = new URLSearchParams(window.location.search);
            const querytype = urlParams.get('type');
            
            if(querytype =='note') {
                $("#notestab").click();
            }
            
            var body = $('body');
            var noteModal = $('#formNoteModal');
            var noteForm = $('#admin_form_note_modal');
            var note = $('#note');
            
            
            $('#addnotebutton').click(function(){
                
                let el = $(this);
                let ProposalNoteContainer = $('#formNoteModalLabel').find('span');
                let url = el.data('route');
                console.log(url);
                let ProposalName = el.data('proposal_name');

                noteForm.attr('action', url);
                ProposalNoteContainer.text(ProposalName);
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


            var mediaModal = $('#formMediaModal');
            var mediaForm = $('#admin_form_media_modal');
            var file = $('#file');
            
            $('#addmediabutton').click(function(){

                let el = $(this);
                let ProposalMediaContainer = $('#formMediaModalLabel').find('span');
                let url = el.data('route');
                let ProposalName = el.data('proposal_name');
                mediaForm.attr('action', url);
                ProposalMediaContainer.text(ProposalName);
                mediaModal.modal('show');
                
            });

            mediaModal.on('show.bs.modal', function(){
                mediaForm.find('em.state-error').remove();
                mediaForm.find('.field.state-error').removeClass('state-error');
            })

            mediaModal.on('hidden.bs.modal', function(){
                mediaForm.find('em.state-error').remove();
                mediaForm.find('.field.state-error').removeClass('state-error');
            })

            mediaForm.validate({
                rules: {
                    description: {
                        required : true,
                        plainText: true
                    }
                },
                messages: {
                    description: {
                        required : "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_entry')"
                    }
                },
                submitHandler: function(form){
                    let errors = false;

                    if (!errors) {
                        mediaForm.submit();
                    }
                }

            });


            var mediaModal2 = $('#formMediaModal2');
            var mediaForm2 = $('#admin_form_media_modal2');
            var file2 = $('#file2');

            $('#addmediabutton2').click(function(){

                let el = $(this);
                let ProposalMediaContainer = $('#formMediaModalLabel2').find('span');
                let ProposalMediaContainer2 = $('#formMediaModalService').find('span');
                let url = el.data('route');
                let ProposalName = el.data('proposal_name');
                let ServiceName = el.data('service_name');
                let ServiceID =  el.data('service_id');
                mediaForm2.attr('action', url);
                $("#proposal_detail_id2").val(ServiceID); 
                ProposalMediaContainer.text(ProposalName);
                ProposalMediaContainer2.text(ServiceName);
                
                mediaModal2.modal('show');

            });

            mediaModal2.on('show.bs.modal', function(){
                mediaForm2.find('em.state-error').remove();
                mediaForm2.find('.field.state-error').removeClass('state-error');
            })

            mediaModal2.on('hidden.bs.modal', function(){
                mediaForm2.find('em.state-error').remove();
                mediaForm2.find('.field.state-error').removeClass('state-error');
            })

            mediaForm2.validate({
                rules: {
                    description2: {
                        required : true,
                        plainText: true
                    }
                },
                messages: {
                    description2: {
                        required : "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_entry')"
                    }
                },
                submitHandler: function(form){
                    let errors = false;

                    if (!errors) {
                        mediaForm2.submit();
                    }
                }
            });

        });


    </script>
@stop

