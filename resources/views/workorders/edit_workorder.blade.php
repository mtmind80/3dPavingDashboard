@extends('layouts.master')

@section('title')
    3D Paving Work Orders
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.edit') @lang('translation.work_orders')
        @endslot
        @slot('li_1')
            <a href="/dashboard">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            <a href="/workorders">@lang('translation.work_orders')</a>
        @endslot
        @slot('li_3')
            @lang('translation.edit') @lang('translation.work_order')
        @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('update_workorder', ['id' => $proposal['id'] ?? null]) }}"
                          accept-charset="UTF-8" id="proposal_form" class="admin-form">
                        @csrf

                        <input type="hidden" name="location_id" id="location_id" value="{{ $proposal['location_id']}}">
                        <input type="hidden" name="proposal_id" id="proposal_id" value="{{ $proposal['id']}}">


                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 ">
                                <h3>{{$contact['first_name'] }} {{$contact['last_name'] }}</h3>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
                                <h3>{{$contact['address1'] }}
                                {{$contact['address2'] }}
                                {{$contact['city'] }}
                                {{$contact['state'] }}
                                {{$contact['postal_code'] }}
                                </h3></div>

                        </div>


                        <div class="row">
                            <div class="col-lg-12">
                                <h3>Work Order Information</h3>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
                                <x-form-text id="name" name="name" class="check-lead"
                                             :params="['label' => 'Work Order Name', 'iconClass' => 'fas fa-circle', 'required' => true]">{{ $proposal['name'] ?? "Name This Work Order" }}</x-form-text>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
                                <x-form-select name="customer_staff_id" :items="$staff"
                                               selected="{{ $proposal['customer_staff_id'] ?? null }}"
                                               :params="['label' => 'Client Manager', 'required' => false]"></x-form-select>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
                                <x-form-select name="salesmanager_id" :items="$salesManagersCB"
                                               selected="{{ $proposal['salesmanager_id'] ?? null }}"
                                               :params="['label' => '3D Paving Manager', 'required' => false]"></x-form-select>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
                                <x-form-select name="salesperson_id" :items="$salesPersonsCB"
                                               selected="{{ $proposal['salesperson_id'] ?? null }}"
                                               :params="['label' => 'Sales Person', 'required' => false]"></x-form-select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md- col-sm-6 admin-form-item-widget">


                                @if ($proposal['progressive_billing'])
                                    <x-form-check-box name="progressive_billing" id="progressive_billing" value="1"
                                                      :checked="true">
                                        @lang('translation.progressivebilling')
                                    </x-form-check-box>
                                @else
                                    <x-form-check-box name="progressive_billing" id="progressive_billing" value="1"
                                                      :checked="false">
                                        @lang('translation.progressivebilling')
                                    </x-form-check-box>
                                @endif


                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">


                                @if ($proposal['mot_required'])
                                    <x-form-check-box name="mot_required" id="mot_required" value="1"
                                                      :checked="true">
                                        @lang('translation.mot') @lang('translation.required')
                                    </x-form-check-box>
                                @else
                                    <x-form-check-box name="mot_required" id="mot_required" value="1"
                                                      :checked="false">
                                        @lang('translation.mot') @lang('translation.required')
                                    </x-form-check-box>
                                @endif


                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
                                @if ($proposal['permit_required'])
                                    <x-form-check-box name="permit_required" id="permit_required" value="1"
                                                      :checked="true">
                                        @lang('translation.permit') @lang('translation.required')
                                    </x-form-check-box>
                                @else
                                    <x-form-check-box name="permit_required" id="permit_required" value="1"
                                                      :checked="false">
                                        @lang('translation.permit') @lang('translation.required')
                                    </x-form-check-box>

                                @endif
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 admin-form-item-widget">
                                @if ($proposal['nto_required'])
                                    <x-form-check-box name="nto_required" id="nto_required" value="1"
                                                      :checked="true">
                                        @lang('translation.require') @lang('translation.nto')
                                    </x-form-check-box>
                                @else
                                    <x-form-check-box name="nto_required" id="nto_required" value="1"
                                                      :checked="false">
                                        @lang('translation.require') @lang('translation.nto')
                                    </x-form-check-box>

                                @endif
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
                        plainText: true
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
                    window.location.href = "{{ $returnTo }}";
                } else {
                    window.location.href = "{{ route('show_proposal',['id'=>$proposal['id']]) }}";
                }
            });
        });


    </script>
@stop

