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
            <a href="{{route('show_workorder',['id'=> $workorder['id']])}}">@lang('translation.edit') @lang('translation.work_order')</a>
        @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('add_payment')}}"
                          accept-charset="UTF-8" id="proposal_form" id="payment_admin-form"
                          class="admin-form">
                        @csrf

                        <input type="hidden" name="proposal_id" id="proposal_id" value="{{ $workorder['id']}}">
                        <input type="hidden" name="created_by" id="created_by" value="{{auth()->user()->id}}">

                        <div class="row">
                            <div class="col-lg-12 ">
                                <h2>Add Payment</h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 ">
                                <h3>{{$workorder['name'] }}</h3>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
                                <x-form-select name="payment_type" id="payment_type" :items="$payment_types"
                                               :params="['label' => 'Payment Type', 'iconClass' => 'fas fa-money-bill-alt', 'required' => true]"></x-form-select>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
                                <x-form-text id="payment" name="payment" class="check-lead"
                                             :params="['label' => 'Amount', 'iconClass' => 'fas fa-dollar-sign', 'required' => true]"></x-form-text>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
                                <x-form-text id="check_no" name="check_no" class="check-lead"
                                             :params="['label' => 'Check Number', 'iconClass' => 'fas fa-money-check', 'required' => false]"></x-form-text>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
                                <x-form-textarea id="note" name="note" class="check-lead"
                                                 :params="['label' => 'Note', 'iconClass' => 'fas fa-address-card', 'required' => false]"></x-form-textarea>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-12 tr">
                                <x-button id="cancel_button2" class="btn-light"><i
                                        class="far fa-arrow-alt-circle-left "></i>Return to WorkOrder
                                </x-button>
                                <x-button id="cancel_button" class="btn-light"><i
                                        class="far fa-arrow-alt-circle-left "></i>Cancel
                                </x-button>
                                <x-button id="submit_button" class="btn-dark" type="submit"><i
                                        class="fas fa-save"></i>Add Payment
                                </x-button>
                            </div>
                        </div>
                    </form>

                    <p></p>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Type</th>
                            <th>Payment</th>
                            <th>Check No.</th>
                            <th>Note</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $ttotal = 0;
                        @endphp
                        @foreach($payments as $payment)
                            @php
                                $ttotal = $ttotal +  $payment['payment'];
                            @endphp
                            <tr>
                                <td>{{$payment['payment_type']}}</td>
                                <td>${{number_format($payment['payment'], 2)}}
                                </td>
                                <td>{{$payment['check_no']}}
                                </td>
                                <td>{{$payment['note']}}</td>
                                <td>{{ \Carbon\Carbon::parse($payment['created_at'])->format('j F, Y')}}</td>
                                <td>
                                    <a title="Delete Payment"
                                       href="{{route('delete_payment', ['proposal_id'=>$payment['proposal_id'],'id' => $payment['id']])}}">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                        <tr class="alert-success">
                            <td>Total</td>
                            <td>${{number_format($ttotal, 2)}}</td>
                            <td colspan="4'"></td>
                        </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        @stop

        @section('page-js')
            <script>
                $(document).ready(function () {
                    $('#proposal_form').validate({
                        rules: {
                            payment: {
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
                            window.location.href = "{{ route('show_workorder',['id'=>$workorder['id']]) }}";
                        }
                    });
                    $('#cancel_button2').click(function () {
                        window.location.href = "{{ route('show_workorder',['id'=>$workorder['id']]) }}";
                    });

                });


            </script>
@stop

