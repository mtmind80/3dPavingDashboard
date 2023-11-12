<!-- vehicle sections -->

<!-- input fields header row -->
<div class="row">
    <div class="col-sm-6 mb2 fwb">
        <label class="control-label">Select Vehicle<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
    <div class="col-sm-2 tc">
        <label class="control-label">Number of Vehicles<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
    <div class="col-sm-2 tc">
        <label class="control-label">How Many Days<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
    <div class="col-sm-2 tc">
        <label class="control-label">Hours per day<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="@lang('translation.field_required')"></i></label>
    </div>
</div>

<!-- input fields values row -->
<form method="POST" action="#" accept-charset="UTF-8" id="vehicle_form" class="admin-form">
    @csrf
    <input type="hidden" name="proposal_detail_vehicle_id" id="proposal_detail_vehicle_id">
    <div class="row">
        <div class="col-sm-3 admin-form-item-widget">
            <select name="vehicle_id" id="vehicle_id" class="form-control">
            <option value="0">Select a Vehicle</option>
            @foreach ($vehiclesCB as $v)
                <option value="{{$v->id}}">{{$v->NameRate}}</option>
            @endforeach
            </select>
        </div>
        <div class="col-sm-3 xs-hidden"></div>
        <div class="col-sm-2 tc admin-form-item-widget">
            <x-form-text name="number_of_vehicles"
                 class="check-contact tc"
                 placeholder="enter value"
                 id="vehicle_quantity"
                 :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
            ></x-form-text>
        </div>
        <div class="col-sm-2 tc admin-form-item-widget">
            <x-form-text name="days"
                 class="check-contact tc"
                 placeholder="enter value"
                 id="vehicle_days"
                 :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
            ></x-form-text>
        </div>
        <div class="col-sm-2 tc admin-form-item-widget">
            <x-form-text name="hours"
                 class="check-contact tc"
                 placeholder="enter value"
                 id="vehicle_hours"
                 :params="[
                    'label' => 'none',
                    'iconClass' => 'none',
                    'value' => '',
                ]"
            ></x-form-text>
        </div>
    </div>
</form>

<!-- vehicle header row -->
<div id="vehicle_rows_header" class="row fwb pb4 border-bottom-solid{{ !empty($proposalDetail->vehicles) && $proposalDetail->vehicles->count() > 0 ? '' : ' hidden' }}">
    <div class="col-sm-6">Vehicle Type</div>
    <div class="col-sm-1 tc">Quantity</div>
    <div class="col-sm-1 tc">Days</div>
    <div class="col-sm-1 tc">Hours</div>
    <div class="col-sm-1 tc">Rate</div>
    <div class="col-sm-1 tc">Total</div>
    <div class="col-sm-1 tc">Edit / Remove</div>
</div>

<!-- vehicles row -->
<div id="vehicle_rows_container" class="mb20">
    @if (!empty($proposalDetail->vehicles) && $proposalDetail->vehicles->count() > 0)
        @include('estimator._form_service_vehicles', ['vehicles' => $proposalDetail->vehicles])
    @endif
</div>

<!-- vehicle footer row -->
<div class="row mt12">
    <div class="col-sm-3">
        <a id="vehicle_add_button" href="javascript:" class="{{ $site_button_class }} vehicle-submit">Add Vehicle</a>
        <a id="vehicle_update_button" href="javascript:" class="btn btn-info hidden vehicle-submit">Update Vehicle</a>
        <a id="vehicle_cancel_button" href="javascript:" class="btn btn-light hidden ml6">Cancel</a>
    </div>
    <div class="col-sm-2 pt8 m0">
        <label class="control-label">Total Vehicles</label>
    </div>
    <div class="col-sm-2">
        <div class="admin-form-item-widget">
            <x-form-show
                class="show-check-contact"
                :params="[
                    'id' => 'vehicle_total_cost',
                ]">
            </x-form-show>
        </div>
    </div>
    <div class="col-sm-5 xs-hidden"></div>
</div>

@push('partials-scripts')
    <script>
        $(document).ready(function () {
            var proposalDetailId = Number('{{ $proposalDetail->id }}');

            var vehicleElForm = $('#vehicle_form');
            var vehicleElFormProposalDetailVehicleId = $('#proposal_detail_vehicle_id');
            var vehicleElFormVehicleId = $('#vehicle_id');
            var vehicleElFormNumberOfVehicles = $('#vehicle_quantity');
            var vehicleElFormDays = $('#vehicle_days');
            var vehicleElFormHours = $('#vehicle_hours');

            var vehicleElRowsHeader = $('#vehicle_rows_header');
            var vehicleElRowsContainer = $('#vehicle_rows_container');

            var vehicleSubmitButton = $('.vehicle-submit');
            var vehicleAddButton = $('#vehicle_add_button');
            var vehicleUpdateButton = $('#vehicle_update_button');
            var vehicleCancelButton = $('#vehicle_cancel_button');
            var vehicleElTotalCost = $('#vehicle_total_cost');
            var vehicleElEstimatorFormFieldTotalCost = $('#estimator_form_vehicle_total_cost');

            var vehicleAlert = $('#vehicle_alert');

            vehicleAlert.on('click', function(ev){
                ev.stopPropagation();
                ev.preventDefault();
                closeAlert(vehicleAlert);
            });

            vehicleUpdateTotalCost();

            vehicleSubmitButton.on('click', function(){
                vehicleElForm.validate({
                    rules: {
                        vehicle_id: {
                            required: true,
                            positive: true
                        },
                        number_of_vehicles: {
                            required: true,
                            positive: true
                        },
                        days: {
                            required: true,
                            float  : true
                        },
                        hours: {
                            required: true,
                            float  : true
                        }
                    },
                    messages: {
                        vehicle_id: {
                            required: "@lang('translation.field_required')",
                            positive: "@lang('translation.select_item')"
                        },
                        number_of_vehicles: {
                            required: "@lang('translation.field_required')",
                            positive: "@lang('translation.invalid_entry')"
                        },
                        days: {
                            required: "@lang('translation.field_required')",
                            positive: "@lang('translation.invalid_entry')"
                        },
                        hours: {
                            required: "@lang('translation.field_required')",
                            positive: "@lang('translation.invalid_entry')"
                        }
                    }
                });

                if (vehicleElForm.valid()) {
                    let formData = vehicleElForm.serializeObject();
                    let extraFormProperties = {proposal_detail_id: proposalDetailId};

                    $.extend(formData, extraFormProperties);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        type: "POST",
                        url: "{{ route('ajax_vehicle_add_or_update') }}",
                        beforeSend: function (request){
                            showSpinner();
                        },
                        complete: function (){
                            hideSpinner();
                        },
                        success: function (response){
                            if (!response) {
                                showErrorAlert('Critical error has occurred.', vehicleAlert);
                            } else if (response.success) {
                                vehicleElRowsContainer.html(response.html);

                                vehicleUpdateTotalCost();
                                vehicleResetForm();

                                vehicleAddButton.removeClass('hidden');
                                vehicleUpdateButton.addClass('hidden');
                                vehicleCancelButton.addClass('hidden');

                                if (response.message) {
                                    showSuccessAlert(response.message, vehicleAlert);
                                }
                            } else {
                                showErrorAlert(response.message, vehicleAlert);
                            }
                        },
                        error: function (response){
                            @if (env('APP_ENV') === 'local')
                                showErrorAlert(response.responseJSON.message, vehicleAlert);
                            @else
                                showErrorAlert('Critical error has occurred.', vehicleAlert);
                            @endif
                        }
                    });
                }
            });

            vehicleElRowsContainer.on('click', '.vehicle-remove-button', function(){
                let el = $(this);
                let proposal_detail_vehicle_id = el.data('proposal_detail_vehicle_id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        proposal_detail_id: proposalDetailId,
                        proposal_detail_vehicle_id: proposal_detail_vehicle_id
                    },
                    type: "POST",
                    url: "{{ route('ajax_vehicle_remove') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function (){
                        hideSpinner();
                    },
                    success: function (response){
                        if (!response) {
                            showErrorAlert('Critical error has occurred.', vehicleAlert);
                        } else if (response.success) {
                            vehicleElRowsContainer.html(response.html);

                            let vehicleRows = $('.vehicle-row');

                            if (vehicleRows.length === 0) {
                                vehicleElRowsHeader.addClass('hidden');
                            }

                            vehicleUpdateTotalCost();

                            if (response.message) {
                                showSuccessAlert(response.message, vehicleAlert);
                            }
                        } else {
                            showErrorAlert(response.message, vehicleAlert);
                        }
                    },
                    error: function (response){
                        @if (env('APP_ENV') === 'local')
                            showErrorAlert(response.responseJSON.message, vehicleAlert);
                        @else
                            showErrorAlert('Critical error has occurred.', vehicleAlert);
                        @endif
                    }
                });
            });

            vehicleElRowsContainer.on('click', '.vehicle-edit-button', function(){
                let el = $(this);
                vehicleElFormProposalDetailVehicleId.val(el.data('proposal_detail_vehicle_id'));
                vehicleElFormVehicleId.val(el.data('vehicle_id'));
                vehicleElFormNumberOfVehicles.val(el.data('number_of_vehicles'));
                vehicleElFormDays.val(el.data('days'));
                vehicleElFormHours.val(el.data('hours'));

                vehicleAddButton.addClass('hidden');
                vehicleUpdateButton.removeClass('hidden');
                vehicleCancelButton.removeClass('hidden');
            });

            vehicleCancelButton.click(function(){
                vehicleResetForm();

                vehicleAddButton.removeClass('hidden');
                vehicleUpdateButton.addClass('hidden');
                vehicleCancelButton.addClass('hidden');
            });

            function vehicleUpdateTotalCost()
            {
                let vehicleCosts = $('.vehicle-cost');
                let totalCost = 0;
                let currrencyTotalCost = 0;

                vehicleCosts.each(function(index){
                    let el = $(this);
                    totalCost += Number(el.data('cost'));
                });

                currrencyTotalCost = currencyFormat(totalCost);

                vehicleElTotalCost.html(currrencyTotalCost);
                vehicleElEstimatorFormFieldTotalCost.val(totalCost);

                headerElVehiclesCost.html(currrencyTotalCost);
                headerElVehiclesCost.data('vehicle_total_cost', totalCost);
            }

            function vehicleResetForm()
            {
                vehicleElForm.trigger('reset');
                vehicleElFormProposalDetailVehicleId.val('');
            }
        });
    </script>
@endpush


