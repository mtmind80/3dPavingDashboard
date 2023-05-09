@extends('layouts.master')

@section('title')
    3D Paving Proposals
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.create') @lang('translation.proposal')
        @endslot
        @slot('li_1')
            <a href="/dashboard">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            <a href="/proposals">@lang('translation.Proposals')</a>
        @endslot
        @slot('li_3')
            @lang('translation.create') @lang('translation.proposal')
        @endslot
    @endcomponent

    <div class="row admin-form">

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('create_proposal', ['id' => $proposal->id ?? null]) }}"
                          accept-charset="UTF-8" id="proposal_form" class="admin-form">
                        @csrf
                        <input type="hidden" name="lead_id" id="lead_id" value="{{ $lead_id ?? null }}">
                        <input type="hidden" name="contact_id" id="contact_id" value="{{ $contact->id ?? null }}">
                        <input type="hidden" name="proposal_id" id="proposal_id" value="{{ $proposal->id ?? null }}">
                        <input type="hidden" name="proposal_statuses_id" id="proposal_statuses_id"
                               value="{{ $proposal->proposal_statuses_id ?? 1 }}">

                        <div class="row">
                            <div class="col-lg-12">
                                <h3>Client Information</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
                                {{$contact->FullName }}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
                                {{$contact->FullAddressTwoLineAttribute }}
                            </div>

                        </div>

                        
                        <div class="row">
                            <div class="col-lg-12">
                                <h3>Proposal Information</h3>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">

                                
                                <x-form-text id="name" name="name" class="check-lead"
                                             :params="['label' => 'Proposal Name', 'iconClass' => 'fas fa-circle', 'required' => true]">{{ $proposal['name'] ?? $contact->FullName . " Proposal Name" }}</x-form-text>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
                                <x-form-select name="customer_staff_id" :items="$staff"
                                               selected="{{ $proposal->customer_staff_id ?? null }}"
                                               :params="['label' => 'Client Manager', 'required' => false]"></x-form-select>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
                                <x-form-select name="salesmanager_id" :items="$salesManagersCB"
                                               selected="{{ $proposal->salesmanager_id ?? null }}"
                                               :params="['label' => '3D Paving Manager', 'required' => false]"></x-form-select>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
                                <x-form-select name="salesperson_id" :items="$salesPersonsCB"
                                               selected="{{ $proposal->salesperson_id ?? null }}"
                                               :params="['label' => 'Sales Person', 'required' => false]"></x-form-select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-6 admin-form-item-widget">
                                <x-form-check-box name="mot_required" id="mot_required" value="1"
                                                  :checked="false">
                                    @lang('translation.mot') @lang('translation.required')
                                </x-form-check-box>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-6 admin-form-item-widget">
                                <x-form-check-box name="permit_required" id="permit_required" value="1"
                                                  :checked="false">
                                    @lang('translation.permit') @lang('translation.required')
                                </x-form-check-box>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-6 admin-form-item-widget">
                                <x-form-check-box name="nto_required" id="nto_required" value="1"
                                                  :checked="false">
                                    @lang('translation.require') @lang('translation.nto')
                                </x-form-check-box>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <h3>Primary Location</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-6 admin-form-item-widget">
                                <x-form-select name="location_type_id" :items="$locationTypesCB"
                                               selected="1"
                                               :params="['label' => 'Location Type', 'required' => false]"></x-form-select>
                            </div>
                            <div class="col-lg-6 col-md-8 col-sm-8 admin-form-item-widget">
                                <x-form-text name="address1" :params="['label' => 'Address', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $contact->address1 ?? null }}</x-form-text>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 admin-form-item-widget">
                                <x-form-text name="address2" :params="['label' => 'Address 2', 'iconClass' => 'fas fa-building', 'required' => false]">{{ $contact->address2 ?? null }}</x-form-text>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-6 admin-form-item-widget">
                                <x-form-text name="city" :params="['label' => 'City', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $contact->city ?? null }}</x-form-text>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 admin-form-item-widget">
                                <x-form-select name="county" :items="$countiesCB" selected="{{ $contact->county ?? null }}"  :params="['label' => 'County', 'required' => true]"></x-form-select>
                            </div>
                            <div class="col-lg-3 col-md-8 col-sm-8 admin-form-item-widget">
                                <x-form-text name="state" :params="['label' => 'State', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $contact->state ?? null }}</x-form-text>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-4 admin-form-item-widget">
                                <x-form-text name="postal_code" :params="['label' => 'Zipcode', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $contact->postal_code ?? null }}</x-form-text>
                            </div>
                        </div>
                        

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12 admin-form-item-widget">
                                <x-form-text id="parcel_number" name="parcel_number" class="text-md-center"
                                             :params="['label' => 'Parcel Number', 'iconClass' => 'fas fa-building', 'required' => false]"></x-form-text>

                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 admin-form-item-widget">
                                <x-form-text id="discount" name="discount" class="test-expected"
                                             :params="['label' => 'Discount %', 'iconClass' => 'fas fa-money', 'required' => true]">{{ $proposal['discount'] ?? 0 }}</x-form-text>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 admin-form-item-widget">
                                <x-form-check-box name="progressive_billing" id="progressive_billing" value="1"
                                                  :checked="false">
                                    @lang('translation.progressivebilling')
                                </x-form-check-box>
                            </div>

                        </div>

                        
                        <div class="row buttons">
                            <div class="col-sm-12 tr">
                                <x-button id="cancel_button" class="btn-light"><i
                                            class="far fa-arrow-alt-circle-left "></i>{{ $cancel_caption }}</x-button>
                                <x-button id="submit_button" class="btn-dark" type="submit"><i
                                            class="fas fa-save"></i>{{$submit_caption}}
                                </x-button>
                            </div>
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
            $('#proposal_form').validate({
                rules: {
                    name: {
                        required: true,
                        personName: true
                    },
                },
                messages: {
                    name: {
                        required: "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_entry')"
                    },
                },
                submitHandler: function (form) {
                    let errors = false;

                    if (!errors) {
                        form.submit();
                    }
                }
            });

            $('#cancel_button').click(function () {
                if ("{{ $returnTo }}" !== "") {
                    window.location = "{{ $returnTo }}";
                } else {
                    window.location = "{{ route('dashboard') }}";
                }
            });
        });
    </script>
@stop

