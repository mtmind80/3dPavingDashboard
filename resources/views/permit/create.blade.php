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
                    <div class="row ml-0 mr-0">
                        <form method="post" action="{{ route('create_permit') }}" id="add_permit_form" class="w-100">
                            @csrf
                            <input type="hidden" name="proposal_id" value="{{ $proposal->id }}">
                            <div class="row">
                                <div class="col-lg-3">
                                    <x-form-select
                                        name="status_selected"
                                        :items="$statusOptionsCB"
                                        :params="[
                                            'label' => 'Status',
                                            'required' => true,
                                        ]"
                                    ></x-form-select>
                                </div>
                                <div class="col-lg-3">
                                    <x-form-select
                                        name="type"
                                        :items="$typeOptionsCB"
                                        :params="[
                                            'label' => 'Type',
                                            'required' => true,
                                        ]"
                                    ></x-form-select>
                                </div>
                                <div class="col-lg-3 admin-form-item-widget">
                                    <x-form-select
                                        name="cert_holder"
                                        :items="$cert_holders"
                                        :params="[
                                            'label' => 'Cert Holder',
                                            'iconClass' => 'fas fa-briefcase',
                                            'required' => true
                                        ]"
                                    ></x-form-select>
                                </div>
                                <div class="col-lg-3">
                                    <x-form-text
                                        name="number"
                                        :params="[
                                            'label' => 'Permit Number',
                                            'iconClass' => 'fas fa-folder',
                                            'required' => true
                                        ]"
                                    ></x-form-text>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <span class="form-field-label">County:<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="this field is required"></i></span>
                                    <label class="field select prepend-icon">
                                        <select name="county" id="county" class="form-control grayed">
                                            <option value="">Select county</option>
                                            @foreach($counties as $county)
                                                <option value="{{ $county->county }}">{{ $county->county }}</option>
                                            @endforeach
                                        </select>
                                        <i class="arrow double"></i>
                                        <span class="field-icon"><i class="fas fa-indent"></i></span>
                                    </label>
                                </div>
                                <div class="col-lg-3">
                                    <span class="form-field-label">City:<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="this field is required"></i></span>
                                    <label class="field select prepend-icon">
                                        <select name="city" id="city" class="form-control grayed">
                                            <option value="">Select county first</option>
                                        </select>
                                        <i class="arrow double"></i>
                                        <span class="field-icon"><i class="fas fa-indent"></i></span>
                                    </label>
                                </div>
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
                                <div class="col-lg-12 pt10">
                                    <buton
                                        type="button"
                                        id="submitFormButton"
                                        class="btn btn-primary"
                                    >
                                        Save Permit
                                    </buton>
                                    <a href="{{ route('show_workorder',['id' => $proposal->id]) }}" class="btn btn-default">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form method="post" action="{{ route('create_permit') }}" id="add_permit_hidden_form" class="hidden">
        @csrf
        <input type="hidden" name="serialized_permit_form_data" id="serialized_permit_form_data">
    </form>
@stop

@section('page-js')
    <script>
        $(document).ready(function(){
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
                            cityEl.html('<option value="0">Select county last</option>');
                            console.log('Critical error has occurred.');
                        } else if (response.success) {
                            let data = response.data;
                            let html = '<option value="">Select city</option>';

                            $.each(data, function(key, value){
                                html += '<option value="'+ value +'">'+ value +'</option>';
                            })
                            cityEl.html(html);
                        } else {
                            // controller defined response error message
                            cityEl.html('<option value="">Select county first</option>');
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

            var addPermitForm = $("#add_permit_form");
            var submitFormButton = $('#submitFormButton');

            submitFormButton.click(function(){
                addPermitForm.submit();
            });

        });
    </script>
@stop

