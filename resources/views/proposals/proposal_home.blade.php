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

    <div class="row admin-form show-proposal-page">

        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        <li class="nav-item no-border">
                            <a class="nav-link active" data-toggle="tab" href="#proposal" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block h4">@lang('translation.proposal')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#services" id='servicestab' role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                <span class="d-none d-sm-block h4">@lang('translation.services')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#notes" id="notestab" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                <span class="d-none d-sm-block h4">@lang('translation.notes') / @lang('translation.media')</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#crm" id="crmtab" role="tab">
                                <span class="d-block d-sm-none"><i class="ri-compass-2-line"></i></span>
                                <span class="d-none d-sm-block h4">@lang('translation.status') / @lang('translation.letters')</span>
                            </a>
                        </li>
                    </ul>
                    <table class="bg-light table-centered w-100 font-size-24 p10">
                        <tr>
                            <td>
                                {{$proposal['name']}}
                            <td class="tc">
                                STATUS: {{ App\Models\ProposalStatus::find($proposal['proposal_statuses_id'])->status }}
                            </td>
                        </tr>
                    </table>
                    <!-- Tab panes -->
                    <div class="tab-content plr0 pt30 pb0 text-muted">
                        <div class="tab-pane active" id="proposal" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table width="100%" class="table-centered table-bordered font-size-20">
                                    <tr>
                                    @if($proposal['IsEditable'])
                                            <td>
                                                <a href="{{route('edit_proposal',['id'=> $proposal['id']])}}"
                                                   title="@lang('translation.edit') @lang('translation.proposal')"
                                                   class="{{$site_button_class}}">
                                                    <i class="fas fa-plus"></i> @lang('translation.edit') @lang('translation.proposal')
                                                </a>

                                            </td>

                                    @else

                                            <td class="alert-sucess">
                                                <strong>Services are not editable.</strong>
                                            </td>
                                    @endif

                                        <td class="tc">

                                            <button id="printproposal"  class="{{$site_button_class}}">
                                                <i class="fas fa-plus"></i> @lang('translation.print') @lang('translation.proposal')
                                            </button>
<!--
                                            <a  href="{{route('print_proposal',['proposal_id'=> $proposal['id']])}}"
                                               title="@lang('translation.print') @lang('translation.proposal')"
                                               class="{{$site_button_class}}">
                                                <i class="fas fa-plus"></i> @lang('translation.print') @lang('translation.proposal')
                                            </a>
-->
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
                                                <a href="{{route('contact_details',['contact'=>$proposal['contact_id']])}}" title="find contact">{{ App\Models\Contact::find($proposal['contact_id'])->FullName }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('translation.proposalclient')</td>
                                        <td>
                                            @if($proposal['customer_staff_id'])
                                                <a href="{{route('contact_details',['contact'=>$proposal['customer_staff_id']])}}"  title="find staff">{{ App\Models\Contact::find($proposal['customer_staff_id'])->FullName }}</a>
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
                                            @if($proposal['on_alert'])
                                            <td class="bg-alert">
                                                YES  &nbsp;&nbsp; Reason: {{$proposal['alert_reason']}}
                                                <x-href-button url="{{ route('proposal_alert_reset', ['proposal_id' => $proposal['id']]) }}" class="btn-danger ptb2 fr"><i class="fas fa-times"></i>Remove Alert</x-href-button>
                                            @else
                                            <td>
                                                NO
                                                <x-href-button id="set_alert_button" class="btn-success ptb2 fr"><i class="fas fa-check"></i>Set Alert</x-href-button>
                                            @endif
                                        </td>
                                    </tr>

                                        <tr>
                                            <td>Create Change Order</td>
                                            <td>
                                                <a href="{{route('contact_details',['contact'=>$proposal['customer_staff_id']])}}"  title="create change order">New Change Order</a>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>CLone Proposal</td>
                                            <td>
                                                <a href="{{route('contact_details',['contact'=>$proposal['customer_staff_id']])}}" title="clone this proposal">Clone This Proposal</a>

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
                        </div>
                        <div class="tab-pane" id="services" role="tabpanel">
                            <div class="row">
                                <div id="services_container" class="col-sm-12">
                                    <div class="row">
                                        <div class="col-md-8 col-sm-6 mb20">
                                        @if ($proposal['IsEditable'])
                                            <x-href-button url="{{ route('new_service', ['proposal_id' => $proposal['id']]) }}" class="mr10 btn btn-success"><i class="fas fa-plus"></i>Add Service</x-href-button>
                                            &nbsp;&nbsp;
                                            <x-href-button url="{{ route('refresh_material', ['id' => $proposal['id']]) }}" class="mr10 btn btn-success"><i class="fas fa-plus"></i>@lang('translation.RefreshMaterials')</x-href-button>

                                            @endif

                                        @if (!empty($services) && $services->count() > 0)
                                            <x-reorder-button
                                                :url="route('services_reorder')"
                                                :params="[
                                                'hidden-fields' => [
                                                'proposal_id' => $proposal['id']
                                                    ]
                                                ]"
                                            ></x-reorder-button>
                                        @endif
                                        </div>
                                        <div class="col-md-4 col-sm-6 mb20 xs-hidden"></div>
                                    </div>

                                    @include('proposals._proposal_services')
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="notes" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-12">
                                    <a href="javascript:" id="addnotebutton" class="{{$site_button_class}}"
                                       data-action="add-note"
                                       data-route="{{ route('proposal_note_store', ['proposal' => $proposal['id']]) }}"
                                       data-proposal_name="{{ $proposal['name'] }}">
                                        <span class="fas fa-sticky-note"></span>@lang('translation.add') @lang('translation.note')
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
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
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <a href="javascript:" id="addmediabutton" class="{{$site_button_class}}" data-action="add-media" data-route="{{ route('proposal_media_store', ['proposal' => $proposal['id']]) }}" data-proposal_name="{{ $proposal['name'] }}">
                                        <span class="fas fa-sticky-note"></span> @lang('translation.upload')
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
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
                        </div>
                        <div class="tab-pane" id="crm" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3>@lang('translation.status')</h3>

                                    <ul>
                                        <li>Set Alert</li>
                                        <li>Change Status</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
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
    </div>

    @include('modals.form_media_modal')

    @include('modals.form_media_modal2')

    @include('modals.form_proposal_note_modal')

    @include('modals.form_proposal_alert_reason_modal')
@stop

@section('css-files')
    <link href="{{ URL::asset('/backend/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('js-plugin-files')
    <script src="{{ URL::asset('/backend/js/vendor.min.js')}}"></script>
@stop

@section('page-js')
    <script>
        var selectedTab = "{{ $selectedTab ?? '' }}"

        $(document).ready(function () {


            $("#printproposal").click(function(){

                let timerInterval

                Swal.fire({
                    title: 'Be Patient',
                    html: 'Preparing your proposal for download. </br>This can take a few minutes!</br>I will close automatically in <strong>10</strong> seconds.<br/><br/>',
                    icon: 'info',
                    heightAuto: false,
                    timerProgressBar : true,
                    timer: 10000,
                    customClass: {
                        title: 'info font-size-44',
                        htmlContainer: 'fs-55',
                        timerProgressBar: 'fs-22',
                        footer:"font-size-44 font-weight-semibold",
                    },
                    width: '90em',
                    footer : "Thank you for your patience.",
                    showConfirmButton: false,
                })

                window.location.href="{{route('print_proposal',['proposal_id'=> $proposal['id']])}}";

/*
                Swal.fire({
                    title: 'Auto close alert!',
                    html:
                        'I will close in <strong></strong> seconds.<br/><br/>' +
                        '<button id="increase" class="btn btn-warning">' +
                        'I need 5 more seconds!' +
                        '</button><br/><br/>' +
                        '<button id="stop" class="btn btn-danger">' +
                        'Please stop the timer!!' +
                        '</button><br/><br/>' +
                        '<button id="resume" class="btn btn-success" disabled>' +
                        'Phew... you can restart now!' +
                        '</button><br/><br/>' +
                        '<button id="toggle" class="btn btn-primary">' +
                        'Toggle' +
                        '</button>',
                    timer: 10000,
                    didOpen: () => {
                        const content = Swal.getHtmlContainer()
                        const $ = content.querySelector.bind(content)

                        const stop = $('#stop')
                        const resume = $('#resume')
                        const toggle = $('#toggle')
                        const increase = $('#increase')

                        Swal.showLoading()

                        function toggleButtons () {
                            stop.disabled = !Swal.isTimerRunning()
                            resume.disabled = Swal.isTimerRunning()
                        }

                        stop.addEventListener('click', () => {
                            Swal.stopTimer()
                            toggleButtons()
                        })

                        resume.addEventListener('click', () => {
                            Swal.resumeTimer()
                            toggleButtons()
                        })

                        toggle.addEventListener('click', () => {
                            Swal.toggleTimer()
                            toggleButtons()
                        })

                        increase.addEventListener('click', () => {
                            Swal.increaseTimer(5000)
                        })

                        timerInterval = setInterval(() => {
                            Swal.getHtmlContainer().querySelector('strong')
                                .textContent = (Swal.getTimerLeft() / 1000)
                                .toFixed(0)
                        }, 100)
                    },
                    willClose: () => {
                        clearInterval(timerInterval)
                    }
                })

                */
                return;

            })


            if (selectedTab !== "") {
                let a = $('#'+selectedTab);
                let li = a.closest('li');
                let ul = li.closest('ul');

                ul.find('li').removeClass('no-border');
                ul.find('a').removeClass('active');

                li.addClass('no-border');
                a.addClass('active').click();
            }

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

            var alertModal = $('#formAlertReasonModal');
            var alertForm = $('#admin_form_alert_reason_modal');
            var alertReason = $('#form_alert_reason_alert_reason');


            $('#set_alert_button').click(function(){
                alertModal.modal('show');
            });

            alertModal.on('show.bs.modal', function(){
                alertForm.find('em.state-error').remove();
                alertForm.find('.field.state-error').removeClass('state-error');
                alertReason.val('');
            })

            alertModal.on('hidden.bs.modal', function(){
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
                        required : "@lang('translation.field_required')",
                        text: "@lang('translation.invalid_entry')"
                    }
                },
                submitHandler: function(form){
                    let errors = false;

                    if (!errors) {
                        alertForm.submit();
                    }
                }
            });
        });


    </script>
@stop

