@extends('layouts.master')

@section('title') 3D Paving Permit Details @endsection


@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.permit') {{$proposal->name}}
        @endslot
        @slot('li_1')
            <a href="{{ route('dashboard') }}" xmlns="http://www.w3.org/1999/html">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            <a href="{{ route('permits') }}">@lang('translation.all') @lang('translation.permits')</a>
        @endslot
        @slot('li_3')
            <a href="{{ route('show_workorder', ['id'=>$proposal->id]) }}">@lang('translation.work_order')</a>
        @endslot

    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Tab panes -->
                            <div class="row ml-0 mr-0">
                                <form method="post" action="{{route('create_permit')}}"
                    C                  id="permitform" class="w-100">
                                    @csrf
                                    <input type="hidden" name="proposal_id" value="{{$proposal->id}}">
                                    <input type="hidden" name="proposal_detail_id" value="0">
                                    <input type="hidden" name="created_by" value="{{auth()->user()->id}}">
                                    <input type="hidden" name="last_updated_by" value="{{auth()->user()->id}}">


                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label>Status:</label>
                                            <select class="form-control" name="status">
                                                @foreach($statuses as $status)
                                                    <option>{{$status}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Type:</label>
                                            <select class="form-control" name="type">
                                                @foreach($types as $type)
                                                    <option>{{$type}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="col-lg-3 admin-form-item-widget">
                                            <x-form-select name="cert_holder" id="cert_holder" :items="$cert_holders"
                                                           :params="['label' => 'Cert Holder', 'iconClass' => 'fas fa-briefcase','required' => true]"></x-form-select>
                                        </div>
                                        <div class="col-lg-3">
                                            <x-form-text name="number"
                                                         :params="['label' => 'Permit Number', 'iconClass' => 'fas fa-folder', 'required' => true]"></x-form-text>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label>County:</label>
                                            <select name="county" id="county" class="form-control">
                                                <option>Select county</option>
                                                @foreach($counties as $county)
                                                    <option>{{$county->county}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label>City:</label>
                                            <select name="city" id="city" class="form-control">
                                                <option value="0">Select county first</option>
                                            </select>
                                        </div>
                                        {{--
                                        <div class="col-lg-2">
                                            <x-form-text id="city" name="city" :
                                                         params="['label' => 'City', 'iconClass' => 'fas fa-file','required' => true]"></x-form-text>
                                        </div>
                                        --}}
                                        <div class="col-lg-3">
                                            <x-form-date-picker
                                                name="submitted_on"
                                                :params="[
                                    'id' => 'submitted_on',
                                    'label' => 'Submitted On',
                                    'iconClass' => 'fas fa-calendar',

                                ]"
                                            ></x-form-date-picker>

                                        </div>
                                        <div class="col-lg-3">
                                            <x-form-date-picker
                                                name="expires_on"
                                                :params="[
                                    'id' => 'expires_on',
                                    'label' => 'Expires On',
                                    'iconClass' => 'fas fa-calendar',
                                ]"
                                            ></x-form-date-picker>

                                        </div>

                                    </div>


                                    <div class="row mt-2">
                                        <div class="col-lg-12">
                                            <p></p>
                                            <input type="submit" value="Save Permit" class="{{$site_button_class}}" />
                                            <input type="button" id="cancelbutton" value="Cancel" class="{{$site_button_class}}" />
                                        </div>
                                    </div>
                                </form>

                            </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page-js')
    <script>

        $(document).ready(function(){
            var addPermitform = $("#permitform");
            addPermitform.validate({
                rules: {
                    number: {
                        required : true,
                        plainText: true
                    },
                    city: {
                        required : true,
                        plainText: true
                    }

                },
                messages: {
                    number: {
                        required : "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_entry')"
                    },
                    city: {
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

            $("#cancelbutton").on('click', function(){
                window.location.href="{{route('show_workorder',['id'=>$proposal->id])}}";
            });

            var countyEl = $('#county');
            var cityEl = $('#city');

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
                            cityEl.html('<option value="0">Select county first</option>');
                            console.log('Critical error has occurred.');
                        } else if (response.success) {
                            let data = response.data;
                            let html = '<option>NA</option>';

                            $.each(data, function(key, value){
                                html += '<option>'+ value +'</option>';
                            })
                            cityEl.html(html);
                        } else {
                            // controller defined response error message
                            cityEl.html('<option value="0">Select county first</option>');
                            console.log(response.message);
                        }
                    },
                    error: function (response) {
                        cityEl.html('<option value="0">Select county first</option>');
                        @if (app()->environment() === 'local')
                            console.log(response.responseJSON.message);
                        @else
                            console.log(response.message);
                        @endif
                    }
                });
            });
        });
    </script>
@stop

