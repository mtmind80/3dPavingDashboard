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
                        <li class="nav-item  no-border">
                            <a class="nav-link active" data-toggle="tab" href="#proposal" role="tab">
                                <span class="d-block list-item"><i class="ri-home-2-line"></i> &nbsp;@lang('translation.proposal')</span>
                            </a>
                            <br/>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#services" id='servicestab' role="tab">
                                <span class="d-block  list-item"><i class="ri-tools-line"></i> &nbsp;@lang('translation.services')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#notes" id="notestab" role="tab">
                                <span class="d-block list-item"><i class="ri-camera-2-line"></i> &nbsp;@lang('translation.notes') / @lang('translation.media')</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#crm" id="crmtab" role="tab">
                                <span class="d-block list-item"><i class="ri-file-2-line"></i> &nbsp;@lang('translation.status') / @lang('translation.letters')</span>
                            </a>
                        </li>
                    </ul>
                    <table class="bg_lightning table-centered w-100 font-size-24 p18">
                        <tr>
                            <td>
                                {{$proposal['name']}}
                            </td>
                            <td class="tc">
                                STATUS: {{ App\Models\ProposalStatus::find($proposal['proposal_statuses_id'])->status }}
                            </td>
                        </tr>
                        @if($changeorder)
                            <tr>
                                <td colspan="2" class="font-size-12 p10">
                                    This is a change order for workorder {{$changeorder['job_master_id']}} (<a
                                        href="{{route("show_workorder", ['id'=>$changeorder['proposal_id']])}}">view
                                        work order</a>)
                                </td>
                            </tr>
                        @endif
                    </table>
                    <!-- Tab panes -->
                    <div class="tab-content plr0 pt30 pb0 text-muted">
                        <div class="tab-pane active" id="proposal" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table width="100%" class="table-centered table-bordered font-size-20">
                                        <tr>

                                            <td>
                                                <a href="{{route('edit_proposal',['id'=> $proposal['id']])}}"
                                                   title="@lang('translation.edit') @lang('translation.proposal')"
                                                   class="{{$site_button_class}}">
                                                    <i class="fas fa-plus"></i> @lang('translation.edit') @lang('translation.proposal')
                                                </a>

                                                @if(!$proposal['IsEditable'])
                                                    <br/><strong>Services are not editable.</strong>
                                                @endif

                                            </td>


                                            <td class="tc">
                                                <form id="printform" name="printform" method="POST"
                                                      action="">
                                                    @csrf

                                                    <table class="table no-border">
                                                        <td class="tc w-25 border-0">&nbsp</td>
                                                        <td class="tc w-25 border-0">Change Print Date:</td>
                                                        <td class="tc w-25 border-0"><input type="text"
                                                                                            name="print_date"
                                                                                            value="{{ \Carbon\Carbon::parse(Now())->format('Y-m-d') }}"
                                                                                            id="print_date"
                                                                                            class="form-control"
                                                                                            data-provide="datepicker"
                                                                                            data-date-format="yyyy-mm-dd">
                                                        </td>
                                                        <td class="tc w-25 border-0"><input type="hidden"
                                                                                            name="proposal_id"
                                                                                            value="{{$proposal['id']}}">

                                                            <button id="printproposal" class="{{$site_button_class}}">
                                                                <i class="fas fa-plus"></i> @lang('translation.print') @lang('translation.proposal')
                                                            </button>
                                                        </td>

                                                    </table>

                                                    <!--
                                                    <a  href="{{route('print_proposal',['proposal_id'=> $proposal['id']])}}"
                                                    title="@lang('translation.print') @lang('translation.proposal')"
                                                    class="{{$site_button_class}}">
                                                    <i class="fas fa-plus"></i> @lang('translation.print') @lang('translation.proposal')
                                                    </a>
                                                    -->
                                                </form>
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


                                        <!--                                 <tr>
                                        <td>@lang('translation.salesmanager')</td>
                                        <td>
                                            @if($proposal['salesmanager_id'])
                                            {{ App\Models\User::find($proposal['salesmanager_id'])->FullName }}
                                        @endif
                                        </td>
                                    </tr>
       -->
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

                                                    <a href="Javascript:showData(1);"
                                                       title="find contact">{{ App\Models\Contact::find($proposal['contact_id'])->FullName }}</a>

                                                @endif
                                                <span style="float:right;"><a
                                                        href="{{ route('change_client',['proposal_id' => $proposal['id']]) }}"
                                                        class='button' title="Change Client">Change Client</a></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>@lang('translation.proposalclient')</td>
                                            <td>
                                                @if($proposal['customer_staff_id'])
                                                    <a href="{{route("contact_details",['contact' => $proposal['customer_staff_id']])}}"
                                                       title="find staff">{{ App\Models\Contact::find($proposal['customer_staff_id'])->FullName }}</a>
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
                                                    @endif
                                                </td>


                                        <tr>
                                            <td>Clone Proposal</td>
                                            <td>
                                                <a href="Javascript:AREYOUSURE('You are about to clone this proposal. Are you sure?','{{route('clone_proposal',['id'=>$proposal['id']])}}');"
                                                   title="Clone this proposal">Clone This Proposal</a>

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
                                                <x-href-button
                                                    url="{{ route('new_service', ['proposal_id' => $proposal['id']]) }}"
                                                    class="mr10 btn btn-success"><i class="fas fa-plus"></i>Add Service
                                                </x-href-button>
                                                &nbsp;&nbsp;
                                                <x-href-button
                                                    url="{{ route('refresh_material', ['id' => $proposal['id']]) }}"
                                                    class="mr10 btn btn-success"><i
                                                        class="fas fa-plus"></i>@lang('translation.RefreshMaterials')
                                                </x-href-button>

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
                                        <span
                                            class="fas fa-sticky-note"></span>@lang('translation.add') @lang('translation.note')
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
                                    <a href="javascript:" id="addmediabutton" class="{{$site_button_class}}"
                                       data-action="add-media"
                                       data-route="{{ route('proposal_media_store', ['proposal' => $proposal['id']]) }}"
                                       data-proposal_name="{{ $proposal['name'] }}">
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
                                                    <a target='blank'
                                                       href="{{$hostwithHttp}}/{{$media->file_path . $media->file_name}}">Download</a>
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
                                    <h3>@lang('translation.change') @lang('translation.status')</h3>
                                    <form id="statusform" name="statusform" method="POST"
                                          action="{{route('change_status')}}">
                                        @csrf
                                        <input type="hidden" name="proposal_id" value="{{$proposal['id']}}">

                                        <select id="proposalstatus" name="status" class="form-control-sm"
                                                onchange="Javascript:shownote(this);">
                                            <option value="1">Reset Proposal to Pending</option>
                                            <option value="4">Proposal Sent to Client</option>
                                            <option value="2" selected>Client Approved Proposal</option>
                                            <option value="3">Client Rejected Proposal (add reason)</option>
                                        </select>
                                        <br/>
                                        <div id='noteform' style="display:none;">Reason Rejected:<br/><textarea
                                                class="form-control" rows='3' cols='65' name="note" id="note"
                                                placeholder="Add Note Here"></textarea>
                                            <br/>
                                            Rejected Reason: <br/><select class="form-control-sm" id="reason"
                                                                          name="reason">
                                                <option selected>Client decided not to do the work.</option>
                                                <option>Client accepted a competeing bid.</option>
                                                <option>Could not meet the clients needs.</option>
                                                <option>Other</option>
                                            </select>
                                            <br/>
                                        </div>

                                        <input type="submit" id="changestatus" class="{{$site_button_class}}"
                                               value="Click to Change Status">

                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3>@lang('translation.letters')</h3>

                                    <ul>
                                        <li>Thank You For Signing</li>
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
    <link href="{{ URL::asset('/backend/css/vendor.min.css') }}" rel="stylesheet" type="text/css"/>
@stop

@section('js-plugin-files')
    <script src="{{ URL::asset('/backend/js/vendor.min.js')}}"></script>
@stop

@section('page-js')
    <script>
        var selectedTab = "{{ $selectedTab ?? '' }}"

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


        function showData(c) {


            if (c == 1) // customer
            {

                Swal.fire({
                    title: 'Client Contact',
                    html: '<br/><h2><a href="{{route("contact_details",["contact"=>$proposal["contact_id"]])}}" title="find contact">{{ $proposal_customer["first_name"] }}  {{ $proposal_customer["last_name"] }}</a></h2>' +
                        '<br/><h3>{{ $proposal_customer['email'] }}</h3>' +
                        '<br/><h3>{{ $proposal_customer['phone'] }}</h3>' +
                        '<br/><h3>{{ $proposal_customer['address1'] }} {{ $proposal_customer['address2'] }}</h3>' +
                        '<br/><h3>{{ $proposal_customer['city'] }}, {{ $proposal_customer['state'] }} {{ $proposal_customer['postal_code'] }}</h3>',
                    icon: 'info',
                    heightAuto: false,
                    width: '60em',
                    showConfirmButton: true,

                })


            }
            if (c == 2) // sales
            {
                Swal.fire({
                    title: 'Client Staff Contact',
                    html: '<br/><h2><a href="{{route("contact_details",["contact"=>$proposal["customer_staff_id"]])}}"  title="find staff">{{ $proposal_staff["first_name"] }}  {{ $proposal_staff["last_name"] }}</a></h2>' +
                        '<br/><h3>{{ $proposal_staff['email'] }}</h3>' +
                        '<br/><h3>{{ $proposal_staff['phone'] }}</h3>' +
                        '<br/><h3>{{ $proposal_staff['address1'] }} {{ $proposal_staff['address2'] }}</h3>' +
                        '<br/><h3>{{ $proposal_staff['city'] }}, {{ $proposal_staff['state'] }} {{ $proposal_staff['postal_code'] }}</h3>',
                    icon: 'info',
                    heightAuto: false,
                    width: '60em',
                    showConfirmButton: true,

                })


            }


        }


        $(document).ready(function () {


            function checkform() {

                @if($proposal['salesperson_id'])
                    return true;
                @else
                    return false;
                @endif
            }

            $("#printproposal").click(function () {


                if (checkform()) {
                    $('#printform').attr('action', '{{route('print_proposal')}}');
                    @if(count($medias))
                    $('#printform').attr('action', '{{route('setup_proposal')}}');
                    @endif

                    $("#printform").submit();
                    //window.location.href = "/print/setup/?proposal_id={{$proposal['id']}}&print_date=" + print_date;
                    return;

                }
                alert("You must select a sales person before printing the contract. Edit the proposal and select a sales person.")
                return;

            })


            if (selectedTab !== "") {
                let a = $('#' + selectedTab);
                let li = a.closest('li');
                let ul = li.closest('ul');

                ul.find('li').removeClass('no-border');
                ul.find('a').removeClass('active');

                li.addClass('no-border');
                a.addClass('active').click();
            }

            if (document.referrer.includes('proposaldetails/edit')) {
                $("#servicestab").click();
            }

            console.log(document.referrer);

            const urlParams = new URLSearchParams(window.location.search);
            const querytype = urlParams.get('type');

            if (querytype == 'note') {
                $("#notestab").click();
            }

            var body = $('body');
            var noteModal = $('#formNoteModal');
            var noteForm = $('#admin_form_note_modal');
            var note = $('#note');


            $('#addnotebutton').click(function () {

                let el = $(this);
                let ProposalNoteContainer = $('#formNoteModalLabel').find('span');
                let url = el.data('route');
                console.log(url);
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
            var file = $('#file');

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


            var mediaModal2 = $('#formMediaModal2');
            var mediaForm2 = $('#admin_form_media_modal2');
            var file2 = $('#file2');

            $('#addmediabutton2').click(function () {

                let el = $(this);
                let ProposalMediaContainer = $('#formMediaModalLabel2').find('span');
                let ProposalMediaContainer2 = $('#formMediaModalService').find('span');
                let url = el.data('route');
                let ProposalName = el.data('proposal_name');
                let ServiceName = el.data('service_name');
                let ServiceID = el.data('service_id');
                mediaForm2.attr('action', url);
                $("#proposal_detail_id2").val(ServiceID);
                ProposalMediaContainer.text(ProposalName);
                ProposalMediaContainer2.text(ServiceName);

                mediaModal2.modal('show');

            });

            mediaModal2.on('show.bs.modal', function () {
                mediaForm2.find('em.state-error').remove();
                mediaForm2.find('.field.state-error').removeClass('state-error');
            })

            mediaModal2.on('hidden.bs.modal', function () {
                mediaForm2.find('em.state-error').remove();
                mediaForm2.find('.field.state-error').removeClass('state-error');
            })

            mediaForm2.validate({
                rules: {
                    description2: {
                        required: true,
                        plainText: true
                    }
                },
                messages: {
                    description2: {
                        required: "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_entry')"
                    }
                },
                submitHandler: function (form) {
                    let errors = false;

                    if (!errors) {
                        mediaForm2.submit();
                    }
                }
            });

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
        });


        function shownote(nform) {

            var f = parseInt(nform.value);
            if (f == 3) { //rejected
                Swal.fire({
                    title: 'Proposal Rejected',
                    html: '<br/><h3>If you are rejecting this proposal please give a short reason.</h3>',
                    icon: 'info',
                    heightAuto: false,
                    width: '80em',
                    showConfirmButton: true,

                })

                $("#noteform").show();
                return;
            }
            $("#noteform").hide();

        }
    </script>
@stop

