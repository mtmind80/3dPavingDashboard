@extends('layouts.master')

@section('title')
    3D Paving Proposals
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.edit') @lang('translation.proposal')
        @endslot
        @slot('li_1')
            <a href="/dashboard">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            <a href="/proposals">@lang('translation.Proposals')</a>
        @endslot
        @slot('li_3')
            @lang('translation.edit') @lang('translation.proposal')
        @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{route('update_location',['proposal'=>$proposal->id,'location'=>$proposal->location_id])}}"
                          accept-charset="UTF-8" id="location_form" class="admin-form">
                        @csrf

                        <input type="hidden" name="location_id" id="location_id" value="{{ $location['id']}}">
                        <input type="hidden" name="proposal_id" id="proposal_id" value="{{ $proposal['id']}}">

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h3>Primary Location for {{$proposal['name']}}</h3>
                                        </div>
                                    </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 admin-form-item-widget">
                                <x-form-radio-group
                                    id="newLocation"
                                    name="newlocation"
                                    :radios="$radios"
                                    :params="['direction' => 'h', 'label'=>'New Location']"
                                ></x-form-radio-group>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 admin-form-item-widget">

                            </div>

                        </div>

                                    <div class="row">
                                        <div class="col-lg-2 col-md-6 col-sm-6 admin-form-item-widget">
                                            <x-form-select name="location_type_id" :items="$locationTypesCB"
                                                           selected="{{$location['location_type_id']}}"
                                                           :params="['label' => 'Location Type', 'required' => true]"></x-form-select>
                                        </div>
                                        <div class="col-lg-6 col-md-8 col-sm-8 admin-form-item-widget">
                                            <x-form-text name="address_line1" :params="['label' => 'Address', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $location->address_line1 ?? null }}</x-form-text>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 admin-form-item-widget">
                                            <x-form-text name="address_line2" :params="['label' => 'Address 2', 'iconClass' => 'fas fa-building', 'required' => false]">{{ $location->address_line2 ?? null }}</x-form-text>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-6 admin-form-item-widget">
                                            <x-form-text name="city" :params="['label' => 'City', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $location->city ?? null }}</x-form-text>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6 admin-form-item-widget">
                                            <x-form-select name="county" :items="$countiesCB" selected="{{ $location->county ?? null }}"  :params="['label' => 'County', 'required' => true]"></x-form-select>
                                        </div>
                                        <div class="col-lg-3 col-md-8 col-sm-8 admin-form-item-widget">
                                            <x-form-text name="state" :params="['label' => 'State', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $location->state ?? null }}</x-form-text>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-4 admin-form-item-widget">
                                            <x-form-text name="postal_code" :params="['label' => 'Zipcode', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $location->postal_code ?? null }}</x-form-text>
                                        </div>
                                    </div>



                                    <div class="row buttons">
                                        <div class="col-sm-12 tr">
                                            <x-button id="submit_button" class="btn-dark" type="submit"><i
                                                    class="fas fa-save"></i>Save and Continue</x-button>
                                            <x-button id="cancel_button" class="btn-light"><i
                                                    class="far fa-arrow-alt-circle-left "></i>Cancel</x-button>

                                    </div>

                            </form>
                        </div>
            </div>
        </div>
    </div>
@stop

@section('page-js')
    <script>
        $(document).ready(function () {
            $('#location_form').validate({
                rules: {
                    address_line1: {
                        required: true,
                        plainText: true
                    },
                },
                messages: {
                    address_line1: {
                        required: "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_entry')"
                    },
                },
                submitHandler: function (form) {
                    let errors = false;

                    if (!errors) {
                        form.submit();
                    }
                        return false;

                }
            });

            $('#cancel_button').click(function () {
                if ("{{ $returnTo }}" !== "") {
                    window.location.href = "{{ $returnTo }}";
                } else {
                    window.location.href = "{{ route('show_proposal',['id'=>$proposal['id']]) }}";
                }
            });

        });


    </script>
@stop

