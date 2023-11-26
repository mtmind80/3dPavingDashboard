@extends('layouts.master')

@section('title') 3D Paving Permit Details @endsection


@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.permit') {{$permit->proposal->name}}
        @endslot
        @slot('li_1')
            <a href="{{ route('dashboard') }}" xmlns="http://www.w3.org/1999/html">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            <a href="{{ route('permits') }}">@lang('translation.all') @lang('translation.permits')</a>
        @endslot
        @slot('li_3')
            <a href="{{ route('show_workorder', ['id'=>$permit->proposal->id]) }}">@lang('translation.work_order')</a>
        @endslot

    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        <li class="nav-item  no-border">
                            <a class="nav-link active" data-toggle="tab" href="#permits" role="tab">
                                <span class="d-block list-item"><i class="ri-building-2-line"></i>@lang('translation.edit') @lang('translation.permit')</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a id="tab_link_notes" class="nav-link" data-toggle="tab" href="#notes" role="tab">
                                <span class="d-block list-item"><i class="ri-inbox-line"></i>@lang('translation.notes') / @lang('translation.fees')</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content ">
                        <div class="tab-pane active" id="permits" role="tabpanel">
                            <div class="row mt-3 ml-0 mr-0">
                                <form method="post" action="{{route('permit_update',['permit'=>$permit->id])}}"
                                      id="permitform" class="w-100">
                                    @csrf

                                    <input name="_method" type="hidden" value="PATCH">
                                    <input type="hidden" name="returnTo" value="{{url()->current()}}">
                                    <input type="hidden" name="id" value="{{$permit->id}}">
                                    <input type="hidden" name="last_updated_by" value="{{auth()->user()->id}}">
                                    <input type="hidden" name="created_by" value="{{$permit->created_by}}">
                                    <input type="hidden" name="proposal_detail_id"
                                           value="{{isset($permit->proposal_detail->id) ? $permit->proposal_detail->id : 0 }}">
                                    <input type="hidden" name="proposal_id" value="{{$permit->proposal->id}}">

                                    <div class="row">
                                        <div class="col-lg-2">
                                            <label>Status:</label>
                                            <select class="form-control" name="status">
                                                <option>{{$permit->status}}</option>
                                                <option>Not Submitted</option>
                                                <option>Submitted</option>
                                                <option>Under Review</option>
                                                <option>Completed</option>
                                                <option>Comments</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2">
                                            <label>Type:</label>
                                            <select class="form-control" name="type">
                                                <option>{{$permit->type}}</option>
                                                <option>Regular</option>
                                                <option>Special</option>
                                                <option>Other</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2">
                                            <x-form-text name="number"
                                                         :params="['label' => 'Permit Number', 'iconClass' => 'fas fa-folder']">{{ $permit->number }}</x-form-text>
                                        </div>

                                        <div class="col-lg-2">
                                            <label>County:</label>
                                            <select name="county" id="county" class="form-control">
                                                @foreach($countiesCB as $county)
                                                    <option{{ $permit->county === $county ? ' selected' : '' }}>{{ $county }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-2">
                                            <label>City:</label>
                                            <select name="city" id="city" class="form-control">
                                                @foreach($citiesCB as $city)
                                                    <option{{ $permit->city === $city ? ' selected' : '' }}>{{ $city }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{--
                                        <div class="col-lg-2">
                                            <label>County:</label>
                                            <select name="county" id="county" class="form-control">
                                                <option>{{$permit->county}}</option>
                                                @foreach($counties as $county)
                                                    <option>{{$county->county}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="col-lg-2">
                                            <x-form-text name="city" :
                                                         params="['label' => 'City', 'iconClass' => 'fas fa-file','required' => true]">{{ $permit->city }}</x-form-text>
                                        </div>
                                        --}}
                                        <div class="col-lg-2">
                                            <x-form-date-picker
                                                name="expires_on"
                                                :params="[
                                    'id' => 'expires_on',
                                    'label' => 'Expires On',
                                    'iconClass' => 'fas fa-calendar',
                                    'value' => $permit->expires_on,
                                ]"
                                            ></x-form-date-picker>

                                        </div>

                                    </div>


                                    <div class="row mt-2">
                                        <div class="col-lg-12">
                                            <p></p>
                                            <input type="submit" value="Update Permit" class="{{$site_button_class}}" />
                                            <input type="button" id="returntoworkorder" value="View Work Order" class="{{$site_button_class}}" />
                                            <input type="button" id="deletepermit" value="Delete Permit" class="{{$site_button_class}}" />
                                        </div>
                                    </div>
                                </form>

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
                            @php
                            $ttotal =0;
                            @endphp
                            <div class="tab-pane" id="notes" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-8 col-sm-6 mb20">
                                        <x-href-button id="add_note_button" class="{{$site_button_class}}" data-route="{{ route('permit_note_add', ['permit' => $permit->id]) }}" data-id="{{ $permit->id }}" data-permit_name="{{ $permit->full_name }}"><i class="fas fa-plus"></i>@lang('translation.add') @lang('translation.note')</x-href-button>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb20"></div>
                                </div>
                                <div class="row info-color">
                                    <div class="col-lg-6 admin-form-item-widget">
                                        <strong>Note</strong>
                                    </div>
                                    <div class="col-lg-4  admin-form-item-widget">
                                        <strong>Created By</strong>
                                    </div>
                                    <div class="col-lg-2 admin-form-item-widget">
                                        <strong>Fee</strong>
                                    </div>
                                </div>
                                @foreach ($permit->notes as $notes)
                                    <div class="row">
                                        <div class="col-lg-6 admin-form-item-widget">
                                            {{ $notes->note ?? null }}
                                        </div>
                                        <div class="col-lg-4  admin-form-item-widget">
                                            <p class="mb4 fs14">{{ $notes->creator }}:</p>
                                            {{ $notes->created_at->format('M-d-Y') ?? null }}
                                        </div>
                                        <div class="col-lg-2 admin-form-item-widget">
                                            ${{ number_format($notes->fee,2) ?? '0.00' }}
                                        </div>
                                    </div>
                                    @php($ttotal = $ttotal + $notes->fee)
                                @endforeach
Total Cost: ${{ number_format($ttotal, 2) }}

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
            const permitId = Number("{{ $permit->id }}");

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

            var countyEl = $('#county');
            var cityEl = $('#city');
            const initialCounty = "{{ $permit->county }}";
            const initialCity = "{{ $permit->city }}";

            countyEl.on('change', function(){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        county: $(this).val()
                    },
                    type: "POST",
                    url: "{{ route('ajax_fetch_cities') }}",
                    beforeSend: function (request) {
                        showSpinner();
                    },
                    complete: function () {
                        hideSpinner();
                    },
                    success: function (response) {
                        if (typeof response.success === 'undefined' || !response) {
                            countyEl.val(initialCounty);
                            cityEl.val(initialCity);
                            console.log('Critical error has occurred.');
                        } else if (response.success) {
                            let data = response.data;
                            let html = '';

                            $.each(data, function(key, value){
                                html += '<option>'+ value +'</option>';
                            })
                            cityEl.html(html);
                        } else {
                            // controller defined response error message
                            countyEl.val(initialCounty);
                            cityEl.val(initialCity);
                            console.log(response.message);
                        }
                    },
                    error: function (response) {
                        countyEl.val(initialCounty);
                        cityEl.val(initialCity);
                        @if (app()->environment() === 'local')
                            console.log(response.responseJSON.message);
                        @else
                            console.log(response.message);
                        @endif
                    }
                });
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

        $("#returntoworkorder").on('click', function(){
            window.location.href="{{route('show_workorder',['id'=>$permit->proposal->id])}}";
        });

        $("#deletepermit").on('click', function(){

            var remove = confirm("You are about to remove this permit . Are you Sure?");
                if(remove){
                    window.location.href="{{route('remove_permit',['id'=>$permit->id])}}";
                }
                return true;
        });

    </script>
@stop

